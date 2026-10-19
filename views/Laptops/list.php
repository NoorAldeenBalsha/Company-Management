<?php 
/** @var array $laptops */ 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Laptops Inventory</h2>
    <a href="index.php?page=laptops&action=create" class="btn btn-primary shadow-sm fw-bold">
        <i class="bi bi-laptop"></i> Add New Laptop
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped text-center mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Model / Brand</th>
                        <th>Serial Number</th>
                        <th>Status / Assigned To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($laptops)): ?>
                        <?php foreach ($laptops as $lap): ?>
                            <tr>
                                <td class="fw-bold"><?= htmlspecialchars((string)$lap['model']) ?></td>
                                <td class="font-monospace"><?= htmlspecialchars((string)$lap['serial_number']) ?></td>
                                <td>
                                    <?php if (!empty($lap['full_name'])): ?>
                                        <span class="badge bg-success shadow-sm">
                                            <i class="bi bi-person-fill"></i> <?= htmlspecialchars((string)$lap['full_name']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-info text-dark shadow-sm">
                                            <i class="bi bi-box-seam"></i> In Warehouse
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($lap['full_name'])): ?>
                                        <form action="index.php?page=laptops&action=unassign" method="POST" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $lap['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-info fw-bold" title="Return to Warehouse">
                                                <i class="bi bi-arrow-return-left"></i> Return
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <div class="btn-group ms-1" role="group">
                                        <a href="index.php?page=laptops&action=edit&id=<?= $lap['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="index.php?page=laptops&action=delete&id=<?= $lap['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="py-5 text-muted">
                                <i class="bi bi-pc-display fs-3 d-block mb-2"></i>
                                No laptops found in the system.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>