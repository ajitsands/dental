<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<?php
// Get current week dates
$today = isset($_GET['date']) ? strtotime($_GET['date']) : time();
$startOfWeek = strtotime('monday this week', $today);
$weekDates = [];
for ($i = 0; $i < 6; $i++) {
    $weekDates[] = date('D d', strtotime("+$i days", $startOfWeek));
}
$weekRangeText = date('M d', $startOfWeek) . ' - ' . date('M d', strtotime('+5 days', $startOfWeek)) . ', ' . date('Y', $startOfWeek);
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Appointments & Scheduling</h2>
        <p class="text-muted">Manage your clinic chairs and doctor schedules for <strong><?php echo $_SESSION['branch_name'] ?? 'Main Clinic'; ?></strong></p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal"><i class="fas fa-calendar-plus me-1"></i> Book Appointment</button>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="btn-group" id="view-toggle">
            <button class="btn btn-outline-secondary btn-sm">Day</button>
            <button class="btn btn-primary btn-sm">Week</button>
            <button class="btn btn-outline-secondary btn-sm">Month</button>
        </div>
        <h5 class="mb-0" id="current-date-range"><?php echo $weekRangeText; ?></h5>
        <div class="btn-group">
            <a href="?date=<?php echo date('Y-m-d', strtotime('-1 week', $startOfWeek)); ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chevron-left"></i></a>
            <a href="?date=<?php echo date('Y-m-d'); ?>" class="btn btn-outline-secondary btn-sm">Today</a>
            <a href="?date=<?php echo date('Y-m-d', strtotime('+1 week', $startOfWeek)); ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center" style="min-width: 800px;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Time</th>
                        <?php foreach($weekDates as $day) echo "<th>$day</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $times = ['09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '02:00 PM', '03:00 PM', '04:00 PM'];
                    foreach($times as $time):
                    ?>
                    <tr>
                        <td class="table-light fw-bold small"><?php echo $time; ?></td>
                        <?php foreach($weekDates as $day): ?>
                        <td style="min-height: 80px; vertical-align: top; padding: 5px;">
                            <?php 
                            foreach($data['appointments'] as $app): 
                                $appTime = date('h:i A', strtotime($app->start_time));
                                $appDay = date('D d', strtotime($app->start_time));
                                if($appTime == $time && $appDay == $day):
                            ?>
                            <div class="badge bg-primary w-100 p-2 text-start mb-1 shadow-sm" style="white-space: normal;">
                                <div class="fw-bold"><?php echo $app->patient_name; ?></div>
                                <div class="small opacity-75"><?php echo $app->notes; ?></div>
                                <div class="mt-1" style="font-size: 9px;">
                                    <i class="fas fa-chair me-1"></i> Chair <?php echo $app->chair_id; ?>
                                </div>
                            </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Book New Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="appointmentForm">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Select Patient</label>
                            <select name="patient_id" class="form-select select2-patient" required>
                                <option value="">-- Search Patient by Name or Mobile --</option>
                                <?php 
                                $patients = $data['patients'];
                                usort($patients, function($a, $b) {
                                    return strcmp($a->name, $b->name);
                                });
                                foreach($patients as $patient): 
                                ?>
                                <option value="<?php echo $patient->id; ?>">
                                    <?php echo $patient->name; ?> (<?php echo $patient->contact; ?>) - ID: <?php echo $patient->unique_id; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time Slot</label>
                            <select name="time" class="form-select" required>
                                <?php foreach($times as $t): ?>
                                <option><?php echo $t; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Select Chair</label>
                            <select name="chair_id" class="form-select">
                                <option value="1">Chair 1</option>
                                <option value="2">Chair 2</option>
                                <option value="3">Chair 3</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Select Dentist</label>
                            <select name="user_id" class="form-select">
                                <option value="<?php echo $_SESSION['user_id']; ?>"><?php echo $_SESSION['user_name']; ?> (You)</option>
                                <option value="2">Dr. Sarah Smith</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Treatment/Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="e.g., Root Canal"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn" onclick="confirmBooking()">Confirm Appointment</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2-patient').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#bookingModal'),
        placeholder: '-- Search Patient by Name or Mobile --',
        allowClear: true,
        width: '100%'
    });
});

function confirmBooking() {
    const formData = $('#appointmentForm').serialize();
    const btn = $('#saveBtn');
    
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>/appointment/save',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            if(response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Appointment Booked',
                    text: response.message,
                    confirmButtonColor: '#0d6efd'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Booking Failed',
                    text: response.message,
                    confirmButtonColor: '#0d6efd'
                });
                btn.prop('disabled', false).text('Confirm Appointment');
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Something went wrong on the server.'
            });
            btn.prop('disabled', false).text('Confirm Appointment');
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
