<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>/patient">Patients</a></li>
                <li class="breadcrumb-item active"><?php echo $data['patient']->name; ?></li>
            </ol>
        </nav>
        <h2 class="mb-0"><?php echo $data['patient']->name; ?> <span class="badge bg-light text-primary fs-6 ms-2"><?php echo $data['patient']->unique_id; ?></span></h2>
    </div>
    <div class="col-md-4 text-end pt-3">
        <a href="<?php echo BASE_URL; ?>/patient/chart/<?php echo $data['patient']->id; ?>" class="btn btn-outline-info rounded-pill px-4 shadow-sm">
            <i class="fas fa-tooth me-1"></i> View Dental Chart
        </a>
    </div>
</div>

<div class="row">
    <!-- Patient Info Sidebar -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Patient Profile</h5>
                <div class="mb-3">
                    <label class="small text-muted d-block">Origin Branch</label>
                    <span class="fw-bold"><i class="fas fa-clinic-medical me-1"></i> <?php echo $data['patient']->origin_branch; ?></span>
                </div>
                <div class="mb-3">
                    <label class="small text-muted d-block">Contact Info</label>
                    <div class="fw-bold"><?php echo $data['patient']->contact; ?></div>
                    <div class="small"><?php echo $data['patient']->email; ?></div>
                </div>
                <div class="mb-3">
                    <label class="small text-muted d-block">Gender / Age</label>
                    <span class="fw-bold"><?php echo $data['patient']->gender; ?> / <?php echo $data['patient']->age; ?> yrs</span>
                </div>
                <hr>
                <div class="mb-3">
                    <label class="small text-muted d-block">Medical History</label>
                    <p class="mb-0"><?php echo nl2br($data['patient']->medical_history ?: 'No history recorded.'); ?></p>
                </div>
                <?php if($data['patient']->medical_alerts): ?>
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i> <?php echo $data['patient']->medical_alerts; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Treatment History -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Unified Treatment History</h5>
                
                <ul class="nav nav-tabs border-0 mb-4" id="historyTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold border-0" data-bs-toggle="tab" data-bs-target="#appointments">Appointments</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold border-0" data-bs-toggle="tab" data-bs-target="#invoices">Billings & Treatments</button>
                    </li>
                </ul>

                <div class="tab-content" id="historyTabContent">
                    <!-- Appointments Tab -->
                    <div class="tab-pane fade show active" id="appointments">
                        <?php if(empty($data['appointments'])): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x mb-3 text-muted opacity-25"></i>
                                <p class="text-muted">No appointments found for this patient.</p>
                            </div>
                        <?php else: ?>
                            <div class="timeline">
                                <?php foreach($data['appointments'] as $app): ?>
                                    <div class="p-3 border rounded-3 mb-3 bg-light bg-opacity-50">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="fw-bold"><?php echo date('D, M j, Y', strtotime($app->start_time)); ?></div>
                                                <div class="small text-muted"><?php echo date('h:i A', strtotime($app->start_time)); ?></div>
                                            </div>
                                            <span class="badge bg-white text-dark border"><i class="fas fa-clinic-medical me-1"></i> <?php echo $app->branch_name; ?></span>
                                        </div>
                                        <div class="mt-2">
                                            <span class="text-muted">Doctor:</span> <span class="fw-bold text-primary"><?php echo $app->doctor_name ?: 'N/A'; ?></span>
                                        </div>
                                        <?php if($app->notes): ?>
                                            <div class="mt-2 small text-muted fst-italic">"<?php echo $app->notes; ?>"</div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Invoices Tab -->
                    <div class="tab-pane fade" id="invoices">
                        <?php if(empty($data['invoices'])): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-muted opacity-25"></i>
                                <p class="text-muted">No billing history found.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Invoice #</th>
                                            <th>Branch</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['invoices'] as $inv): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo $inv->invoice_number; ?></td>
                                            <td><span class="small"><?php echo $inv->branch_name; ?></span></td>
                                            <td class="fw-bold text-success"><?php echo defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : '₹'; ?> <?php echo number_format($inv->final_amount, 2); ?></td>
                                            <td>
                                                <span class="badge <?php echo ($inv->status == 'Paid') ? 'bg-success' : 'bg-warning'; ?>">
                                                    <?php echo $inv->status; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
