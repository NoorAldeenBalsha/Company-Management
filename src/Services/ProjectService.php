<?php
declare(strict_types=1);

class ProjectService {
    
    private ProjectRepository $projectRepo;
    private PDO $pdo;

    // Constructor to initialize dependencies.
    public function __construct(ProjectRepository $projectRepo, PDO $pdo) {
        $this->projectRepo = $projectRepo;
        $this->pdo = $pdo;
    }

    // ==========================================
    //  Validation Logic 
    // ==========================================

    // Strict data validation for project inputs.
    private function validateProjectData(Project $project): void {
        if (empty(trim($project->getName()))) {
            throw new InvalidArgumentException("Error: Project name cannot be empty.");
        }
        if (empty(trim($project->getCustomerName()))) {
            throw new InvalidArgumentException("Error: Customer name cannot be empty.");
        }
        if (empty($project->getDateStart()) || !strtotime($project->getDateStart())) {
            throw new InvalidArgumentException("Error: Invalid start date.");
        }
    }

    // ==========================================
    // Core Project Operations
    // ==========================================

    // Process adding a new project and assigning a mandatory team leader.
    public function addProject(Project $project, int $leaderId): bool {
        $this->validateProjectData($project);
        
        if ($leaderId <= 0) {
            throw new InvalidArgumentException("Error: You must assign a Leader to create the project.");
        }

        try {
            $this->pdo->beginTransaction();
            
            // 1. Create the project and get its new ID
            $projectId = $this->projectRepo->create($project);
            
            // 2. Assign the leader immediately
            $this->projectRepo->assignEmployee($projectId, $leaderId, 'lead');
            
            $this->pdo->commit();   
            
            // Log only after successful commit
            AppLogger::log('PROJECT_ADDED', "A new project was created: {$project->getName()} with Leader ID: $leaderId");
            
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Failed to create project: " . $e->getMessage());
        }
    }

    // Process updating a project's details.
    public function updateProject(Project $project): bool {
        $this->validateProjectData($project);
        
        $isUpdated = $this->projectRepo->update($project) > 0;
        
        if ($isUpdated) {
            AppLogger::log('PROJECT_UPDATED', "Project ID ({$project->getId()}) was updated");
        }
        
        return $isUpdated;
    }

    // Process deleting a project permanently, including clearing its team members.
    public function deleteProject(int $id): bool {
        try {
            $this->pdo->beginTransaction();
            
            // 1. Remove all employees from the project first (Pivot table)
            $stmt = $this->pdo->prepare("DELETE FROM project_employee WHERE project_id = :id");
            $stmt->execute([':id' => $id]);

            // 2. Delete the project itself
            $isDeleted = $this->projectRepo->delete($id) > 0;
            
            $this->pdo->commit();
            
            if ($isDeleted) {
                AppLogger::log('PROJECT_DELETED', "Project ID ($id) was permanently deleted", 'WARNING');
            }
            
            return $isDeleted;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception("Failed to delete project: " . $e->getMessage());
        }
    }

    // ==========================================
    // Team Management 
    // ==========================================

    // Assign an employee to a project with strict business rules verification.
    public function assignEmployeeToProject(int $projectId, int $employeeId, string $role): bool {
        
        // Rule 1: Check if already in the team
        if ($this->projectRepo->isEmployeeInProject($projectId, $employeeId)) {
            throw new Exception("Error: This employee is already assigned to this project.");
        }
        
        // Rule 2: Only one Lead per project
        if ($role === 'lead' && $this->projectRepo->hasLead($projectId)) {
            throw new Exception("Error: This project already has a Lead.");
        }
        
        // Rule 3: Max 3 active projects per employee
        $activeCount = $this->projectRepo->getActiveProjectsCountForEmployee($employeeId);
        if ($activeCount >= 3) {
            throw new Exception("Error: This employee is already working on 3 active projects.");
        }
        
        $isAssigned = $this->projectRepo->assignEmployee($projectId, $employeeId, $role);
        
        if ($isAssigned) {
            AppLogger::log('TEAM_ASSIGNED', "Employee ID ($employeeId) was assigned to Project ID ($projectId) as $role");
        }
        
        return $isAssigned;
    }

    // Remove an employee from a project.
    public function removeEmployeeFromProject(int $projectId, int $employeeId, ?int $newLeaderId = null): bool {
        // Check the current role of the employee
        $role = $this->projectRepo->getEmployeeRole($projectId, $employeeId);

        // Leader Removal Logic (Requires Transaction)
        if ($role === 'lead') {
            if (!$newLeaderId) {
                throw new Exception("Error: You cannot remove the Project Leader without assigning a new one.");
            }
            if ($employeeId === $newLeaderId) {
                throw new Exception("Error: The new leader must be a different employee.");
            }

            try {
                $this->pdo->beginTransaction();
                
                // 1. Assign the new leader first to ensure the project isn't left leaderless
                $this->projectRepo->setLeader($projectId, $newLeaderId);
                
                // 2. Remove the old leader
                $this->projectRepo->removeEmployee($projectId, $employeeId);
                
                $this->pdo->commit();
                
                AppLogger::log('TEAM_REMOVED', "Employee ID ($employeeId) was removed from Project ID ($projectId). (Replaced by Leader ID: $newLeaderId)", 'WARNING');
                return true;
            } catch (Exception $e) {
                $this->pdo->rollBack();
                throw $e;
            }
        }

        // Standard Employee Removal Logic
        $isRemoved = $this->projectRepo->removeEmployee($projectId, $employeeId) > 0;
        
        if ($isRemoved) {
            AppLogger::log('TEAM_REMOVED', "Employee ID ($employeeId) was removed from Project ID ($projectId)", 'WARNING');
        }
        
        return $isRemoved;
    }
}