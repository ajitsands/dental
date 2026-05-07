<?php
// scratch/check_branches.php
define('APPROOT', dirname(__DIR__));
require_once APPROOT . '/config/config.php';
require_once APPROOT . '/config/database.php';
require_once APPROOT . '/app/core/Database.php';

$db = new Database();
$db->query("SELECT id, name, country, tax_pct FROM branches");
$results = $db->resultSet();
echo "ID | Name | Country | Tax%\n";
echo "---------------------------\n";
foreach($results as $r) {
    echo "{$r->id} | {$r->name} | {$r->country} | {$r->tax_pct}\n";
}
