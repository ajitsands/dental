<?php
// config/config.php

define('APP_NAME', 'DenSmart');
define('BASE_URL', 'http://localhost:8080'); // Update as needed

// Session configuration
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
