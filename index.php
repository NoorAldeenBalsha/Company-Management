<?php
declare(strict_types=1);
session_start();

// Embedding files & Logger Initialization
require_once __DIR__ . '/src/autoload.php';
require_once __DIR__ . '/src/Utils/AppLogger.php';


AppLogger::init(__DIR__ . '/logs/system.log');
//=====================================================================================
// Setting up the connection & Dependencies
$config = require __DIR__ . '/config/config.php';
$db = new Database($config);
$pdo = $db->getConnection();

//  Repositories 
$employeeRepo = new EmployeeRepository($pdo);
$laptopRepo   = new LaptopRepository($pdo);
$projectRepo  = new ProjectRepository($pdo);
$sectionRepo  = new SectionRepository($pdo);

//  Services 
$employeeService  = new EmployeeService($employeeRepo, $pdo);
$laptopService    = new LaptopService($laptopRepo);
$projectService   = new ProjectService($projectRepo, $pdo);
$sectionService   = new SectionService($sectionRepo);
$dashboardService = new DashboardService($employeeRepo, $projectRepo, $laptopRepo, $sectionRepo);
//=====================================================================================
//  Routing Variables
$page   = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'list';
//=====================================================================================
// Global Helper Functions

// XSS Protection Function
function escape(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Helper function to render views inside the Master Layout
function view(string $path, array $data = []) {
    extract($data);
    ob_start();
    require __DIR__ . "/views/$path.php";
    $content = ob_get_clean();
    require __DIR__ . "/views/layout/master.php";
    exit; 
}
//=====================================================================================
//  Main Controller Logic (Router)

// Dashboard
if ($page === 'dashboard') {
    $dashboardData = $dashboardService->getDashboardData();
    view('dashboard/index', $dashboardData);
}

// System logs
elseif ($page === 'system-logs') {
    $logs = AppLogger::readLogs();
    view('logs/logs', ['logs' => $logs]);
}

// Employee
elseif ($page === 'employees') {
    $allSections = $sectionRepo->findAll();
    
    if ($action === 'list') {
        $keyword = trim($_GET['search'] ?? '');
        $filterSectionId = (int)($_GET['section_id'] ?? 0);
        
        if ($keyword !== '') {
            $employees = $employeeRepo->findByName($keyword);
        } elseif ($filterSectionId > 0) {
            $employees = $employeeRepo->findBySectionId($filterSectionId);
        } else {
            $employees = $employeeRepo->findAll();
        }
        view('employees/list', [
            'employees' => $employees,
            'searchKeyword' => $keyword,
            'allSections' => $allSections,
            'filterSectionId' => $filterSectionId
        ]);
    }
    elseif ($action === 'create') { 
        $availableLaptops = $laptopRepo->getAvailableLaptops();
        view('employees/create', ['allSections' => $allSections, 'availableLaptops' => $availableLaptops]);
    } 
    elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $laptopId = !empty($_POST['laptop_id']) ? (int)$_POST['laptop_id'] : null;
            $employeeService->addEmployee(
                $_POST['full_name'] ?? '',
                $_POST['email'] ?? '',
                $_POST['phone_number'] ?? '',
                $_POST['date_start'] ?? '',
                (int)($_POST['section_id'] ?? 0),
                $laptopId
            );
            $_SESSION['success'] = "Employee added successfully!";
            unset($_SESSION['old_input']);
            header('Location: index.php?page=employees&action=list');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header('Location: index.php?page=employees&action=create');
            exit;
        }
    }
    elseif ($action === 'edit') {
        $id = (int)($_GET['id'] ?? 0);
        $employee = $employeeRepo->findById($id);
        if (!$employee) {
            $_SESSION['error'] = "The employee is not here!";
            header('Location: index.php?page=employees&action=list');
            exit;
        }
        
        $availableLaptops = $laptopRepo->getAvailableLaptops();
        $currentLaptop = $laptopRepo->getLaptopByEmployeeId($id);
        
        view('employees/edit', [
            'employee' => $employee, 
            'allSections' => $allSections,
            'availableLaptops' => $availableLaptops,
            'currentLaptop' => $currentLaptop
        ]);
    }
    elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $laptopId = !empty($_POST['laptop_id']) ? (int)$_POST['laptop_id'] : null;
        try {
            $employeeService->updateEmployee(
                $id,
                $_POST['full_name'] ?? '',
                $_POST['email'] ?? '',
                $_POST['phone_number'] ?? '',
                (int)($_POST['section_id'] ?? 0),
                $laptopId
            );
            $_SESSION['success'] = "Modified successfully!";
            unset($_SESSION['old_input']);
            header('Location: index.php?page=employees&action=list');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header("Location: index.php?page=employees&action=edit&id=$id");
            exit;
        }
    }
    elseif ($action === 'delete') {
        $id = (int)($_GET['id'] ?? 0);
        $employee = $employeeRepo->findById($id);
        if (!$employee) {
            $_SESSION['error'] = "The employee is not here!";
            header('Location: index.php?page=employees&action=list');
            exit;
        }
        view('employees/delete', ['employee' => $employee]);
    }
    elseif ($action === 'destroy' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $employeeService->deleteEmployee($id);
            $_SESSION['success'] = "Deleted successfully!";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: index.php?page=employees&action=list');
        exit;
    }
    elseif ($action === 'projects') {
        $id = (int)($_GET['id'] ?? 0);
        $employee = $employeeRepo->findById($id);
        
        if (!$employee) {
            $_SESSION['error'] = "Employee not found!";
            header('Location: index.php?page=employees&action=list');
            exit;
        }
        
        $employeeProjects = $projectRepo->getProjectsByEmployeeId($id);
        view('employees/projects', [
            'employee' => $employee, 
            'projects' => $employeeProjects
        ]);
    }
}

