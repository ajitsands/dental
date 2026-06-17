<?php
// public/force_pull.php
// Script to forcefully reset local conflicts and pull the latest code.
// Accessible at https://app.densmart.us/force_pull.php

header('Content-Type: text/plain; charset=utf-8');

$projectRoot = dirname(__DIR__);
chdir($projectRoot);

echo "DenSmart Force Pull Utility\n";
echo "========================================\n";

$dbFile = $projectRoot . '/config/database.php';
$bakFile = $dbFile . '.bak';

// 1. Backup database.php
if (file_exists($dbFile)) {
    copy($dbFile, $bakFile);
    echo "✔ Backed up database credentials.\n";
}

// 2. Discard all local changes
echo "Executing: git reset --hard HEAD\n";
$resetOutput = shell_exec('git reset --hard HEAD 2>&1');
echo trim($resetOutput) . "\n";
echo "----------------------------------------\n";

// 3. Restore database.php
if (file_exists($bakFile)) {
    copy($bakFile, $dbFile);
    unlink($bakFile);
    echo "✔ Restored database credentials.\n";
}

// 4. Ignore local changes to database.php
echo "Executing: git update-index --assume-unchanged config/database.php\n";
$ignoreOutput = shell_exec('git update-index --assume-unchanged config/database.php 2>&1');
echo $ignoreOutput ? trim($ignoreOutput) : "Done";
echo "\n----------------------------------------\n";

// 5. Pull latest code from GitHub
echo "Executing: git pull origin main\n";
$pullOutput = shell_exec('git pull origin main 2>&1');
echo trim($pullOutput) . "\n";
echo "========================================\n";
echo "SUCCESS: Server git reset and updated to latest commit!\n";
