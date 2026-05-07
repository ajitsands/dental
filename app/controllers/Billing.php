<?php
// app/controllers/Billing.php

class Billing extends Controller {
    private $billingModel;
    private $patientModel;
    private $userModel;
    private $serviceModel;
    private $walletModel;
    private $inventoryModel;
    private $branchModel;

    public function __construct() {
        $this->checkAuth();
        $this->billingModel = $this->model('BillingModel');
        $this->patientModel = $this->model('PatientModel');
        $this->userModel = $this->model('User');
        $this->serviceModel = $this->model('ServiceModel');
        $this->walletModel = $this->model('WalletModel');
        $this->inventoryModel = $this->model('InventoryModel');
        $this->branchModel = $this->model('BranchModel');
    }

    public function index() {
        $invoices = $this->billingModel->getInvoicesByBranch($_SESSION['branch_id'] ?? 1);
        
        $data = [
            'title' => 'Billing & Invoices - DenSmart',
            'patients' => $this->patientModel->getPatients(),
            'doctors' => $this->getStaffByRole('Dentist'),
            'technicians' => $this->getStaffByRole('Technician'),
            'nurses' => $this->getStaffByRole('Nurse'),
            'services' => $this->serviceModel->getAllServices(),
            'invoices' => $invoices
        ];
        $this->view('billing/index', $data);
    }

    private function getStaffByRole($roleName) {
        $db = new Database();
        $db->query('SELECT u.* FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name = :role AND u.branch_id = :branch');
        $db->bind(':role', $roleName);
        $db->bind(':branch', $_SESSION['branch_id'] ?? 1);
        return $db->resultSet();
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $invoiceData = [
                'patient_id' => $_POST['patient_id'],
                'doctor_id' => $_POST['doctor_id'],
                'technician_id' => $_POST['technician_id'],
                'nurse_id' => $_POST['nurse_id'],
                'total_amount' => $_POST['total_amount'],
                'tax_amount' => $_POST['tax_amount'],
                'final_amount' => $_POST['final_amount']
            ];

            $invoiceId = $this->billingModel->createInvoice($invoiceData);

            if ($invoiceId) {
                // Link services
                if (isset($_POST['services']) && is_array($_POST['services'])) {
                    $db = new Database();
                    foreach($_POST['services'] as $service) {
                        $db->query('INSERT INTO invoice_items (invoice_id, service_id, unit_price, total_price) VALUES (:inv, :svc, :price, :total)');
                        $db->bind(':inv', $invoiceId);
                        $db->bind(':svc', $service['id']);
                        $db->bind(':price', $service['price']);
                        $db->bind(':total', $service['price']);
                        $db->execute();

                        // Automated Stock Deduction
                        $this->inventoryModel->deductStockForService($service['id']);
                    }
                }
                echo json_encode(['status' => 'success', 'message' => 'Invoice created', 'id' => $invoiceId]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create invoice']);
            }
            exit;
        }
    }

    public function recordPayment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $invoiceId = $_POST['invoice_id'];
            $amount = $_POST['amount'];
            $mode = $_POST['mode'];

            $paymentId = $this->billingModel->recordPayment($invoiceId, $amount, $mode);

            if ($paymentId) {
                $this->distributeCommission($invoiceId, $amount, $paymentId);
                echo json_encode(['status' => 'success', 'message' => 'Payment recorded and commissions added to wallets.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to record payment']);
            }
            exit;
        }
    }

    private function distributeCommission($invoiceId, $paidAmount, $paymentId) {
        $invoice = $this->billingModel->getInvoiceWithStaff($invoiceId);
        if (!$invoice) return;

        $db = new Database();
        $db->query('SELECT s.* FROM invoice_items ii JOIN services s ON ii.service_id = s.id WHERE ii.invoice_id = :id');
        $db->bind(':id', $invoiceId);
        $services = $db->resultSet();

        $totalDocComm = 0;
        $totalTechComm = 0;
        $totalNurseComm = 0;

        foreach($services as $svc) {
            $totalDocComm += ($svc->cost * $svc->doc_comm_pct / 100);
            $totalTechComm += ($svc->cost * $svc->tech_comm_pct / 100);
            $totalNurseComm += ($svc->cost * $svc->nurse_comm_pct / 100);
        }

        $ratio = $paidAmount / $invoice->final_amount;
        
        // 1. Doctor Credit
        if ($invoice->doctor_id && $totalDocComm > 0) {
            $this->walletModel->creditWallet($invoice->doctor_id, $totalDocComm * $ratio, "Commission from Invoice #{$invoice->invoice_number}", $paymentId);
        }

        // 2. Technician Credit
        if ($invoice->technician_id && $totalTechComm > 0) {
            $this->walletModel->creditWallet($invoice->technician_id, $totalTechComm * $ratio, "Technician Commission from Invoice #{$invoice->invoice_number}", $paymentId);
        }

        // 3. Nurse Credit
        if ($invoice->nurse_id && $totalNurseComm > 0) {
            $this->walletModel->creditWallet($invoice->nurse_id, $totalNurseComm * $ratio, "Nurse Commission from Invoice #{$invoice->invoice_number}", $paymentId);
        }
    }

    public function printReceipt($id) {
        $invoice = $this->billingModel->getInvoiceById($id);
        if (!$invoice) die('Invoice not found');
        
        $db = new Database();
        $db->query('SELECT ii.*, s.name as service_name FROM invoice_items ii JOIN services s ON ii.service_id = s.id WHERE ii.invoice_id = :id');
        $db->bind(':id', $id);
        $items = $db->resultSet();

        $data = [
            'title' => 'Print Receipt',
            'invoice' => $invoice,
            'items' => $items,
            'branch' => $this->branchModel->getBranchById($invoice->branch_id)
        ];
        $this->view('billing/print_receipt', $data);
    }
}
