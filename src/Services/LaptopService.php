<?php

declare(strict_types=1);

class LaptopService {
    
    private LaptopRepository $laptopRepo;

    // Constructor to initialize the LaptopRepository instance.
    public function __construct(LaptopRepository $laptopRepo) { $this->laptopRepo = $laptopRepo;}

    // ==========================================
    // Core Business Operations
    // ==========================================

    // Process adding a new laptop to the warehouse or assigning it directly to an employee.
    public function addLaptop(Laptop $laptop): bool {
        
        // Fail-Fast: Check if serial number is unique
        if ($this->laptopRepo->isSerialNumberExists($laptop->getSerialNumber())) {
            throw new SerialNumberAlreadyExists("Error: The serial number '{$laptop->getSerialNumber()}' already exists.");
        }

        // Fail-Fast: If assigning to an employee directly, check if they already have one
        if ($laptop->getEmployeeId() !== null && $this->laptopRepo->hasLaptop($laptop->getEmployeeId())) {
            throw new EmployeeAlreadyHasLaptop("Error: This employee already has a laptop. They cannot have two.");
        }
        
        // Execute the database operation
        $isCreated = $this->laptopRepo->create($laptop);
        
        // Log ONLY if the operation was successful
        if ($isCreated) {
            AppLogger::log('LAPTOP_ADDED', "A new laptop (SN: {$laptop->getSerialNumber()}) was added to the system");
        }
        
        return $isCreated;
    }

    // Process updating laptop details and reassignments.
    public function updateLaptop(Laptop $laptop): bool {
        
        // Fail-Fast: Prevent assigning to an employee who already has a different laptop
        if ($laptop->getEmployeeId() !== null) {
            $existingLaptop = $this->laptopRepo->getLaptopByEmployeeId($laptop->getEmployeeId());
            if ($existingLaptop && (int)$existingLaptop['id'] !== $laptop->getId()) {
                throw new Exception("Error: This employee already has a laptop.");
            }
        }
        
        $isUpdated = $this->laptopRepo->update($laptop) > 0;
        
        if ($isUpdated) {
            AppLogger::log('LAPTOP_UPDATED', "Laptop ID ({$laptop->getId()}) details were updated");
        }
        
        return $isUpdated;
    }

    // Process deleting a laptop from the system permanently.
    public function deleteLaptop(int $id): bool {
        $laptop = $this->laptopRepo->findById($id);
        
        if (!$laptop) {
            throw new Exception("Error: Laptop not found.");
        }
        
        // Business Rule: Prevent deletion of assigned laptops (Data Integrity)
        if ($laptop->getEmployeeId() !== null) {
            throw new CannotDeleteLapAssignedToEmplloyee("You cannot delete this laptop because it is currently assigned to an employee. You must return it to the warehouse first.");}
        
        $isDeleted = $this->laptopRepo->delete($id) > 0;
        
        if ($isDeleted) {AppLogger::log('LAPTOP_DELETED', "Laptop ID ($id) was permanently deleted", 'WARNING'); }
        
        return $isDeleted;
    }

    // ==========================================
    //  Inventory Management
    // ==========================================

    // Process unassigning a laptop from an employee and returning it to the warehouse.
    public function returnToWarehouse(int $id): bool {
        
        $isReturned = $this->laptopRepo->returnToWarehouse($id) > 0;
        
        if ($isReturned) {
            AppLogger::log('LAPTOP_RETURNED', "Laptop ID ($id) was returned to the warehouse", 'INFO');
        }
        
        return $isReturned;
    }
}