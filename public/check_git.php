<?php
// public/check_git.php
// Diagnostic tool to check Git status and configuration on the server.
// Accessible at https://app.densmart.us/check_git.php

header('Content-Type: text/plain; charset=utf-8');

$projectRoot = dirname(__DIR__);
chdir($projectRoot);

echo "DenSmart Git Server Diagnostic\n";
echo "========================================\n";
echo "Current Working Directory: " . getcwd() . "\n";
echo "System User: " . exec('whoami') . "\n";
echo "========================================\n";

$commands = [
    'git --version',
    'git remote -v',
    'git status',
    'git log -n 1 --oneline'
];

foreach ($commands as $cmd) {
    echo "Executing: $cmd\n";
    echo "----------------------------------------\n";
    $output = shell_exec($cmd . " 2>&1");
    echo $output ? trim($output) : "No output";
    echo "\n========================================\n";
}
