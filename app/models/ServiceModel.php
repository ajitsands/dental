<?php
// app/models/ServiceModel.php

class ServiceModel extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllServices() {
        $this->db->query('SELECT * FROM services WHERE branch_id = :branch_id ORDER BY name ASC');
        $this->db->bind(':branch_id', $_SESSION['branch_id'] ?? 1);
        return $this->db->resultSet();
    }

    public function addService($data) {
        $this->db->query('INSERT INTO services (branch_id, name, cost, doc_comm_pct, tech_comm_pct, nurse_comm_pct, status) 
                          VALUES (:branch_id, :name, :cost, :doc_comm_pct, :tech_comm_pct, :nurse_comm_pct, :status)');
        
        $this->db->bind(':branch_id', $_SESSION['branch_id'] ?? 1);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':cost', $data['cost']);
        $this->db->bind(':doc_comm_pct', $data['doc_comm_pct']);
        $this->db->bind(':tech_comm_pct', $data['tech_comm_pct']);
        $this->db->bind(':nurse_comm_pct', $data['nurse_comm_pct']);
        $this->db->bind(':status', $data['status'] ?? 'Active');

        return $this->db->execute();
    }

    public function getServiceById($id) {
        $this->db->query('SELECT * FROM services WHERE id = :id AND branch_id = :branch_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':branch_id', $_SESSION['branch_id'] ?? 1);
        return $this->db->single();
    }

    public function updateService($data) {
        $this->db->query('UPDATE services SET name = :name, cost = :cost, doc_comm_pct = :doc_comm_pct, tech_comm_pct = :tech_comm_pct, nurse_comm_pct = :nurse_comm_pct, status = :status WHERE id = :id AND branch_id = :branch_id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':branch_id', $_SESSION['branch_id'] ?? 1);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':cost', $data['cost']);
        $this->db->bind(':doc_comm_pct', $data['doc_comm_pct']);
        $this->db->bind(':tech_comm_pct', $data['tech_comm_pct']);
        $this->db->bind(':nurse_comm_pct', $data['nurse_comm_pct']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function deleteService($id) {
        $this->db->query('DELETE FROM services WHERE id = :id AND branch_id = :branch_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':branch_id', $_SESSION['branch_id'] ?? 1);
        return $this->db->execute();
    }
}
