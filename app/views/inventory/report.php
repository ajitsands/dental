<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Inventory Audit Report</h2>
        <p class="text-muted">Item-wise history of additions and patient consumption.</p>
    </div>
    <div class="col-md-4 text-end">
        <button onclick="window.print()" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fas fa-print me-1"></i> Print Report
        </button>
        <a href="<?php echo BASE_URL; ?>/inventory" class="btn btn-primary rounded-pill px-4 ms-2">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="reportTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>Item Name</th>
                        <th>Action</th>
                        <th>Quantity</th>
                        <th>Patient / Reason</th>
                        <th>Handled By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['logs'] as $log): ?>
                    <tr>
                        <td class="ps-4 small"><?php echo formatDateTime($log->created_at); ?></td>
                        <td class="fw-bold"><?php echo $log->item_name; ?></td>
                        <td>
                            <?php if($log->type == 'add'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                    <i class="fas fa-plus-circle me-1"></i> Added
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                                    <i class="fas fa-minus-circle me-1"></i> Consumed
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?php echo $log->quantity; ?> <?php echo $log->unit; ?></td>
                        <td>
                            <?php if($log->patient_name): ?>
                                <div class="fw-bold text-primary small"><i class="fas fa-user me-1"></i> <?php echo $log->patient_name; ?></div>
                            <?php endif; ?>
                            <div class="small text-muted italic"><?php echo $log->notes ?: '-'; ?></div>
                        </td>
                        <td class="small"><?php echo $log->user_name; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#reportTable').DataTable({
        "pageLength": 25,
        "order": [[0, "desc"]],
        "language": { "search": "", "searchPlaceholder": "Filter report..." }
    });
});
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
