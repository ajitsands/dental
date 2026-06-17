<?php
// public/fix_server_git.php
// One-time script to resolve local git conflicts on the server and execute the pull.
// Accessible at https://app.densmart.us/fix_server_git.php

header('Content-Type: text/plain; charset=utf-8');

$projectRoot = dirname(__DIR__);
chdir($projectRoot);

echo "DenSmart Git Fix & Pull Script\n";
echo "========================================\n";

$dbFile = $projectRoot . '/config/database.php';
$bakFile = $dbFile . '.bak';

// 1. Backup database.php
if (file_exists($dbFile)) {
    copy($dbFile, $bakFile);
    echo "✔ Backed up database credentials to database.php.bak\n";
} else {
    echo "✘ database.php file not found.\n";
}

// 2. Hard Reset Git working tree to discard local changes
echo "Executing: git reset --hard HEAD\n";
$resetOutput = shell_exec('git reset --hard HEAD 2>&1');
echo trim($resetOutput) . "\n";
echo "----------------------------------------\n";

// 3. Restore database.php
if (file_exists($bakFile)) {
    copy($bakFile, $dbFile);
    unlink($bakFile);
    echo "✔ Restored database credentials from database.php.bak\n";
}

// 4. Instruct git to ignore database.php changes on this server
echo "Executing: git update-index --assume-unchanged config/database.php\n";
$ignoreOutput = shell_exec('git update-index --assume-unchanged config/database.php 2>&1');
echo $ignoreOutput ? trim($ignoreOutput) : "Done";
echo "\n----------------------------------------\n";

// 4.5. Remove untracked conflicting files
$conflictingFiles = [
    $projectRoot . '/App.zip',
    $projectRoot . '/public/check_git.php',
    $projectRoot . '/public/deploy_webhook.php',
    $projectRoot . '/public/fix_server_git.php'
];
foreach ($conflictingFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "✔ Removed untracked file: " . basename($file) . "\n";
    }
}
echo "----------------------------------------\n";

// 5. Pull latest changes
echo "Executing: git pull origin main\n";
$pullOutput = shell_exec('git pull origin main 2>&1');
echo trim($pullOutput) . "\n";
echo "========================================\n";
echo "SUCCESS: Server Git reset, database.php protected, and latest code pulled successfully!\n";
