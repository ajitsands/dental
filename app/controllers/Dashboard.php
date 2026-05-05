<?php
// app/controllers/Dashboard.php

class Dashboard extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - DenSmart',
            'branch_name' => $_SESSION['branch_name'] ?? 'Unknown Branch'
        ];
        $this->view('dashboard/index', $data);
    }
}
