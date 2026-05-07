<?php

class Lang extends Controller {
    public function set($lang) {
        // Validate language
        $allowed = ['en', 'ar'];
        if (in_array($lang, $allowed)) {
            $_SESSION['lang'] = $lang;
        }
        
        // Redirect back to previous page
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL;
        header('Location: ' . $referer);
    }
}
