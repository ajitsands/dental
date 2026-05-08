<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('billing_invoices'); ?></h2>
        <p class="text-muted"><?php echo __('manage_payments'); ?> <strong><?php echo $_SESSION['branch_name'] ?? 'Main Clinic'; ?></strong></p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#invoiceModal"><i class="fas fa-file-invoice me-1"></i> <?php echo __('create_new_invoice'); ?></button>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <?php
    $totalRevenue = 0;
    $totalPaid = 0;
    foreach($data['invoices'] as $inv) {
        $totalRevenue += $inv->final_amount;
        if($inv->status == 'Paid') $totalPaid += $inv->final_amount;
    }
    $dues = $totalRevenue - $totalPaid;
    ?>
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3 shadow-sm border-0">
            <h6 class="mb-1 opacity-75 small uppercase"><?php echo __('total_revenue'); ?></h6>
            <h3 class="mb-0 fw-bold"><?php echo formatCurrency($totalRevenue); ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white p-3 shadow-sm border-0">
            <h6 class="mb-1 opacity-75 small uppercase"><?php echo __('collected_amount'); ?></h6>
            <h3 class="mb-0 fw-bold"><?php echo formatCurrency($totalPaid); ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white p-3 shadow-sm border-0">
            <h6 class="mb-1 opacity-75 small uppercase"><?php echo __('outstanding_dues'); ?></h6>
            <h3 class="mb-0 fw-bold"><?php echo formatCurrency($dues); ?></h3>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="card shadow-sm border-0 overflow-hidden">
    <div class="card-body">
        <div class="table-responsive">
            <table id="invoiceTable" class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th><?php echo __('invoice_num'); ?></th>
                        <th><?php echo __('patient'); ?></th>
                        <th><?php echo __('doctor'); ?></th>
                        <th><?php echo __('date'); ?></th>
                        <th><?php echo __('amount'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th class="text-end"><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['invoices'] as $inv): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?php echo $inv->invoice_number; ?></td>
                        <td class="fw-medium"><?php echo $inv->patient_name; ?></td>
                        <td>Dr. <?php echo $inv->doctor_name; ?></td>
                        <td class="small"><?php echo date('M d, Y', strtotime($inv->created_at)); ?></td>
                        <td class="fw-bold"><?php echo formatCurrency($inv->final_amount); ?></td>
                        <td>
                            <?php if($inv->status == 'Paid'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Paid</span>
                            <?php elseif($inv->status == 'Partially Paid'): ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Partial</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-success" onclick="openPaymentModal(<?php echo $inv->id; ?>, <?php echo $inv->final_amount; ?>, '<?php echo $inv->invoice_number; ?>')" title="Record Payment">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                                <a href="<?php echo BASE_URL; ?>/billing/printReceipt/<?php echo $inv->id; ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Print Thermal Receipt">
                                    <i class="fas fa-print"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteInvoice(<?php echo $inv->id; ?>, '<?php echo $inv->invoice_number; ?>')" title="Delete Invoice">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Create New Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="invoiceForm">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Patient</label>
                            <select name="patient_id" class="form-select select2-init" required>
                                <option value="">-- Choose Patient --</option>
                                <?php foreach($data['patients'] as $p) echo "<option value='{$p->id}'>{$p->name} ({$p->contact})</option>"; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Doctor</label>
                            <select name="doctor_id" class="form-select select2-init" required>
                                <option value="">-- Choose Doctor --</option>
                                <?php foreach($data['doctors'] as $d) echo "<option value='{$d->id}'>Dr. {$d->name}</option>"; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Technician</label>
                            <select name="technician_id" class="form-select select2-init">
                                <option value="">-- Select Technician --</option>
                                <?php foreach($data['technicians'] as $t) echo "<option value='{$t->id}'>{$t->name}</option>"; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Nurse</label>
                            <select name="nurse_id" class="form-select select2-init">
                                <option value="">-- Select Nurse --</option>
                                <?php foreach($data['nurses'] as $n) echo "<option value='{$n->id}'>{$n->name}</option>"; ?>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="itemsTable">
                            <thead class="table-light">
                                <tr class="small text-muted uppercase">
                                    <th style="width: 50%;">Service / Procedure</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th style="width: 40px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="item-row">
                                    <td>
                                        <select class="form-select service-select" onchange="updateRowPrice(this)">
                                            <option value="" data-price="0">-- Select Service --</option>
                                            <?php foreach($data['services'] as $s) echo "<option value='{$s->id}' data-price='{$s->cost}'>{$s->name}</option>"; ?>
                                        </select>
                                    </td>
                                    <td><input type="number" class="form-control item-price" value="0" oninput="calculateTotals()"></td>
                                    <td><input type="number" class="form-control item-qty" value="1" oninput="calculateTotals()"></td>
                                    <td><input type="text" class="form-control item-total fw-bold bg-light" value="0" readonly></td>
                                    <td><button type="button" class="btn btn-link text-danger p-0" onclick="removeRow(this)"><i class="fas fa-trash-alt"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="addRow()"><i class="fas fa-plus me-1"></i> Add Service</button>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-bold">Invoice Notes</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Notes for patient..."></textarea>
                        </div>
                        <div class="col-md-5">
                            <div class="card bg-light border-0 p-3 rounded-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-bold" id="subtotalText">0.00</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted" id="taxLabel">Tax (<?php echo ($_SESSION['tax_pct'] ?? 18) . '% ' . ($_SESSION['tax_type'] ?? 'GST'); ?>)</span>
                                    <span id="taxText">0.00</span>
                                </div>
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold">Grand Total</h5>
                                    <h4 class="mb-0 text-primary fw-bold" id="finalTotalText">0.00</h4>
                                </div>
                                <input type="hidden" name="total_amount" id="subtotalInput">
                                <input type="hidden" name="tax_amount" id="taxInput">
                                <input type="hidden" name="final_amount" id="finalTotalInput">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-5 rounded-pill shadow" id="saveInvoiceBtn" onclick="saveInvoice()">Generate Invoice</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Record Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="paymentForm">
                    <input type="hidden" name="invoice_id" id="payInvoiceId">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted uppercase">Invoice Number</label>
                        <input type="text" id="payInvoiceNum" class="form-control bg-light fw-bold" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted uppercase">Amount to Pay</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-success text-white"><?php echo getCurrencySymbol(); ?></span>
                            <input type="number" name="amount" id="payAmount" class="form-control form-control-lg fw-bold border-0 bg-light text-success" required>
                        </div>
                        <div class="small mt-2">Maximum: <span class="fw-bold" id="payFullAmount"></span></div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold text-muted uppercase">Payment Mode</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="mode" id="mode-cash" value="Cash" checked>
                                <label class="btn btn-outline-secondary w-100 py-2" for="mode-cash"><i class="fas fa-money-bill-alt me-2"></i> Cash</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="mode" id="mode-card" value="Card">
                                <label class="btn btn-outline-secondary w-100 py-2" for="mode-card"><i class="fas fa-credit-card me-2"></i> Card</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="mode" id="mode-upi" value="UPI">
                                <label class="btn btn-outline-secondary w-100 py-2" for="mode-upi"><i class="fas fa-mobile-alt me-2"></i> UPI</label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="mode" id="mode-benefit" value="Benefit">
                                <label class="btn btn-outline-secondary w-100 py-2" for="mode-benefit"><i class="fas fa-exchange-alt me-2"></i> Benefit</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success w-100 py-3 rounded-4 fw-bold shadow-sm" id="savePaymentBtn" onclick="savePayment()">
                    Confirm & Add to Staff Wallets
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#invoiceTable').DataTable({
        "order": [[3, "desc"]],
        "pageLength": 10,
        "language": dtLanguage
    });

    $('.select2-init').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#invoiceModal'),
        width: '100%'
    });
});

