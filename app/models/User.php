<?php
// app/models/User.php

class User extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function login($email, $password) {
        $row = $this->findUserByEmail($email);
        if ($row == false) return false;
        if (password_verify($password, $row->password)) {
            return $row;
        } else {
            return false;
        }
    }

    public function getUserById($id) {
        $this->db->query('SELECT u.*, r.name as role_name 
                          FROM users u 
                          JOIN roles r ON u.role_id = r.id 
                          WHERE u.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password, branch_id, role_id, phone, commission_pct, status) VALUES(:name, :email, :password, :branch_id, :role_id, :phone, :commission_pct, :status)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':branch_id', $data['branch_id']);
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':phone', $data['phone'] ?? null);
        $this->db->bind(':commission_pct', $data['commission_pct'] ?? 0);
        $this->db->bind(':status', $data['status'] ?? 'active');

        return $this->db->execute();
    }

    public function updateUser($data) {
        $sql = 'UPDATE users SET name = :name, email = :email, role_id = :role_id, phone = :phone, commission_pct = :commission_pct, status = :status';
        if (!empty($data['password'])) { $sql .= ', password = :password'; }
        $sql .= ' WHERE id = :id';
        
        $this->db->query($sql);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':commission_pct', $data['commission_pct']);
        $this->db->bind(':status', $data['status']);
        
        if (!empty($data['password'])) {
            $this->db->bind(':password', $data['password']);
        }

        return $this->db->execute();
    }

    public function getStaffByBranch($branch_id) {
        $this->db->query('SELECT u.*, r.name as role_name 
                          FROM users u 
                          JOIN roles r ON u.role_id = r.id 
                          WHERE u.branch_id = :branch_id AND u.role_id != 3 AND u.role_id != 6
                          ORDER BY u.name ASC');
        $this->db->bind(':branch_id', $branch_id);
        return $this->db->resultSet();
    }

    public function getAllStaff() {
        $this->db->query('SELECT u.*, r.name as role_name, b.name as branch_name 
                          FROM users u 
                          JOIN roles r ON u.role_id = r.id 
                          JOIN branches b ON u.branch_id = b.id
                          WHERE u.role_id != 3
                          ORDER BY b.name ASC, u.name ASC');
        return $this->db->resultSet();
    }

    public function getRoles() {
        $this->db->query('SELECT * FROM roles ORDER BY id ASC');
        return $this->db->resultSet();
    }

    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
