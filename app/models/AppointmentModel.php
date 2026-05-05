<?php
// app/models/AppointmentModel.php

class AppointmentModel extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all appointments
    public function getAppointments() {
        $this->db->query('SELECT a.*, p.name as patient_name 
                          FROM appointments a 
                          JOIN patients p ON a.patient_id = p.id 
                          ORDER BY a.start_time ASC');
        return $this->db->resultSet();
    }

    // Add appointment
    public function addAppointment($data) {
        $this->db->query('INSERT INTO appointments (patient_id, user_id, chair_id, start_time, end_time, notes) 
                          VALUES (:patient_id, :user_id, :chair_id, :start_time, :end_time, :notes)');
        
        $this->db->bind(':patient_id', $data['patient_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':chair_id', $data['chair_id']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }
}
