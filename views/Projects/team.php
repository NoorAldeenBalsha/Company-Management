<?php 
/** @var object $project */
/** @var array $allEmployees */
/** @var array $team */ 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Project Team: <span class="text-primary"><?= htmlspecialchars($project->getName()) ?></span></h2>
    <a href="index.php?page=projects&action=list" class="btn btn-secondary shadow-sm">Back to Projects</a>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="bi bi-person-plus"></i> Assign Team Member
            </div>
            <div class="card-body p-4">
                <form action="index.php?page=projects&action=assign" method="POST">
                    <input type="hidden" name="project_id" value="<?= $project->getId() ?>">
                    
                    <div class="mb-3">
                        <label class="fw-bold form-label">Select Employee</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">-- Choose Employee --</option>
                            <?php foreach ($allEmployees as $emp): ?>
                                <option value="<?= $emp['id'] ?>">
                                    <?= htmlspecialchars((string)$emp['full_name']) ?> (<?= htmlspecialchars((string)($emp['section_name'] ?? 'No Dept')) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold form-label">Project Role</label>
                        <select name="role" class="form-select" required>
                            <option value="developer">Developer</option>
                            <option value="tester">Tester</option>
                            <option value="lead">Lead</option>
                        </select>
                    </div>
                    <hr class="mt-4">
                    <button type="submit" class="btn btn-success w-100 fw-bold">Assign to Project</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white fw-bold">
                <i class="bi bi-people-fill"></i> Current Team Members
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-center mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($team)): ?>
                                <?php foreach ($team as $member): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars((string)$member['full_name']) ?></td>
                                        <td>
                                            <?php if ($member['role'] === 'lead'): ?>
                                                <span class="badge bg-danger shadow-sm"><i class="bi bi-star-fill"></i> Lead</span>
                                            <?php elseif ($member['role'] === 'developer'): ?>
                                                <span class="badge bg-primary shadow-sm">Developer</span>
                                            <?php else: ?>
                                                <span class="badge bg-info text-dark shadow-sm">Tester</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?page=projects&action=confirm_remove&project_id=<?= $project->getId() ?>&employee_id=<?= $member['employee_id'] ?>" 
                                               class="btn btn-sm btn-outline-danger fw-bold">
                                                <i class="bi bi-person-x-fill"></i> Remove
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="py-5 text-muted">
                                        <i class="bi bi-person-slash fs-3 d-block mb-2"></i>
                                        There are no members in this project right now.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>