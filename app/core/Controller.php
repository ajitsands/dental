<?php
// app/core/Controller.php

class Controller {
    // Load model
    public function model($model) {
        require_once APPROOT . '/app/models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {
        if (file_exists(APPROOT . '/app/views/' . $view . '.php')) {
            require_once APPROOT . '/app/views/' . $view . '.php';
        } else {
            die('View does not exist: ' . APPROOT . '/app/views/' . $view . '.php');
        }
    }

    // Helper to check if user is logged in
    public function checkAuth() {
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit();
        }
        
        // If logged in but no branch selected, and not already on branch selection page
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        if (!isBranchSelected() && strpos($url, 'auth/selectBranch') === false && strpos($url, 'auth/logout') === false) {
            header('Location: ' . BASE_URL . '/auth/selectBranch');
            exit();
        }
    }
}
