<?php require_once '../app/views/layouts/header.php'; ?>

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
                        <th>Last Visit</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Mock Data -->
                    <tr>
                        <td class="ps-4"><span class="fw-bold">P-1001</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Amit+Shah&background=random" alt="" class="rounded-circle me-2" width="35">
                                <div>
                                    <div class="fw-bold">Amit Shah</div>
                                    <small class="text-danger fw-bold" style="font-size: 10px;">DIABETIC</small>
                                </div>
                            </div>
                        </td>
                        <td>45 / Male</td>
                        <td>+91 98765 43210</td>
                        <td>2024-05-01</td>
                        <td class="text-end pe-4">
                            <a href="<?php echo BASE_URL; ?>/patient/chart/1001" class="btn btn-sm btn-outline-primary"><i class="fas fa-tooth me-1"></i> Chart</a>
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td class="ps-4"><span class="fw-bold">P-1002</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Sara+Khan&background=random" alt="" class="rounded-circle me-2" width="35">
                                <div>
                                    <div class="fw-bold">Sara Khan</div>
                                </div>
                            </div>
                        </td>
                        <td>28 / Female</td>
                        <td>+91 99887 76655</td>
                        <td>2024-04-28</td>
                        <td class="text-end pe-4">
                            <a href="<?php echo BASE_URL; ?>/patient/chart/1002" class="btn btn-sm btn-outline-primary"><i class="fas fa-tooth me-1"></i> Chart</a>
                            <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
