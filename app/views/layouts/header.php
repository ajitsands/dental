<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang'] ?? 'en'; ?>" dir="<?php echo isRTL() ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($data['title']) ? $data['title'] : APP_NAME; ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <?php if (isRTL()): ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <?php else: ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php endif; ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --accent-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            
            /* Light Theme Variables */
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --navbar-bg: linear-gradient(to right, #ffffff, #f8fafc);
            --sidebar-active: #f1f5f9;
        }

        [data-theme="dark"] {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-color: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --navbar-bg: #1e293b;
            --sidebar-active: #334155;
        }

        body {
            font-family: <?php echo isRTL() ? "'Cairo', sans-serif" : "'Inter', sans-serif"; ?>;
            background-color: var(--bg-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        [dir="rtl"] .ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: auto !important;
            margin-right: 0 !important;
        }

        [dir="rtl"] .text-end {
            text-align: left !important;
        }

        [dir="rtl"] .text-start {
            text-align: right !important;
        }

        [dir="rtl"] .me-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }

        [dir="rtl"] .me-3 {
            margin-left: 1rem !important;
            margin-right: 0 !important;
        }

        [dir="rtl"] .ms-2 {
            margin-right: 0.5rem !important;
            margin-left: 0 !important;
        }

        [dir="rtl"] .dropdown-menu-end {
            left: 0 !important;
            right: auto !important;
        }

        .main-content {
            flex: 1 0 auto;
        }

        footer {
            background-color: var(--card-bg);
            border-color: var(--border-color) !important;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            color: inherit;
        }

        body:not(.text-white) h1, 
        body:not(.text-white) h2, 
        body:not(.text-white) h3, 
        body:not(.text-white) h4, 
        body:not(.text-white) h5, 
        body:not(.text-white) h6 {
            color: var(--text-color);
        }

        .text-white h1, 
        .text-white h2, 
        .text-white h3, 
        .text-white h4, 
        .text-white h5, 
        .text-white h6 {
            color: #ffffff !important;
        }

        .card {
            border-color: var(--border-color);
        }

        .card:not(.bg-primary):not(.bg-success):not(.bg-danger):not(.bg-warning):not(.bg-info) {
            background-color: var(--card-bg);
        }

        .card:not(.text-white) {
            color: var(--text-color);
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar-brand, .navbar-brand i {
            color: #ffffff !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            color: var(--text-color);
        }

        .dropdown-item:hover {
            background-color: var(--sidebar-active);
            color: var(--primary-color);
        }

        .table {
            color: var(--text-color) !important;
            background-color: transparent !important;
            border-color: var(--border-color) !important;
        }

        .table tr, .table td, .table th {
            background-color: var(--card-bg) !important;
            color: var(--text-color) !important;
            border-color: var(--border-color) !important;
        }

        /* Hover and Stripe Fixes */
        .table-hover tbody tr:hover td {
            background-color: var(--sidebar-active) !important;
            color: var(--primary-color) !important;
        }

        .table-striped tbody tr:nth-of-type(odd) td {
            background-color: var(--bg-color) !important;
        }

        thead th {
            background-color: var(--sidebar-active) !important;
            color: var(--text-color) !important;
            border-bottom: 2px solid var(--border-color) !important;
        }

        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
        }

        /* Dark Mode Button Fixes */
        [data-theme="dark"] .btn-outline-secondary {
            color: #94a3b8;
            border-color: #334155;
        }
        [data-theme="dark"] .btn-outline-secondary:hover {
            background-color: #334155;
            color: #fff;
        }

        [data-theme="dark"] .dataTables_wrapper .dataTables_length,
        [data-theme="dark"] .dataTables_wrapper .dataTables_filter,
        [data-theme="dark"] .dataTables_wrapper .dataTables_info,
        [data-theme="dark"] .dataTables_wrapper .dataTables_processing,
        [data-theme="dark"] .dataTables_wrapper .dataTables_paginate {
            color: var(--text-muted);
        }

        /* Pagination Styling */
        [data-theme="dark"] .page-link {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-color);
        }
        [data-theme="dark"] .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }
        [data-theme="dark"] .page-item.disabled .page-link {
            background-color: var(--bg-color);
            border-color: var(--border-color);
            color: var(--text-muted);
        }

        .form-control, .form-select {
            background-color: var(--card-bg);
            border-color: var(--border-color);
            color: var(--text-color);
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.8rem;
        }

        /* Dashboard Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem;
        }

        /* Horizontal Menu DenSmart Logo */
        .densmart-logo {
            width: 40px;
            height: 40px;
            background: var(--accent-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 12px;
        }

        /* Arabic Support */
        body.rtl {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body class="<?php echo isset($_SESSION['lang']) && $_SESSION['lang'] == 'ar' ? 'rtl' : ''; ?>">
    
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>">
                <?php if(!empty($_SESSION['branch_logo'])): ?>
                    <div class="densmart-logo" style="background: transparent;">
                        <img src="<?php echo BASE_URL . '/' . $_SESSION['branch_logo']; ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                <?php else: ?>
                    <div class="densmart-logo">
                        <i class="fas fa-tooth"></i>
                    </div>
                <?php endif; ?>
                <?php echo $_SESSION['branch_name'] ?? APP_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php 
                    $current_url = $_SERVER['REQUEST_URI']; 
                    function isActive($path, $current_url) {
                        return (strpos($current_url, $path) !== false) ? 'active' : '';
                    }
                ?>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('/dashboard', $current_url); ?>" href="<?php echo BASE_URL; ?>/dashboard"><i class="fas fa-chart-line me-1"></i> <?php echo __('dashboard'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('/patient', $current_url); ?>" href="<?php echo BASE_URL; ?>/patient"><i class="fas fa-users me-1"></i> <?php echo __('patients'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('/appointment', $current_url); ?>" href="<?php echo BASE_URL; ?>/appointment"><i class="fas fa-calendar-alt me-1"></i> <?php echo __('appointments'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('/billing', $current_url); ?>" href="<?php echo BASE_URL; ?>/billing"><i class="fas fa-file-invoice-dollar me-1"></i> <?php echo __('billing'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('/inventory', $current_url); ?>" href="<?php echo BASE_URL; ?>/inventory"><i class="fas fa-boxes me-1"></i> <?php echo __('inventory'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('/service', $current_url); ?>" href="<?php echo BASE_URL; ?>/service"><i class="fas fa-concierge-bell me-1"></i> <?php echo __('services'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo isActive('/staff', $current_url); ?>" href="<?php echo BASE_URL; ?>/staff"><i class="fas fa-user-md me-1"></i> <?php echo __('staff'); ?></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php echo (isActive('/wallet', $current_url) || isActive('/ledger', $current_url)) ? 'active' : ''; ?>" href="#" id="finDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-wallet me-1"></i> <?php echo __('financials'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/patient/ledger"><i class="fas fa-address-book me-2"></i> <?php echo __('patient_balances'); ?></a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/wallet"><i class="fas fa-users-cog me-2"></i> <?php echo __('staff_commission'); ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item fw-bold" href="<?php echo BASE_URL; ?>/report/ledgerSummary"><i class="fas fa-file-invoice-dollar me-2"></i> Ledger Summary</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <div class="dropdown me-3">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i> <?php echo strtoupper($_SESSION['lang'] ?? 'EN'); ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/lang/set/en">English</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/lang/set/ar">Arabic (العربية)</a></li>
                        </ul>
                    </div>
                    <?php if (isset($_SESSION['impersonating_branch'])): ?>
                        <div class="alert alert-warning py-1 px-3 mb-0 me-3 d-flex align-items-center rounded-pill border-0" style="font-size: 0.85rem;">
                            <i class="fas fa-eye me-2"></i> Viewing: <strong><?php echo $_SESSION['branch_name']; ?></strong>
                            <a href="<?php echo BASE_URL; ?>/dashboard/switchBranch/global" class="btn btn-xs btn-dark ms-2 rounded-pill py-0 px-2" style="font-size: 0.75rem;">Back to Global</a>
                        </div>
                    <?php endif; ?>
                    <div class="theme-toggle me-3">
                        <button class="btn btn-link text-white opacity-75 p-0 border-0" id="themeToggle" onclick="toggleTheme()">
                            <i class="fas fa-moon fs-5" id="themeIcon"></i>
                        </button>
                    </div>

                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="profileDropdown" data-bs-toggle="dropdown">
                            <div class="densmart-logo bg-primary text-white me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                <?php echo substr($_SESSION['user_name'] ?? 'U', 0, 1); ?>
                            </div>
                            <span class="d-none d-md-inline text-white"><?php echo $_SESSION['user_name'] ?? 'User'; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/settings"><i class="fas fa-cog me-2"></i> <?php echo __('settings'); ?></a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i> <?php echo __('logout'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
        // Theme Management
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            const icon = document.getElementById('themeIcon');
            if (theme === 'dark') {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            }
        }

        function toggleTheme() {
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            setTheme(newTheme);
        }

        // Initialize theme on load
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            setTheme(savedTheme);
        })();

        // Global DataTable Language Configuration
        const dtLanguage = {
            search: "<?php echo __('dt_search'); ?>",
            lengthMenu: "<?php echo __('dt_length'); ?>",
            info: "<?php echo __('dt_info'); ?>",
            emptyTable: "<?php echo __('dt_empty'); ?>",
            zeroRecords: "<?php echo __('dt_empty'); ?>",
            paginate: {
                next: '<i class="fas fa-chevron-<?php echo isRTL() ? 'left' : 'right'; ?>"></i>',
                previous: '<i class="fas fa-chevron-<?php echo isRTL() ? 'right' : 'left'; ?>"></i>'
            }
        };
    </script>

    <div class="main-content">
        <div class="container-fluid mt-4">
    <!-- JQuery (Moved to Header for view scripts) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
