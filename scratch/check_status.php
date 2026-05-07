<?php
// scratch/check_status.php
define('APPROOT', dirname(__DIR__));
require_once APPROOT . '/config/config.php';
require_once APPROOT . '/config/database.php';
require_once APPROOT . '/app/core/Database.php';

session_start();

$db = new Database();
$db->query("SELECT id, name, country, tax_pct, tax_type FROM branches");
$branches = $db->resultSet();

echo "SESSION INFO:\n";
echo "User ID: " . ($_SESSION['user_id'] ?? 'N/A') . "\n";
echo "Branch ID: " . ($_SESSION['branch_id'] ?? 'N/A') . "\n";
echo "Branch Country (Session): " . ($_SESSION['branch_country'] ?? 'N/A') . "\n";
echo "Tax Type (Session): " . ($_SESSION['tax_type'] ?? 'N/A') . "\n";

echo "\nDATABASE BRANCHES:\n";
foreach($branches as $b) {
    echo "ID: {$b->id} | Name: {$b->name} | Country: {$b->country} | Tax: {$b->tax_pct}% {$b->tax_type}\n";
}
