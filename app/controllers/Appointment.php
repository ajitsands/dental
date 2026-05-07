<?php
// app/controllers/Appointment.php

class Appointment extends Controller {
    private $appointmentModel;
    private $patientModel;

    public function __construct() {
        $this->checkAuth();
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
            header('Content-Type: application/json');
            
            // Sanitize and process
            $data = [
                'patient_id' => $_POST['patient_id'] ?? null,
                'user_id' => $_SESSION['user_id'] ?? 1,
                'chair_id' => $_POST['chair_id'] ?? 1,
                'start_time' => ($_POST['date'] ?? '') . ' ' . date('H:i:s', strtotime($_POST['time'] ?? '09:00 AM')),
                'end_time' => ($_POST['date'] ?? '') . ' ' . date('H:i:s', strtotime(($_POST['time'] ?? '09:00 AM') . ' +1 hour')),
                'notes' => trim($_POST['notes'] ?? '')
            ];

            if (!$data['patient_id']) {
                echo json_encode(['status' => 'error', 'message' => 'Please select a patient']);
                exit;
            }

            if ($this->appointmentModel->addAppointment($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Appointment booked successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error occurred while saving']);
            }
            exit;
        }
    }
    public function cancel($id) {
        header('Content-Type: application/json');
        if ($this->appointmentModel->cancelAppointment($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Appointment cancelled successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to cancel appointment']);
        }
        exit;
    }

    public function extend($id) {
        header('Content-Type: application/json');
        if ($this->appointmentModel->extendAppointment($id, 30)) {
            echo json_encode(['status' => 'success', 'message' => 'Appointment extended by 30 minutes']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to extend appointment']);
        }
        exit;
    }
}
