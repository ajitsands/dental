<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('global_patient_registry'); ?></h2>
        <p class="text-muted"><?php echo __('manage_all_patients'); ?></p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>/patient/register" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-1"></i> New Patient
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="patientTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><?php echo __('patient_id'); ?></th>
                        <th><?php echo __('name'); ?></th>
                        <th><?php echo __('contact'); ?></th>
                        <th><?php echo __('registered_at'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th class="text-end pe-4"><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['patients'] as $p): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-primary"><?php echo $p->unique_id; ?></td>
                        <td>
                            <div class="fw-bold"><?php echo $p->name; ?></div>
                            <div class="small text-muted"><?php echo $p->gender; ?>, <?php echo $p->age; ?> years</div>
                        </td>
                        <td>
                            <div><?php echo $p->contact; ?></div>
                            <div class="small text-muted"><?php echo $p->email; ?></div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><i class="fas fa-clinic-medical me-1"></i> <?php echo $p->origin_branch; ?></span>
                        </td>
                        <td>
                            <?php if($p->medical_alerts == 'CRITICAL'): ?>
                                <span class="badge bg-danger">CRITICAL ALERT</span>
                            <?php else: ?>
                                <span class="badge bg-success">Healthy</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="<?php echo BASE_URL; ?>/patient/details/<?php echo $p->id; ?>" class="btn btn-sm btn-outline-primary" title="View History">
                                    <i class="fas fa-history"></i> History
                                </a>
                                <button onclick="viewPrescriptions(<?php echo $p->id; ?>, '<?php echo addslashes($p->name); ?>')" class="btn btn-sm btn-outline-success" title="Print Prescription">
                                    <i class="fas fa-file-prescription"></i> Rx
                                </button>
                                <a href="<?php echo BASE_URL; ?>/patient/ledger/<?php echo $p->id; ?>" class="btn btn-sm btn-outline-warning" title="Financial Ledger">
                                    <i class="fas fa-wallet"></i> Ledger
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Prescription Modal -->
<div class="modal fade" id="rxModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-file-prescription me-2"></i> Prescriptions for <span id="rxPatientName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="rxList" class="list-group list-group-flush">
                    <!-- Loaded via AJAX -->
                </div>
                <div id="rxEmpty" class="p-5 text-center d-none">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No prescriptions found for this patient.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let rxModal;

$(document).ready(function() {
    $('#patientTable').DataTable({
        "pageLength": 10,
        "language": dtLanguage
    });

    // Initialize modal after everything is loaded
    rxModal = new bootstrap.Modal(document.getElementById('rxModal'));
});

function viewPrescriptions(id, name) {
    if(!rxModal) rxModal = new bootstrap.Modal(document.getElementById('rxModal'));
    
    $('#rxPatientName').text(name);
    $('#rxList').html('<div class="p-4 text-center"><span class="spinner-border text-success"></span> Loading...</div>');
    $('#rxEmpty').addClass('d-none');
    rxModal.show();

    $.get('<?php echo BASE_URL; ?>/patient/getPrescriptions/' + id, function(response) {
        if(response.status === 'success' && response.data.length > 0) {
            let html = '';
            response.data.forEach(rx => {
                const date = new Date(rx.start_time).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                html += `
                    <div class="list-group-item p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-bold mb-1">Prescription - ${date}</div>
                            <div class="small text-muted">By Dr. ${rx.doctor_name}</div>
                            <div class="mt-1 small text-truncate" style="max-width: 400px;">${rx.medicines}</div>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/dashboard/printPrescription/${rx.appointment_id}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                            <i class="fas fa-print me-1"></i> Print
                        </a>
                    </div>
                `;
            });
            $('#rxList').html(html);
        } else {
            $('#rxList').empty();
            $('#rxEmpty').removeClass('d-none');
        }
    }, 'json');
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
