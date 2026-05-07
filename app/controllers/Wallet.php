<?php
// app/controllers/Wallet.php

class Wallet extends Controller {
    private $walletModel;
    private $userModel;

    public function __construct() {
        $this->checkAuth();
        $this->walletModel = $this->model('WalletModel');
        $this->userModel = $this->model('User');
    }

    public function index() {
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        $branch_id = $isSuperAdmin ? null : ($_SESSION['branch_id'] ?? 1);

        $staff = $this->walletModel->getStaffBalances($branch_id);

        $data = [
            'title' => 'Staff Wallets & Payouts',
            'staff' => $staff,
            'isSuperAdmin' => $isSuperAdmin
        ];
        $this->view('wallet/index', $data);
    }

    public function ledger($userId) {
        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            header('Location: ' . BASE_URL . '/wallet');
            exit;
        }

        $ledger = $this->walletModel->getLedger($userId);

        $data = [
            'title' => 'Staff Ledger - ' . $user->name,
            'user' => $user,
            'ledger' => $ledger
        ];
        $this->view('wallet/ledger', $data);
    }

    public function payout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');

            $data = [
                'user_id' => $_POST['user_id'],
                'amount' => floatval($_POST['amount']),
                'description' => trim($_POST['description'])
            ];

            if ($data['amount'] <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid payout amount']);
                exit;
            }

            if ($this->walletModel->addPayout($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Payout recorded successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to process payout']);
            }
            exit;
        }
    }
}
