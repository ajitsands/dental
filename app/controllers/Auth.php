<?php
// app/controllers/Auth.php

class Auth extends Controller {
    private $userModel;
    private $db;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->db = new Database();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $loggedInUser = $this->userModel->login($email, $password);

            if ($loggedInUser) {
                $this->createUserSession($loggedInUser);
                echo json_encode(['status' => 'success', 'message' => 'Login successful']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
            }
            exit;
        } else {
            $data = ['title' => 'Login - DenSmart'];
            $this->view('auth/login', $data);
        }
    }

    public function selectBranch() {
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['branch_id'] = $_POST['branch_id'];
            
            // Get branch name for display
            $this->db->query("SELECT name FROM branches WHERE id = :id");
            $this->db->bind(':id', $_POST['branch_id']);
            $branch = $this->db->single();
            $_SESSION['branch_name'] = $branch->name;

            header('Location: ' . BASE_URL . '/dashboard');
            exit();
        } else {
            // Get all branches
            $this->db->query("SELECT * FROM branches");
            $branches = $this->db->resultSet();

            $data = [
                'title' => 'Select Branch - DenSmart',
                'branches' => $branches
            ];
            $this->view('auth/select_branch', $data);
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['role_id'] = $user->role_id;
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['role_id']);
        unset($_SESSION['branch_id']);
        unset($_SESSION['branch_name']);
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
    }
}
