<?php 
/** @var array $allEmployees */ 
$old = $_SESSION['old_input'] ?? [];
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Add New Laptop</h2>
    <a href="index.php?page=laptops&action=list" class="btn btn-secondary shadow-sm">Back to Inventory</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold">
        Laptop Specifications
    </div>
    <div class="card-body p-4">
        <form action="index.php?page=laptops&action=store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Model / Brand (e.g., Dell, HP, Mac)</label>
                    <input type="text" name="model" class="form-control" required 
                           value="<?= htmlspecialchars($old['model'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Serial Number</label>
                    <input type="text" name="serial_number" class="form-control text-uppercase" required 
                           value="<?= htmlspecialchars($old['serial_number'] ?? '') ?>">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Date of Purchase</label>
                    <input type="date" name="date_buy" class="form-control" required 
                           value="<?= htmlspecialchars($old['date_buy'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-info">Assign to Employee (Optional)</label>
                    <select name="employee_id" class="form-select border-info">
                        <option value="">-- Keep in Warehouse --</option>
                        <?php foreach ($allEmployees as $emp): ?>
                            <?php $selected = (isset($old['employee_id']) && $old['employee_id'] == $emp['id']) ? 'selected' : ''; ?>
                            <option value="<?= $emp['id'] ?>" <?= $selected ?>><?= htmlspecialchars((string)$emp['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-muted">If left empty, the laptop will be stored in the warehouse inventory.</small>
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-success fw-bold px-4">Save Laptop</button>
            <a href="index.php?page=laptops&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?>