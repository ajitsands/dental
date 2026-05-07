<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('customer_ledger'); ?></h2>
        <p class="text-muted"><?php echo __('manage_patient_credits'); ?></p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="customerLedgerTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><?php echo __('patient'); ?></th>
                        <?php if($data['isSuperAdmin']): ?><th>Branch</th><?php endif; ?>
                        <th><?php echo __('total_revenue'); ?></th>
                        <th><?php echo __('collected_amount'); ?></th>
                        <th><?php echo __('outstanding_dues'); ?></th>
                        <th class="text-end pe-4"><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['balances'] as $p): ?>
                    <?php 
                        $balance = ($p->total_invoiced ?? 0) - ($p->total_paid ?? 0);
                        $balanceClass = $balance > 0 ? 'text-danger' : 'text-success';
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?php echo $p->name; ?></div>
                            <div class="small text-muted"><?php echo $p->unique_id; ?></div>
                        </td>
                        <?php if($data['isSuperAdmin']): ?>
                            <td><span class="small text-muted"><?php echo $p->branch_name; ?></span></td>
                        <?php endif; ?>
                        <td class="fw-bold"><?php echo formatCurrency($p->total_invoiced ?? 0); ?></td>
                        <td class="text-success"><?php echo formatCurrency($p->total_paid ?? 0); ?></td>
                        <td class="fw-bold <?php echo $balanceClass; ?> fs-5">
                            <?php echo formatCurrency($balance); ?>
                        </td>
                        <td class="text-end pe-4">
                            <a href="<?php echo BASE_URL; ?>/patient/ledger/<?php echo $p->id; ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fas fa-file-invoice me-1"></i> View Statement
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#customerLedgerTable').DataTable({
        "pageLength": 10,
        "language": dtLanguage
    });
});
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
