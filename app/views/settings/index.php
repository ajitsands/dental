<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-3">
        <div class="card p-3 mb-4 shadow-sm">
            <div class="nav flex-column nav-pills" id="settings-tabs">
                <button class="nav-link active mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-clinic"><i class="fas fa-clinic-medical me-2"></i> Clinic Profile</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-branches"><i class="fas fa-code-branch me-2"></i> Manage Branches</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-tax"><i class="fas fa-percentage me-2"></i> Tax & Billing</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-localization"><i class="fas fa-language me-2"></i> Localization</button>
                <button class="nav-link mb-2 text-start" data-bs-toggle="pill" data-bs-target="#tab-users"><i class="fas fa-users-cog me-2"></i> Staff Roles</button>
            </div>
        </div>
    </div>
    
    <div class="col-md-9">
        <div class="tab-content" id="settings-tabContent">
            <!-- Clinic Profile -->
            <div class="tab-pane fade show active" id="tab-clinic">
                <div class="card p-4 shadow-sm">
                    <h4 class="mb-4">Clinic Profile</h4>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-12 text-center mb-3">
                                <div class="densmart-logo mx-auto mb-2" style="width: 80px; height: 80px; font-size: 2rem;">
                                    <i class="fas fa-tooth"></i>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary">Change Logo</button>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Clinic Name</label>
                                <input type="text" class="form-control" value="DenSmart Central">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Primary Email</label>
                                <input type="email" class="form-control" value="contact@densmart.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" class="form-control" value="+91 9876543210">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" rows="2">123 Dental Street, Medical Plaza, City</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Save Profile</button>
                    </form>
                </div>
            </div>

            <!-- Manage Branches -->
            <div class="tab-pane fade" id="tab-branches">
                <div class="card p-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Manage Branches</h4>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBranchModal"><i class="fas fa-plus me-1"></i> Add New Branch</button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Branch Name</th>
                                    <th>Location</th>
                                    <th>Tax Type</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['branches'] as $branch): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $branch->name; ?></td>
                                    <td><?php echo $branch->country; ?></td>
                                    <td><span class="badge bg-secondary"><?php echo $branch->tax_type; ?></span></td>
                                    <td><span class="badge bg-success">Active</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
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
                <div class="card p-4 shadow-sm">
                    <h4 class="mb-4">Tax & Billing Configuration</h4>
                    <form>
                        <div class="mb-4">
                            <label class="form-label d-block">Tax Type</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="tax_type" id="tax_gst" checked>
                                <label class="btn btn-outline-primary" for="tax_gst">GST (India)</label>
                                
                                <input type="radio" class="btn-check" name="tax_type" id="tax_vat">
                                <label class="btn btn-outline-primary" for="tax_vat">VAT (GCC/Middle East)</label>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">VAT/GST Number</label>
                                <input type="text" class="form-control" placeholder="Enter Registration Number">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tax Rate (%)</label>
                                <input type="number" class="form-control" value="18">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Save Tax Settings</button>
                    </form>
                </div>
            </div>

            <!-- Localization -->
            <div class="tab-pane fade" id="tab-localization">
                <div class="card p-4 shadow-sm">
                    <h4 class="mb-4">Localization Settings</h4>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Default Language</label>
                            <select class="form-select">
                                <option value="en">English (US)</option>
                                <option value="ar">Arabic (العربية)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Time Zone</label>
                            <select class="form-select">
                                <option value="Asia/Kolkata">(GMT+05:30) India Standard Time</option>
                                <option value="Asia/Bahrain">(GMT+03:00) Bahrain</option>
                                <option value="Asia/Dubai">(GMT+04:00) Dubai</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Apply Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Clinic Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="branchForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Branch Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g., DenSmart South">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Country</label>
                            <select name="country" class="form-select">
                                <option value="India">India</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="UAE">UAE</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tax Type</label>
                            <select name="tax_type" class="form-select">
                                <option value="GST">GST (India)</option>
                                <option value="VAT">VAT (Middle East)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">VAT/GST Number</label>
                            <input type="text" name="tax_number" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Timezone</label>
                            <select name="timezone" class="form-select">
                                <option value="Asia/Kolkata">Asia/Kolkata (GMT+5:30)</option>
                                <option value="Asia/Bahrain">Asia/Bahrain (GMT+3:00)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBranchBtn" onclick="saveBranch()">Add Branch</button>
            </div>
        </div>
    </div>
</div>

<script>
function saveBranch() {
    const formData = $('#branchForm').serialize();
    const btn = $('#saveBranchBtn');
    
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>/settings/addBranch',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
                btn.prop('disabled', false).text('Add Branch');
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Connection failed'
            });
            btn.prop('disabled', false).text('Add Branch');
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
