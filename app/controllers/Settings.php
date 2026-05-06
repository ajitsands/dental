<?php
// app/controllers/Settings.php

class Settings extends Controller {
    private $branchModel;

    public function __construct() {
        $this->checkAuth();
        $this->branchModel = $this->model('BranchModel');
    }

    public function index() {
        $branches = $this->branchModel->getAllBranches();
        $data = [
            'title' => 'Settings - DenSmart',
            'branches' => $branches
        ];
        $this->view('settings/index', $data);
    }

    public function addBranch() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'contact' => trim($_POST['contact']),
                'address' => trim($_POST['address']),
                'country' => trim($_POST['country']),
                'tax_number' => trim($_POST['tax_number']),
                'tax_type' => $_POST['tax_type'],
                'timezone' => $_POST['timezone']
            ];

            if ($this->branchModel->addBranch($data)) {
                echo json_encode(['status' => 'success', 'message' => 'New branch added successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to add branch']);
            }
            exit;
        }
    }
}
