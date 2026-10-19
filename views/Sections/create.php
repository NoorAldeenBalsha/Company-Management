<?php $old = $_SESSION['old_input'] ?? []; ?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Add New Department</h2>
    <a href="index.php?page=sections&action=list" class="btn btn-secondary shadow-sm">Back to Departments</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold">
        Department Details
    </div>
    <div class="card-body p-4">
        <form action="index.php?page=sections&action=store" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Department Name</label>
                <input type="text" name="name" class="form-control" required placeholder="e.g., IT, HR, Marketing" value="<?= htmlspecialchars($old['name'] ?? '') ?>">
            </div>
            <hr class="my-4">
            <button type="submit" class="btn btn-success fw-bold px-4">Save Department</button>
            <a href="index.php?page=sections&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?>