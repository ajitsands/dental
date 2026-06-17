<?php
// config/config.php

if(!defined('APP_NAME')) define('APP_NAME', 'DenSmart');

if(!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
    define('BASE_URL', $protocol . $host);
}

if(!defined('CURRENCY_SYMBOL')) define('CURRENCY_SYMBOL', '₹');
if(!defined('APP_TITLE')) define('APP_TITLE', 'DenSmart Dental Clinic');


