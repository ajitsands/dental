<?php
// app/models/BranchModel.php

class BranchModel extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllBranches() {
        $this->db->query('SELECT * FROM branches ORDER BY name ASC');
        return $this->db->resultSet();
    }

    public function getBranchById($id) {
        $this->db->query('SELECT * FROM branches WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addBranch($data) {
        $this->db->query('INSERT INTO branches (name, address, contact, email, tax_number, country, timezone, tax_type, tax_pct, logo) 
                          VALUES (:name, :address, :contact, :email, :tax_number, :country, :timezone, :tax_type, :tax_pct, :logo)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact', $data['contact']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':tax_number', $data['tax_number']);
        $this->db->bind(':country', $data['country']);
        $this->db->bind(':timezone', $data['timezone']);
        $this->db->bind(':tax_type', $data['tax_type']);
        $this->db->bind(':tax_pct', $data['tax_pct']);
        $this->db->bind(':logo', $data['logo'] ?? '');

        return $this->db->execute();
    }

    public function updateBranch($data) {
        $this->db->query('UPDATE branches SET name = :name, address = :address, contact = :contact, email = :email, 
                          tax_number = :tax_number, country = :country, timezone = :timezone, tax_type = :tax_type, tax_pct = :tax_pct, logo = :logo 
                          WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact', $data['contact']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':tax_number', $data['tax_number']);
        $this->db->bind(':country', $data['country']);
        $this->db->bind(':timezone', $data['timezone']);
        $this->db->bind(':tax_type', $data['tax_type']);
        $this->db->bind(':tax_pct', $data['tax_pct']);
        $this->db->bind(':logo', $data['logo'] ?? '');

        return $this->db->execute();
    }
}
