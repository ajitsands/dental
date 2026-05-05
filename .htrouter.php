<?php
// .htrouter.php
// Router script for the PHP built-in server to handle clean URLs and static files correctly.

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . '/public' . $uri;

// If it's a file that exists and is NOT a directory, serve it directly
if (file_exists($file) && !is_dir($file)) {
    return false;
}

// Otherwise, route everything to public/index.php
$_GET['url'] = ltrim($uri, '/');
require_once __DIR__ . '/public/index.php';
