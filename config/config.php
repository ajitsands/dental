<?php
// config/config.php

define('APP_NAME', 'DenSmart');
define('BASE_URL', 'http://localhost:8080');
define('CURRENCY_SYMBOL', '₹');
define('APP_TITLE', 'DenSmart Dental Clinic');

// Session configuration
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
