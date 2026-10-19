<?php /** @var string $content */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Management System - Noor Aldeen</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-brand { font-weight: 800; letter-spacing: 1px; }
        .nav-link { font-weight: 500; transition: all 0.3s ease; }
        .nav-link:hover, .nav-link.active { color: #0dcaf0 !important; transform: translateY(-1px); }
        .content-wrapper { 
            background: #ffffff; 
            border-radius: 10px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
            padding: 30px; 
            margin-top: 25px; 
            min-height: 80vh;
        }
    </style>
</head>
<body>

    <!--  NAVBAR  -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="index.php?page=dashboard">
                <i class="bi bi-buildings-fill text-info"></i> CorpSys
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?= (!isset($_GET['page']) || $_GET['page'] === 'dashboard') ? 'active' : '' ?>" href="index.php?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'sections') ? 'active' : '' ?>" href="index.php?page=sections&action=list"><i class="bi bi-diagram-3"></i> Sections</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'employees') ? 'active' : '' ?>" href="index.php?page=employees&action=list"><i class="bi bi-people"></i> Employees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'projects') ? 'active' : '' ?>" href="index.php?page=projects&action=list"><i class="bi bi-briefcase"></i> Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['page']) && $_GET['page'] === 'laptops') ? 'active' : '' ?>" href="index.php?page=laptops&action=list"><i class="bi bi-laptop"></i> Laptops</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-sm btn-outline-warning fw-bold" href="index.php?page=system-logs">
                            <i class="bi bi-terminal"></i> System Logs
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- ========================================== -->

    <!--  CONTENT  -->
    <div class="container-fluid px-4 mb-5">
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show mt-4 shadow-sm border-0 border-start border-5 border-success" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <strong>Success:</strong> <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-4 shadow-sm border-0 border-start border-5 border-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="content-wrapper">
            <?= $content ?? '' ?>
        </div>
        
    </div>
    <!-- =========================================== -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>