<?php
// app/models/AppointmentModel.php

class AppointmentModel extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAppointmentsByBranch($branch_id) {
        $this->db->query('SELECT a.*, p.name as patient_name 
                          FROM appointments a 
                          JOIN patients p ON a.patient_id = p.id 
                          WHERE a.branch_id = :branch_id AND a.status != "Cancelled"
                          ORDER BY a.start_time ASC');
        $this->db->bind(':branch_id', $branch_id);
        return $this->db->resultSet();
    }

    public function getAppointments() {
        $branch_id = $_SESSION['branch_id'] ?? 1;
        return $this->getAppointmentsByBranch($branch_id);
    }

    public function addAppointment($data) {
        $branchId = $_SESSION['branch_id'] ?? 1;

        // Ensure a chair exists for this branch
        $this->db->query("SELECT id FROM chairs WHERE branch_id = :branch_id LIMIT 1");
        $this->db->bind(':branch_id', $branchId);
        $chair = $this->db->single();
        
        if (!$chair) {
            $this->db->query("INSERT INTO chairs (branch_id, name) VALUES (:branch_id, 'Chair 1')");
            $this->db->bind(':branch_id', $branchId);
            $this->db->execute();
            $chairId = $this->db->lastInsertId();
        } else {
            $chairId = $chair->id;
        }

        $this->db->query('INSERT INTO appointments (branch_id, patient_id, user_id, chair_id, start_time, end_time, notes) 
                          VALUES (:branch_id, :patient_id, :user_id, :chair_id, :start_time, :end_time, :notes)');
        
        $this->db->bind(':branch_id', $branchId);
        $this->db->bind(':patient_id', $data['patient_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':chair_id', $chairId);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    public function updateStatus($id, $status) {
        $this->db->query('UPDATE appointments SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function addPrescription($data) {
        $this->db->query('INSERT INTO prescriptions (appointment_id, patient_id, doctor_id, medicines, instructions) 
                          VALUES (:appointment_id, :patient_id, :doctor_id, :medicines, :instructions)');
        $this->db->bind(':appointment_id', $data['appointment_id']);
        $this->db->bind(':patient_id', $data['patient_id']);
        $this->db->bind(':doctor_id', $data['doctor_id']);
        $this->db->bind(':medicines', $data['medicines']);
        $this->db->bind(':instructions', $data['instructions']);
        return $this->db->execute();
    }

    public function getPrescription($appointmentId) {
        $this->db->query('SELECT * FROM prescriptions WHERE appointment_id = :id');
        $this->db->bind(':id', $appointmentId);
        return $this->db->single();
    }

    public function getPrescriptionsByPatient($patientId) {
        $this->db->query('SELECT p.*, a.start_time, u.name as doctor_name 
                          FROM prescriptions p 
                          JOIN appointments a ON p.appointment_id = a.id 
                          JOIN users u ON p.doctor_id = u.id 
                          WHERE p.patient_id = :id 
                          ORDER BY a.start_time DESC');
        $this->db->bind(':id', $patientId);
        return $this->db->resultSet();
    }
    public function cancelAppointment($id) {
        $this->db->query('UPDATE appointments SET status = "Cancelled" WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function extendAppointment($id, $minutes = 30) {
        $this->db->query('UPDATE appointments SET end_time = DATE_ADD(end_time, INTERVAL :mins MINUTE) WHERE id = :id');
        $this->db->bind(':mins', $minutes);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function rescheduleAppointment($id, $startTime, $endTime) {
        $this->db->query('UPDATE appointments SET start_time = :start, end_time = :end WHERE id = :id');
        $this->db->bind(':start', $startTime);
        $this->db->bind(':end', $endTime);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