// Project
elseif ($page === 'projects') {
    if ($action === 'list') {
        $projects = $projectRepo->findAll();
        view('projects/list', ['projects' => $projects]);
    }
    elseif ($action === 'create') { 
        $allEmployees = $employeeRepo->findAll();
        view('projects/create', ['allEmployees' => $allEmployees]);
    }
    elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $project = new Project(
                $_POST['name'] ?? '',
                $_POST['customer_name'] ?? '',
                $_POST['date_start'] ?? '',
                $_POST['status'] ?? 'Work on'
            );
            $leaderId = (int)($_POST['leader_id'] ?? 0);
            
            $projectService->addProject($project, $leaderId);
            
            $_SESSION['success'] = "The project and its leader have been added successfully!";
            unset($_SESSION['old_input']);
            header('Location: index.php?page=projects&action=list');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header('Location: index.php?page=projects&action=create');
            exit;
        }
    }
    elseif ($action === 'edit') {
        $id = (int)($_GET['id'] ?? 0);
        $project = $projectRepo->findById($id);
        if (!$project) {
            $_SESSION['error'] = "Project not found!";
            header('Location: index.php?page=projects&action=list');
            exit;
        }
        view('projects/edit', ['project' => $project]);
    }
    elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $project = new Project(
                $_POST['name'] ?? '',
                $_POST['customer_name'] ?? '',
                $_POST['date_start'] ?? '',
                $_POST['status'] ?? 'Work on',
                $id
            );
            $projectService->updateProject($project);
            $_SESSION['success'] = "Project updated successfully!";
            unset($_SESSION['old_input']);
            header('Location: index.php?page=projects&action=list');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header("Location: index.php?page=projects&action=edit&id=$id");
            exit;
        }
    }
    elseif ($action === 'delete') {
        $id = (int)($_GET['id'] ?? 0);
        $project = $projectRepo->findById($id);
        if (!$project) {
            $_SESSION['error'] = "Project not found!";
            header('Location: index.php?page=projects&action=list');
            exit;
        }
        view('projects/delete', ['project' => $project]);
    }
    elseif ($action === 'destroy' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $projectService->deleteProject($id);
            $_SESSION['success'] = "Project deleted successfully!";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: index.php?page=projects&action=list');
        exit;
    }
    elseif ($action === 'team') {
        $id = (int)($_GET['id'] ?? 0);
        $project = $projectRepo->findById($id);
        if (!$project) {
            $_SESSION['error'] = "The project doesn't exist!";
            header('Location: index.php?page=projects&action=list');
            exit;
        }
        $team = $projectRepo->getTeamByProjectId($id);
        $allEmployees = $employeeRepo->findAll();
        view('projects/team', [ 'project' => $project,'team' => $team,'allEmployees' => $allEmployees]);
    }
    elseif ($action === 'assign' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $projectId = (int)($_POST['project_id'] ?? 0);
        $employeeId = (int)($_POST['employee_id'] ?? 0);
        $role = $_POST['role'] ?? '';
        try {
            $projectService->assignEmployeeToProject($projectId, $employeeId, $role);
            $_SESSION['success'] = "The employee has been successfully assigned to the project!";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header("Location: index.php?page=projects&action=team&id=$projectId");
        exit;
    }
    elseif ($action === 'confirm_remove') {
        $projectId = (int)($_GET['project_id'] ?? 0);
        $employeeId = (int)($_GET['employee_id'] ?? 0);
        
        $project = $projectRepo->findById($projectId);
        $employee = $employeeRepo->findById($employeeId);
        
        $role = $projectRepo->getEmployeeRole($projectId, $employeeId);
        $allEmployees = $employeeRepo->findAll();
        
        view('projects/remove_confirm', [
            'project' => $project, 
            'employee' => $employee, 
            'role' => $role, 
            'allEmployees' => $allEmployees
        ]);
    }
    elseif ($action === 'remove_member' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $projectId = (int)($_POST['project_id'] ?? 0);
        $employeeId = (int)($_POST['employee_id'] ?? 0);
        $newLeaderId = !empty($_POST['new_leader_id']) ? (int)$_POST['new_leader_id'] : null;
        
        try {
            $projectService->removeEmployeeFromProject($projectId, $employeeId, $newLeaderId);
            $_SESSION['success'] = "The employee was removed successfully.";
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: index.php?page=projects&action=team&id=$projectId");
        exit;
    }
}

// Section
elseif ($page === 'sections') {
    if ($action === 'list') {
        $sections = $sectionRepo->findAll();
        view('sections/list', ['sections' => $sections]);
    }
    elseif ($action === 'create') {view('sections/create');}
    elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $name = trim($_POST['name'] ?? '');
            $section = new Section($name);
            $sectionService->addSection($section);
            
            $_SESSION['success'] = "Section added successfully!";
            unset($_SESSION['old_input']);
            header('Location: index.php?page=sections&action=list');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header('Location: index.php?page=sections&action=create');
            exit;
        }
    }
    elseif ($action === 'edit') {
        $id = (int)($_GET['id'] ?? 0);
        $section = $sectionRepo->findById($id);
        
        if (!$section) {
            $_SESSION['error'] = "Section not found!";
            header('Location: index.php?page=sections&action=list');
            exit;
        }
        view('sections/edit', ['section' => $section]);
    }
    elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        
        try {
            $section = new Section($name, $id);
            $sectionService->updateSection($section);
            
            $_SESSION['success'] = "Section updated successfully!";
            unset($_SESSION['old_input']);
            header('Location: index.php?page=sections&action=list');
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header("Location: index.php?page=sections&action=edit&id=$id");
            exit;
        }
    }
    elseif ($action === 'delete') {
        $id = (int)($_GET['id'] ?? 0);
        $section = $sectionRepo->findById($id);
        if (!$section) {
            $_SESSION['error'] = "Section not found!";
            header('Location: index.php?page=sections&action=list');
            exit;
        }
        view('sections/delete', ['section' => $section]);
    }
    elseif ($action === 'destroy' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $sectionService->deleteSection((int)($_POST['id'] ?? 0));
            $_SESSION['success'] = "Section deleted successfully!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Cannot delete: " . $e->getMessage();
        }
        header('Location: index.php?page=sections&action=list');
        exit;
    }
}

