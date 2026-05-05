<?php
// app/controllers/Appointment.php

class Appointment extends Controller {
    private $appointmentModel;
    private $patientModel;

    public function __construct() {
        $this->appointmentModel = $this->model('AppointmentModel');
        $this->patientModel = $this->model('PatientModel');
    }

    public function index() {
        $appointments = $this->appointmentModel->getAppointments();
        $patients = $this->patientModel->getPatients();
        $data = [
            'title' => 'Appointments - DenSmart',
            'appointments' => $appointments,
            'patients' => $patients
        ];
        $this->view('appointments/index', $data);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and process
            $data = [
                'patient_id' => $_POST['patient_id'],
                'user_id' => 1, // Mock user ID (Logged in user)
                'chair_id' => $_POST['chair_id'],
                'start_time' => $_POST['date'] . ' ' . date('H:i:s', strtotime($_POST['time'])),
                'end_time' => $_POST['date'] . ' ' . date('H:i:s', strtotime($_POST['time'] . ' +1 hour')),
                'notes' => $_POST['notes']
            ];

            if ($this->appointmentModel->addAppointment($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }
}
