<?php
// Script to reset all billing data as requested by the user
require_once 'config/config.php'; // Adjust path if needed
require_once 'app/core/Database.php';

$db = new Database();

try {
    echo "Starting Billing Reset...\n";
    
    // Disable foreign key checks to allow truncation
    $db->query("SET FOREIGN_KEY_CHECKS = 0");
    $db->execute();
    
    echo "Clearing Payments...\n";
    $db->query("TRUNCATE TABLE payments");
    $db->execute();
    
    echo "Clearing Invoice Items...\n";
    $db->query("TRUNCATE TABLE invoice_items");
    $db->execute();
    
    echo "Clearing Invoices...\n";
    $db->query("TRUNCATE TABLE invoices");
    $db->execute();

    // Re-enable foreign key checks
    $db->query("SET FOREIGN_KEY_CHECKS = 1");
    $db->execute();
    
    echo "SUCCESS: All invoices and payments have been removed. You can now start fresh from INV-2026-0001.\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
