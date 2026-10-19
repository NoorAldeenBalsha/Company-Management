<?php
declare(strict_types=1);

class EmployeeService {
    
    private EmployeeRepository $employeeRepo;
    private PDO $pdo; 

    // Constructor to initialize dependencies.
    public function __construct(EmployeeRepository $employeeRepo, PDO $pdo) { 
        $this->employeeRepo = $employeeRepo;
        $this->pdo = $pdo;
    }

    // ==========================================
    //  Validation Logic
    // ==========================================

    // Strict data validation for employee inputs.
    private function validateEmployeeData(string $fullName, string $email, string $phone, int $sectionId): void {
        
        // 1. Name Validation: Must be at least 3 chars, letters and spaces only (Supports Arabic & English)
        if (mb_strlen($fullName) < 3 || !preg_match('/^[\p{L}\s]+$/u', $fullName)) {
            throw new InvalidArgumentException("Error: Full name must contain only letters and spaces, and be at least 3 characters long.");
        }

        // 2. Email Validation: Must be a valid email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Error: The email address format is invalid.");
        }

        // 3. Phone Validation: Syrian format strictly starting with 09 and exactly 10 digits
        if (!preg_match('/^09[0-9]{8}$/', $phone)) {
            throw new InvalidArgumentException("Error: Phone number must be exactly 10 digits and start with '09' (e.g., 0912345678).");
        }

        // 4. Section Validation: Must be a valid positive integer
        if ($sectionId <= 0) {
            throw new InvalidArgumentException("Error: Please select a valid section.");
        }
    }

    // ==========================================
    //  Core Business Operations
    // ==========================================

    // Process adding a new employee, validating data, and assigning a laptop if provided.
    public function addEmployee(string $fullName, string $email, string $phone, string $dateStart, int $sectionId, ?int $laptopId = null): bool {
        
        // Step 1: Validate data types and formats (Fail-Fast)
        $this->validateEmployeeData($fullName, $email, $phone, $sectionId);

        // Validate Date Start (Basic check)
        if (empty($dateStart) || !strtotime($dateStart)) {
            throw new InvalidArgumentException("Error: Please provide a valid start date.");
        }

        // Step 2: Check for unique email
        if ($this->employeeRepo->isEmailExists($email)) {
            throw new Exception("Error: The email '$email' is already in use by another employee.");
        }

        // Step 3: Check for unique phone
        if ($this->employeeRepo->isPhoneExists($phone)) {
            throw new Exception("Error: The phone number '$phone' is already registered.");
        }

        try {
            $this->pdo->beginTransaction();
            
            $employee = new Employee($fullName, $email, $phone, $dateStart, $sectionId);
            $newEmpId = $this->employeeRepo->create($employee); 
            
            if ($laptopId !== null) {
                $stmt = $this->pdo->prepare("UPDATE laptops SET employee_id = :emp_id WHERE id = :lap_id AND employee_id IS NULL");
                $stmt->execute([':emp_id' => $newEmpId, ':lap_id' => $laptopId]);
            }
            
            $this->pdo->commit();
            
            // Log the successful operation
            AppLogger::log('EMPLOYEE_ADDED', "A new employee was added: $fullName (Email: $email)");
            
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Process updating an employee's details and modifying their laptop assignment.
    public function updateEmployee(int $id, string $fullName, string $email, string $phone, int $sectionId, ?int $laptopId = null): bool {
        
        $this->validateEmployeeData($fullName, $email, $phone, $sectionId);

        if ($this->employeeRepo->isEmailExists($email, $id)) {
            throw new Exception("Error: The email '$email' is already in use.");
        }
        if ($this->employeeRepo->isPhoneExists($phone, $id)) {
            throw new Exception("Error: The phone number '$phone' is already registered.");
        }

        $oldEmployee = $this->employeeRepo->findById($id);
        if (!$oldEmployee) {
            throw new Exception("Error: Employee not found!");
        }

        try {
            $this->pdo->beginTransaction();

            $employee = new Employee($fullName, $email, $phone, $oldEmployee->getDateStart(), $sectionId, $id);
            $this->employeeRepo->update($employee);
            
            // 1. Unassign any previously assigned laptops
            $stmt1 = $this->pdo->prepare("UPDATE laptops SET employee_id = NULL WHERE employee_id = :emp_id");
            $stmt1->execute([':emp_id' => $id]);
            
            // 2. Assign the new laptop (if provided)
            if ($laptopId !== null) {
                $stmt2 = $this->pdo->prepare("UPDATE laptops SET employee_id = :emp_id WHERE id = :lap_id");
                $stmt2->execute([':emp_id' => $id, ':lap_id' => $laptopId]);
            }

            $this->pdo->commit();
            
            // Log the successful update
            AppLogger::log('EMPLOYEE_UPDATED', "Employee ID ($id) details were updated by admin");
            
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Process employee deletion, gracefully handling related data (Laptops & Projects).
    public function deleteEmployee(int $id): bool {
        try {
            $this->pdo->beginTransaction();

            // 1. Return the employee's laptop to the warehouse
            $stmt1 = $this->pdo->prepare("UPDATE laptops SET employee_id = NULL WHERE employee_id = :id");
            $stmt1->execute([':id' => $id]);

            // 2. Remove the employee from all projects
            $stmt2 = $this->pdo->prepare("DELETE FROM project_employee WHERE employee_id = :id");
            $stmt2->execute([':id' => $id]);

            // 3. Finally, delete the employee using the Repo
            $isDeleted = $this->employeeRepo->delete($id) > 0;
            
            $this->pdo->commit();

            // Log the deletion (Warning level)
            AppLogger::log('EMPLOYEE_DELETED', "Employee ID ($id) was deleted from the system", 'WARNING');
            
            return $isDeleted;
            
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Failed to delete employee: " . $e->getMessage());
        }
    }
}