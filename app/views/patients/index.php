<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Patient Management</h2>
        <p class="text-muted">Search, view, and manage your clinic's patients.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>/patient/register" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New Patient</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0" placeholder="Search by name, phone, or ID...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">All Genders</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-outline-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Patient ID</th>
                        <th>Name</th>
                        <th>Age/Gender</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['patients'])): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No patients found. Click "New Patient" to add one.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($data['patients'] as $patient): ?>
                        <tr>
                            <td class="ps-4"><span class="fw-bold"><?php echo $patient->unique_id; ?></span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($patient->name); ?>&background=random" alt="" class="rounded-circle me-2" width="35">
                                    <div>
                                        <div class="fw-bold"><?php echo $patient->name; ?></div>
                                        <?php if(!empty($patient->medical_alerts)): ?>
                                            <small class="text-danger fw-bold" style="font-size: 10px;"><?php echo $patient->medical_alerts; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $patient->age; ?> / <?php echo $patient->gender; ?></td>
                            <td><?php echo $patient->contact; ?></td>
                            <td><span class="badge bg-success bg-opacity-10 text-success">Active</span></td>
                            <td class="text-end pe-4">
                                <a href="<?php echo BASE_URL; ?>/patient/chart/<?php echo $patient->id; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-tooth me-1"></i> Chart</a>
                                <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
