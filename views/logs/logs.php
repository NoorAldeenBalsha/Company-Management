<?php /** @var array $logs */ ?>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <h2 class="text-secondary fw-bold"><i ></i> System Activity Logs</h2>
    <a href="index.php?page=dashboard" class="btn btn-secondary shadow-sm">Back to Dashboard</a>
</div>

<div class="card shadow-sm border-0 bg-dark text-light">
    <div class="card-header bg-black text-success fw-bold border-0">
        <i class="bi bi-circle-fill text-danger small"></i>
        <i class="bi bi-circle-fill text-warning small mx-1"></i>
        <i class="bi bi-circle-fill text-success small"></i>
        <span class="ms-2 font-monospace">system.log - Live Audit Trail</span>
    </div>
    <div class="card-body p-4" style="height: 500px; overflow-y: auto; background-color: #1e1e1e;">
        <?php if (!empty($logs)): ?>
            <ul class="list-unstyled font-monospace mb-0" style="font-size: 14px;">
                <?php foreach ($logs as $log): ?>
                    <?php 
                        $textColor = 'text-info';
                        if (strpos($log, '[WARNING]') !== false) $textColor = 'text-warning';
                        if (strpos($log, '[ERROR]') !== false) $textColor = 'text-danger';
                    ?>
                    <li class="mb-2 <?= $textColor ?>" style="border-bottom: 1px solid #333; padding-bottom: 5px;">
                        > <?= htmlspecialchars($log) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="text-muted font-monospace">> _ No system events recorded yet...</div>
        <?php endif; ?>
    </div>
</div>