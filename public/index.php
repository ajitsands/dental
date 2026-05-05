<?php
// public/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Use absolute paths based on the current directory
define('APPROOT', dirname(__DIR__));

require_once APPROOT . '/config/config.php';
require_once APPROOT . '/config/database.php';
require_once APPROOT . '/app/helpers/session_helper.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    $file = APPROOT . '/app/core/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize Router
$init = new Router();
