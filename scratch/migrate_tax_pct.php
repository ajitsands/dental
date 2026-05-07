<?php
// scratch/migrate_tax_pct.php

define('APPROOT', dirname(__DIR__));
require_once APPROOT . '/config/config.php';
require_once APPROOT . '/config/database.php';
require_once APPROOT . '/app/core/Database.php';

$db = new Database();

try {
    echo "Starting migration...\n";
    $db->query("ALTER TABLE branches ADD COLUMN tax_pct DECIMAL(5, 2) DEFAULT 18.00 AFTER tax_type;");
    if ($db->execute()) {
        echo "Successfully added 'tax_pct' column to 'branches' table.\n";
    } else {
        echo "Failed to execute migration. Column might already exist.\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
