<?php
// app/controllers/Inventory.php

class Inventory extends Controller {
    public function index() {
        $data = ['title' => 'Inventory Management - DenSmart'];
        $this->view('inventory/index', $data);
    }
}
