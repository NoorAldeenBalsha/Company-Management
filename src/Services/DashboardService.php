<?php
declare(strict_types=1);

class DashboardService {
    
    private EmployeeRepository $employeeRepo;
    private ProjectRepository $projectRepo;
    private LaptopRepository $laptopRepo;
    private SectionRepository $sectionRepo;

    // Constructor to inject the required repositories.
    public function __construct(
        EmployeeRepository $employeeRepo,
        ProjectRepository $projectRepo,
        LaptopRepository $laptopRepo,
        SectionRepository $sectionRepo
    ) {
        $this->employeeRepo = $employeeRepo;
        $this->projectRepo = $projectRepo;
        $this->laptopRepo = $laptopRepo;
        $this->sectionRepo = $sectionRepo;
    }

    // ==========================================
    // Data Aggregation
    // ==========================================

    // Fetch and aggregate all necessary data for the main dashboard view
     
    public function getDashboardData(): array {
        
        // 1. Compile General Statistics
        $stats = [
            'total_employees'   => $this->employeeRepo->countAll(),
            'total_sections'    => $this->sectionRepo->countAll(),
            'total_projects'    => $this->projectRepo->countAll(),
            'active_projects'   => $this->projectRepo->countByStatus('Work on'),
            'done_projects'     => $this->projectRepo->countByStatus('Done'),
            'total_laptops'     => $this->laptopRepo->countAll(),
            'available_laptops' => $this->laptopRepo->countAvailable(),
            'assigned_laptops'  => $this->laptopRepo->countAssigned(),
        ];

        // 2. Compile Dashboard Arrays & Actionable Alerts
        return [
            'stats'            => $stats,
            'latestHires'      => $this->employeeRepo->getLatestHires(5),
            'latestProjects'   => $this->projectRepo->getLatestProjects(5),
            'noLaptopEmps'     => $this->employeeRepo->getEmployeesWithoutLaptops(),
            'overloadedEmps'   => $this->employeeRepo->getOverloadedEmployees(),
            'sectionChartData' => $this->employeeRepo->getEmployeesCountPerSection()
        ];
    }
}