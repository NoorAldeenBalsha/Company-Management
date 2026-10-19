<?php 
/** @var object $project */ 
$old = $_SESSION['old_input'] ?? []; 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Edit Project</h2>
    <a href="index.php?page=projects&action=list" class="btn btn-secondary shadow-sm">Back to Projects</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark fw-bold">
        Update Information: <span class="text-primary"><?= htmlspecialchars($project->getName()) ?></span>
    </div>
    <div class="card-body p-4">
        <form action="index.php?page=projects&action=update" method="POST">
            <input type="hidden" name="id" value="<?= $project->getId() ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Project Name</label>
                    <input type="text" name="name" class="form-control" required 
                           value="<?= htmlspecialchars($old['name'] ?? $project->getName()) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" required 
                           value="<?= htmlspecialchars($old['customer_name'] ?? $project->getCustomerName()) ?>">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="date_start" class="form-control" required 
                           value="<?= htmlspecialchars($old['date_start'] ?? $project->getDateStart()) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <?php $currentStatus = $old['status'] ?? $project->getStatus(); ?>
                        <option value="Work on" <?= $currentStatus === 'Work on' ? 'selected' : '' ?>>Work on</option>
                        <option value="Done" <?= $currentStatus === 'Done' ? 'selected' : '' ?>>Done</option>
                    </select>
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-warning fw-bold px-4">Update Project</button>
            <a href="index.php?page=projects&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?>