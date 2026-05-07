<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-dark">Welcome, Super Admin</h1>
        <p class="text-muted fs-5">Select a clinic branch to begin operations or view global analytics.</p>
    </div>

    <!-- Global Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center h-100" style="border-radius: 20px; background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); color: white;">
                <div class="small fw-bold opacity-75 mb-2 uppercase">Global Revenue (By Region)</div>
                <div class="h6 fw-bold mb-0">
                    <?php 
                    foreach($data['revenuePerCountry'] as $country => $rev) {
                        echo '<div class="mb-1">' . formatCurrency($rev, $country) . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center h-100" style="border-radius: 20px; background: #1e293b; color: white;">
                <div class="small fw-bold opacity-75 mb-2 uppercase">Total Payable (By Region)</div>
                <div class="h6 fw-bold mb-0">
                    <?php foreach($data['payablePerCountry'] as $country => $amt): ?>
                        <div class="mb-1 text-warning"><?php echo formatCurrency($amt, $country); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center h-100" style="border-radius: 20px; border: 2px dashed #cbd5e1; background: #f8fafc;">
                <div class="small fw-bold text-muted mb-2 uppercase">Pending Receivable (By Region)</div>
                <div class="h6 fw-bold mb-0">
                    <?php foreach($data['receivablePerCountry'] as $country => $amt): ?>
                        <div class="mb-1 text-danger"><?php echo formatCurrency($amt, $country); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-4 text-center">Available Clinic Branches</h4>
    
    <div class="row g-4 justify-content-center">
        <?php foreach($data['branchStats'] as $bs): ?>
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm h-100 text-center p-3 branch-card" style="border-radius: 20px; transition: transform 0.3s ease;">
                <div class="densmart-logo mx-auto mb-3" style="width: 60px; height: 60px; background: #eff6ff; color: #3b82f6; font-size: 1.5rem;">
                    <i class="fas fa-clinic-medical"></i>
                </div>
                <h5 class="fw-bold mb-1"><?php echo $bs->name; ?></h5>
                <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt me-1"></i> <?php echo $bs->location; ?></p>
                
                <div class="row g-2 mb-3 px-2">
                    <div class="col-6 text-start border-end">
                        <div class="text-muted x-small">Revenue</div>
                        <div class="fw-bold small text-success"><?php echo formatCurrency($bs->revenue, $bs->location); ?></div>
                    </div>
                    <div class="col-6 text-end">
                        <div class="text-muted x-small">Pending</div>
                        <div class="fw-bold small text-danger"><?php echo formatCurrency($bs->receivable, $bs->location); ?></div>
                    </div>
                    <div class="col-12 mt-2 pt-2 border-top">
                        <div class="text-muted x-small">Payable (Staff)</div>
                        <div class="fw-bold small text-warning"><?php echo formatCurrency($bs->payable, $bs->location); ?></div>
                    </div>
                </div>

                <a href="<?php echo BASE_URL; ?>/dashboard/switchBranch/<?php echo $bs->id; ?>" class="btn btn-primary w-100 rounded-pill shadow-sm py-2 fw-bold">
                    Launch Branch
                </a>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Option to add new branch -->
        <div class="col-md-4 col-lg-3">
            <a href="<?php echo BASE_URL; ?>/settings" class="text-decoration-none h-100">
                <div class="card border-0 shadow-sm h-100 text-center p-3 d-flex flex-column justify-content-center align-items-center" style="border-radius: 20px; border: 2px dashed #cbd5e1; background: #f8fafc; color: #64748b;">
                    <div class="mb-2"><i class="fas fa-plus-circle fa-2x"></i></div>
                    <div class="fw-bold">Add New Branch</div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
    .branch-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .x-small { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .btn-xs { padding: 0.2rem 0.5rem; font-size: 0.75rem; }
</style>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
