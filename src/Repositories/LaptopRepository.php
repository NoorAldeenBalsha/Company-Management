<?php
declare(strict_types=1);

class LaptopRepository {
    
    private PDO $pdo;

    //Constructor to initialize the database connection.
    public function __construct(PDO $pdo) { $this->pdo = $pdo;}

    // ==========================================
    // Core CRUD Operations
    // ==========================================

    // Create a new laptop in the database.
    public function create(Laptop $laptop): bool {
        $sql = "INSERT INTO laptops (serial_number, model, date_buy, employee_id) 
                VALUES (:serial_number, :model, :date_buy, :employee_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':serial_number' => $laptop->getSerialNumber(),
            ':model'         => $laptop->getModel(),
            ':date_buy'      => $laptop->getDateBuy(),
            ':employee_id'   => $laptop->getEmployeeId()
        ]);
    }

    
    //Update an existing laptop's data.
    public function update(Laptop $laptop): int {
        $sql = "UPDATE laptops SET model = :model, employee_id = :employee_id WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':model'       => $laptop->getModel(),
            ':employee_id' => $laptop->getEmployeeId(),
            ':id'          => $laptop->getId()
        ]);
        return $stmt->rowCount();
    }

    // Delete a laptop permanently from the database.
    public function delete(int $id): int {
        $stmt = $this->pdo->prepare("DELETE FROM laptops WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // ==========================================
    // Primary Fetch Methods
    // ==========================================

    // Retrieve a specific laptop by its ID and return it as an Object.
    public function findById(int $id): ?Laptop {
        $stmt = $this->pdo->prepare("SELECT * FROM laptops WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        
        if (!$row) return null;
        
        // Handle nullable employee_id correctly
        $empId = $row['employee_id'] !== null ? (int)$row['employee_id'] : null;
        
        return new Laptop(
            $row['serial_number'], 
            $row['model'], 
            $row['date_buy'], 
            $empId, 
            (int)$row['id']
        );
    }

    // Retrieve all laptops along with the assigned employee's name (if any).
    public function findAll(): array {
        $stmt = $this->pdo->query("
            SELECT l.*, e.full_name 
            FROM laptops l 
            LEFT JOIN employees e ON l.employee_id = e.id 
            ORDER BY l.id DESC
        ");
        return $stmt->fetchAll();
    }

    // ==========================================
    // Validation & Checks
    // ==========================================

    // Check if a serial number already exists in the system.
    public function isSerialNumberExists(string $serialNumber, int $excludeId = 0): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM laptops WHERE serial_number = :serial AND id != :exclude_id");
        $stmt->execute([':serial' => $serialNumber, ':exclude_id' => $excludeId]);
        return (int)$stmt->fetchColumn() > 0;
    }
    
    // Check if a specific employee is already holding a laptop.
    public function hasLaptop(int $employeeId): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM laptops WHERE employee_id = :employee_id");
        $stmt->execute([':employee_id' => $employeeId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // ==========================================
    // Business Logic & Assignment
    // ==========================================

    // Unassign a laptop from an employee and return it to the warehouse.
    public function returnToWarehouse(int $id): int {
        $stmt = $this->pdo->prepare("UPDATE laptops SET employee_id = NULL WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // Retrieve only the laptops that are currently in the warehouse (unassigned).
    public function getAvailableLaptops(): array {
        $stmt = $this->pdo->query("SELECT * FROM laptops WHERE employee_id IS NULL ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // Retrieve the specific laptop currently assigned to a given employee.
     
    public function getLaptopByEmployeeId(int $employeeId): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM laptops WHERE employee_id = :emp_id LIMIT 1");
        $stmt->execute([':emp_id' => $employeeId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // ==========================================
    // Dashboard & Statistics
    // ==========================================

    // Get the total count of all laptops in the company.
    public function countAll(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM laptops")->fetchColumn();
    }

    // Get the count of laptops currently stored in the warehouse.
    public function countAvailable(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM laptops WHERE employee_id IS NULL")->fetchColumn();
    }

    // Get the count of laptops currently assigned to employees.
    public function countAssigned(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM laptops WHERE employee_id IS NOT NULL")->fetchColumn();
    }
}