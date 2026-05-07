<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<style>
    /* Settings Sidebar Styling */
    #settings-tabs .nav-link {
        color: #475569;
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    #settings-tabs .nav-link:hover {
        background-color: rgba(13, 110, 253, 0.05);
        color: var(--primary-color);
    }

    #settings-tabs .nav-link.active {
        background-color: var(--primary-color) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }

    #settings-tabs .nav-link i {
        width: 20px;
        text-align: center;
    }
</style>

<div class="row">
    <div class="col-md-3">
        <div class="card p-3 mb-4 shadow-sm border-0">
            <div class="nav flex-column nav-pills" id="settings-tabs">
                <button class="nav-link active mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-clinic"><i class="fas fa-clinic-medical me-2"></i> Clinic Profile</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-branches"><i class="fas fa-code-branch me-2"></i> Manage Branches</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-tax"><i class="fas fa-percentage me-2"></i> Tax & Billing</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-localization"><i class="fas fa-language me-2"></i> Localization</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="tab-content" id="settings-tabContent">
            <!-- Clinic Profile -->
            <div class="tab-pane fade show active" id="tab-clinic">
                <div class="card p-4 shadow-sm border-0">
                    <h4 class="fw-bold mb-4">Clinic Profile</h4>
                    <?php $b = $data['currentBranch']; ?>
                    <form id="profileForm">
                        <input type="hidden" name="id" value="<?php echo $b->id; ?>">
                        <div class="row g-3">
                            <div class="col-md-12 text-center mb-3">
                                <div class="densmart-logo mx-auto mb-2" style="width: 80px; height: 80px; font-size: 2rem;">
                                    <?php if($b->logo): ?>
                                        <img src="<?php echo BASE_URL . $b->logo; ?>" class="rounded-circle w-100 h-100 object-fit-cover">
                                    <?php else: ?>
                                        <i class="fas fa-tooth"></i>
                                    <?php endif; ?>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3">Change Logo</button>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Clinic Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $b->name; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Primary Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $b->email; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Contact Number</label>
                                <input type="text" name="contact" class="form-control" value="<?php echo $b->contact; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tax/GST Number</label>
                                <input type="text" name="tax_number" class="form-control" value="<?php echo $b->tax_number; ?>">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Full Address</label>
                                <textarea name="address" class="form-control" rows="2" required><?php echo $b->address; ?></textarea>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-4 px-5 rounded-pill shadow" id="saveProfileBtn" onclick="saveProfile()">Save Profile Changes</button>
                    </form>
                </div>
            </div>

            <!-- Manage Branches -->
            <div class="tab-pane fade" id="tab-branches">
                <div class="card p-4 shadow-sm border-0">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Clinic Branches</h4>
                        <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="openAddBranchModal()"><i class="fas fa-plus me-1"></i> Add Branch</button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="branchTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Branch Name</th>
                                    <th>Location</th>
                                    <th>Tax Configuration</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['branches'] as $branch): ?>
                                <tr id="branch-row-<?php echo $branch->id; ?>">
                                    <td class="fw-bold text-primary branch-name"><?php echo $branch->name; ?></td>
                                    <td class="branch-country"><?php echo $branch->country; ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border branch-tax"><?php echo $branch->tax_type; ?>: <?php echo $branch->tax_number ?: 'N/A'; ?></span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary" onclick="openEditBranchModal(<?php echo $branch->id; ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tax & Billing -->
            <div class="tab-pane fade" id="tab-tax">
                <div class="card p-4 shadow-sm border-0">
                    <h4 class="fw-bold mb-4">Tax & Billing Configuration</h4>
                    <form id="taxForm">
                        <input type="hidden" name="id" value="<?php echo $b->id; ?>">
                        <!-- Hidden fields to maintain other branch data -->
                        <input type="hidden" name="name" value="<?php echo $b->name; ?>">
                        <input type="hidden" name="email" value="<?php echo $b->email; ?>">
                        <input type="hidden" name="contact" value="<?php echo $b->contact; ?>">
                        <input type="hidden" name="address" value="<?php echo $b->address; ?>">
                        <input type="hidden" name="country" value="<?php echo $b->country; ?>">
                        <input type="hidden" name="timezone" value="<?php echo $b->timezone; ?>">

                        <div class="mb-4">
                            <label class="form-label d-block small fw-bold uppercase text-muted">Tax System</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tax_type" id="tax_gst" value="GST" <?php echo ($b->tax_type == 'GST') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-primary py-2" for="tax_gst"><i class="fas fa-indian-rupee-sign me-1"></i> GST (India)</label>
                                
                                <input type="radio" class="btn-check" name="tax_type" id="tax_vat" value="VAT" <?php echo ($b->tax_type == 'VAT') ? 'checked' : ''; ?>>
                                <label class="btn btn-outline-primary py-2" for="tax_vat"><i class="fas fa-globe me-1"></i> VAT (GCC/Global)</label>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Tax Registration Number</label>
                                <input type="text" name="tax_number" class="form-control" value="<?php echo $b->tax_number; ?>" placeholder="Enter GSTIN or VAT Number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Standard Tax Rate (%)</label>
                                <input type="number" class="form-control" value="18" readonly disabled>
                                <div class="form-text small">Global rate is currently fixed at 18%</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-4 px-5 rounded-pill shadow" id="saveTaxBtn" onclick="saveSettings('taxForm', 'saveTaxBtn')">Save Tax Settings</button>
                    </form>
                </div>
            </div>

            <!-- Localization -->
            <div class="tab-pane fade" id="tab-localization">
                <div class="card p-4 shadow-sm border-0">
                    <h4 class="fw-bold mb-4">Localization Settings</h4>
                    <form id="locForm">
                        <input type="hidden" name="id" value="<?php echo $b->id; ?>">
                        <!-- Hidden fields to maintain other branch data -->
                        <input type="hidden" name="name" value="<?php echo $b->name; ?>">
                        <input type="hidden" name="email" value="<?php echo $b->email; ?>">
                        <input type="hidden" name="contact" value="<?php echo $b->contact; ?>">
                        <input type="hidden" name="address" value="<?php echo $b->address; ?>">
                        <input type="hidden" name="tax_number" value="<?php echo $b->tax_number; ?>">
                        <input type="hidden" name="tax_type" value="<?php echo $b->tax_type; ?>">

                        <div class="mb-3">
                            <label class="form-label small fw-bold uppercase text-muted">Clinic Country</label>
                            <select name="country" class="form-select">
                                <option value="India" <?php echo ($b->country == 'India') ? 'selected' : ''; ?>>India</option>
                                <option value="Bahrain" <?php echo ($b->country == 'Bahrain') ? 'selected' : ''; ?>>Bahrain</option>
                                <option value="UAE" <?php echo ($b->country == 'UAE') ? 'selected' : ''; ?>>UAE</option>
                                <option value="Saudi Arabia" <?php echo ($b->country == 'Saudi Arabia') ? 'selected' : ''; ?>>Saudi Arabia</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold uppercase text-muted">Clinic Time Zone</label>
                            <select name="timezone" class="form-select">
                                <option value="Asia/Kolkata" <?php echo ($b->timezone == 'Asia/Kolkata') ? 'selected' : ''; ?>>Asia/Kolkata (GMT+05:30) India</option>
                                <option value="Asia/Bahrain" <?php echo ($b->timezone == 'Asia/Bahrain') ? 'selected' : ''; ?>>Asia/Bahrain (GMT+03:00) Bahrain</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary mt-4 px-5 rounded-pill shadow" id="saveLocBtn" onclick="saveSettings('locForm', 'saveLocBtn')">Apply Localization</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Branch Modal -->
