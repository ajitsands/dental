<?php
/**
 * DenSmart SQL Sanitizer
 * This script fixes the "#1273 - Unknown collation: 'utf8mb4_0900_ai_ci'" error
 * by converting the SQL file to a compatible version for your server.
 */

$inputFile = 'SQL.sql';
$outputFile = 'SQL_FOR_SERVER.sql';

if (!file_exists($inputFile)) {
    die("Error: $inputFile not found in the root folder.\n");
}

echo "Reading $inputFile...\n";
$content = file_get_contents($inputFile);

echo "Replacing incompatible collations...\n";
// Fixes the 0900_ai_ci error
$content = str_replace('utf8mb4_0900_ai_ci', 'utf8mb4_unicode_ci', $content);

// Some older servers also struggle with utf8mb4 in general, 
// so we ensure it's standard utf8 if needed (uncomment if error persists)
// $content = str_replace('utf8mb4', 'utf8', $content);

echo "Saving to $outputFile...\n";
if (file_put_contents($outputFile, $content)) {
    echo "✅ Success! Please upload '$outputFile' to your server's phpMyAdmin.\n";
} else {
    echo "❌ Error: Could not save the file.\n";
}
