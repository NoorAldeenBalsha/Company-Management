<?php
declare(strict_types=1);

class DashboardRepository {
    
    private PDO $pdo;

    public function __construct(PDO $pdo) { $this->pdo = $pdo;}

    // Get total number of employees
    public function getTotalEmployees(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM employees");
        return (int)$stmt->fetchColumn();
    }

    // Get count of active projects only
    public function getActiveProjectsCount(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'Work on'");
        return (int)$stmt->fetchColumn();
    }

    // Get count of laptops available in the warehouse
    public function getAvailableLaptopsCount(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM laptops WHERE employee_id IS NULL");
        return (int)$stmt->fetchColumn();
    }
}