function addRow() {
    const row = $('.item-row:first').clone();
    row.find('input').val(0);
    row.find('.item-qty').val(1);
    row.find('select').val('');
    $('#itemsTable tbody').append(row);
}

function removeRow(btn) {
    if($('#itemsTable tbody tr').length > 1) {
        $(btn).closest('tr').remove();
        calculateTotals();
    }
}

function updateRowPrice(select) {
    const price = $(select).find(':selected').data('price');
    const row = $(select).closest('tr');
    row.find('.item-price').val(price);
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    $('.item-row').each(function() {
        const price = parseFloat($(this).find('.item-price').val()) || 0;
        const qty = parseFloat($(this).find('.item-qty').val()) || 0;
        const total = price * qty;
        $(this).find('.item-total').val(total.toFixed(2));
        subtotal += total;
    });

    const taxPct = <?php echo $_SESSION['tax_pct'] ?? 18; ?>;
    let tax = 0;
    if (taxPct > 0) {
        tax = subtotal * (taxPct / 100);
    }
    const final = subtotal + tax;

    $('#subtotalText').text(subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2}));
    $('#taxText').text(tax.toLocaleString('en-IN', {minimumFractionDigits: 2}));
    $('#finalTotalText').text(final.toLocaleString('en-IN', {minimumFractionDigits: 2}));

    $('#subtotalInput').val(subtotal.toFixed(2));
    $('#taxInput').val(tax.toFixed(2));
    $('#finalTotalInput').val(final.toFixed(2));
}

function saveInvoice() {
    const btn = $('#saveInvoiceBtn');
    const formData = new FormData(document.getElementById('invoiceForm'));
    
    $('.item-row').each(function(index) {
        const svcId = $(this).find('.service-select').val();
        if(svcId) {
            formData.append(`services[${index}][id]`, svcId);
            formData.append(`services[${index}][price]`, $(this).find('.item-price').val());
        }
    });

    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

    $.ajax({
        url: '<?php echo BASE_URL; ?>/billing/create',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({icon: 'success', title: 'Invoice Generated', text: 'Billing complete. Commissions will be added to wallets upon payment.'}).then(() => location.reload());
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text('Generate Invoice');
            }
        }
    });
}

function openPaymentModal(id, amount, num) {
    $('#payInvoiceId').val(id);
    $('#payInvoiceNum').val(num);
    $('#payAmount').val(amount);
    $('#payFullAmount').text(amount.toFixed(2));
    new bootstrap.Modal(document.getElementById('paymentModal')).show();
}

function savePayment() {
    const btn = $('#savePaymentBtn');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing Wallet Credits...');
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>/billing/recordPayment',
        type: 'POST',
        data: $('#paymentForm').serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({
                    icon: 'success', 
                    title: 'Payment Successful', 
                    text: 'The amount has been split and credited to the Doctor, Technician, and Nurse wallets.'
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text('Confirm & Add to Staff Wallets');
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection error', 'error');
            btn.prop('disabled', false).text('Confirm & Add to Staff Wallets');
        }
    });
}

function deleteInvoice(id, num) {
    Swal.fire({
        title: 'Delete Invoice?',
        text: `Are you sure you want to delete ${num}? This will also reverse all staff commissions related to this invoice.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo BASE_URL; ?>/billing/delete/' + id,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: response.message, timer: 1500 }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                }
            });
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
