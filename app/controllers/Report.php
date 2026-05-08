<?php
// app/controllers/Report.php

class Report extends Controller {
    private $patientModel;
    private $walletModel;

    public function __construct() {
        $this->checkAuth();
        $this->patientModel = $this->model('PatientModel');
        $this->walletModel = $this->model('WalletModel');
    }

    public function ledgerSummary() {
        // Fetch Patient Receivables
        $patients = $this->patientModel->getAllPatientBalances($_SESSION['branch_id'] ?? null);
        
        // Fetch Staff Payables
        $staff = $this->walletModel->getStaffBalances($_SESSION['branch_id'] ?? null);

        // Consolidate for a single DataTable
        $consolidated = [];

        foreach($patients as $p) {
            $balance = (float)$p->total_invoiced - (float)$p->total_paid;
            if($balance > 0) {
                $consolidated[] = [
                    'name' => $p->name,
                    'ref' => $p->unique_id,
                    'type' => 'Receivable',
                    'category' => 'Patient',
                    'amount' => $balance
                ];
            }
        }

        foreach($staff as $s) {
            if($s->wallet_balance != 0) {
                $consolidated[] = [
                    'name' => $s->name,
                    'ref' => $s->role_name,
                    'type' => $s->wallet_balance > 0 ? 'Payable' : 'Receivable (Advance)',
                    'category' => 'Staff',
                    'amount' => abs($s->wallet_balance)
                ];
            }
        }

        $data = [
            'title' => 'Financial Ledger Summary',
            'ledger' => $consolidated
        ];

        $this->view('reports/ledger_summary', $data);
    }
}
