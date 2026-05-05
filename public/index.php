<?php
// public/index.php

require_once '../config/config.php';
require_once '../config/database.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    require_once '../app/core/' . $className . '.php';
});

// Initialize Router
$init = new Router();
