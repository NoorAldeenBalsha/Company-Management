<?php 
/** @var array $allEmployees */ 
$old = $_SESSION['old_input'] ?? []; 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Add New Project</h2>
    <a href="index.php?page=projects&action=list" class="btn btn-secondary shadow-sm">Back to Projects</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold">
        Project Details
    </div>
    <div class="card-body p-4">
        <form action="index.php?page=projects&action=store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Project Name</label>
                    <input type="text" name="name" class="form-control" required 
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Customer Name</label>
                    <input type="text" name="customer_name" class="form-control" required 
                           value="<?= htmlspecialchars($old['customer_name'] ?? '') ?>">
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="date_start" class="form-control" required 
                           value="<?= htmlspecialchars($old['date_start'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select" required>
                        <?php $status = $old['status'] ?? 'Work on'; ?>
                        <option value="Work on" <?= $status === 'Work on' ? 'selected' : '' ?>>Work on</option>
                        <option value="Done" <?= $status === 'Done' ? 'selected' : '' ?>>Done</option>
                    </select>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="p-3 border border-primary rounded bg-light">
                        <label class="form-label fw-bold text-primary">Assign Project Leader <span class="text-danger">*</span></label>
                        <select name="leader_id" class="form-select border-primary" required>
                            <option value="">-- Select Leader --</option>
                            <?php foreach ($allEmployees as $emp): ?>
                                <?php $selected = (isset($old['leader_id']) && $old['leader_id'] == $emp['id']) ? 'selected' : ''; ?>
                                <option value="<?= $emp['id'] ?>" <?= $selected ?>><?= htmlspecialchars((string)$emp['full_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted"><i class="bi bi-info-circle"></i> A project cannot be created without a Leader.</small>
                    </div>
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-success fw-bold px-4">Save Project</button>
            <a href="index.php?page=projects&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?>