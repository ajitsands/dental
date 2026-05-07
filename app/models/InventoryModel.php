<?php
// app/models/InventoryModel.php

class InventoryModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getInventoryByBranch($branchId) {
        $this->db->query('SELECT * FROM inventory WHERE branch_id = :branch ORDER BY item_name ASC');
        $this->db->bind(':branch', $branchId);
        return $this->db->resultSet();
    }

    public function getItemById($id) {
        $this->db->query('SELECT * FROM inventory WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addItem($data) {
        $this->db->query('INSERT INTO inventory (branch_id, item_name, category, quantity, unit, low_stock_threshold) VALUES (:branch, :name, :category, :qty, :unit, :threshold)');
        $this->db->bind(':branch', $data['branch_id']);
        $this->db->bind(':name', $data['item_name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':qty', $data['quantity']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':threshold', $data['low_stock_threshold']);
        return $this->db->execute();
    }

    public function updateItem($data) {
        $this->db->query('UPDATE inventory SET item_name = :name, category = :category, unit = :unit, low_stock_threshold = :threshold WHERE id = :id');
        $this->db->bind(':name', $data['item_name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':threshold', $data['low_stock_threshold']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function ensureInventoryLogsTable() {
        $this->db->query("CREATE TABLE IF NOT EXISTS inventory_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            branch_id INT NOT NULL,
            inventory_id INT NOT NULL,
            patient_id INT NULL,
            user_id INT NOT NULL,
            type ENUM('add', 'consume') NOT NULL,
            quantity DECIMAL(10,2) NOT NULL,
            notes TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        $this->db->execute();
    }

    public function logTransaction($data) {
        $this->db->query('INSERT INTO inventory_logs (branch_id, inventory_id, patient_id, user_id, type, quantity, notes) 
                          VALUES (:branch, :inv, :pat, :user, :type, :qty, :notes)');
        $this->db->bind(':branch', $_SESSION['branch_id'] ?? 1);
        $this->db->bind(':inv', $data['item_id']);
        $this->db->bind(':pat', $data['patient_id'] ?? null);
        $this->db->bind(':user', $_SESSION['user_id'] ?? 1);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':qty', $data['quantity']);
        $this->db->bind(':notes', $data['notes'] ?? '');
        return $this->db->execute();
    }

    public function getLogsByBranch($branchId) {
        $this->db->query('SELECT l.*, i.item_name, i.unit, p.name as patient_name, u.name as user_name 
                          FROM inventory_logs l 
                          JOIN inventory i ON l.inventory_id = i.id 
                          LEFT JOIN patients p ON l.patient_id = p.id 
                          JOIN users u ON l.user_id = u.id 
                          WHERE l.branch_id = :branch 
                          ORDER BY l.created_at DESC');
        $this->db->bind(':branch', $branchId);
        return $this->db->resultSet();
    }

    public function updateStock($id, $quantity, $type = 'deduct') {
        if ($type == 'deduct') {
            $this->db->query('UPDATE inventory SET quantity = quantity - :qty WHERE id = :id');
        } else {
            $this->db->query('UPDATE inventory SET quantity = quantity + :qty WHERE id = :id');
        }
        $this->db->bind(':qty', $quantity);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deductStockForService($serviceId) {
        // Find what inventory items this service uses
        $this->db->query('SELECT * FROM service_inventory WHERE service_id = :sid');
        $this->db->bind(':sid', $serviceId);
        $mappings = $this->db->resultSet();

        foreach($mappings as $map) {
            $this->updateStock($map->inventory_id, $map->quantity_used, 'deduct');
        }
    }
}
