<?php require_once APPROOT . '/app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Patient Registration</h4>
                <a href="<?php echo BASE_URL; ?>/patient" class="btn btn-outline-secondary btn-sm">Back to List</a>
            </div>
            <div class="card-body p-4">
                <form id="patientForm" enctype="multipart/form-data">
                    <div class="row g-4">
                        <!-- Basic Info -->
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Enter patient name">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Age</label>
                                    <input type="number" name="age" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="contact" class="form-control" required placeholder="+91 / +973 ...">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Optional">
                                </div>
                            </div>
                        </div>

                        <!-- Photo Upload -->
                        <div class="col-md-4 text-center">
                            <label class="form-label d-block">Patient Photo</label>
                            <div class="border rounded p-3 mb-2" style="height: 150px; display: flex; align-items: center; justify-content: center; background: #f8fafc;">
                                <i class="fas fa-user-circle fa-5x text-muted"></i>
                            </div>
                            <input type="file" name="photo" class="form-control form-control-sm">
                        </div>

                        <hr>

                        <!-- Medical History -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-primary"><i class="fas fa-file-medical me-2"></i> Medical History</h5>
                            <div class="mb-3">
                                <label class="form-label">Conditions (Diabetes, Allergies, BP, etc.)</label>
                                <textarea name="medical_history" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Current Medications</label>
                                <textarea name="medications" class="form-control" rows="2"></textarea>
                            </div>
                        </div>

                        <!-- Dental History -->
                        <div class="col-md-6">
                            <h5 class="mb-3 text-info"><i class="fas fa-tooth me-2"></i> Dental History</h5>
                            <div class="mb-3">
                                <label class="form-label">Previous Procedures / Major Issues</label>
                                <textarea name="dental_history" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="medical_alert" id="medicalAlert">
                                <label class="form-check-label text-danger fw-bold" for="medicalAlert">Set as Critical Medical Alert</label>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <button type="reset" class="btn btn-light me-2">Clear</button>
                            <button type="submit" class="btn btn-primary px-5" id="saveBtn">Save Patient Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#patientForm').on('submit', function(e) {
        e.preventDefault();
        
        const btn = $('#saveBtn');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

        $.ajax({
            url: '<?php echo BASE_URL; ?>/patient/register',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        confirmButtonColor: '#0d6efd'
                    }).then(() => {
                        window.location.href = '<?php echo BASE_URL; ?>/patient';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                        confirmButtonColor: '#0d6efd'
                    });
                    btn.prop('disabled', false).text('Save Patient Profile');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Could not connect to the server. Please try again.',
                    confirmButtonColor: '#0d6efd'
                });
                btn.prop('disabled', false).text('Save Patient Profile');
            }
        });
    });
});
</script>

<?php require_once APPROOT . '/app/views/layouts/footer.php'; ?>
