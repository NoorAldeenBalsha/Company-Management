<?php 
/** @var object $employee */
/** @var object $project */
/** @var string $role */
/** @var array $allEmployees */ 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Team Management</h2>
    <a href="index.php?page=projects&action=team&id=<?= $project->getId() ?>" class="btn btn-secondary shadow-sm">Back to Team</a>
</div>

<div class="card shadow-sm border-0 mt-4 border-warning">
    <div class="card-header bg-warning text-dark fw-bold">
        Confirm Member Removal
    </div>
    <div class="card-body text-center py-4">
        <h4 class="mb-4">
            Are you sure about removing <strong class="text-danger"><?= htmlspecialchars($employee->getFullName()) ?></strong> 
            from project <strong class="text-primary"><?= htmlspecialchars($project->getName()) ?></strong>?
        </h4>
        
        <?php if ($role === 'lead'): ?>
            <div class="alert alert-danger mx-auto text-start shadow-sm" style="max-width: 600px;">
                <i class="bi bi-shield-fill-exclamation fs-4 float-start me-3"></i>
                <strong>CRITICAL SECURITY:</strong> This employee is the Project Leader. 
                You MUST assign a new leader before removing them to ensure the project remains active.
            </div>
        <?php endif; ?>
        
        <form action="index.php?page=projects&action=remove_member" method="POST" class="mt-4 mx-auto" style="max-width: 600px;">
            <input type="hidden" name="project_id" value="<?= $project->getId() ?>">
            <input type="hidden" name="employee_id" value="<?= $employee->getId() ?>">
            
            <?php if ($role === 'lead'): ?>
                <div class="mb-4 text-start p-3 border border-danger rounded bg-light">
                    <label class="fw-bold text-danger mb-2">Select Replacement Leader:</label>
                    <select name="new_leader_id" class="form-select border-danger" required>
                        <option value="">-- Choose New Leader --</option>
                        <?php foreach ($allEmployees as $emp): ?>
                            <?php if ($emp['id'] != $employee->getId()): ?>
                                <option value="<?= $emp['id'] ?>"><?= htmlspecialchars((string)$emp['full_name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <hr class="my-4">
            <button type="submit" class="btn btn-danger px-4 fw-bold">Yes, Remove Employee</button>
            <a href="index.php?page=projects&action=team&id=<?= $project->getId() ?>" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>