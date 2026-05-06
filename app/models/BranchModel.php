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

    public function addBranch($data) {
        $this->db->query('INSERT INTO branches (name, address, contact, email, tax_number, country, timezone, tax_type) 
                          VALUES (:name, :address, :contact, :email, :tax_number, :country, :timezone, :tax_type)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':contact', $data['contact']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':tax_number', $data['tax_number']);
        $this->db->bind(':country', $data['country']);
        $this->db->bind(':timezone', $data['timezone']);
        $this->db->bind(':tax_type', $data['tax_type']);

        return $this->db->execute();
    }
}
