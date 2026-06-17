<?php
// public/deploy_webhook.php
// Deployment Webhook for Git auto-deployment (GitHub, GitLab, etc.)
// Put this in public/deploy_webhook.php

// Define a secure deployment secret key. Change this to a secure random string!
define('DEPLOY_SECRET', 'densmart_secure_deploy_token_2026');

// Specify the target branch to deploy
define('TARGET_BRANCH', 'main'); 

header('Content-Type: application/json');

// --- 1. Security Check ---
// We support both a simple URL parameter check (?token=...) AND standard header validation
$token = $_GET['token'] ?? '';
$isValid = false;

if (hash_equals(DEPLOY_SECRET, $token)) {
    $isValid = true;
}

// GitHub Signature validation (Alternative secure method)
if (!$isValid && isset($_SERVER['HTTP_X_HUB_SIGNATURE_256'])) {
    $payload = file_get_contents('php://input');
    $hash = 'sha256=' . hash_hmac('sha256', $payload, DEPLOY_SECRET);
    if (hash_equals($hash, $_SERVER['HTTP_X_HUB_SIGNATURE_256'])) {
        $isValid = true;
    }
}

// GitLab Token validation (Alternative secure method)
if (!$isValid && isset($_SERVER['HTTP_X_GITLAB_TOKEN'])) {
    if (hash_equals(DEPLOY_SECRET, $_SERVER['HTTP_X_GITLAB_TOKEN'])) {
        $isValid = true;
    }
}

if (!$isValid) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Forbidden: Invalid secret token.']);
    exit;
}

// --- 2. Decode Hook Payload (Optional Verification) ---
$payload = json_decode(file_get_contents('php://input'), true);
if ($payload && isset($payload['ref'])) {
    $branch = str_replace('refs/heads/', '', $payload['ref']);
    if ($branch !== TARGET_BRANCH) {
        echo json_encode(['status' => 'ignored', 'message' => "Push was to branch '$branch'. Deployment target is '" . TARGET_BRANCH . "'. Ignored."]);
        exit;
    }
}

// --- 3. Execute Git Pull ---
// Go up one directory to the project root directory
$projectRoot = dirname(__DIR__);
chdir($projectRoot);

// Ensure path to Git is available in your shell or specify absolute git path (e.g. /usr/bin/git)
$commands = [
    'git status 2>&1',
    'git pull origin ' . TARGET_BRANCH . ' 2>&1'
];

$output = [];
foreach ($commands as $cmd) {
    $output[] = "Command: $cmd";
    $cmdOutput = shell_exec($cmd);
    $output[] = $cmdOutput ? trim($cmdOutput) : "No output";
    $output[] = "--------------------------------------";
}

echo json_encode([
    'status' => 'success',
    'message' => 'Deployment executed successfully.',
    'project_directory' => $projectRoot,
    'branch' => TARGET_BRANCH,
    'details' => $output
]);
