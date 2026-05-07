<?php
// app/controllers/Settings.php

class Settings extends Controller {
    private $branchModel;

    public function __construct() {
        $this->checkAuth();
        $this->branchModel = $this->model('BranchModel');
    }

    public function index() {
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        $currentBranchId = $_SESSION['branch_id'] ?? 1;

        if ($isSuperAdmin) {
            $branches = $this->branchModel->getAllBranches();
        } else {
            // Branch Admin only sees their own branch
            $branches = [$this->branchModel->getBranchById($currentBranchId)];
        }
        
        $currentBranch = $this->branchModel->getBranchById($currentBranchId);
        
        $data = [
            'title' => 'Settings - DenSmart',
            'branches' => $branches,
            'currentBranch' => $currentBranch,
            'isSuperAdmin' => $isSuperAdmin
        ];
        $this->view('settings/index', $data);
    }

    public function saveBranch() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
            $currentBranchId = $_SESSION['branch_id'] ?? 1;
            $targetId = $_POST['id'] ?? null;

            // Security Check: If not Super Admin, force update to current branch only
            if (!$isSuperAdmin) {
                $targetId = $currentBranchId;
            }

            $data = [
                'id' => $targetId,
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'contact' => trim($_POST['contact'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'country' => trim($_POST['country'] ?? 'India'),
                'tax_number' => trim($_POST['tax_number'] ?? ''),
                'tax_type' => $_POST['tax_type'] ?? 'GST',
                'timezone' => $_POST['timezone'] ?? 'Asia/Kolkata'
            ];

            if ($data['id']) {
                $result = $this->branchModel->updateBranch($data);
                $message = 'Settings updated successfully';
            } else {
                // Only Super Admin can add new branches
                if ($isSuperAdmin) {
                    $result = $this->branchModel->addBranch($data);
                    $message = 'New branch added successfully';
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Unauthorized to create branches']);
                    exit;
                }
            }

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => $message]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save settings']);
            }
            exit;
        }
    }

    public function getBranch($id) {
        header('Content-Type: application/json');
        
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        $currentBranchId = $_SESSION['branch_id'] ?? 1;

        // Security Check
        if (!$isSuperAdmin && $id != $currentBranchId) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
            exit;
        }

        $branch = $this->branchModel->getBranchById($id);
        if ($branch) {
            echo json_encode(['status' => 'success', 'data' => $branch]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Branch not found']);
        }
        exit;
    }
}
