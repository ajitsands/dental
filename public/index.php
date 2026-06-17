<?php
// public/index.php
ob_start(); // Buffer output to prevent "headers already sent"

// CORS Headers - Allow requests from the main landing page
header("Access-Control-Allow-Origin: https://densmart.us");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Handle Preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use absolute paths based on the current directory
define('APPROOT', dirname(__DIR__));

require_once APPROOT . '/config/config.php';
require_once APPROOT . '/config/database.php';
require_once APPROOT . '/app/helpers/session_helper.php';
require_once APPROOT . '/app/helpers/currency_helper.php';
require_once APPROOT . '/app/helpers/language_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    $file = APPROOT . '/app/core/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize Router
try {
    $db = new Database();
    // Migration for tax_pct
    try {
        $db->query("SELECT tax_pct FROM branches LIMIT 1");
        $db->execute();
    } catch (Exception $e) {
        $db->query("ALTER TABLE branches ADD COLUMN tax_pct DECIMAL(5, 2) DEFAULT 18.00 AFTER tax_type");
        $db->execute();
    }

    // Migration for service_inventory
    try {
        $db->query("SELECT id FROM service_inventory LIMIT 1");
        $db->execute();
    } catch (Exception $e) {
        $db->query("CREATE TABLE IF NOT EXISTS service_inventory (
            id INT AUTO_INCREMENT PRIMARY KEY,
            service_id INT,
            inventory_id INT,
            quantity_used INT DEFAULT 1,
            FOREIGN KEY (service_id) REFERENCES services(id),
            FOREIGN KEY (inventory_id) REFERENCES inventory(id)
        )");
        $db->execute();
    }
} catch (Exception $e) {
    // Global catch to prevent crash if DB is down
}

// Ensure session variables are refreshed for localization
if (isset($_SESSION['user_id']) && isset($_SESSION['branch_id'])) {
    try {
        $db = new Database();
        $db->query("SELECT country, tax_pct, tax_type FROM branches WHERE id = :id");
        $db->bind(':id', $_SESSION['branch_id']);
        $branch = $db->single();
        if ($branch) {
            $_SESSION['branch_country'] = $branch->country;
            $_SESSION['tax_pct'] = $branch->tax_pct;
            $_SESSION['tax_type'] = (strtolower($branch->country) == 'india') ? 'GST' : 'VAT';
        }
    } catch (Exception $e) {
        // Ignore if error
    }
}

$init = new Router();
