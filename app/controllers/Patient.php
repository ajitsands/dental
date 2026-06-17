<?php
// app/controllers/Patient.php

class Patient extends Controller {
    private $patientModel;

    public function __construct() {
        $this->checkAuth();
        $this->patientModel = $this->model('PatientModel');
    }

    public function index() {
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        $branches = [];
        if ($isSuperAdmin) {
            $branchModel = $this->model('BranchModel');
            $branches = $branchModel->getAllBranches();
        }

        $patients = $this->patientModel->getPatients();
        $data = [
            'title' => 'Patient Management',
            'patients' => $patients,
            'isSuperAdmin' => $isSuperAdmin,
            'branches' => $branches
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

    public function downloadTemplate() {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=patients_import_template.csv');
        
        $output = fopen('php://output', 'w');
        // Add UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, [
            'Name',
            'Age',
            'Gender',
            'Contact',
            'Email',
            'Medical History',
            'Dental History',
            'Medical Alerts'
        ]);
        
        // Add a sample row
        fputcsv($output, [
            'John Doe',
            '35',
            'Male',
            '+919876543210',
            'johndoe@example.com',
            'Hypertension',
            'None',
            'No'
        ]);
        
        fclose($output);
        exit;
    }

    public function import() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
            $branch_id = $_SESSION['branch_id'] ?? 1;
            
            if ($isSuperAdmin) {
                if (empty($_POST['branch_id'])) {
                    echo json_encode(['status' => 'error', 'message' => 'Branch selection is required for Super Admin.']);
                    exit;
                }
                $branch_id = (int)$_POST['branch_id'];
            }
            
            if (!isset($_FILES['patient_file']) || $_FILES['patient_file']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to upload file.']);
                exit;
            }
            
            $fileTmpPath = $_FILES['patient_file']['tmp_name'];
            $fileName = $_FILES['patient_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            if ($fileExtension !== 'csv') {
                echo json_encode(['status' => 'error', 'message' => 'Please upload a valid CSV file.']);
                exit;
            }
            
            if (($handle = fopen($fileTmpPath, 'r')) !== FALSE) {
                // Skip BOM if present
                $bom = fread($handle, 3);
                if ($bom !== "\xEF\xBB\xBF") {
                    rewind($handle);
                }
                
                // Read headers
                $headers = fgetcsv($handle);
                if (!$headers) {
                    echo json_encode(['status' => 'error', 'message' => 'The uploaded file is empty.']);
                    exit;
                }
                
                // Normalize headers to map columns
                $headerMap = [];
                foreach ($headers as $index => $header) {
                    $cleaned = strtolower(trim($header));
                    $headerMap[$cleaned] = $index;
                }
                
                // Find index for each column (handling slight variation in names)
                $colIndex = [
                    'name' => null,
                    'age' => null,
                    'gender' => null,
                    'contact' => null,
                    'email' => null,
                    'medical_history' => null,
                    'dental_history' => null,
                    'medical_alerts' => null
                ];
                
                foreach ($headerMap as $header => $index) {
                    if (strpos($header, 'name') !== false) {
                        $colIndex['name'] = $index;
                    } elseif (strpos($header, 'age') !== false) {
                        $colIndex['age'] = $index;
                    } elseif (strpos($header, 'gender') !== false) {
                        $colIndex['gender'] = $index;
                    } elseif (strpos($header, 'contact') !== false || strpos($header, 'phone') !== false || strpos($header, 'mobile') !== false) {
                        $colIndex['contact'] = $index;
                    } elseif (strpos($header, 'email') !== false) {
                        $colIndex['email'] = $index;
                    } elseif (strpos($header, 'medical') !== false && strpos($header, 'history') !== false) {
                        $colIndex['medical_history'] = $index;
                    } elseif (strpos($header, 'dental') !== false && strpos($header, 'history') !== false) {
                        $colIndex['dental_history'] = $index;
                    } elseif (strpos($header, 'alert') !== false || strpos($header, 'critical') !== false) {
                        $colIndex['medical_alerts'] = $index;
                    }
                }
                
                // Fallback to absolute column indices if name not found by text mapping
                if ($colIndex['name'] === null) {
                    $colIndex = [
                        'name' => 0,
                        'age' => 1,
                        'gender' => 2,
                        'contact' => 3,
                        'email' => 4,
                        'medical_history' => 5,
                        'dental_history' => 6,
                        'medical_alerts' => 7
                    ];
                }
                
                $successCount = 0;
                $rowCount = 0;
                
                while (($row = fgetcsv($handle)) !== FALSE) {
                    // Skip empty rows
                    if (count($row) === 1 && empty($row[0])) {
                        continue;
                    }
                    
                    $rowCount++;
                    
                    // Fetch name - must be non-empty
                    $nameIdx = $colIndex['name'];
                    $name = isset($row[$nameIdx]) ? trim($row[$nameIdx]) : '';
                    if (empty($name)) {
                        continue; // Skip rows without a name
                    }
                    
                    // Age mapping
                    $ageIdx = $colIndex['age'];
                    $age = isset($row[$ageIdx]) && trim($row[$ageIdx]) !== '' ? (int)trim($row[$ageIdx]) : null;
                    if ($age !== null && $age <= 0) {
                        $age = null;
                    }
                    
                    // Gender mapping - normalize to Male, Female, Other
                    $genderIdx = $colIndex['gender'];
                    $genderRaw = isset($row[$genderIdx]) ? strtolower(trim($row[$genderIdx])) : '';
                    $gender = null;
                    if (in_array($genderRaw, ['male', 'm'])) {
                        $gender = 'Male';
                    } elseif (in_array($genderRaw, ['female', 'f'])) {
                        $gender = 'Female';
                    } elseif (!empty($genderRaw)) {
                        $gender = 'Other';
                    }
                    
                    // Contact
                    $contactIdx = $colIndex['contact'];
                    $contact = isset($row[$contactIdx]) ? trim($row[$contactIdx]) : '';
                    
                    // Email
                    $emailIdx = $colIndex['email'];
                    $email = isset($row[$emailIdx]) ? trim($row[$emailIdx]) : '';
                    
                    // Medical History
                    $medHistIdx = $colIndex['medical_history'];
                    $medicalHistory = isset($row[$medHistIdx]) ? trim($row[$medHistIdx]) : '';
                    
                    // Dental History
                    $dentHistIdx = $colIndex['dental_history'];
                    $dentalHistory = isset($row[$dentHistIdx]) ? trim($row[$dentHistIdx]) : '';
                    
                    // Medical Alerts
                    $alertsIdx = $colIndex['medical_alerts'];
                    $alertsRaw = isset($row[$alertsIdx]) ? strtolower(trim($row[$alertsIdx])) : '';
                    $medicalAlerts = '';
                    if (in_array($alertsRaw, ['yes', 'critical', 'alert', 'true', '1'])) {
                        $medicalAlerts = 'CRITICAL';
                    }
                    
                    $data = [
                        'branch_id' => $branch_id,
                        'name' => $name,
                        'age' => $age,
                        'gender' => $gender,
                        'contact' => $contact,
                        'email' => $email,
                        'medical_history' => $medicalHistory,
                        'dental_history' => $dentalHistory,
                        'medical_alerts' => $medicalAlerts
                    ];
                    
                    if ($this->patientModel->addPatient($data)) {
                        $successCount++;
                    }
                }
                
                fclose($handle);
                
                if ($successCount > 0) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => "Successfully imported $successCount out of $rowCount patients."
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'No patients were imported. Please check your template structure.'
                    ]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to read uploaded file.']);
            }
            exit;
        }
    }
}
