<?php /** @var array $projects */ ?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Projects Management</h2>
    <a href="index.php?page=projects&action=create" class="btn btn-primary shadow-sm fw-bold">
        <i class="bi bi-folder-plus"></i> Add New Project
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped text-center mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Project Name</th>
                        <th>Customer</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Actions</th>
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
                                        <span class="badge bg-success shadow-sm">Done</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark shadow-sm">Work on</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?page=projects&action=team&id=<?= $proj['id'] ?>" class="btn btn-sm btn-outline-info fw-bold" title="Manage Team">
                                            <i class="bi bi-people-fill"></i> Team
                                        </a>
                                        <a href="index.php?page=projects&action=edit&id=<?= $proj['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="index.php?page=projects&action=delete&id=<?= $proj['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="py-5 text-muted">
                                <i class="bi bi-folder-x fs-3 d-block mb-2"></i>
                                There are no projects at the moment.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>