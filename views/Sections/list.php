<?php /** @var array $sections */ ?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Departments Management</h2>
    <a href="index.php?page=sections&action=create" class="btn btn-primary shadow-sm fw-bold">
        <i class="bi bi-plus-circle"></i> Add New Department
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped text-center mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Department Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($sections)): ?>
                        <?php foreach ($sections as $sec): ?>
                            <tr>
                                <td><?= htmlspecialchars((string)$sec['id']) ?></td>
                                <td class="fw-bold"><?= htmlspecialchars((string)$sec['name']) ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?page=employees&action=list&section_id=<?= $sec['id'] ?>" class="btn btn-sm btn-outline-info fw-bold" title="View Employees">
                                            <i class="bi bi-people-fill"></i> Employees
                                        </a>
                                        <a href="index.php?page=sections&action=edit&id=<?= $sec['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="index.php?page=sections&action=delete&id=<?= $sec['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-5 text-muted">
                                <i class="bi bi-building fs-3 d-block mb-2"></i>
                                No departments found in the system.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>