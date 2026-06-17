<?php
// app/models/PatientModel.php

class PatientModel extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getPatients() {
        // Patients are now GLOBAL - joined with branches to show origin
        $this->db->query('SELECT p.*, b.name as origin_branch 
                          FROM patients p 
                          LEFT JOIN branches b ON p.branch_id = b.id 
                          ORDER BY p.name ASC');
        return $this->db->resultSet();
    }

    public function getPatientById($id) {
        $this->db->query('SELECT p.*, b.name as origin_branch 
                          FROM patients p 
                          LEFT JOIN branches b ON p.branch_id = b.id 
                          WHERE p.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getPatientHistory($patientId) {
        // Fetch all appointments across all branches
        $this->db->query('SELECT a.*, b.name as branch_name, u.name as doctor_name 
                          FROM appointments a 
                          JOIN branches b ON a.branch_id = b.id 
                          LEFT JOIN users u ON a.user_id = u.id
                          WHERE a.patient_id = :id 
                          ORDER BY a.start_time DESC');
        $this->db->bind(':id', $patientId);
        $appointments = $this->db->resultSet();

        // Fetch all invoices/treatments across all branches
        $this->db->query('SELECT i.*, b.name as branch_name 
                          FROM invoices i 
                          JOIN branches b ON i.branch_id = b.id 
                          WHERE i.patient_id = :id 
                          ORDER BY i.created_at DESC');
        $this->db->bind(':id', $patientId);
        $invoices = $this->db->resultSet();

        return [
            'appointments' => $appointments,
            'invoices' => $invoices
        ];
    }

    public function addPatient($data) {
        $branchId = $data['branch_id'] ?? $_SESSION['branch_id'] ?? 1;

        $this->db->query('INSERT INTO patients (branch_id, unique_id, name, age, gender, contact, email, medical_history, dental_history, medical_alerts) 
                          VALUES (:branch_id, :unique_id, :name, :age, :gender, :contact, :email, :medical_history, :dental_history, :medical_alerts)');
        
        $this->db->bind(':branch_id', $branchId);
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

    public function getPatientLedger($patientId) {
        // Debits (Invoices)
        $this->db->query('SELECT "Invoice" as type, invoice_number as ref, final_amount as amount, created_at as date, status 
                          FROM invoices WHERE patient_id = :id 
                          ORDER BY created_at DESC');
        $this->db->bind(':id', $patientId);
        $invoices = $this->db->resultSet();

        // Credits (Payments)
        $this->db->query('SELECT "Payment" as type, payment_mode as ref, amount, transaction_date as date, "" as status 
                          FROM payments p
                          JOIN invoices i ON p.invoice_id = i.id
                          WHERE i.patient_id = :id 
                          ORDER BY transaction_date DESC');
        $this->db->bind(':id', $patientId);
        $payments = $this->db->resultSet();

        $ledger = array_merge($invoices, $payments);
        usort($ledger, function($a, $b) {
            return strtotime($b->date) - strtotime($a->date);
        });

        return $ledger;
    }

    public function getAllPatientBalances($branch_id = null) {
        $sql = 'SELECT p.id, p.name, p.unique_id, b.name as branch_name,
                (SELECT SUM(final_amount) FROM invoices WHERE patient_id = p.id) as total_invoiced,
                (SELECT SUM(pm.amount) FROM payments pm JOIN invoices inv ON pm.invoice_id = inv.id WHERE inv.patient_id = p.id) as total_paid
                FROM patients p
                JOIN branches b ON p.branch_id = b.id';
        
        if ($branch_id) {
            $sql .= ' WHERE p.branch_id = :branch_id';
        }

        $this->db->query($sql);
        if ($branch_id) $this->db->bind(':branch_id', $branch_id);
        return $this->db->resultSet();
    }
    public function getAgingDebtPatients($days = 30) {
        // Find patients with unpaid or partially paid invoices older than $days
        $this->db->query('SELECT p.name, p.unique_id, i.invoice_number, i.final_amount, i.created_at,
                          (SELECT SUM(amount) FROM payments WHERE invoice_id = i.id) as paid_amount
                          FROM invoices i
                          JOIN patients p ON i.patient_id = p.id
                          WHERE i.status != "Paid" 
                          AND i.created_at < DATE_SUB(NOW(), INTERVAL :days DAY)
                          AND i.branch_id = :branch');
        $this->db->bind(':days', $days);
        $this->db->bind(':branch', $_SESSION['branch_id'] ?? 1);
        return $this->db->resultSet();
    }
    public function saveDentalChart($patientId, $toothNumber, $condition, $notes, $surfaces = '') {
        $this->db->query('INSERT INTO dental_charts (patient_id, tooth_number, condition_name, notes, surfaces) 
                          VALUES (:patient_id, :tooth_number, :condition, :notes, :surfaces)
                          ON DUPLICATE KEY UPDATE condition_name = :condition, notes = :notes, surfaces = :surfaces');
        $this->db->bind(':patient_id', $patientId);
        $this->db->bind(':tooth_number', $toothNumber);
        $this->db->bind(':condition', $condition);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':surfaces', $surfaces);
        return $this->db->execute();
    }

    public function getDentalChart($patientId) {
        $this->db->query('SELECT * FROM dental_charts WHERE patient_id = :id');
        $this->db->bind(':id', $patientId);
        return $this->db->resultSet();
    }
}
