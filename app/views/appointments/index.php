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
        <h2 class="mb-1"><?php echo __('appointments_scheduling'); ?></h2>
        <p class="text-muted"><?php echo __('manage_bookings'); ?> <strong><?php echo $_SESSION['branch_name'] ?? 'Main Clinic'; ?></strong></p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal"><i class="fas fa-calendar-plus me-1"></i> Book Appointment</button>
    </div>
</div>

<div class="card">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
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
                <thead>
                    <tr>
                        <th style="width: 100px;">Time</th>
                        <?php foreach($weekDates as $day) echo "<th>$day</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $times = [
                        '09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', 
                        '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM', 
                        '05:00 PM', '06:00 PM', '07:00 PM', '08:00 PM', '09:00 PM'
                    ];
                    foreach($times as $time):
                    ?>
                    <tr>
                        <td class="fw-bold small" style="background-color: var(--sidebar-active);"> <?php echo $time; ?></td>
                        <?php foreach($weekDates as $day): ?>
                        <td style="min-height: 80px; vertical-align: top; padding: 5px;">
                            <?php 
                            foreach($data['appointments'] as $app): 
                                $appTime = date('h:i A', strtotime($app->start_time));
                                $appDay = date('D d', strtotime($app->start_time));
                                if($appTime == $time && $appDay == $day):
                            ?>
                            <div class="appointment-card badge bg-primary w-100 p-2 text-start mb-1 shadow-sm" 
                                 onclick="manageAppointment(<?php echo $app->id; ?>, '<?php echo addslashes($app->patient_name); ?>', '<?php echo date('H:i A', strtotime($app->start_time)); ?>')"
                                 style="white-space: normal; cursor: pointer; transition: transform 0.2s;">
                                 <div class="fw-bold d-flex justify-content-between">
                                     <span><?php echo $app->patient_name; ?></span>
                                     <i class="fas fa-ellipsis-v small opacity-50"></i>
                                 </div>
                                 <div class="small opacity-75"><?php echo $app->notes; ?></div>
                                 <div class="mt-1 d-flex justify-content-between align-items-center" style="font-size: 9px;">
                                     <span><i class="fas fa-chair me-1"></i> Chair <?php echo $app->chair_id; ?></span>
                                     <span><?php echo date('H:i', strtotime($app->start_time)) . '-' . date('H:i', strtotime($app->end_time)); ?></span>
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

<!-- Manage Appointment Modal -->
<div class="modal fade" id="manageModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Manage Appointment</h5></div>
            <div class="modal-body text-center">
                <input type="hidden" id="manageAppId">
                <h6 id="managePatientName"></h6>
                <p class="text-muted" id="manageAppTime"></p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="extendAppointment()">Extend 30m</button>
                    <button class="btn btn-danger" onclick="cancelAppointment()">Cancel Appointment</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let manageModal;

$(document).ready(function() {
    $('.select2-patient').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#bookingModal'),
        placeholder: '-- Search Patient by Name or Mobile --',
        allowClear: true,
        width: '100%'
    });

    // Initialize manage modal after scripts load
    if (document.getElementById('manageModal')) {
        manageModal = new bootstrap.Modal(document.getElementById('manageModal'));
    }
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

function manageAppointment(id, name, time) {
    if(!manageModal) manageModal = new bootstrap.Modal(document.getElementById('manageModal'));
    
    $('#manageAppId').val(id);
    $('#managePatientName').text(name);
    $('#manageAppTime').text(time);
    manageModal.show();
}

function extendAppointment() {
    const id = $('#manageAppId').val();
    $.post('<?php echo BASE_URL; ?>/appointment/extend/' + id, function(response) {
        if(response.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Extended', text: response.message, timer: 1500 }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', response.message, 'error');
        }
    }, 'json');
}

function cancelAppointment() {
    const id = $('#manageAppId').val();
    Swal.fire({
        title: 'Cancel Appointment?',
        text: "This will remove the appointment from the schedule.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Cancel it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('<?php echo BASE_URL; ?>/appointment/cancel/' + id, function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Cancelled', text: response.message, timer: 1500 }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            }, 'json');
        }
    });
}
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