<div class="modal fade" id="branchModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="branchModalTitle">Add New Clinic Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="branchForm">
                    <input type="hidden" name="id" id="branchId">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Branch Name</label>
                            <input type="text" name="name" id="branchName" class="form-control" required placeholder="e.g., DenSmart South">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="branchEmail" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact" id="branchContact" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <select name="country" id="branchCountry" class="form-select">
                                <option value="India">India</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="UAE">UAE</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tax Type</label>
                            <select name="tax_type" id="branchTaxType" class="form-select">
                                <option value="GST">GST (India)</option>
                                <option value="VAT">VAT (Middle East)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">VAT/GST Number</label>
                            <input type="text" name="tax_number" id="branchTaxNumber" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Timezone</label>
                            <select name="timezone" id="branchTimezone" class="form-select">
                                <option value="Asia/Kolkata">Asia/Kolkata (GMT+5:30)</option>
                                <option value="Asia/Bahrain">Asia/Bahrain (GMT+3:00)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" id="branchAddress" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-4 rounded-pill shadow" id="saveBranchBtn" onclick="saveBranch()">Save Branch Details</button>
            </div>
        </div>
    </div>
</div>

<script>
let branchModal;

$(document).ready(function() {
    branchModal = new bootstrap.Modal(document.getElementById('branchModal'));
    
    // Maintain active tab on page reload if any
    const activeTab = localStorage.getItem('activeSettingsTab');
    if (activeTab) {
        $(`button[data-bs-target="${activeTab}"]`).tab('show');
    }

    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
        localStorage.setItem('activeSettingsTab', $(e.target).data('bs-target'));
    });
});

