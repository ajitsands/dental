<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Global Patient Registry</h2>
        <p class="text-muted">Access patient records and history across all clinic branches.</p>
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
                        <th class="ps-4">Patient ID</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Registered At</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
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
                                <a href="<?php echo BASE_URL; ?>/patient/chart/<?php echo $p->id; ?>" class="btn btn-sm btn-outline-info" title="Dental Chart">
                                    <i class="fas fa-tooth"></i> Chart
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

<script>
$(document).ready(function() {
    $('#patientTable').DataTable({
        "pageLength": 10,
        "language": { "search": "", "searchPlaceholder": "Search by name, ID or phone..." }
    });
});
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
