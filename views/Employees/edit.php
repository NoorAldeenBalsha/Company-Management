<?php 
/** @var object $employee */ 
/** @var array $allSections */ 
/** @var array $currentLaptop */
/** @var array $availableLaptops */
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Edit Employee</h2>
    <a href="index.php?page=employees&action=list" class="btn btn-secondary shadow-sm">Back to List</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-warning text-dark fw-bold">
        Update Information: <?= htmlspecialchars($employee->getFullName()) ?>
    </div>
    <div class="card-body p-4">
        <form action="index.php?page=employees&action=update" method="POST">
            <input type="hidden" name="page" value="employees">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $employee->getId() ?>">

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="full_name" class="form-control" 
                           value="<?= htmlspecialchars($_SESSION['old_input']['full_name'] ?? $employee->getFullName()) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control" 
                           value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? $employee->getEmail()) ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" 
                           value="<?= htmlspecialchars($_SESSION['old_input']['phone_number'] ?? $employee->getPhoneNumber()) ?>" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Assign to Department</label>
                    <select name="section_id" class="form-select" required>
                        <option value="">-- Select Section --</option>
                        <?php foreach ($allSections as $sec): ?>
                            <?php 
                                $selectedId = $_SESSION['old_input']['section_id'] ?? $employee->getSectionId();
                                $isSelected = ($sec['id'] == $selectedId) ? 'selected' : '';
                            ?>
                            <option value="<?= $sec['id'] ?>" <?= $isSelected ?>>
                                <?= htmlspecialchars($sec['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-info">Assigned Laptop</label>
                    <select name="laptop_id" class="form-select border-info">
                        <option value="">-- No Laptop (Return to Warehouse) --</option>
                        
                        <?php if (!empty($currentLaptop)): ?>
                            <option value="<?= $currentLaptop['id'] ?>" selected>
                                <?= htmlspecialchars($currentLaptop['model']) ?> (SN: <?= htmlspecialchars($currentLaptop['serial_number']) ?>) - CURRENT
                            </option>
                        <?php endif; ?>

                        <?php if(!empty($availableLaptops)): ?>
                            <optgroup label="Available in Warehouse">
                                <?php foreach ($availableLaptops as $lap): ?>
                                    <option value="<?= $lap['id'] ?>">
                                        <?= htmlspecialchars($lap['model']) ?> (SN: <?= htmlspecialchars($lap['serial_number']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">Select a new laptop to exchange, or "No Laptop" to return the current one.</small>
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-warning fw-bold px-4">Save Changes</button>
            <a href="index.php?page=employees&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?>