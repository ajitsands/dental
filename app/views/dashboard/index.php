<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Welcome back, Dr. John!</h2>
        <p class="text-muted">Here's what's happening at your clinic today.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?php echo BASE_URL; ?>/appointment" class="btn btn-primary"><i class="fas fa-plus me-1"></i> New Appointment</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Stats Cards -->
    <div class="col-md-3">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-primary bg-opacity-10 text-primary me-3">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Appointments</h6>
                    <h3 class="mb-0">12</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-success bg-opacity-10 text-success me-3">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">New Patients</h6>
                    <h3 class="mb-0">5</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-warning bg-opacity-10 text-warning me-3">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Revenue</h6>
                    <h3 class="mb-0">₹45,000</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <div class="d-flex align-items-center">
                <div class="densmart-logo bg-danger bg-opacity-10 text-danger me-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted">Low Stock</h6>
                    <h3 class="mb-0">3</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Appointments</h5>
                <a href="<?php echo BASE_URL; ?>/appointment" class="btn btn-sm btn-link">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient</th>
                                <th>Time</th>
                                <th>Treatment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Amit+Shah&background=random" alt="" class="rounded-circle me-2" width="30">
                                        Amit Shah
                                    </div>
                                </td>
                                <td>10:30 AM</td>
                                <td>Root Canal</td>
                                <td><span class="badge bg-primary">Confirmed</span></td>
                                <td><a href="<?php echo BASE_URL; ?>/patient/chart/1001" class="btn btn-sm btn-outline-primary">Chart</a></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=Sara+Khan&background=random" alt="" class="rounded-circle me-2" width="30">
                                        Sara Khan
                                    </div>
                                </td>
                                <td>11:15 AM</td>
                                <td>Scaling</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td><a href="<?php echo BASE_URL; ?>/patient/chart/1002" class="btn btn-sm btn-outline-primary">Chart</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Revenue Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'Revenue',
                data: [12000, 19000, 3000, 5000, 20000, 30000],
                borderColor: '#0d6efd',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(13, 110, 253, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
