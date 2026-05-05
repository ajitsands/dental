<?php
// app/controllers/Patient.php

class Patient extends Controller {
    private $patientModel;

    public function __construct() {
        $this->checkAuth();
        $this->patientModel = $this->model('PatientModel');
    }

    public function index() {
        $patients = $this->patientModel->getPatients();
        $data = [
            'title' => 'Patient Management',
            'patients' => $patients
        ];
        $this->view('patients/index', $data);
    }

    public function chart($id = null) {
        $data = [
            'title' => 'Dental Chart - DenSmart',
            'patient_id' => $id
        ];
        $this->view('patients/chart', $data);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            // Sanitize
            $medical_history = trim($_POST['medical_history']);
            $medications = trim($_POST['medications']);
            $full_history = $medical_history . ($medications ? "\nMedications: " . $medications : "");

            $data = [
                'name' => trim($_POST['name']),
                'age' => trim($_POST['age']),
                'gender' => trim($_POST['gender']),
                'contact' => trim($_POST['contact']),
                'email' => trim($_POST['email']),
                'medical_history' => $full_history,
                'dental_history' => trim($_POST['dental_history']),
                'medical_alerts' => isset($_POST['medical_alert']) ? 'CRITICAL' : ''
            ];

            if ($this->patientModel->addPatient($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Patient registered successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error occurred']);
            }
            exit;
        } else {
            $data = ['title' => 'Register Patient'];
            $this->view('patients/register', $data);
        }
    }
}
