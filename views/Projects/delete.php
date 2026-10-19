<?php /** @var object $project */ ?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">System Security</h2>
    <a href="index.php?page=projects&action=list" class="btn btn-secondary shadow-sm">Back to Projects</a>
</div>

<div class="card shadow-sm border-0 mt-4 border-danger">
    <div class="card-header bg-danger text-white fw-bold">
        Confirm Deletion
    </div>
    <div class="card-body text-center py-5">
        <h5 class="text-danger mb-3">Are you sure you want to delete the project:</h5>
        <h2 class="fw-bold mb-4"><?= htmlspecialchars($project->getName()) ?></h2>
        
        <p class="text-muted">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
            <strong>Warning:</strong> All employee assignments to this project will also be removed permanently!
        </p>
        
        <form action="index.php?page=projects&action=destroy" method="POST" class="mt-4">
            <input type="hidden" name="id" value="<?= $project->getId() ?>">
            <button type="submit" class="btn btn-danger px-4 fw-bold">Yes, Delete Project</button>
            <a href="index.php?page=projects&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>