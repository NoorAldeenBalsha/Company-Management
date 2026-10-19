<?php

declare(strict_types=1);

class ReportRepository {
    
    private PDO $pdo;

    // Constructor to initialize the database connection.
    public function __construct(PDO $pdo) {$this->pdo = $pdo;}

    // ==========================================
    // Employee & Section Reports
    // ==========================================

    // Retrieve all employees belonging to a specific section.
    public function getEmployeesBySection(int $sectionId): array {
        $sql = "SELECT id, full_name, email, phone_number, date_start 
                FROM employees WHERE section_id = :section_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':section_id' => $sectionId]);
        return $stmt->fetchAll();
    }

    // Search for employees by their full name, including their section name.
    public function searchEmployeeByName(string $name): array {
        $sql = "SELECT e.*, s.name AS section_name 
                FROM employees e
                JOIN sections s ON e.section_id = s.id
                WHERE e.full_name LIKE :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':name' => '%' . $name . '%']);
        return $stmt->fetchAll();
    }

    // ==========================================
    // Project Reports
    // ==========================================

    // Retrieve detailed information about a project's team members and their roles.
    public function getProjectDetails(int $projectId): array {
        $sql = "SELECT e.full_name, pe.role 
                FROM project_employee pe
                JOIN employees e ON pe.employee_id = e.id
                WHERE pe.project_id = :project_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll();
    }

    // Retrieve all projects a specific employee is working on, along with their status and role.
    public function getProjectsByEmployee(int $employeeId): array {
        $sql = "SELECT p.name, p.status, pe.role 
                FROM project_employee pe
                JOIN projects p ON pe.project_id = p.id
                WHERE pe.employee_id = :employee_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':employee_id' => $employeeId]);
        return $stmt->fetchAll();
    }

    // ==========================================
    // Inventory & Laptop Reports
    // ==========================================

    // Display the inventory of all available laptops currently stored in the warehouse.
    public function getWarehouseLaptops(): array {
        $sql = "SELECT serial_number, model, date_buy 
                FROM laptops WHERE employee_id IS NULL";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
}