<?php
// clear_data.php
// Script to clear all transactional and setup data from the database except the superadmin user.
// Useful for resetting workflow testing.

// Check database configuration location
if (file_exists(__DIR__ . '/config/database.php')) {
    require_once __DIR__ . '/config/database.php';
} elseif (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
} else {
    die("Error: database.php configuration file not found.\n");
}

// Check if running from CLI or if a web request contains the confirmation parameter
$isCli = (php_sapi_name() === 'cli');
$confirmed = isset($_GET['confirm']) && $_GET['confirm'] === 'yes';

if (!$isCli) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Security check: Must be logged in as Super Admin (role_id 6)
    if (!isset($_SESSION['role_id']) || (int)$_SESSION['role_id'] !== 6) {
        http_response_code(403);
        die("Error: Unauthorized access. You must be logged in as a Super Admin to run this script.\n");
    }
}

if (!$isCli && !$confirmed) {

    header('Content-Type: text/html; charset=utf-8');
    echo '
    <div style="max-width: 600px; margin: 50px auto; padding: 30px; border: 2px solid #ef4444; border-radius: 12px; font-family: sans-serif; background-color: #fef2f2; text-align: center;">
        <h2 style="color: #991b1b; margin-top: 0;">⚠️ WARNING: Database Reset Required</h2>
        <p style="color: #7f1d1d; font-size: 1.1rem; line-height: 1.6;">
            This action will <strong>PERMANENTLY DELETE</strong> all patients, appointments, billing invoices, payments, inventory, services, and staff records. 
            Only the <strong>Super Admin</strong> login will be preserved.
        </p>
        <p style="margin: 25px 0;">
            <a href="?confirm=yes" style="display: inline-block; background-color: #dc2626; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold; transition: background-color 0.2s;">
                Yes, Clear All Data
            </a>
            <a href="index.php" style="display: inline-block; margin-left: 15px; background-color: #4b5563; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: bold;">
                Cancel
            </a>
        </p>
    </div>
    ';
    exit;
}

if (!$isCli) {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "DenSmart Database Cleanser\n";
echo "==========================\n";

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database: " . DB_NAME . "\n";
    
    // Disable foreign keys to truncate/delete cleanly
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    $tablesToTruncate = [
        'wallet_transactions',
        'payments',
        'invoice_items',
        'invoices',
        'prescriptions',
        'procedures',
        'treatment_plans',
        'dental_charts',
        'tooth_chart',
        'appointments',
        'service_inventory',
        'inventory_logs',
        'inventory',
        'services',
        'patients',
        'chairs'
    ];
    
    foreach ($tablesToTruncate as $table) {
        try {
            $pdo->exec("TRUNCATE TABLE `$table`");
            echo " - Truncated table: $table\n";
        } catch (PDOException $ex) {
            echo " ! Error truncating $table: " . $ex->getMessage() . "\n";
        }
    }
    
    // Clear all users except superadmin
    $pdo->exec("DELETE FROM users WHERE email != 'superadmin@sandslab.com'");
    echo " - Removed all users except superadmin@sandslab.com\n";
    
    // Clear other branches
    $pdo->exec("DELETE FROM branches WHERE id != 1");
    $pdo->exec("ALTER TABLE branches AUTO_INCREMENT = 2");
    echo " - Removed all branches except Main Clinic (Branch ID: 1)\n";
    
    // Ensure core roles exist
    $roles = [
        [1, 'Admin'],
        [2, 'Dentist'],
        [3, 'Receptionist'],
        [4, 'Technician'],
        [5, 'Nurse'],
        [6, 'Super Admin']
    ];
    $stmt = $pdo->prepare("INSERT IGNORE INTO roles (id, name) VALUES (?, ?)");
    foreach ($roles as $role) {
        $stmt->execute($role);
    }
    echo " - Ensured core roles are present in database\n";
    
    // Ensure branch 1 exists
    $pdo->exec("INSERT IGNORE INTO branches (id, name, country, timezone, tax_type, tax_pct) 
                VALUES (1, 'DenSmart Main Clinic', 'India', 'Asia/Kolkata', 'GST', 18.00)");
    echo " - Ensured Main Clinic branch (ID: 1) is present\n";
    
    // Ensure superadmin user exists
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (id, branch_id, role_id, name, email, password, phone, status, commission_pct, wallet_balance)
                           VALUES (6, 1, 6, 'Super admin', 'superadmin@sandslab.com', ?, '9895765626', 'active', 0.00, 0.00)
                           ON DUPLICATE KEY UPDATE 
                               branch_id = 1,
                               role_id = 6,
                               password = ?,
                               status = 'active'");
    $stmt->execute([$hashedPassword, $hashedPassword]);
    echo " - Ensured Super Admin user exists (Login: superadmin@sandslab.com / Password: admin123)\n";
    
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    echo "==========================\n";
    echo "SUCCESS: Database successfully cleared and reset to fresh installation state!\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
