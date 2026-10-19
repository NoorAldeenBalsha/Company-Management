<?php /** @var object $employee */ ?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">System Security</h2>
    <a href="index.php?page=employees&action=list" class="btn btn-secondary shadow-sm">Back to List</a>
</div>

<div class="card shadow-sm border-0 mt-4 border-danger">
    <div class="card-header bg-danger text-white fw-bold">
        Confirm Deletion
    </div>
    <div class="card-body text-center py-5">
        <h5 class="text-danger mb-3">Are you sure you want to delete this employee?</h5>
        <h2 class="fw-bold mb-4"><?= htmlspecialchars($employee->getFullName()) ?></h2>
        
        <p class="text-muted">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
            Warning: This will unassign their laptop and remove them from all projects. This action cannot be undone.
        </p>

        <form action="index.php?page=employees&action=destroy" method="POST" class="mt-4">
            <input type="hidden" name="page" value="employees">
            <input type="hidden" name="action" value="destroy">
            <input type="hidden" name="id" value="<?= $employee->getId() ?>">
            
            <button type="submit" class="btn btn-danger px-4 fw-bold">Yes, Delete Employee</button>
            <a href="index.php?page=employees&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>