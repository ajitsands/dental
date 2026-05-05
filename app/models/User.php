<?php
// app/models/User.php

class User extends Model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password) {
        $row = $this->findUserByEmail($email);

        if ($row == false) return false;

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Register User
    public function register($data) {
        $this->db->query('INSERT INTO users (name, email, password, branch_id, role_id) VALUES(:name, :email, :password, :branch_id, :role_id)');
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':branch_id', $data['branch_id']);
        $this->db->bind(':role_id', $data['role_id']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
