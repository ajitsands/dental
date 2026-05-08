<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/patient/ledger">Ledgers</a></li>
                <li class="breadcrumb-item active"><?php echo $data['patient']->name; ?></li>
            </ol>
        </nav>
        <h2 class="mb-0">Statement of Account</h2>
        <p class="text-muted">Financial history for <strong><?php echo $data['patient']->name; ?></strong> (<?php echo $data['patient']->unique_id; ?>)</p>
    </div>
    <div class="col-md-4 text-end pt-3">
        <?php 
            $totalInvoiced = 0; $totalPaid = 0;
            foreach($data['ledger'] as $row) {
                if($row->type == 'Invoice') $totalInvoiced += $row->amount;
                else $totalPaid += $row->amount;
            }
            $balance = $totalInvoiced - $totalPaid;
        ?>
        <div class="card <?php echo $balance > 0 ? 'bg-danger' : 'bg-success'; ?> text-white border-0 shadow-sm p-3 d-inline-block" style="min-width: 250px; border-radius: 15px;">
            <div class="small fw-bold opacity-75 uppercase">Total Outstanding</div>
            <div class="h3 fw-bold mb-0"><?php echo formatCurrency($balance); ?></div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="fas fa-list-alt text-primary me-2"></i> Transaction History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="singleLedgerTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Description / Reference</th>
                        <th class="text-danger bg-danger-subtle bg-opacity-10 text-center">Charge (DR)</th>
                        <th class="text-success bg-success-subtle bg-opacity-10 text-center">Payment (CR)</th>
                        <th class="pe-4 text-end">Running Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $runningBalance = 0;
                    // Sort by date ASC for running balance calculation
                    $chronLedger = array_reverse($data['ledger']);
                    foreach($chronLedger as $t): 
                        if($t->type == 'Invoice') $runningBalance += $t->amount;
                        else $runningBalance -= $t->amount;
                    ?>
                    <tr>
                        <td class="ps-4"><?php echo date('M j, Y', strtotime($t->date)); ?></td>
                        <td>
                            <div class="fw-bold">
                                <?php if($t->type == 'Invoice'): ?>
                                    <span class="text-danger"><i class="fas fa-file-invoice me-2"></i> Invoice #<?php echo $t->ref; ?></span>
                                <?php else: ?>
                                    <span class="text-success"><i class="fas fa-receipt me-2"></i> Payment via <?php echo $t->ref; ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-center fw-bold text-danger bg-danger-subtle bg-opacity-10" style="color: #dc3545 !important;">
                            <?php echo ($t->type == 'Invoice') ? formatCurrency($t->amount) : '-'; ?>
                        </td>
                        <td class="text-center fw-bold text-success bg-success-subtle bg-opacity-10" style="color: #28a745 !important;">
                            <?php echo ($t->type == 'Payment') ? formatCurrency($t->amount) : '-'; ?>
                        </td>
                        <td class="pe-4 text-end fw-bold" style="color: <?php echo ($runningBalance > 0) ? '#dc3545' : '#28a745'; ?> !important;">
                            <?php echo formatCurrency($runningBalance); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold border-top-2">
                    <tr>
                        <td colspan="4" class="ps-4 text-end">TOTAL CHARGES (Debits):</td>
                        <td class="pe-4 text-end text-danger fs-5"><?php echo formatCurrency($totalInvoiced); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="ps-4 text-end">TOTAL PAYMENTS (Credits):</td>
                        <td class="pe-4 text-end text-success fs-5"><?php echo formatCurrency($totalPaid); ?></td>
                    </tr>
                    <tr class="table-dark">
                        <td colspan="4" class="ps-4 text-end fs-5 text-white">NET OUTSTANDING BALANCE:</td>
                        <td class="pe-4 text-end fs-4 text-warning"><?php echo formatCurrency($balance); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="mt-4 text-end">
    <button class="btn btn-outline-secondary rounded-pill px-4 me-2" onclick="window.print()">
        <i class="fas fa-print me-1"></i> Print Statement
    </button>
    <a href="<?php echo BASE_URL; ?>/billing" class="btn btn-primary rounded-pill px-4 shadow-sm">
        <i class="fas fa-plus me-1"></i> New Billing / Payment
    </a>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
