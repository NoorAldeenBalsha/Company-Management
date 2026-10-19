<?php 
/** @var array $employees */
/** @var array $allSections */
/** @var string $searchKeyword */
/** @var int|null $filterSectionId */
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Employees Management</h2>
    <a href="index.php?page=employees&action=create" class="btn btn-primary shadow-sm fw-bold">
        <i class="bi bi-person-plus-fill"></i> Add New Employee
    </a>
</div>

<!-- Search & Filter Form -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body bg-light rounded">
        <form action="index.php" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="page" value="employees">
            <input type="hidden" name="action" value="list">
            
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by name..." 
                       value="<?= htmlspecialchars($searchKeyword ?? '', ENT_QUOTES) ?>">
            </div>
            
            <div class="col-md-4">
                <select name="section_id" class="form-select">
                    <option value="">-- All Departments --</option>
                    <?php if (!empty($allSections)): ?>
                        <?php foreach ($allSections as $sec): ?>
                            <option value="<?= $sec['id'] ?>" <?= (isset($filterSectionId) && $filterSectionId == $sec['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sec['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 fw-bold"><i class="bi bi-funnel-fill"></i> Filter</button>
                
                <?php if (!empty($searchKeyword) || !empty($filterSectionId)): ?>
                    <a href="index.php?page=employees&action=list" class="btn btn-outline-danger">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Employees Table -->
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped text-center mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($employees)): ?>
                        <?php foreach ($employees as $emp): ?>
                            <tr>
                                <td><?= htmlspecialchars((string)$emp['id']) ?></td>
                                <td class="fw-bold"><?= htmlspecialchars((string)$emp['full_name']) ?></td>
                                <td><a href="mailto:<?= htmlspecialchars((string)$emp['email']) ?>" class="text-decoration-none"><?= htmlspecialchars((string)$emp['email']) ?></a></td>
                                <td><?= htmlspecialchars((string)$emp['phone_number']) ?></td>
                                <td>
                                    <?php if (!empty($emp['section_name'])): ?>
                                        <span class="badge bg-info text-dark"><?= htmlspecialchars((string)$emp['section_name']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="index.php?page=employees&action=projects&id=<?= $emp['id'] ?>" class="btn btn-sm btn-outline-primary" title="View Projects">
                                            <i class="bi bi-briefcase"></i>
                                        </a>
                                        <a href="index.php?page=employees&action=edit&id=<?= $emp['id'] ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="index.php?page=employees&action=delete&id=<?= $emp['id'] ?>" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-muted py-5">
                                <?php if (!empty($searchKeyword) || !empty($filterSectionId)): ?>
                                    <i class="bi bi-search fs-3 d-block mb-2"></i> No employees found matching your filters.
                                <?php else: ?>
                                    <i class="bi bi-people fs-3 d-block mb-2"></i> No employees exist in the system yet.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>