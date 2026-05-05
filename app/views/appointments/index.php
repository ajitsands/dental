<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2 class="mb-1">Appointments & Scheduling</h2>
        <p class="text-muted">Manage your clinic chairs and doctor schedules.</p>
    </div>
    <div class="col-md-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal"><i class="fas fa-calendar-plus me-1"></i> Book Appointment</button>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div class="btn-group" id="view-toggle">
            <button class="btn btn-outline-secondary btn-sm" onclick="changeView('Day', this)">Day</button>
            <button class="btn btn-primary btn-sm" onclick="changeView('Week', this)">Week</button>
            <button class="btn btn-outline-secondary btn-sm" onclick="changeView('Month', this)">Month</button>
        </div>
        <h5 class="mb-0" id="current-date-range">May 5 - May 11, 2026</h5>
        <div class="btn-group">
            <button class="btn btn-outline-secondary btn-sm" onclick="navigateDate('prev')"><i class="fas fa-chevron-left"></i></button>
            <button class="btn btn-outline-secondary btn-sm" onclick="navigateDate('today')">Today</button>
            <button class="btn btn-outline-secondary btn-sm" onclick="navigateDate('next')"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 text-center" style="min-width: 800px;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Time</th>
                        <?php 
                        $days = ['Mon 05', 'Tue 06', 'Wed 07', 'Thu 08', 'Fri 09', 'Sat 10'];
                        foreach($days as $day) echo "<th>$day</th>";
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $times = ['09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '02:00 PM', '03:00 PM', '04:00 PM'];
                    foreach($times as $time):
                    ?>
                    <tr>
                        <td class="table-light fw-bold small"><?php echo $time; ?></td>
                        <?php foreach($days as $day): ?>
                        <td>
                            <?php 
                            // Check if there's an appointment for this time and day (mocking day 06 for now)
                            foreach($data['appointments'] as $app): 
                                $appTime = date('h:i A', strtotime($app->start_time));
                                $appDay = date('D d', strtotime($app->start_time));
                                if($appTime == $time && $appDay == $day):
                            ?>
                            <div class="badge bg-primary w-100 p-2 text-start mb-1">
                                <div class="fw-bold"><?php echo $app->patient_name; ?></div>
                                <div class="small"><?php echo $app->notes; ?> - Chair <?php echo $app->chair_id; ?></div>
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
                            <select name="patient_id" class="form-select" required>
                                <option value="">-- Choose Patient --</option>
                                <?php foreach($data['patients'] as $patient): ?>
                                <option value="<?php echo $patient->id; ?>"><?php echo $patient->name; ?> (<?php echo $patient->unique_id; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="2026-05-06" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Time Slot</label>
                            <select name="time" class="form-select" required>
                                <option>09:00 AM</option>
                                <option>10:00 AM</option>
                                <option>11:00 AM</option>
                                <option>12:00 PM</option>
                                <option>02:00 PM</option>
                                <option>03:00 PM</option>
                                <option>04:00 PM</option>
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
                                <option value="1">Dr. John Doe</option>
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
function changeView(view, btn) {
    $('#view-toggle button').removeClass('btn-primary').addClass('btn-outline-secondary');
    $(btn).removeClass('btn-outline-secondary').addClass('btn-primary');
}

function navigateDate(dir) {
    if(dir === 'prev') $('#current-date-range').text('April 28 - May 4, 2026');
    if(dir === 'next') $('#current-date-range').text('May 12 - May 18, 2026');
    if(dir === 'today') $('#current-date-range').text('May 5 - May 11, 2026');
}

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
                    text: 'The appointment has been successfully saved to the database.',
                    confirmButtonColor: '#0d6efd'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Booking Failed',
                    text: 'Something went wrong while saving the appointment.',
                    confirmButtonColor: '#0d6efd'
                });
                btn.prop('disabled', false).text('Confirm Appointment');
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: 'Could not connect to the server.',
                confirmButtonColor: '#0d6efd'
            });
            btn.prop('disabled', false).text('Confirm Appointment');
        }
    });
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>
