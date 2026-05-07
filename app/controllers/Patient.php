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

    public function ledger($id = null) {
        if ($id) {
            // Individual Patient Ledger
            $patient = $this->patientModel->getPatientById($id);
            if (!$patient) {
                header('Location: ' . BASE_URL . '/patient/ledger');
                exit;
            }
            $ledger = $this->patientModel->getPatientLedger($id);
            $data = [
                'title' => 'Statement of Account - ' . $patient->name,
                'patient' => $patient,
                'ledger' => $ledger
            ];
            $this->view('patients/ledger_single', $data);
        } else {
            // List all patient balances
            $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
            $branch_id = $isSuperAdmin ? null : ($_SESSION['branch_id'] ?? 1);
            $balances = $this->patientModel->getAllPatientBalances($branch_id);
            
            $data = [
                'title' => 'Customer Ledgers',
                'balances' => $balances,
                'isSuperAdmin' => $isSuperAdmin
            ];
            $this->view('patients/ledger_list', $data);
        }
    }

    public function details($id) {
        $patient = $this->patientModel->getPatientById($id);
        if (!$patient) {
            header('Location: ' . BASE_URL . '/patient');
            exit;
        }

        $history = $this->patientModel->getPatientHistory($id);
        
        $data = [
            'title' => 'Patient Profile - ' . $patient->name,
            'patient' => $patient,
            'appointments' => $history['appointments'],
            'invoices' => $history['invoices']
        ];
        $this->view('patients/view', $data);
    }

    public function chart($id = null) {
        if (!$id) {
            header('Location: ' . BASE_URL . '/patient');
            exit;
        }

        // Auto-create table if missing (Developer convenience)
        $db = new Database();
        $db->query("CREATE TABLE IF NOT EXISTS dental_charts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            patient_id INT NOT NULL,
            tooth_number INT NOT NULL,
            condition_name VARCHAR(50) NOT NULL,
            notes TEXT,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY (patient_id, tooth_number)
        )");
        $db->execute();

        // Ensure 'surfaces' column exists for existing tables
        $db->query("SELECT COUNT(*) as count FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'dental_charts' AND COLUMN_NAME = 'surfaces'");
        if ($db->single()->count == 0) {
            $db->query("ALTER TABLE dental_charts ADD COLUMN surfaces VARCHAR(100) DEFAULT ''");
            $db->execute();
        }

        $patient = $this->patientModel->getPatientById($id);
        $chartData = $this->patientModel->getDentalChart($id);
        
        $data = [
            'title' => 'Dental Chart - ' . ($patient->name ?? 'Unknown'),
            'patient' => $patient,
            'chartData' => $chartData
        ];
        $this->view('patients/chart', $data);
    }

    public function saveChart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $patientId = $_POST['patient_id'];
            $toothNumber = $_POST['tooth_number'];
            $condition = $_POST['condition'];
            $notes = $_POST['notes'] ?? '';
            $surfaces = $_POST['surfaces'] ?? '';

            if ($this->patientModel->saveDentalChart($patientId, $toothNumber, $condition, $notes, $surfaces)) {
                echo json_encode(['status' => 'success', 'message' => 'Chart updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save chart']);
            }
            exit;
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $data = [
                'name' => trim($_POST['name']),
                'age' => trim($_POST['age']),
                'gender' => trim($_POST['gender']),
                'contact' => trim($_POST['contact']),
                'email' => trim($_POST['email']),
                'medical_history' => trim($_POST['medical_history']),
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
    public function getPrescriptions($id) {
        $appointmentModel = $this->model('AppointmentModel');
        $prescriptions = $appointmentModel->getPrescriptionsByPatient($id);
        
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $prescriptions]);
        exit;
    }
}
