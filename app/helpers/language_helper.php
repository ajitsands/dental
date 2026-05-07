<?php

/**
 * Language Helper
 * Handles multi-language support (English/Arabic)
 */

function __($key) {
    static $translations = [];
    
    // Determine language
    $lang = $_SESSION['lang'] ?? 'en';
    
    // Load translation file if not already loaded
    if (!isset($translations[$lang])) {
        $path = APPROOT . '/app/lang/' . $lang . '.php';
        if (file_exists($path)) {
            $translations[$lang] = include $path;
        } else {
            $translations[$lang] = [];
        }
    }
    
    // Return translation or the key itself if not found
    return $translations[$lang][$key] ?? $key;
}

function isRTL() {
    return ($_SESSION['lang'] ?? 'en') === 'ar';
}
