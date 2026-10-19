<?php 
/** @var array $allSections */ 
/** @var array $availableLaptops */
$old = $_SESSION['old_input'] ?? []; 
?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold">Add a New Employee</h2>
    <a href="index.php?page=employees&action=list" class="btn btn-secondary shadow-sm">Back to List</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold">
        Employee Details
    </div>
    <div class="card-body p-4">
        <form action="index.php?page=employees&action=store" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="full_name" class="form-control" required value="<?= htmlspecialchars($old['full_name'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" placeholder="09..." required value="<?= htmlspecialchars($old['phone_number'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Start Date</label>
                    <input type="date" name="date_start" class="form-control" required value="<?= htmlspecialchars($old['date_start'] ?? '') ?>">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Department / Section</label>
                    <select name="section_id" class="form-select" required>
                        <option value="">-- Choose Department --</option>
                        <?php foreach ($allSections as $sec): ?>
                            <option value="<?= $sec['id'] ?>" <?= (isset($old['section_id']) && $old['section_id'] == $sec['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sec['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-info">Assign Laptop (Optional)</label>
                    <select name="laptop_id" class="form-select border-info">
                        <option value="">-- No Laptop --</option>
                        <?php if(!empty($availableLaptops)): ?>
                            <?php foreach ($availableLaptops as $lap): ?>
                                <option value="<?= $lap['id'] ?>"><?= htmlspecialchars($lap['model']) ?> (SN: <?= htmlspecialchars($lap['serial_number']) ?>)</option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <small class="text-muted">Only laptops currently in the warehouse are shown.</small>
                </div>
            </div>

            <hr class="my-4">
            <button type="submit" class="btn btn-success px-4 fw-bold">Save Employee</button>
            <a href="index.php?page=employees&action=list" class="btn btn-light px-4 border">Cancel</a>
        </form>
    </div>
</div>
<?php unset($_SESSION['old_input']); ?>