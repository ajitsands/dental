<?php
// app/controllers/Dashboard.php

class Dashboard extends Controller {
    private $billingModel;
    private $branchModel;
    private $userModel;
    private $patientModel;
    private $appointmentModel;
    private $db;

    public function __construct() {
        $this->checkAuth();
        $this->db = new Database();
        $this->billingModel = $this->model('BillingModel');
        $this->branchModel = $this->model('BranchModel');
        $this->userModel = $this->model('User');
        $this->patientModel = $this->model('PatientModel');
        $this->appointmentModel = $this->model('AppointmentModel');
    }

    public function index() {
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        
        // If Super Admin has "locked" into a branch, show that branch's dashboard instead
        if ($isSuperAdmin && !isset($_GET['global'])) {
            if (isset($_SESSION['impersonating_branch'])) {
                return $this->branchDashboard();
            }
            return $this->superAdminDashboard();
        }

        return $this->branchDashboard();
    }

    private function superAdminDashboard() {
        $branches = $this->branchModel->getAllBranches();
        $branchStats = [];
        $revenuePerCountry = [];
        $receivablePerCountry = [];
        $payablePerCountry = [];

        foreach ($branches as $branch) {
            // 1. Revenue & Receivables (Invoices)
            $invoices = $this->billingModel->getInvoicesByBranch($branch->id);
            $branchRevenue = 0;
            $branchReceivable = 0;
            foreach ($invoices as $inv) {
                if ($inv->status == 'Paid') {
                    $branchRevenue += $inv->final_amount;
                } else {
                    $branchReceivable += ($inv->final_amount - ($inv->paid_amount ?? 0));
                }
            }

            // 2. Payables (Staff Wallets)
            $this->db->query("SELECT SUM(CASE WHEN type = 'Credit' THEN amount ELSE -amount END) as total 
                              FROM wallet_transactions wt 
                              JOIN users u ON wt.user_id = u.id 
                              WHERE u.branch_id = :branch_id");
            $this->db->bind(':branch_id', $branch->id);
            $branchPayable = $this->db->single()->total ?? 0;

            $country = $branch->country ?: 'India';
            
            if (!isset($revenuePerCountry[$country])) $revenuePerCountry[$country] = 0;
            if (!isset($receivablePerCountry[$country])) $receivablePerCountry[$country] = 0;
            if (!isset($payablePerCountry[$country])) $payablePerCountry[$country] = 0;

            $revenuePerCountry[$country] += $branchRevenue;
            $receivablePerCountry[$country] += $branchReceivable;
            $payablePerCountry[$country] += $branchPayable;

            $branchStats[] = (object)[
                'id' => $branch->id,
                'name' => $branch->name,
                'revenue' => $branchRevenue,
                'receivable' => $branchReceivable,
                'payable' => $branchPayable,
                'patient_count' => count($this->userModel->getStaffByBranch($branch->id)),
                'location' => $country
            ];
        }

        $data = [
            'title' => 'Global Dashboard - DenSmart',
            'branchStats' => $branchStats,
            'revenuePerCountry' => $revenuePerCountry,
            'receivablePerCountry' => $receivablePerCountry,
            'payablePerCountry' => $payablePerCountry
        ];
        $this->view('dashboard/super_admin', $data);
    }

    private function branchDashboard() {
        $branchId = $_SESSION['branch_id'] ?? 1;
        $today = date('Y-m-d');

        // 1. Today's Appointments
        $this->db->query("SELECT a.*, p.name as patient_name, p.unique_id as patient_uid 
                          FROM appointments a 
                          JOIN patients p ON a.patient_id = p.id 
                          WHERE a.branch_id = :branch_id 
                          AND DATE(a.start_time) = :today
                          ORDER BY a.start_time ASC");
        $this->db->bind(':branch_id', $branchId);
        $this->db->bind(':today', $today);
        $todayAppointments = $this->db->resultSet();

        // 2. New Patients Today
        $this->db->query("SELECT COUNT(*) as count FROM patients WHERE branch_id = :branch_id AND DATE(created_at) = :today");
        $this->db->bind(':branch_id', $branchId);
        $this->db->bind(':today', $today);
        $newPatients = $this->db->single()->count;

        // 3. Branch Revenue (Total Paid)
        $invoices = $this->billingModel->getInvoicesByBranch($branchId);
        $totalRevenue = 0;
        foreach ($invoices as $inv) {
            if ($inv->status == 'Paid') $totalRevenue += $inv->final_amount;
        }

        $data = [
            'title' => 'Dashboard - DenSmart',
            'branch_name' => $_SESSION['branch_name'] ?? 'Unknown Branch',
            'todayAppointments' => $todayAppointments,
            'newPatients' => $newPatients,
            'totalRevenue' => $totalRevenue,
            'recentAppointments' => array_slice($todayAppointments, 0, 5), // Just show first 5 for now
            'agingDebt' => $this->patientModel->getAgingDebtPatients(30)
        ];
        $this->view('dashboard/index', $data);
    }

    public function switchBranch($id) {
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        if (!$isSuperAdmin) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        if ($id == 'global') {
            unset($_SESSION['impersonating_branch']);
            // Reset to original (if any) or default
            $_SESSION['branch_id'] = 1; 
            $_SESSION['branch_name'] = 'DenSmart Central';
            $_SESSION['branch_country'] = 'India';
            $_SESSION['tax_pct'] = 18.00;
        } else {
            $branch = $this->branchModel->getBranchById($id);
            if ($branch) {
                $_SESSION['branch_id'] = $branch->id;
                $_SESSION['branch_name'] = $branch->name;
                $_SESSION['branch_country'] = $branch->country;
                $_SESSION['tax_pct'] = $branch->tax_pct;
                $_SESSION['impersonating_branch'] = true;
            }
        }

        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            $id = $_POST['id'];
            $status = $_POST['status'];
            if ($this->appointmentModel->updateStatus($id, $status)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            exit;
        }
    }

    public function savePrescription() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            $data = [
                'appointment_id' => $_POST['appointment_id'],
                'patient_id' => $_POST['patient_id'],
                'doctor_id' => $_SESSION['user_id'],
                'medicines' => $_POST['medicines'],
                'instructions' => $_POST['instructions']
            ];
            
            if ($this->appointmentModel->addPrescription($data)) {
                $this->appointmentModel->updateStatus($data['appointment_id'], 'Completed');
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            exit;
        }
    }

    public function printPrescription($id) {
        $prescription = $this->appointmentModel->getPrescription($id);
        $this->db->query("SELECT a.*, p.name as patient_name, p.age, p.gender, u.name as doctor_name, b.name as branch_name, b.address, b.contact, b.logo
                          FROM appointments a
                          JOIN patients p ON a.patient_id = p.id
                          JOIN users u ON a.user_id = u.id
                          JOIN branches b ON a.branch_id = b.id
                          WHERE a.id = :id");
        $this->db->bind(':id', $id);
        $details = $this->db->single();

        $data = [
            'title' => 'Print Prescription',
            'prescription' => $prescription,
            'details' => $details
        ];
        $this->view('dashboard/print_prescription', $data);
    }
}
