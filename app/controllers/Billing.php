<?php
// app/controllers/Billing.php

class Billing extends Controller {
    public function index() {
        $data = ['title' => 'Billing & Invoices - DenSmart'];
        $this->view('billing/index', $data);
    }
}
