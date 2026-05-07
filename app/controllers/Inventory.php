<?php
// app/controllers/Inventory.php

class Inventory extends Controller {
    private $inventoryModel;

    public function __construct() {
        $this->checkAuth();
        $this->inventoryModel = $this->model('InventoryModel');
        $this->inventoryModel->ensureInventoryLogsTable();
    }

    public function index() {
        $branchId = $_SESSION['branch_id'] ?? 1;
        $items = $this->inventoryModel->getInventoryByBranch($branchId);
        
        $patientModel = $this->model('PatientModel');
        $patients = $patientModel->getPatients();

        $data = [
            'title' => 'Inventory Management - DenSmart',
            'items' => $items,
            'patients' => $patients
        ];
        $this->view('inventory/index', $data);
    }

    public function updateStock() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            $id = $_POST['item_id'];
            $qty = $_POST['quantity'];
            $type = $_POST['type']; // 'add' or 'consume'

            if ($this->inventoryModel->updateStock($id, $qty, $type == 'add' ? 'add' : 'deduct')) {
                $this->inventoryModel->logTransaction($_POST);
                echo json_encode(['status' => 'success', 'message' => 'Stock updated successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update stock']);
            }
            exit;
        }
    }

    public function report() {
        $branchId = $_SESSION['branch_id'] ?? 1;
        $logs = $this->inventoryModel->getLogsByBranch($branchId);
        
        $data = [
            'title' => 'Inventory Report - DenSmart',
            'logs' => $logs
        ];
        $this->view('inventory/report', $data);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            
            $data = [
                'id' => $_POST['id'] ?? null,
                'branch_id' => $_SESSION['branch_id'] ?? 1,
                'item_name' => $_POST['item_name'],
                'category' => $_POST['category'],
                'quantity' => $_POST['quantity'] ?? 0,
                'unit' => $_POST['unit'],
                'low_stock_threshold' => $_POST['low_stock_threshold']
            ];

            if ($data['id']) {
                if ($this->inventoryModel->updateItem($data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Item updated successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update item']);
                }
            } else {
                if ($this->inventoryModel->addItem($data)) {
                    echo json_encode(['status' => 'success', 'message' => 'Item added to inventory']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to add item']);
                }
            }
            exit;
        }
    }

    public function getItem($id) {
        header('Content-Type: application/json');
        $item = $this->inventoryModel->getItemById($id);
        echo json_encode($item);
        exit;
    }
}
