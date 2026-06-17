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

    public function deleteBranchWithAllData($id) {
        try {
            $this->db->beginTransaction();

            // Turn off foreign keys temporarily for deletion
            $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->execute();

            // 1. Delete transactions and dependent tables
            $this->db->query("DELETE FROM wallet_transactions WHERE user_id IN (SELECT id FROM users WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM payments WHERE invoice_id IN (SELECT id FROM invoices WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM invoice_items WHERE invoice_id IN (SELECT id FROM invoices WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM invoices WHERE branch_id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM prescriptions WHERE appointment_id IN (SELECT id FROM appointments WHERE branch_id = :id) OR patient_id IN (SELECT id FROM patients WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM procedures WHERE appointment_id IN (SELECT id FROM appointments WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM treatment_plans WHERE patient_id IN (SELECT id FROM patients WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM tooth_chart WHERE patient_id IN (SELECT id FROM patients WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM dental_charts WHERE patient_id IN (SELECT id FROM patients WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM appointments WHERE branch_id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM service_inventory WHERE service_id IN (SELECT id FROM services WHERE branch_id = :id) OR inventory_id IN (SELECT id FROM inventory WHERE branch_id = :id)");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM inventory_logs WHERE branch_id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM inventory WHERE branch_id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM services WHERE branch_id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM patients WHERE branch_id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM chairs WHERE branch_id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            // Deleting staff/users (keep super admin if they are associated with this branch, though controller prevents deleting their current/active branch)
            $this->db->query("DELETE FROM users WHERE branch_id = :id AND role_id != 6");
            $this->db->bind(':id', $id);
            $this->db->execute();

            // Finally, delete the branch itself
            $this->db->query("DELETE FROM branches WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteBranchAndTransferData($id, $targetId) {
        try {
            $this->db->beginTransaction();

            // Update all records with a direct branch_id to target branch
            $tablesToUpdate = [
                'users',
                'patients',
                'chairs',
                'appointments',
                'invoices',
                'services',
                'inventory',
                'inventory_logs'
            ];

            foreach ($tablesToUpdate as $table) {
                $this->db->query("UPDATE `$table` SET branch_id = :target_id WHERE branch_id = :id");
                $this->db->bind(':target_id', $targetId);
                $this->db->bind(':id', $id);
                $this->db->execute();
            }

            // Finally, delete the empty branch
            $this->db->query("DELETE FROM branches WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
