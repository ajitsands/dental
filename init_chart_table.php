<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();
$sql = "CREATE TABLE IF NOT EXISTS dental_charts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    tooth_number INT NOT NULL,
    condition_name VARCHAR(50) NOT NULL,
    notes TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (patient_id, tooth_number)
)";

try {
    $db->query($sql);
    $db->execute();
    echo "Table dental_charts created successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
