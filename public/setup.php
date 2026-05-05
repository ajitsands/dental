<?php
// public/setup.php

require_once '../config/config.php';
require_once '../config/database.php';
require_once '../app/core/Database.php';

echo "<h2>DenSmart Database Setup</h2>";

try {
    // Connect without database name first to create it
    $dsn = 'mysql:host=' . DB_HOST;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    echo "<p style='color: green;'>✔ Database '" . DB_NAME . "' created or already exists.</p>";

    // Connect to the specific database
    $pdo->exec("USE " . DB_NAME);

    // Read schema file
    $schemaFile = '../database/schema.sql';
    if (!file_exists($schemaFile)) {
        die("<p style='color: red;'>✘ Schema file not found at $schemaFile</p>");
    }

    $sql = file_get_contents($schemaFile);

    // Simple split by semicolon (not perfect for all SQL, but works for our schema)
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }

    echo "<p style='color: green;'>✔ All tables created successfully.</p>";
    echo "<p style='color: blue;'><b>Next steps:</b> Remove this file for security and visit <a href='index.php'>Dashboard</a>.</p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>✘ Error: " . $e->getMessage() . "</p>";
    echo "<p>Please check your <b>config/database.php</b> settings.</p>";
}
