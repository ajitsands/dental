<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('welcome_back'); ?>, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p class="text-muted">Clinic Workflow: <strong><?php echo $data['branch_name']; ?></strong> | <?php echo date('l, jS M Y'); ?></p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>/appointment" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-1"></i> New Appointment
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Workflow Stats -->
    <div class="col-md-3">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted small fw-bold uppercase"><?php echo __('total_booked'); ?></h6>
                    <h3 class="mb-0 fw-bold"><?php echo count($data['todayAppointments']); ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted small fw-bold uppercase"><?php echo __('waiting_reported'); ?></h6>
                    <h3 class="mb-0 fw-bold" id="waitingCount">0</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-info bg-opacity-10 text-info me-3">
                    <i class="fas fa-user-md"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted small fw-bold uppercase"><?php echo __('in_consultation'); ?></h6>
                    <h3 class="mb-0 fw-bold" id="consultingCount">0</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-success bg-opacity-10 text-success me-3">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted small fw-bold uppercase"><?php echo __('completed'); ?></h6>
                    <h3 class="mb-0 fw-bold" id="completedCount">0</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="mb-0 fw-bold"><?php echo __('live_queue'); ?></h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="todayScheduleTable">
                        <thead>
                            <tr>
                                <th class="ps-4"><?php echo __('patient'); ?></th>
                                <th><?php echo __('time'); ?></th>
                                <th><?php echo __('status'); ?></th>
                                <th class="text-end pe-4"><?php echo __('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['todayAppointments'] as $app): ?>
                                <tr data-status="<?php echo $app->status; ?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($app->patient_name); ?>&background=random" alt="" class="rounded-circle me-3" width="35">
                                            <div>
                                                <div class="fw-bold"><?php echo $app->patient_name; ?></div>
                                                <div class="small text-muted"><?php echo $app->patient_uid; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="fw-bold"><?php echo date('h:i A', strtotime($app->start_time)); ?></span></td>
                                    <td>
                                        <?php 
                                            $badgeClass = 'bg-secondary-subtle text-secondary';
                                            if($app->status == 'Reported') $badgeClass = 'bg-warning-subtle text-warning';
                                            if($app->status == 'In Consultation') $badgeClass = 'bg-info-subtle text-info';
                                            if($app->status == 'Completed') $badgeClass = 'bg-success-subtle text-success';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?> border px-3 rounded-pill"><?php echo $app->status; ?></span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <?php if($app->status == 'Booked' || $app->status == 'Confirmed'): ?>
                                            <button class="btn btn-sm btn-warning rounded-pill px-3" onclick="updateStatus(<?php echo $app->id; ?>, 'Reported')">Mark Reported</button>
                                        <?php elseif($app->status == 'Reported'): ?>
                                            <button class="btn btn-sm btn-info text-white rounded-pill px-3" onclick="updateStatus(<?php echo $app->id; ?>, 'In Consultation')"><i class="fas fa-bullhorn me-1"></i> Call Patient</button>
                                        <?php elseif($app->status == 'In Consultation'): ?>
                                            <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="openPrescriptionModal(<?php echo $app->id; ?>, '<?php echo $app->patient_name; ?>', <?php echo $app->patient_id; ?>)">Complete & Prescribe</button>
                                        <?php elseif($app->status == 'Completed'): ?>
                                            <a href="<?php echo BASE_URL; ?>/dashboard/printPrescription/<?php echo $app->id; ?>" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3"><i class="fas fa-print me-1"></i> Prescription</a>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light rounded-pill px-3" onclick="updateStatus(<?php echo $app->id; ?>, 'Booked')">Reset Status</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="mb-0 fw-bold"><?php echo __('quick_actions'); ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="d-grid gap-3">
                    <a href="<?php echo BASE_URL; ?>/patient/register" class="btn btn-outline-secondary text-start p-3 border rounded-3 shadow-sm hover-lift d-block mb-3">
                        <i class="fas fa-user-plus text-primary me-2"></i> <?php echo __('register_new_patient'); ?>
                    </a>
                    <a href="<?php echo BASE_URL; ?>/billing" class="btn btn-outline-secondary text-start p-3 border rounded-3 shadow-sm hover-lift d-block mb-3">
                        <i class="fas fa-file-invoice-dollar text-success me-2"></i> <?php echo __('create_new_invoice'); ?>
                    </a>
                    <a href="<?php echo BASE_URL; ?>/wallet" class="btn btn-outline-secondary text-start p-3 border rounded-3 shadow-sm hover-lift d-block mb-3">
                        <i class="fas fa-wallet text-warning me-2"></i> <?php echo __('staff_commission'); ?>
                    </a>
                    <a href="<?php echo BASE_URL; ?>/patient/ledger" class="btn btn-outline-secondary text-start p-3 border rounded-3 shadow-sm hover-lift d-block">
                        <i class="fas fa-address-book text-info me-2"></i> <?php echo __('patient_balances'); ?>
                    </a>
                </div>
                
                <div class="mt-4 p-3 rounded-3 border-start border-4 border-primary" style="background-color: var(--sidebar-active);">
                    <h6 class="fw-bold small mb-2"><i class="fas fa-lightbulb text-primary me-1"></i> Quick Tip</h6>
                    <p class="small text-muted mb-0">Use the <strong>Financials</strong> menu at the top to see global audit reports.</p>
                </div>

                <?php if(!empty($data['agingDebt'])): ?>
                <div class="mt-4">
                    <h6 class="fw-bold text-danger mb-3"><i class="fas fa-exclamation-circle me-1"></i> Aging Debt Alerts (>30 Days)</h6>
                    <div class="list-group list-group-flush shadow-sm rounded-3 overflow-hidden border">
                        <?php foreach($data['agingDebt'] as $debt): ?>
                        <div class="list-group-item list-group-item-action p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold small text-dark"><?php echo $debt->name; ?></div>
                                    <div class="small text-muted">Inv #<?php echo $debt->invoice_number; ?></div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-danger small"><?php echo formatCurrency($debt->final_amount - ($debt->paid_amount ?? 0)); ?></div>
                                    <div class="small text-muted" style="font-size: 10px;"><?php echo date('M d', strtotime($debt->created_at)); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Prescription Modal -->
<div class="modal fade" id="prescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Consultation Summary & Prescription</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="prescriptionForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="appointment_id" id="prescAppId">
                    <input type="hidden" name="patient_id" id="prescPatientId">
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Medicines & Dosage</label>
                        <textarea name="medicines" class="form-control" rows="4" placeholder="1. Amoxicillin 500mg - 1-0-1 after food (5 days)&#10;2. Paracetamol 650mg - SOS" required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Doctor's Instructions</label>
                        <textarea name="instructions" class="form-control" rows="3" placeholder="Soft diet for 3 days. Warm salt water gargle."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-5 rounded-pill shadow">Complete Visit & Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let prescModal;
$(document).ready(function() {
    prescModal = new bootstrap.Modal(document.getElementById('prescriptionModal'));
    
    $('#todayScheduleTable').DataTable({
        "pageLength": 10,
        "language": dtLanguage
    });

    updateCounters();

    $('#prescriptionForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo BASE_URL; ?>/dashboard/savePrescription',
            type: 'POST',
            data: $(this).serialize(),
            success: function(r) {
                if(r.status === 'success') {
                    Swal.fire('Success', 'Visit completed and prescription saved.', 'success').then(() => location.reload());
                }
            }
        });
    });
});

function updateStatus(id, status) {
    $.post('<?php echo BASE_URL; ?>/dashboard/updateStatus', {id: id, status: status}, function(r) {
        if(r.status === 'success') location.reload();
    });
}

function openPrescriptionModal(appId, name, patientId) {
    $('#prescAppId').val(appId);
    $('#prescPatientId').val(patientId);
    prescModal.show();
}

function updateCounters() {
    $('#waitingCount').text($('tr[data-status="Reported"]').length);
    $('#consultingCount').text($('tr[data-status="In Consultation"]').length);
    $('#completedCount').text($('tr[data-status="Completed"]').length);
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
