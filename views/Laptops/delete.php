<?php /** @var object $laptop */ ?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">System Security</h2>
    <a href="index.php?page=laptops&action=list" class="btn btn-secondary shadow-sm">Back to Inventory</a>
</div>

<div class="card shadow-sm border-0 mt-4 border-danger">
    <div class="card-header bg-danger text-white fw-bold">
        Confirm Deletion
    </div>
    <div class="card-body text-center py-5">
        <h5 class="text-danger mb-3">Are you sure you want to permanently delete this laptop?</h5>
        <h3 class="fw-bold mb-4">
            <?= htmlspecialchars($laptop->getModel()) ?> 
            <br>
            <small class="text-muted fs-5">SN: <?= htmlspecialchars($laptop->getSerialNumber()) ?></small>
        </h3>
        
        <p class="text-muted">
            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
            <strong>Warning:</strong> If this laptop is assigned to an employee, it will be unassigned and permanently removed from the system.
        </p>
        
        <form action="index.php?page=laptops&action=destroy" method="POST" class="mt-4">
            <input type="hidden" name="id" value="<?= $laptop->getId() ?>">
            <button type="submit" class="btn btn-danger px-4 fw-bold">Yes, Delete Laptop</button>
            <a href="index.php?page=laptops&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>