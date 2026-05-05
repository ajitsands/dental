<?php
// app/controllers/Dashboard.php

class Dashboard extends Controller {
    public function __construct() {
        // Auth check would go here
    }

    public function index() {
        $data = [
            'title' => 'Dashboard - DenSmart'
        ];
        $this->view('dashboard/index', $data);
    }
}
