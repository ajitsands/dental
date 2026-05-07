<?php
// app/models/BillingModel.php

class BillingModel extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function createInvoice($data) {
        $this->db->query('INSERT INTO invoices (branch_id, patient_id, invoice_number, total_amount, tax_amount, final_amount, status, doctor_id, technician_id, nurse_id) 
                          VALUES (:branch_id, :patient_id, :invoice_number, :total_amount, :tax_amount, :final_amount, "Unpaid", :doctor_id, :technician_id, :nurse_id)');
        
        $this->db->bind(':branch_id', $_SESSION['branch_id'] ?? 1);
        $this->db->bind(':patient_id', $data['patient_id']);
        $this->db->bind(':invoice_number', 'INV-' . date('Y') . '-' . rand(1000, 9999));
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':tax_amount', $data['tax_amount']);
        $this->db->bind(':final_amount', $data['final_amount']);
        $this->db->bind(':doctor_id', $data['doctor_id']);
        $this->db->bind(':technician_id', $data['technician_id']);
        $this->db->bind(':nurse_id', $data['nurse_id']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function recordPayment($invoiceId, $amount, $mode) {
        $this->db->query('INSERT INTO payments (invoice_id, amount, payment_mode) VALUES (:invoice_id, :amount, :mode)');
        $this->db->bind(':invoice_id', $invoiceId);
        $this->db->bind(':amount', $amount);
        $this->db->bind(':mode', $mode);
        
        if ($this->db->execute()) {
            $paymentId = $this->db->lastInsertId();
            $this->updateInvoiceStatus($invoiceId);
            return $paymentId;
        }
        return false;
    }

    private function updateInvoiceStatus($invoiceId) {
        $this->db->query('SELECT final_amount, (SELECT SUM(amount) FROM payments WHERE invoice_id = :id) as total_paid FROM invoices WHERE id = :id');
        $this->db->bind(':id', $invoiceId);
        $row = $this->db->single();

        $status = 'Unpaid';
        if ($row->total_paid >= $row->final_amount) {
            $status = 'Paid';
        } elseif ($row->total_paid > 0) {
            $status = 'Partially Paid';
        }

        $this->db->query('UPDATE invoices SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $invoiceId);
        $this->db->execute();
    }

    public function getInvoicesByBranch($branchId) {
        $this->db->query('SELECT i.*, p.name as patient_name, d.name as doctor_name 
                          FROM invoices i 
                          JOIN patients p ON i.patient_id = p.id 
                          LEFT JOIN users d ON i.doctor_id = d.id 
                          WHERE i.branch_id = :branch_id 
                          ORDER BY i.created_at DESC');
        $this->db->bind(':branch_id', $branchId);
        return $this->db->resultSet();
    }

    public function getInvoiceWithStaff($invoiceId) {
        $this->db->query('SELECT i.*, d.name as doctor_name, t.name as technician_name, n.name as nurse_name 
                          FROM invoices i 
                          LEFT JOIN users d ON i.doctor_id = d.id 
                          LEFT JOIN users t ON i.technician_id = t.id 
                          LEFT JOIN users n ON i.nurse_id = n.id 
                          WHERE i.id = :id');
        $this->db->bind(':id', $invoiceId);
        return $this->db->single();
    }

    public function getInvoiceById($id) {
        $this->db->query('SELECT i.*, p.name as patient_name, p.unique_id as patient_uid 
                          FROM invoices i 
                          JOIN patients p ON i.patient_id = p.id 
                          WHERE i.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
