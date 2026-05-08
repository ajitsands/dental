<?php
// app/models/WalletModel.php

class WalletModel extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getStaffBalances($branch_id = null) {
        $sql = 'SELECT u.id, u.name, u.email, u.wallet_balance, r.name as role_name, b.name as branch_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                JOIN branches b ON u.branch_id = b.id
                WHERE u.role_id IN (1, 2, 4, 5)'; // Admin, Dentist, Tech, Nurse
        
        if ($branch_id) {
            $sql .= ' AND u.branch_id = :branch_id';
        }

        $sql .= ' ORDER BY u.wallet_balance DESC';
        
        $this->db->query($sql);
        if ($branch_id) $this->db->bind(':branch_id', $branch_id);
        return $this->db->resultSet();
    }

    public function getLedger($userId) {
        $this->db->query('SELECT t.*, i.invoice_number 
                          FROM wallet_transactions t 
                          LEFT JOIN payments p ON t.reference_id = p.id
                          LEFT JOIN invoices i ON p.invoice_id = i.id
                          WHERE t.user_id = :id 
                          ORDER BY t.created_at DESC');
        $this->db->bind(':id', $userId);
        return $this->db->resultSet();
    }

    public function addPayout($data) {
        try {
            $this->db->beginTransaction();

            // 1. Record Debit Transaction
            $this->db->query('INSERT INTO wallet_transactions (user_id, amount, type, description) 
                              VALUES (:user_id, :amount, "Debit", :description)');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->bind(':description', $data['description'] ?: 'Cash Payout');
            $this->db->execute();

            // 2. Update User Balance
            $this->db->query('UPDATE users SET wallet_balance = wallet_balance - :amount WHERE id = :user_id');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':amount', $data['amount']);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function creditWallet($userId, $amount, $description, $referenceId = null) {
        try {
            $this->db->beginTransaction();

            // 1. Record Credit Transaction
            $this->db->query('INSERT INTO wallet_transactions (user_id, amount, type, description, reference_id) 
                              VALUES (:user_id, :amount, "Credit", :description, :ref)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':amount', $amount);
            $this->db->bind(':description', $description);
            $this->db->bind(':ref', $referenceId);
            $this->db->execute();

            // 2. Update User Balance
            $this->db->query('UPDATE users SET wallet_balance = wallet_balance + :amount WHERE id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':amount', $amount);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function reverseCommissionByInvoice($invoiceId) {
        try {
            $this->db->beginTransaction();

            // 1. Get all payments for this invoice
            $this->db->query('SELECT id FROM payments WHERE invoice_id = :id');
            $this->db->bind(':id', $invoiceId);
            $payments = $this->db->resultSet();

            foreach ($payments as $payment) {
                // 2. Find all transactions related to this payment
                $this->db->query('SELECT * FROM wallet_transactions WHERE reference_id = :pay_id AND type = "Credit"');
                $this->db->bind(':pay_id', $payment->id);
                $transactions = $this->db->resultSet();

                foreach ($transactions as $tx) {
                    // 3. Deduct from user balance
                    $this->db->query('UPDATE users SET wallet_balance = wallet_balance - :amount WHERE id = :user_id');
                    $this->db->bind(':amount', $tx->amount);
                    $this->db->bind(':user_id', $tx->user_id);
                    $this->db->execute();
                }

                // 4. Delete the transactions
                $this->db->query('DELETE FROM wallet_transactions WHERE reference_id = :pay_id');
                $this->db->bind(':pay_id', $payment->id);
                $this->db->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
