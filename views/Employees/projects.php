<?php 
/** @var object $employee */
/** @var array $projects */ 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Projects Assignment</h2>
    <a href="index.php?page=employees&action=list" class="btn btn-secondary shadow-sm">Back to Employees</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0 text-muted">Employee: <span class="text-primary fw-bold"><?= htmlspecialchars($employee->getFullName()) ?></span></h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped text-center mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Project Name</th>
                        <th>Customer</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Role in Project</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($projects)): ?>
                        <?php foreach ($projects as $proj): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars((string)$proj['name']) ?></td>
                                <td><?= htmlspecialchars((string)$proj['customer_name']) ?></td>
                                <td><?= htmlspecialchars((string)$proj['date_start']) ?></td>
                                <td>
                                    <?php if ($proj['status'] === 'Done'): ?>
                                        <span class="badge bg-success">Done</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Work on</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($proj['role'] === 'lead'): ?>
                                        <span class="badge bg-danger"><i class="bi bi-star-fill"></i> Lead</span>
                                    <?php elseif ($proj['role'] === 'developer'): ?>
                                        <span class="badge bg-primary">Developer</span>
                                    <?php else: ?>
                                        <span class="badge bg-info text-dark">Tester</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-5 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                This employee is not assigned to any projects currently.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>