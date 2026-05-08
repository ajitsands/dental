<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/wallet">Wallets</a></li>
                <li class="breadcrumb-item active">Ledger</li>
            </ol>
        </nav>
        <h2 class="mb-0">Financial Ledger: <span class="text-primary"><?php echo $data['user']->name; ?></span></h2>
        <p class="text-muted">Detailed transaction history for commissions and payouts.</p>
    </div>
    <div class="col-md-4 text-end pt-3">
        <?php $balanceClass = ($data['user']->wallet_balance >= 0) ? 'bg-success' : 'bg-danger'; ?>
        <div class="card <?php echo $balanceClass; ?> text-white border-0 shadow-sm p-3 d-inline-block" style="min-width: 200px; border-radius: 15px;">
            <div class="small fw-bold opacity-75 uppercase">Current Balance</div>
            <div class="h3 fw-bold mb-0"><?php echo formatCurrency($data['user']->wallet_balance); ?></div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="ledgerTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>Transaction Type</th>
                        <th>Description / Reference</th>
                        <th>Credit (Earnings)</th>
                        <th>Debit (Payouts)</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach($data['ledger'] as $t): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold"><?php echo date('M j, Y', strtotime($t->created_at)); ?></div>
                                <div class="small text-muted"><?php echo date('h:i A', strtotime($t->created_at)); ?></div>
                            </td>
                            <td>
                                <?php if($t->type == 'Credit'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">
                                        <i class="fas fa-plus-circle me-1"></i> Commission
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 rounded-pill">
                                        <i class="fas fa-minus-circle me-1"></i> Payout
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="fw-bold"><?php echo $t->description; ?></div>
                                <?php if($t->invoice_number): ?>
                                    <div class="small text-muted">Ref: Invoice <strong>#<?php echo $t->invoice_number; ?></strong></div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-success">
                                <?php echo ($t->type == 'Credit') ? formatCurrency($t->amount) : '-'; ?>
                            </td>
                            <td class="fw-bold text-danger">
                                <?php echo ($t->type == 'Debit') ? formatCurrency($t->amount) : '-'; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold border-top-2">
                    <?php 
                        $totalCredit = 0; $totalDebit = 0;
                        foreach($data['ledger'] as $t) {
                            if($t->type == 'Credit') $totalCredit += $t->amount;
                            else $totalDebit += $t->amount;
                        }
                        $netBalance = $totalCredit - $totalDebit;
                    ?>
                    <tr>
                        <td colspan="3" class="ps-4 text-end">TOTALS:</td>
                        <td class="text-success"><?php echo number_format($totalCredit, 2); ?></td>
                        <td class="text-danger"><?php echo number_format($totalDebit, 2); ?></td>
                    </tr>
                    <tr class="table-primary border-top">
                        <td colspan="4" class="ps-4 text-end fs-5">NET PAYABLE BALANCE:</td>
                        <?php $netClass = ($netBalance >= 0) ? 'text-success' : 'text-danger'; ?>
                        <td class="<?php echo $netClass; ?> fs-4"><?php echo formatCurrency($netBalance); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#ledgerTable').DataTable({
        "pageLength": 25,
        "order": [[0, "desc"]],
        "language": { "search": "", "searchPlaceholder": "Search transactions..." }
    });
});
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
