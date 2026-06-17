<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('global_patient_registry'); ?></h2>
        <p class="text-muted"><?php echo __('manage_all_patients'); ?></p>
    </div>
    <div class="col-md-4 text-end">
        <button onclick="openImportModal()" class="btn btn-outline-success rounded-pill px-4 shadow-sm me-2">
            <i class="fas fa-file-import me-1"></i> Import Patients
        </button>
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

<!-- Import Patients Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-success"><i class="fas fa-file-import me-2"></i> Import Patients</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="importForm" enctype="multipart/form-data">
                    <div class="mb-3 p-3 bg-light rounded-3">
                        <h6 class="fw-bold mb-2">Instructions:</h6>
                        <ul class="small text-muted mb-0 ps-3">
                            <li>Download the CSV template using the button below.</li>
                            <li>Open it in Excel or Google Sheets, add patient details, and save as CSV.</li>
                            <li>Required field: <strong>Name</strong>.</li>
                            <li>Gender must be: <strong>Male</strong>, <strong>Female</strong>, or <strong>Other</strong>.</li>
                            <li>Age should be a positive number.</li>
                        </ul>
                        <div class="mt-3 text-center">
                            <a href="<?php echo BASE_URL; ?>/patient/downloadTemplate" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fas fa-download me-1"></i> Download CSV Template
                            </a>
                        </div>
                    </div>

                    <?php if($data['isSuperAdmin']): ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Select Branch to Import Into</label>
                        <select name="branch_id" id="importBranch" class="form-select border-primary-subtle" required>
                            <option value="">-- Select Branch --</option>
                            <?php foreach($data['branches'] as $branch): ?>
                                <option value="<?php echo $branch->id; ?>"><?php echo $branch->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Choose CSV File</label>
                        <input type="file" name="patient_file" id="patientFile" class="form-control" accept=".csv" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success px-5 rounded-pill shadow" id="importSubmitBtn" onclick="submitImport()">Import</button>
            </div>
        </div>
    </div>
</div>

<script>
let rxModal;
let importModal;

$(document).ready(function() {
    $('#patientTable').DataTable({
        "pageLength": 10,
        "language": dtLanguage
    });

    // Initialize modals
    rxModal = new bootstrap.Modal(document.getElementById('rxModal'));
    importModal = new bootstrap.Modal(document.getElementById('importModal'));
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

function openImportModal() {
    $('#importForm')[0].reset();
    if($('#importBranch').length) {
        $('#importBranch').val('');
    }
    importModal.show();
}

function submitImport() {
    const fileInput = document.getElementById('patientFile');
    if(!fileInput.files.length) {
        Swal.fire('Error', 'Please select a CSV file to import.', 'error');
        return;
    }

    if($('#importBranch').length && !$('#importBranch').val()) {
        Swal.fire('Error', 'Please select a branch to import patients into.', 'error');
        return;
    }

    const btn = $('#importSubmitBtn');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Importing...');

    const formData = new FormData(document.getElementById('importForm'));

    $.ajax({
        url: '<?php echo BASE_URL; ?>/patient/import',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text('Import');
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection failed or server error', 'error');
            btn.prop('disabled', false).text('Import');
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
