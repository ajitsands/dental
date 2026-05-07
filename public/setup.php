<?php
// public/setup.php

require_once '../config/config.php';
require_once '../config/database.php';
require_once '../app/core/Database.php';

echo "<h2>DenSmart Database Setup</h2>";

try {
    $dsn = 'mysql:host=' . DB_HOST;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $pdo->exec("USE " . DB_NAME);

    // 1. Run the main schema
    $schemaFile = '../database/schema.sql';
    $sql = file_get_contents($schemaFile);
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Ignore errors about existing tables/columns here as we handle them below
            }
        }
    }

    // 2. Add missing columns (Migrations)
    $migrations = [
        ['users', 'commission_pct', 'DECIMAL(5, 2) DEFAULT 0'],
        ['users', 'wallet_balance', 'DECIMAL(10, 2) DEFAULT 0'],
        ['branches', 'commission_model', "ENUM('service', 'individual') DEFAULT 'service'"],
        ['invoices', 'doctor_id', 'INT'],
        ['invoices', 'technician_id', 'INT'],
        ['invoices', 'nurse_id', 'INT'],
        ['services', 'status', "ENUM('Active', 'Inactive') DEFAULT 'Active'"],
        ['appointments', 'branch_id', 'INT'],
        ['appointments', 'status', "ENUM('Booked', 'Confirmed', 'Reported', 'In Consultation', 'Completed', 'Cancelled') DEFAULT 'Booked'"]
    ];

    // 3. Create Prescriptions Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS prescriptions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT,
        patient_id INT,
        doctor_id INT,
        medicines TEXT,
        instructions TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (appointment_id) REFERENCES appointments(id),
        FOREIGN KEY (patient_id) REFERENCES patients(id),
        FOREIGN KEY (doctor_id) REFERENCES users(id)
    )");

    $pdo->exec("INSERT IGNORE INTO roles (id, name) VALUES (4, 'Technician'), (5, 'Nurse'), (6, 'Super Admin')");

    foreach ($migrations as $m) {
        list($table, $column, $definition) = $m;
        $check = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        if ($check->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
            echo "<p style='color: blue;'>+ Added column '$column' to '$table'</p>";
            
            // If we just added branch_id to appointments, set a default for existing rows
            if ($table == 'appointments' && $column == 'branch_id') {
                $pdo->exec("UPDATE appointments SET branch_id = 1 WHERE branch_id IS NULL");
            }
        } else {
            // Force update for specific columns that need ENUM updates
            if ($table == 'appointments' && $column == 'status') {
                $pdo->exec("ALTER TABLE `$table` MODIFY COLUMN `$column` $definition");
                echo "<p style='color: orange;'>~ Updated column '$column' definition in '$table'</p>";
            }
        }
    }

    echo "<p style='color: green;'>✔ Database updated successfully.</p>";
    echo "<p style='color: blue;'><b>Visit:</b> <a href='index.php'>Dashboard</a>.</p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>✘ Error: " . $e->getMessage() . "</p>";
}
