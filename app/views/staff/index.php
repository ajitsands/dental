<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1"><?php echo __('staff_management'); ?></h2>
        <?php if($data['isSuperAdmin']): ?>
            <p class="text-muted"><span class="badge bg-danger">Super Admin Access</span> Managing all clinic branches</p>
        <?php else: ?>
            <p class="text-muted"><?php echo __('manage_clinic_team'); ?> <strong><?php echo $_SESSION['branch_name'] ?? 'Main Clinic'; ?></strong></p>
        <?php endif; ?>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary shadow-sm" onclick="openAddModal()"><i class="fas fa-user-plus me-1"></i> Add New Staff</button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="staffTable" class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4"><?php echo __('staff_name'); ?></th>
                        <?php if($data['isSuperAdmin']): ?><th><?php echo __('branch'); ?></th><?php endif; ?>
                        <th><?php echo __('role'); ?></th>
                        <th><?php echo __('status'); ?></th>
                        <th><?php echo __('wallet_balance'); ?></th>
                        <th class="text-end pe-4"><?php echo __('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['staff'] as $s): ?>
                    <tr id="staff-row-<?php echo $s->id; ?>">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($s->name); ?>&background=random" class="rounded-circle me-3" width="35">
                                <div>
                                    <div class="fw-bold s-name"><?php echo $s->name; ?></div>
                                    <div class="small text-muted"><?php echo $s->email; ?></div>
                                </div>
                            </div>
                        </td>
                        <?php if($data['isSuperAdmin']): ?>
                            <td><span class="badge bg-light text-dark border"><?php echo $s->branch_name ?? 'N/A'; ?></span></td>
                        <?php endif; ?>
                        <td>
                            <?php 
                            $roleClass = 'bg-secondary';
                            if($s->role_name == 'Admin') $roleClass = 'bg-dark';
                            if($s->role_name == 'Super Admin') $roleClass = 'bg-danger';
                            if($s->role_name == 'Dentist') $roleClass = 'bg-primary';
                            if($s->role_name == 'Technician') $roleClass = 'bg-info text-dark';
                            if($s->role_name == 'Nurse') $roleClass = 'bg-success';
                            ?>
                            <span class="badge <?php echo $roleClass; ?> s-role"><?php echo $s->role_name; ?></span>
                        </td>
                        <td class="s-status">
                            <?php if($s->status == 'active'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 rounded-pill">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 rounded-pill">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-success"><?php echo formatCurrency($s->wallet_balance); ?></td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-warning" onclick="openResetModal(<?php echo $s->id; ?>, '<?php echo $s->name; ?>')" title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="openEditModal(<?php echo $s->id; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if ($s->role_name !== 'Super Admin'): ?>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteStaff(<?php echo $s->id; ?>, '<?php echo $s->name; ?>')" title="Delete Staff">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark border-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-key me-2"></i> Reset Password</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="resetUserId">
                <p class="small text-muted mb-3">Set a new password for <strong id="resetUserName"></strong></p>
                <div class="mb-3">
                    <label class="form-label small fw-bold">New Password</label>
                    <input type="text" id="newPassword" class="form-control form-control-sm" placeholder="Minimum 6 characters">
                </div>
                <div class="d-grid">
                    <button type="button" class="btn btn-warning btn-sm fw-bold" onclick="confirmReset()">Update Password</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Modal -->
<div class="modal fade" id="staffModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Add New Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="staffForm">
                    <input type="hidden" name="id" id="staffId">
                    
                    <?php if($data['isSuperAdmin']): ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Assign to Branch</label>
                        <select name="branch_id" id="staffBranch" class="form-select border-primary-subtle" required>
                            <option value="">-- Select Branch --</option>
                            <?php foreach($data['branches'] as $branch): ?>
                                <option value="<?php echo $branch->id; ?>"><?php echo $branch->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Full Name</label>
                        <input type="text" name="name" id="staffName" class="form-control" placeholder="e.g. Dr. Sarah Smith" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email (Username)</label>
                            <input type="email" name="email" id="staffEmail" class="form-control" placeholder="sarah@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Password <span id="passHint" class="small text-muted">(Optional for Edit)</span></label>
                            <input type="password" name="password" id="staffPass" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Role</label>
                            <select name="role_id" id="staffRole" class="form-select" required>
                                <option value="">-- Select Role --</option>
                                <?php foreach($data['roles'] as $role): ?>
                                    <option value="<?php echo $role->id; ?>"><?php echo $role->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status</label>
                            <select name="status" id="staffStatus" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Contact Phone</label>
                            <input type="text" name="phone" id="staffPhone" class="form-control" placeholder="+91...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Commission (%)</label>
                            <input type="number" name="commission_pct" id="staffComm" class="form-control" value="0" step="0.1">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary px-5 rounded-pill shadow" id="saveStaffBtn" onclick="saveStaff()">Save Staff</button>
            </div>
        </div>
    </div>
</div>

<script>
let staffModal;
let resetModal;

$(document).ready(function() {
    staffModal = new bootstrap.Modal(document.getElementById('staffModal'));
    resetModal = new bootstrap.Modal(document.getElementById('resetModal'));
    $('#staffTable').DataTable({
        "pageLength": 10,
        "language": dtLanguage
    });
});

function openResetModal(id, name) {
    $('#resetUserId').val(id);
    $('#resetUserName').text(name);
    $('#newPassword').val('');
    resetModal.show();
}

function confirmReset() {
    const id = $('#resetUserId').val();
    const pass = $('#newPassword').val();
    
    if(pass.length < 4) {
        Swal.fire('Error', 'Password is too short', 'error');
        return;
    }

    $.ajax({
        url: '<?php echo BASE_URL; ?>/staff/reset_password',
        type: 'POST',
        data: { id: id, new_password: pass },
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire('Success', response.message, 'success');
                resetModal.hide();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        }
    });
}

function openAddModal() {
    $('#modalTitle').text('Add New Staff Member');
    $('#staffForm')[0].reset();
    $('#staffId').val('');
    $('#passHint').hide();
    staffModal.show();
}

function openEditModal(id) {
    $('#modalTitle').text('Edit Staff Member');
    $('#passHint').show();
    $.get('<?php echo BASE_URL; ?>/staff/get/' + id, function(response) {
        if(response.status === 'success') {
            const d = response.data;
            $('#staffId').val(d.id);
            $('#staffName').val(d.name);
            $('#staffEmail').val(d.email);
            $('#staffRole').val(d.role_id);
            $('#staffStatus').val(d.status);
            $('#staffPhone').val(d.phone);
            $('#staffComm').val(d.commission_pct);
            if($('#staffBranch').length) $('#staffBranch').val(d.branch_id);
            staffModal.show();
        } else {
            Swal.fire('Error', response.message, 'error');
        }
    }, 'json');
}

function saveStaff() {
    const btn = $('#saveStaffBtn');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

    $.ajax({
        url: '<?php echo BASE_URL; ?>/staff/save',
        type: 'POST',
        data: $('#staffForm').serialize(),
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({icon: 'success', title: 'Success', text: response.message}).then(() => location.reload());
            } else {
                Swal.fire('Error', response.message, 'error');
                btn.prop('disabled', false).text('Save Staff');
            }
        },
        error: function() {
            Swal.fire('Error', 'Connection failed', 'error');
            btn.prop('disabled', false).text('Save Staff');
        }
    });
}

function deleteStaff(id, name) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Remove " + name + " from the system?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, remove'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?php echo BASE_URL; ?>/staff/delete/' + id, function(response) {
                if(response.status === 'success') {
                    $(`#staff-row-${id}`).fadeOut(300);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }, 'json');
        }
    });
}

</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