// Laptops
elseif ($page === 'laptops') {
    if ($action === 'list') {
        $laptops = $laptopRepo->findAll();
        view('laptops/list', ['laptops' => $laptops]);
    }
    elseif ($action === 'create') {
        $eligibleEmployees = $employeeRepo->findEligibleForLaptop();
        view('laptops/create', ['allEmployees' => $eligibleEmployees]);
    }
    elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $serialNumber = trim($_POST['serial_number'] ?? '');
        $model = trim($_POST['model'] ?? ''); 
        $dateBuy = $_POST['date_buy'] ?? date('Y-m-d'); 
        $employeeId = !empty($_POST['employee_id']) ? (int)$_POST['employee_id'] : null;

        try {
            $laptop = new Laptop($serialNumber, $model, $dateBuy, $employeeId);
            $laptopService->addLaptop($laptop);

            $_SESSION['success'] = "Laptop added successfully!";
            unset($_SESSION['old_input']);
            header('Location: index.php?page=laptops&action=list');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST; 
            header('Location: index.php?page=laptops&action=create');
            exit;
        }
    }
    elseif ($action === 'edit') {
        $id = (int)($_GET['id'] ?? 0);
        $laptop = $laptopRepo->findById($id);
        
        if (!$laptop) {
            $_SESSION['error'] = "Laptop not found!";
            header('Location: index.php?page=laptops&action=list');
            exit;
        }
        $eligibleEmployees = $employeeRepo->findEligibleForLaptop($laptop->getEmployeeId());
        view('laptops/edit', ['laptop' => $laptop, 'allEmployees' => $eligibleEmployees]);
    }
    elseif ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        $serialNumber = trim($_POST['serial_number'] ?? '');
        $model = trim($_POST['model'] ?? ''); 
        $dateBuy = $_POST['date_buy'] ?? ''; 
        $employeeId = !empty($_POST['employee_id']) ? (int)$_POST['employee_id'] : null;

        try {
            $laptop = new Laptop($serialNumber, $model, $dateBuy, $employeeId, $id);
            $laptopService->updateLaptop($laptop);

            $_SESSION['success'] = "Laptop updated successfully!";
            unset($_SESSION['old_input']);
            header('Location: index.php?page=laptops&action=list');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header("Location: index.php?page=laptops&action=edit&id=$id");
            exit;
        }
    }
    elseif ($action === 'delete') {
        $id = (int)($_GET['id'] ?? 0);
        $laptop = $laptopRepo->findById($id);
        
        if (!$laptop) {
            $_SESSION['error'] = "Laptop not found!";
            header('Location: index.php?page=laptops&action=list');
            exit;
        }
        view('laptops/delete', ['laptop' => $laptop]);
    }
    elseif ($action === 'destroy' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $laptopService->deleteLaptop($id);
            $_SESSION['success'] = "Laptop deleted successfully!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error deleting laptop: " . $e->getMessage();
        }
        header('Location: index.php?page=laptops&action=list');
        exit;
    }
    elseif ($action === 'unassign' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = (int)($_POST['id'] ?? 0);
        try {
            $laptopService->returnToWarehouse($id);
            $_SESSION['success'] = "Laptop returned to warehouse successfully!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        header('Location: index.php?page=laptops&action=list');
        exit;
    }
}

// Not found
else {
    view('NotFound/404');
}