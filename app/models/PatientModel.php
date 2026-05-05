<?php
// app/models/PatientModel.php

class PatientModel extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getPatients() {
        $this->db->query('SELECT * FROM patients ORDER BY name ASC');
        return $this->db->resultSet();
    }

    public function addPatient($data) {
        $this->db->query('INSERT INTO patients (branch_id, unique_id, name, age, gender, contact, email, medical_history, dental_history, medical_alerts) 
                          VALUES (:branch_id, :unique_id, :name, :age, :gender, :contact, :email, :medical_history, :dental_history, :medical_alerts)');
        
        $this->db->bind(':branch_id', 1); // Mock branch
        $this->db->bind(':unique_id', 'P-' . rand(1000, 9999));
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':age', $data['age']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':contact', $data['contact']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':medical_history', $data['medical_history']);
        $this->db->bind(':dental_history', $data['dental_history']);
        $this->db->bind(':medical_alerts', $data['medical_alerts']);

        return $this->db->execute();
    }
}
