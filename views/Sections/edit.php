<?php 
/** @var object $section */ 
$old = $_SESSION['old_input'] ?? []; 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Edit Department</h2>
    <a href="index.php?page=sections&action=list" class="btn btn-secondary shadow-sm">Back to Departments</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark fw-bold">
        Update Information: <span class="text-primary"><?= htmlspecialchars($section->getName()) ?></span>
    </div>
    <div class="card-body p-4">
        <form action="index.php?page=sections&action=update" method="POST">
            <input type="hidden" name="id" value="<?= $section->getId() ?>">

            <div class="mb-3">
                <label class="form-label fw-bold">Department Name</label>
                <input type="text" name="name" class="form-control" required 
                       value="<?= htmlspecialchars($old['name'] ?? $section->getName()) ?>">
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-warning fw-bold px-4">Save Changes</button>
            <a href="index.php?page=sections&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?>