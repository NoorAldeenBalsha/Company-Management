<?php

declare(strict_types=1);

class ProjectRepository {
    
    private PDO $pdo;

    // Constructor to initialize the database connection.
    public function __construct(PDO $pdo) {$this->pdo = $pdo;}

    // ==========================================
    // Core CRUD Operations
    // ==========================================

    //Create a new project in the database.
    public function create(Project $project): int {
        $sql = "INSERT INTO projects (name, customer_name, date_start, status) 
                VALUES (:name, :customer_name, :date_start, :status)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name'          => $project->getName(),
            ':customer_name' => $project->getCustomerName(),
            ':date_start'    => $project->getDateStart(),
            ':status'        => $project->getStatus()
        ]);
        return (int)$this->pdo->lastInsertId(); 
    }
    // Update all details of an existing project.
    public function update(Project $project): int {
        $sql = "UPDATE projects 
                SET name = :name, customer_name = :customer_name, date_start = :date_start, status = :status 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':name'          => $project->getName(),
            ':customer_name' => $project->getCustomerName(),
            ':date_start'    => $project->getDateStart(),
            ':status'        => $project->getStatus(),
            ':id'            => $project->getId()
        ]);
        return $stmt->rowCount();
    }
    // Delete a project permanently from the database.
      function delete(int $id): int {
        $stmt = $this->pdo->prepare("DELETE FROM projects WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    //Update only the status of a specific project.
    public function updateStatus(int $id, string $status): int {
        $stmt = $this->pdo->prepare("UPDATE projects SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $id]);
        return $stmt->rowCount();
    }

    // ==========================================
    // Primary Fetch Methods
    // ==========================================

    //Retrieve a specific project by its ID and return it as an Object.
    public function findById(int $id): ?Project {
        $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        
        if (!$row) return null;
        
        return new Project(
            $row['name'],
            $row['customer_name'],
            $row['date_start'],
            $row['status'],
            (int)$row['id']
        );
    }

    // Retrieve all projects from the database.
    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM projects ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // ==========================================
    // Team & Pivot Table Operations
    // ==========================================

    // Assign an employee to a project with a specific role.
    public function assignEmployee(int $projectId, int $employeeId, string $role): bool {
        $sql = "INSERT INTO project_employee (project_id, employee_id, role) VALUES (:project_id, :employee_id, :role)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':project_id'  => $projectId,
            ':employee_id' => $employeeId,
            ':role'        => $role
        ]);
    }
    
    // Remove an employee from a specific project.
    public function removeEmployee(int $projectId, int $employeeId): int {
        $sql = "DELETE FROM project_employee WHERE project_id = :project_id AND employee_id = :employee_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':project_id' => $projectId, ':employee_id' => $employeeId]);
        return $stmt->rowCount();
    }

    //Retrieve all members assigned to a specific project along with their roles.
    public function getTeamByProjectId(int $projectId): array {
        $sql = "SELECT e.id as employee_id, e.full_name, pe.role 
                FROM project_employee pe 
                JOIN employees e ON pe.employee_id = e.id 
                WHERE pe.project_id = :project_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':project_id' => $projectId]);
        return $stmt->fetchAll();
    }

    // Get the exact role of an employee in a specific project.
    public function getEmployeeRole(int $projectId, int $employeeId): ?string {
        $stmt = $this->pdo->prepare("SELECT role FROM project_employee WHERE project_id = :pid AND employee_id = :eid LIMIT 1");
        $stmt->execute([':pid' => $projectId, ':eid' => $employeeId]);
        $role = $stmt->fetchColumn();
        return $role ? (string)$role : null;
    }

    // Set a new leader for a project (promotes existing member or assigns a new one).
    public function setLeader(int $projectId, int $employeeId): void {
        if ($this->isEmployeeInProject($projectId, $employeeId)) {
            $stmt = $this->pdo->prepare("UPDATE project_employee SET role = 'lead' WHERE project_id = :pid AND employee_id = :eid");
            $stmt->execute([':pid' => $projectId, ':eid' => $employeeId]);
        } else {
            $this->assignEmployee($projectId, $employeeId, 'lead');
        }
    }

    // ==========================================
    // Business Logic & Validation
    // ==========================================

    // Check if a project already has a designated leader.
    public function hasLead(int $projectId): bool {
        $sql = "SELECT COUNT(*) FROM project_employee WHERE project_id = :project_id AND role = 'lead'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':project_id' => $projectId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // Check if an employee is currently part of a project's team.eturn bool True if assigned, false otherwise.
    public function isEmployeeInProject(int $projectId, int $employeeId): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM project_employee WHERE project_id = :pid AND employee_id = :eid");
        $stmt->execute([':pid' => $projectId, ':eid' => $employeeId]);
        return $stmt->fetchColumn() > 0;
    }

    // Count how many active ("Work on") projects an employee is currently assigned to.
    public function getActiveProjectsCountForEmployee(int $employeeId): int {
        $sql = "SELECT COUNT(*) FROM project_employee pe 
                JOIN projects p ON pe.project_id = p.id 
                WHERE pe.employee_id = :employee_id AND p.status = 'Work on'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':employee_id' => $employeeId]);
        return (int)$stmt->fetchColumn();
    }

    // Retrieve all projects an employee is working on, including their roles.
    public function getProjectsByEmployeeId(int $employeeId): array {
        $sql = "SELECT p.*, pe.role 
                FROM project_employee pe 
                JOIN projects p ON pe.project_id = p.id 
                WHERE pe.employee_id = :employee_id
                ORDER BY p.id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':employee_id' => $employeeId]);
        return $stmt->fetchAll();
    }

    // ==========================================
    // Dashboard & Statistics
    // ==========================================

    // Get the total count of all projects in the system.
    public function countAll(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
    }

    // Get the count of projects currently marked as "Work on" (active).
    public function countActive(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM projects WHERE status = 'Work on'")->fetchColumn();
    }

    // Get the count of projects based on a specific status.
    public function countByStatus(string $status): int {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM projects WHERE status = :status");
        $stmt->execute([':status' => $status]);
        return (int)$stmt->fetchColumn();
    }

    // Retrieve the most recently created projects.
    public function getLatestProjects(int $limit = 5): array {
        $stmt = $this->pdo->prepare("SELECT * FROM projects ORDER BY id DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}