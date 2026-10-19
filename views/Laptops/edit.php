<?php 
/** @var object $laptop */
/** @var array $allEmployees */ 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Edit Laptop</h2>
    <a href="index.php?page=laptops&action=list" class="btn btn-secondary shadow-sm">Back to Inventory</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark fw-bold">
        Update Details: <span class="font-monospace"><?= htmlspecialchars($laptop->getSerialNumber()) ?></span>
    </div>
    <div class="card-body p-4">
        <form action="index.php?page=laptops&action=update" method="POST">
            <input type="hidden" name="id" value="<?= $laptop->getId() ?>">
            <input type="hidden" name="serial_number" value="<?= htmlspecialchars($laptop->getSerialNumber()) ?>">
            <input type="hidden" name="date_buy" value="<?= htmlspecialchars($laptop->getDateBuy()) ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Model / Brand</label>
                    <input type="text" name="model" class="form-control" required 
                           value="<?= htmlspecialchars($_SESSION['old_input']['model'] ?? $laptop->getModel()) ?>">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-info">Assign to Employee</label>
                    <select name="employee_id" class="form-select border-info">
                        <option value="">-- Keep in Warehouse --</option>
                        <?php foreach ($allEmployees as $emp): ?>
                            <?php 
                                $currentEmpId = $_SESSION['old_input']['employee_id'] ?? $laptop->getEmployeeId();
                                $selected = ($currentEmpId == $emp['id']) ? 'selected' : ''; 
                            ?>
                            <option value="<?= $emp['id'] ?>" <?= $selected ?>><?= htmlspecialchars((string)$emp['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <hr class="my-4">
            <button type="submit" class="btn btn-warning fw-bold px-4">Update Laptop</button>
            <a href="index.php?page=laptops&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?>