<?php 
/** @var array $stats */
/** @var array $latestHires */
/** @var array $latestProjects */
/** @var array $noLaptopEmps */
/** @var array $overloadedEmps */
/** @var array $sectionChartData */
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Company Dashboard</h2>
    <span class="text-muted fw-bold"><i class="bi bi-clock"></i> <?= (new DateTime('now', new DateTimeZone('Asia/Damascus')))->format('Y-m-d H:i') ?></span>
</div>

<!-- Smart Alerts -->
<?php if (!empty($noLaptopEmps)): ?>
    <div class="alert alert-warning border-warning shadow-sm">
        <i class="bi bi-exclamation-triangle-fill text-warning"></i> 
        <strong>Attention:</strong> There are <?= count($noLaptopEmps) ?> employees without laptops! 
        <a href="index.php?page=employees&action=list" class="alert-link text-decoration-none">Review them</a>
    </div>
<?php endif; ?>

<?php if (!empty($overloadedEmps)): ?>
    <div class="alert alert-danger border-danger shadow-sm">
        <i class="bi bi-fire text-danger"></i> 
        <strong>Overload Warning:</strong> <?= count($overloadedEmps) ?> employees are working on 3 active projects (Max Limit reached).
    </div>
<?php endif; ?>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="bi bi-people"></i> Employees</h5>
                <h1 class="display-5 fw-bold"><?= $stats['total_employees'] ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="bi bi-briefcase"></i> Active Projects</h5>
                <h1 class="display-5 fw-bold"><?= $stats['active_projects'] ?></h1>
                <small>Out of <?= $stats['total_projects'] ?> total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="bi bi-laptop"></i> Available Laptops</h5>
                <h1 class="display-5 fw-bold"><?= $stats['available_laptops'] ?></h1>
                <small><?= $stats['assigned_laptops'] ?> assigned</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <h5 class="card-title"><i class="bi bi-building"></i> Departments</h5>
                <h1 class="display-5 fw-bold"><?= $stats['total_sections'] ?></h1>
            </div>
        </div>
    </div>
</div>

<!-- Pure HTML/CSS Charts -->
<div class="row mb-4">
    <!-- Employees per Department (Progress Bars) -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">Employees per Department</div>
            <div class="card-body">
                <?php 
                $maxEmps = 0;
                foreach($sectionChartData as $d) {
                    if($d['emp_count'] > $maxEmps) $maxEmps = $d['emp_count'];
                }
                ?>
                <ul class="list-group list-group-flush">
                    <?php if(!empty($sectionChartData)): ?>
                        <?php foreach($sectionChartData as $d): ?>
                            <?php $width = $maxEmps > 0 ? round(($d['emp_count'] / $maxEmps) * 100) : 0; ?>
                            <li class="list-group-item border-0 px-0 pb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-secondary fw-bold"><?= htmlspecialchars((string)($d['section_name'] ?? 'Unassigned')) ?></span>
                                    <span class="fw-bold text-primary"><?= $d['emp_count'] ?> Employees</span>
                                </div>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $width ?>%;"></div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No department data available.</p>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Laptops Distribution (Stacked Progress Bar) -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">Laptops Distribution</div>
            <div class="card-body d-flex flex-column justify-content-center">
                <?php
                $totalLaps = $stats['total_laptops'];
                $availPct = $totalLaps > 0 ? round(($stats['available_laptops'] / $totalLaps) * 100) : 0;
                $assignPct = $totalLaps > 0 ? round(($stats['assigned_laptops'] / $totalLaps) * 100) : 0;
                ?>
                <h3 class="text-center mb-4 text-secondary"><?= $totalLaps ?> Total Laptops</h3>
                
                <div class="progress mb-3 shadow-sm" style="height: 40px; font-size: 16px;">
                    <?php if($availPct > 0): ?>
                        <div class="progress-bar bg-info text-dark fw-bold" role="progressbar" style="width: <?= $availPct ?>%;">
                            Warehouse (<?= $availPct ?>%)
                        </div>
                    <?php endif; ?>
                    <?php if($assignPct > 0): ?>
                        <div class="progress-bar bg-success fw-bold" role="progressbar" style="width: <?= $assignPct ?>%;">
                            Assigned (<?= $assignPct ?>%)
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="d-flex justify-content-around mt-3">
                    <div><span class="badge bg-info p-2">&nbsp;</span> In Warehouse (<?= $stats['available_laptops'] ?>)</div>
                    <div><span class="badge bg-success p-2">&nbsp;</span> Assigned (<?= $stats['assigned_laptops'] ?>)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Glances -->
<div class="row">
    <!-- Latest Hires -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-dark text-white fw-bold">Newest Employees</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover text-center mb-0">
                        <thead class="table-light">
                            <tr><th>Name</th><th>Department</th><th>Start Date</th></tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($latestHires)): ?>
                                <?php foreach ($latestHires as $emp): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars((string)$emp['full_name']) ?></td>
                                        <td><?= htmlspecialchars((string)($emp['section_name'] ?? '-')) ?></td>
                                        <td><?= htmlspecialchars((string)$emp['date_start']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-muted py-3">No employees found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Projects -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-dark text-white fw-bold">Latest Projects</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover text-center mb-0">
                        <thead class="table-light">
                            <tr><th>Project Name</th><th>Status</th><th>Customer</th></tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($latestProjects)): ?>
                                <?php foreach ($latestProjects as $proj): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars((string)$proj['name']) ?></td>
                                        <td>
                                            <?php if ($proj['status'] === 'Done'): ?>
                                                <span class="badge bg-success">Done</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Work on</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars((string)$proj['customer_name']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-muted py-3">No projects found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>