function saveSettings(formId, btnId) {
    const formData = $('#' + formId).serialize();
    const btn = $('#' + btnId);
    const originalText = btn.text();
    
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>/settings/saveBranch',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Settings Saved',
                    text: 'Your clinic configuration has been updated successfully.'
                });
                btn.prop('disabled', false).text(originalText);
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text(originalText);
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection failed', 'error');
            btn.prop('disabled', false).text(originalText);
        }
    });
}

function saveProfile() {
    const formData = $('#profileForm').serialize();
    const btn = $('#saveProfileBtn');
    
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>/settings/saveBranch',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: 'Your clinic profile details have been successfully saved.'
                });
                btn.prop('disabled', false).text('Save Profile Changes');
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text('Save Profile Changes');
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection failed', 'error');
            btn.prop('disabled', false).text('Save Profile Changes');
        }
    });
}

function openAddBranchModal() {
    $('#branchModalTitle').text('Add New Clinic Branch');
    $('#branchForm')[0].reset();
    $('#branchId').val('');
    branchModal.show();
}

function openEditBranchModal(id) {
    $('#branchModalTitle').text('Edit Clinic Branch');
    
    $.get('<?php echo BASE_URL; ?>/settings/getBranch/' + id, function(response) {
        if(response.status === 'success') {
            const data = response.data;
            $('#branchId').val(data.id);
            $('#branchName').val(data.name);
            $('#branchEmail').val(data.email);
            $('#branchContact').val(data.contact);
            $('#branchCountry').val(data.country);
            $('#branchTaxType').val(data.tax_type);
            $('#branchTaxNumber').val(data.tax_number);
            $('#branchTimezone').val(data.timezone);
            $('#branchAddress').val(data.address);
            branchModal.show();
        } else {
            Swal.fire('Error', response.message, 'error');
        }
    }, 'json');
}

function saveBranch() {
    const formData = $('#branchForm').serialize();
    const btn = $('#saveBranchBtn');
    const branchId = $('#branchId').val();
    const isEdit = branchId != '';
    
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>/settings/saveBranch',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Success', text: response.message });
                branchModal.hide();
                btn.prop('disabled', false).text('Save Branch Details');
                
                // Dynamic UI update
                if (isEdit) {
                    const row = $(`#branch-row-${branchId}`);
                    row.find('.branch-name').text($('#branchName').val());
                    row.find('.branch-country').text($('#branchCountry').val());
                    row.find('.branch-tax').text(`${$('#branchTaxType').val()}: ${$('#branchTaxNumber').val() || 'N/A'}`);
                } else {
                    // Prepend or reload
                    location.reload(); 
                }
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text('Save Branch Details');
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection failed', 'error');
            btn.prop('disabled', false).text('Save Branch Details');
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
