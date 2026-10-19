<?php

declare(strict_types=1);

class EmployeeRepository {

    private PDO $pdo;

    // Constructor to initialize the database connection.
    public function __construct(PDO $pdo) { $this->pdo = $pdo;}

    // ==========================================
    // Core CRUD Operations 
    // ==========================================

    // Create a new employee in the database.
    public function create(Employee $employee): int { 
        $sql = "INSERT INTO employees (full_name, email, phone_number, date_start, section_id) 
                VALUES (:full_name, :email, :phone_number, :date_start, :section_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':full_name'    => $employee->getFullName(),
            ':email'        => $employee->getEmail(),
            ':phone_number' => $employee->getPhoneNumber(),
            ':date_start'   => $employee->getDateStart(),
            ':section_id'   => $employee->getSectionId()
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    // Update an existing employee's data.
    public function update(Employee $employee): int {
        $sql = "UPDATE employees 
                SET full_name = :full_name, email = :email, phone_number = :phone_number, section_id = :section_id 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':full_name'    => $employee->getFullName(),
            ':email'        => $employee->getEmail(),
            ':phone_number' => $employee->getPhoneNumber(),
            ':section_id'   => $employee->getSectionId(),
            ':id'           => $employee->getId()
        ]);
        return $stmt->rowCount();
    }

    // Delete an employee from the database.
    public function delete(int $id): int {
        $stmt = $this->pdo->prepare("DELETE FROM employees WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // ==========================================
    // Primary Fetch Methods 
    // ==========================================

    // Retrieve a specific employee by their ID.
    public function findById(int $id): ?Employee {
        $stmt = $this->pdo->prepare("SELECT * FROM employees WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        
        if (!$row) return null;
        
        return new Employee(
            $row['full_name'],
            $row['email'],
            $row['phone_number'],
            $row['date_start'],
            (int)$row['section_id'],
            (int)$row['id']
        );
    }

    // Retrieve all employees along with their section names.
    public function findAll(): array {
        $stmt = $this->pdo->query("
            SELECT e.*, s.name as section_name 
            FROM employees e 
            LEFT JOIN sections s ON e.section_id = s.id 
            ORDER BY e.id DESC
        ");
        return $stmt->fetchAll();
    }

    // ==========================================
    // Search & Filter Operations 
    // ==========================================

    // Search for employees by matching their name against a keyword.
    public function findByName(string $keyword): array {
        $stmt = $this->pdo->prepare("
            SELECT e.*, s.name as section_name 
            FROM employees e 
            LEFT JOIN sections s ON e.section_id = s.id 
            WHERE e.full_name LIKE :keyword 
            ORDER BY e.id DESC
        ");
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll();
    }

    // Retrieve all employees belonging to a specific section.
    public function findBySectionId(int $sectionId): array {
        $stmt = $this->pdo->prepare("
            SELECT e.*, s.name as section_name 
            FROM employees e 
            LEFT JOIN sections s ON e.section_id = s.id 
            WHERE e.section_id = :section_id
            ORDER BY e.id DESC
        ");
        $stmt->execute([':section_id' => $sectionId]);
        return $stmt->fetchAll();
    }

    // ==========================================
    // Validation & Checks 
    // ==========================================

    // Check if an email address is already in use.
    public function isEmailExists(string $email, int $excludeId = 0): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM employees WHERE email = :email AND id != :exclude_id");
        $stmt->execute([':email' => $email, ':exclude_id' => $excludeId]);
        return (int)$stmt->fetchColumn() > 0;
    }
    
    // Check if a phone number is already registered.
    public function isPhoneExists(string $phone, int $excludeId = 0): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM employees WHERE phone_number = :phone AND id != :exclude_id");
        $stmt->execute([':phone' => $phone, ':exclude_id' => $excludeId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // ==========================================
    // Business Logic (Laptop Management)
    // ==========================================

    // Retrieve employees who are eligible to receive a laptop (currently have none).
    public function findEligibleForLaptop(?int $currentEmployeeId = null): array {
        $sql = "SELECT * FROM employees WHERE id NOT IN (SELECT employee_id FROM laptops WHERE employee_id IS NOT NULL)";
        
        if ($currentEmployeeId !== null) {
            $sql .= " OR id = :emp_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':emp_id' => $currentEmployeeId]);
            return $stmt->fetchAll();
        }
        
        return $this->pdo->query($sql)->fetchAll();
    }

    //Retrieve all employees who strictly do not possess a laptop.
    
    public function getEmployeesWithoutLaptops(): array {
        return $this->pdo->query(
            "SELECT * FROM employees WHERE id NOT IN 
            (SELECT employee_id FROM laptops WHERE employee_id IS NOT NULL)")
            ->fetchAll();
    }

    // ==========================================
    // Dashboard & Statistics 
    // ==========================================

    // Get the total count of all employees in the company.
    public function countAll(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM employees")->fetchColumn();
    }

    //Retrieve the most recently hired employees.
    public function getLatestHires(int $limit = 5): array {
        $stmt = $this->pdo->prepare("SELECT e.*, s.name as section_name FROM employees e LEFT JOIN sections s ON e.section_id = s.id ORDER BY e.id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Retrieve employees who are assigned to 3 or more active projects.
    public function getOverloadedEmployees(): array {
        return $this->pdo->query("
            SELECT e.full_name, COUNT(pe.project_id) as project_count 
            FROM employees e 
            JOIN project_employee pe ON e.id = pe.employee_id 
            JOIN projects p ON pe.project_id = p.id 
            WHERE p.status = 'Work on' 
            GROUP BY e.id 
            HAVING project_count >= 3
        ")->fetchAll();
    }

    // Get the distribution of employees across different sections.
    public function getEmployeesCountPerSection(): array {
        return $this->pdo->query("
            SELECT s.name as section_name, COUNT(e.id) as emp_count 
            FROM sections s 
            LEFT JOIN employees e ON s.id = e.section_id 
            GROUP BY s.id
        ")->fetchAll();
    }
}