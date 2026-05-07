<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('service_management'); ?></h2>
        <p class="text-muted"><?php echo __('manage_treatments'); ?></p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary shadow-sm rounded-pill px-4" onclick="openAddModal()">
            <i class="fas fa-plus me-1"></i> Add New Service
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="serviceTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><?php echo __('service_name'); ?></th>
                        <th><?php echo __('standard_cost'); ?></th>
                        <th><?php echo __('staff_comm'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th class="text-end pe-4"><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['services'] as $service): ?>
                    <tr id="service-row-<?php echo $service->id; ?>">
                        <td class="ps-4">
                            <div class="fw-bold svc-name"><?php echo $service->name; ?></div>
                            <div class="small text-muted">ID: #<?php echo $service->id; ?></div>
                        </td>
                        <td class="fw-bold text-primary svc-cost">
                            <?php echo formatCurrency($service->cost); ?>
                        </td>
                        <td class="svc-comm">
                            <div class="d-flex gap-1">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" title="Doctor">D: <?php echo $service->doc_comm_pct; ?>%</span>
                                <span class="badge bg-info-subtle text-info border border-info-subtle" title="Technician">T: <?php echo $service->tech_comm_pct; ?>%</span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle" title="Nurse">N: <?php echo $service->nurse_comm_pct; ?>%</span>
                            </div>
                        </td>
                        <td class="svc-status">
                            <?php if($service->status == 'Active'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 rounded-pill">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(<?php echo $service->id; ?>)">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteService(<?php echo $service->id; ?>)">
                                    <i class="fas fa-trash"></i>
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

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Add New Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="serviceForm">
                    <input type="hidden" name="id" id="serviceId">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Service Name</label>
                        <input type="text" name="name" id="serviceName" class="form-control" placeholder="e.g. Root Canal Treatment" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Standard Cost (<?php echo getCurrencySymbol(); ?>)</label>
                            <input type="number" name="cost" id="serviceCost" class="form-control" placeholder="5000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" id="serviceStatus" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <hr class="my-4">
                    <p class="small text-muted mb-3 fw-bold uppercase">Staff Commissions (%)</p>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small">Doctor</label>
                            <input type="number" name="doc_comm_pct" id="docComm" class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Technician</label>
                            <input type="number" name="tech_comm_pct" id="techComm" class="form-control" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Nurse</label>
                            <input type="number" name="nurse_comm_pct" id="nurseComm" class="form-control" value="0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-5 rounded-pill shadow" id="saveServiceBtn" onclick="saveService()">Save Service</button>
            </div>
        </div>
    </div>
</div>

<script>
let serviceModal;
const currencySymbol = '<?php echo getCurrencySymbol(); ?>';

$(document).ready(function() {
    serviceModal = new bootstrap.Modal(document.getElementById('serviceModal'));
});

function openAddModal() {
    $('#modalTitle').text('Add New Service');
    $('#serviceForm')[0].reset();
    $('#serviceId').val('');
    $('#serviceStatus').val('Active');
    serviceModal.show();
}

function openEditModal(id) {
    $('#modalTitle').text('Edit Service');
    $.get('<?php echo BASE_URL; ?>/service/get/' + id, function(response) {
        if(response.status === 'success') {
            const data = response.data;
            $('#serviceId').val(data.id);
            $('#serviceName').val(data.name);
            $('#serviceCost').val(data.cost);
            $('#serviceStatus').val(data.status);
            $('#docComm').val(data.doc_comm_pct);
            $('#techComm').val(data.tech_comm_pct);
            $('#nurseComm').val(data.nurse_comm_pct);
            serviceModal.show();
        } else {
            Swal.fire('Error', 'Could not fetch details', 'error');
        }
    }, 'json');
}

function saveService() {
    const btn = $('#saveServiceBtn');
    const serviceId = $('#serviceId').val();
    const isEdit = serviceId != '';
    
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>/service/save',
        type: 'POST',
        data: $('#serviceForm').serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Success', text: response.message });
                serviceModal.hide();
                btn.prop('disabled', false).text('Save Service');
                
                if (isEdit) {
                    const row = $(`#service-row-${serviceId}`);
                    row.find('.svc-name').text($('#serviceName').val());
                    const cost = parseFloat($('#serviceCost').val());
                    const decimals = currencySymbol.includes('BHD') || currencySymbol.includes('KWD') || currencySymbol.includes('OMR') ? 3 : 2;
                    row.find('.svc-cost').html(`${currencySymbol} ${cost.toFixed(decimals)}`);
                    
                    const status = $('#serviceStatus').val();
                    const statusHtml = status === 'Active' 
                        ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">Active</span>'
                        : '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 rounded-pill">Inactive</span>';
                    row.find('.svc-status').html(statusHtml);

                    const commHtml = `
                        <div class="d-flex gap-1">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">D: ${$('#docComm').val()}%</span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle">T: ${$('#techComm').val()}%</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">N: ${$('#nurseComm').val()}%</span>
                        </div>`;
                    row.find('.svc-comm').html(commHtml);
                } else {
                    location.reload(); // Re-fetch to get the new ID easily
                }
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text('Save Service');
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection failed', 'error');
            btn.prop('disabled', false).text('Save Service');
        }
    });
}

function deleteService(id) {
    Swal.fire({
        title: 'Delete Service?',
        text: "This will remove the procedure from your list.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?php echo BASE_URL; ?>/service/delete/' + id, function(response) {
                if(response.status === 'success') {
                    $(`#service-row-${id}`).fadeOut(300, function() { $(this).remove(); });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }, 'json');
        }
    });
}
$(document).ready(function() {
    $('#serviceTable').DataTable({
        "pageLength": 10,
        "language": dtLanguage
    });
});
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
