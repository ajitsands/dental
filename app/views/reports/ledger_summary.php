<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Financial Ledger Summary</h2>
        <p class="text-muted">Consolidated view of all Receivables (Patients) and Payables (Staff).</p>
    </div>
    <div class="col-md-4 text-end pt-2">
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="fas fa-print me-1"></i> Print Report</button>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <?php 
        $totalCredit = 0; // Receivables (Patient Debt)
        $totalDebit = 0;  // Payables (Staff Comm)
        foreach($data['ledger'] as $item) {
            if(strpos($item['type'], 'Receivable') !== false) $totalCredit += $item['amount'];
            else $totalDebit += $item['amount'];
        }
        $netPosition = $totalCredit - $totalDebit;
    ?>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white p-4" style="border-radius: 20px;">
            <h6 class="opacity-75 text-uppercase small fw-bold mb-1">Total Credit (Receivables)</h6>
            <h2 class="fw-bold mb-0"><?php echo formatCurrency($totalCredit); ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-danger text-white p-4" style="border-radius: 20px;">
            <h6 class="opacity-75 text-uppercase small fw-bold mb-1">Total Debit (Payables)</h6>
            <h2 class="fw-bold mb-0"><?php echo formatCurrency($totalDebit); ?></h2>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm <?php echo ($netPosition >= 0) ? 'bg-primary' : 'bg-dark'; ?> text-white p-4" style="border-radius: 20px;">
            <h6 class="opacity-75 text-uppercase small fw-bold mb-1">Net Financial Position</h6>
            <h2 class="fw-bold mb-0 text-white"><?php echo formatCurrency($netPosition); ?></h2>
        </div>
    </div>
</div>

<!-- Ledger DataTable -->
<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="summaryTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Entity Name</th>
                        <th>Category</th>
                        <th class="text-end text-success">Credit (Receivable)</th>
                        <th class="text-end text-danger">Debit (Payable)</th>
                        <th class="text-end pe-4">Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['ledger'] as $item): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?php echo $item['name']; ?></div>
                            <div class="small text-muted"><?php echo $item['ref']; ?></div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 rounded-pill">
                                <?php echo $item['category']; ?>
                            </span>
                        </td>
                        <td class="text-end fw-bold text-success">
                            <?php echo (strpos($item['type'], 'Receivable') !== false) ? formatCurrency($item['amount']) : '-'; ?>
                        </td>
                        <td class="text-end fw-bold text-danger">
                            <?php echo (strpos($item['type'], 'Payable') !== false) ? formatCurrency($item['amount']) : '-'; ?>
                        </td>
                        <td class="text-end pe-4">
                            <?php if(strpos($item['type'], 'Receivable') !== false): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">INCOME DUE</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">PAYOUT DUE</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light fw-bold border-top-2">
                    <tr>
                        <td colspan="2" class="ps-4 text-end">TOTALS:</td>
                        <td class="text-end text-success"><?php echo formatCurrency($totalCredit); ?></td>
                        <td class="text-end text-danger"><?php echo formatCurrency($totalDebit); ?></td>
                        <td></td>
                    </tr>
                    <tr class="<?php echo ($netPosition >= 0) ? 'bg-success-subtle' : 'bg-danger-subtle'; ?>">
                        <td colspan="3" class="ps-4 text-end fs-5">NET FINANCIAL POSITION (DIFFERENCE):</td>
                        <td class="text-end fs-4 fw-bold" style="color: <?php echo ($netPosition >= 0) ? '#28a745' : '#dc3545'; ?> !important;">
                            <?php echo formatCurrency($netPosition); ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#summaryTable').DataTable({
        "pageLength": 50,
        "order": [[3, "desc"]],
        "language": dtLanguage
    });
});
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
