<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('staff_wallet'); ?></h2>
        <p class="text-muted"><?php echo __('track_commissions'); ?></p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="walletTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Staff Member</th>
                        <th>Role</th>
                        <?php if($data['isSuperAdmin']): ?><th>Branch</th><?php endif; ?>
                        <th>Current Balance</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['staff'] as $s): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark"><?php echo $s->name; ?></div>
                            <div class="small text-muted"><?php echo $s->email; ?></div>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?php echo $s->role_name; ?></span></td>
                        <?php if($data['isSuperAdmin']): ?>
                            <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?php echo $s->branch_name; ?></span></td>
                        <?php endif; ?>
                        <td class="fw-bold text-success fs-5">
                            <?php echo formatCurrency($s->wallet_balance); ?>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-primary rounded-pill px-3 me-1" onclick="openPayoutModal(<?php echo $s->id; ?>, '<?php echo $s->name; ?>', <?php echo $s->wallet_balance; ?>)">
                                <i class="fas fa-hand-holding-usd me-1"></i> Pay Out
                            </button>
                            <a href="<?php echo BASE_URL; ?>/wallet/ledger/<?php echo $s->id; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="fas fa-list-ul me-1"></i> Ledger
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payout Modal -->
<div class="modal fade" id="payoutModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Process Payout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small">Record a cash payout to <strong id="payoutStaffName"></strong>.</p>
                <form id="payoutForm">
                    <input type="hidden" name="user_id" id="payoutStaffId">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Payout Amount</label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo getCurrencySymbol(); ?></span>
                            <input type="number" name="amount" id="payoutAmount" class="form-control form-control-lg" step="0.01" required>
                        </div>
                        <div class="form-text small">Max available: <span id="maxBalance"></span></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description / Reference</label>
                        <input type="text" name="description" class="form-control" placeholder="e.g. Weekly commission settlement">
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-5 rounded-pill shadow" id="confirmPayoutBtn" onclick="processPayout()">Confirm Payout</button>
            </div>
        </div>
    </div>
</div>

<script>
let payoutModal;

$(document).ready(function() {
    payoutModal = new bootstrap.Modal(document.getElementById('payoutModal'));
    $('#walletTable').DataTable({
        "pageLength": 10,
        "language": dtLanguage
    });
});

function openPayoutModal(id, name, balance) {
    $('#payoutStaffId').val(id);
    $('#payoutStaffName').text(name);
    $('#payoutAmount').val(balance > 0 ? balance : 0);
    $('#maxBalance').text(balance.toLocaleString());
    payoutModal.show();
}

function processPayout() {
    const btn = $('#confirmPayoutBtn');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

    $.ajax({
        url: '<?php echo BASE_URL; ?>/wallet/payout',
        type: 'POST',
        data: $('#payoutForm').serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire('Success', 'Payout recorded and balance updated.', 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text('Confirm Payout');
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection failed', 'error');
            btn.prop('disabled', false).text('Confirm Payout');
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
