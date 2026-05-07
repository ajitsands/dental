<?php
// app/controllers/Staff.php

class Staff extends Controller {
    private $userModel;
    private $branchModel;

    public function __construct() {
        $this->checkAuth();
        // Admin or Super Admin can manage staff
        $role_id = (int)($_SESSION['role_id'] ?? 0);
        if ($role_id !== 1 && $role_id !== 6) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->userModel = $this->model('User');
        $this->branchModel = $this->model('BranchModel');
    }

    public function index() {
        $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
        
        if ($isSuperAdmin) {
            $staff = $this->userModel->getAllStaff(); // New method for Super Admin
            $branches = $this->branchModel->getAllBranches();
        } else {
            $staff = $this->userModel->getStaffByBranch($_SESSION['branch_id'] ?? 1);
            $branches = [];
        }

        $roles = $this->userModel->getRoles();
        
        $data = [
            'title' => 'Staff Management - DenSmart',
            'staff' => $staff,
            'roles' => $roles,
            'branches' => $branches,
            'isSuperAdmin' => $isSuperAdmin
        ];
        $this->view('staff/index', $data);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $isSuperAdmin = ((int)$_SESSION['role_id'] === 6);
            $branch_id = $_SESSION['branch_id'] ?? 1;

            // If Super Admin, use branch_id from POST if provided
            if ($isSuperAdmin && !empty($_POST['branch_id'])) {
                $branch_id = $_POST['branch_id'];
            }

            $data = [
                'id' => $_POST['id'] ?? null,
                'branch_id' => $branch_id,
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'role_id' => $_POST['role_id'],
                'phone' => trim($_POST['phone']),
                'commission_pct' => trim($_POST['commission_pct'] ?? 0),
                'status' => $_POST['status'] ?? 'active'
            ];

            // Only hash password if provided
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($data['id']) {
                $result = $this->userModel->updateUser($data);
                $message = 'Staff member updated successfully';
            } else {
                if (empty($_POST['password'])) {
                    echo json_encode(['status' => 'error', 'message' => 'Password is required for new staff']);
                    exit;
                }
                $result = $this->userModel->register($data);
                $message = 'Staff member added successfully';
            }

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => $message]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save staff data']);
            }
            exit;
        }
    }

    public function get($id) {
        header('Content-Type: application/json');
        $user = $this->userModel->getUserById($id);
        if ($user) {
            unset($user->password);
            echo json_encode(['status' => 'success', 'data' => $user]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Staff not found']);
        }
        exit;
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            if ($id == $_SESSION['user_id']) {
                echo json_encode(['status' => 'error', 'message' => 'You cannot delete yourself']);
                exit;
            }

            if ($this->userModel->deleteUser($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Staff member removed']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to remove staff']);
            }
            exit;
        }
    }
}
