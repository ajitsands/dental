<?php
// app/controllers/Service.php

class Service extends Controller {
    private $serviceModel;

    public function __construct() {
        $this->checkAuth();
        $this->serviceModel = $this->model('ServiceModel');
    }

    public function index() {
        $services = $this->serviceModel->getAllServices();
        $data = [
            'title' => 'Service Management - DenSmart',
            'services' => $services
        ];
        $this->view('services/index', $data);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $data = [
                'id' => $_POST['id'] ?? null,
                'name' => trim($_POST['name']),
                'cost' => trim($_POST['cost']),
                'doc_comm_pct' => trim($_POST['doc_comm_pct'] ?? 0),
                'tech_comm_pct' => trim($_POST['tech_comm_pct'] ?? 0),
                'nurse_comm_pct' => trim($_POST['nurse_comm_pct'] ?? 0),
                'status' => $_POST['status'] ?? 'Active'
            ];

            if ($data['id']) {
                $result = $this->serviceModel->updateService($data);
                $message = 'Service updated successfully';
            } else {
                $result = $this->serviceModel->addService($data);
                $message = 'Service added successfully';
            }

            if ($result) {
                echo json_encode(['status' => 'success', 'message' => $message]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error: Could not save service.']);
            }
            exit;
        }
    }

    public function get($id) {
        header('Content-Type: application/json');
        $service = $this->serviceModel->getServiceById($id);
        if ($service) {
            echo json_encode(['status' => 'success', 'data' => $service]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Service not found']);
        }
        exit;
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            if ($this->serviceModel->deleteService($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Service deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete']);
            }
            exit;
        }
    }
}
