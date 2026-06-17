-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 08, 2026 at 05:58 PM
-- Server version: 8.0.17
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `densmart_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `chair_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('Booked','Confirmed','Reported','In Consultation','Completed','Cancelled') DEFAULT 'Booked',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `user_id`, `chair_id`, `start_time`, `end_time`, `status`, `notes`, `created_at`, `branch_id`) VALUES
(4, 6, 1, 1, '2026-05-04 10:00:00', '2026-05-04 11:00:00', 'Completed', 'Nothing', '2026-05-05 19:48:07', 1),
(5, 6, 1, 1, '2026-05-06 12:00:00', '2026-05-06 13:00:00', 'Booked', '', '2026-05-05 19:50:03', 1),
(6, 7, 1, 1, '2026-05-05 12:00:00', '2026-05-05 13:00:00', 'Booked', 'Check Up', '2026-05-06 20:25:38', 1),
(7, 7, 6, 2, '2026-05-08 09:00:00', '2026-05-08 10:00:00', 'Booked', 'For Checkup', '2026-05-07 07:37:00', 3),
(8, 7, 1, 1, '2026-05-03 09:00:00', '2026-05-03 10:00:00', 'Completed', 'Root Canal', '2026-05-07 09:54:15', 1),
(9, 6, 1, 1, '2026-05-07 09:00:00', '2026-05-07 10:00:00', 'Completed', '', '2026-05-07 10:18:47', 1),
(10, 7, 1, 1, '2026-05-08 09:00:00', '2026-05-08 10:00:00', 'Completed', 'Folr Test', '2026-05-07 16:30:07', 1),
(11, 6, 1, 1, '2026-05-08 15:00:00', '2026-05-08 16:00:00', 'Booked', 'Testing', '2026-05-07 16:40:38', 1),
(12, 7, 1, 1, '2026-05-09 14:00:00', '2026-05-09 15:00:00', 'Booked', 'Sample', '2026-05-07 16:52:07', 1),
(13, 7, 1, 1, '2026-05-10 14:00:00', '2026-05-10 15:00:00', 'Booked', 'Sample', '2026-05-07 16:54:02', 1),
(14, 7, 1, 1, '2026-05-08 09:00:00', '2026-05-08 10:00:00', 'Completed', 'Ckeaning', '2026-05-08 07:28:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `address` text,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT 'Asia/Kolkata',
  `tax_type` enum('GST','VAT') DEFAULT 'GST',
  `tax_pct` decimal(5,2) DEFAULT '18.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `commission_model` enum('service','individual') DEFAULT 'service'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `logo`, `address`, `contact`, `email`, `tax_number`, `country`, `timezone`, `tax_type`, `tax_pct`, `created_at`, `commission_model`) VALUES
(1, 'Rifa Branch', 'uploads/logos/logo_1_1778151184.png', 'Chandanam Block, Infopark, Koratty\r\nKorattyPO, Koratty', '08891376774', 'anisha.icara@gmail.com', '11256633325', 'Bahrain', 'Asia/Bahrain', 'VAT', 10.00, '2026-05-05 19:36:27', 'service'),
(2, 'Koratty Branch', NULL, 'Notning', '9895016611', 'care@sandslab.com', '658556222', 'Saudi Arabia', 'Asia/Riyadh', 'VAT', 10.00, '2026-05-05 19:52:33', 'service'),
(3, 'Manama Branch', NULL, 'Manama, Kingdom Of Bahrain', '2563225', 'mamama@densmart.com', '155524522', 'Bahrain', 'Asia/Bahrain', 'VAT', 10.00, '2026-05-07 07:18:37', 'service');

-- --------------------------------------------------------

--
-- Table structure for table `chairs`
--

CREATE TABLE `chairs` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chairs`
--

INSERT INTO `chairs` (`id`, `branch_id`, `name`) VALUES
(1, 1, 'Chair 1'),
(2, 3, 'Chair 1');

-- --------------------------------------------------------

--
-- Table structure for table `dental_charts`
--

CREATE TABLE `dental_charts` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `tooth_number` int(11) NOT NULL,
  `condition_name` varchar(50) NOT NULL,
  `notes` text,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `surfaces` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dental_charts`
--

INSERT INTO `dental_charts` (`id`, `patient_id`, `tooth_number`, `condition_name`, `notes`, `updated_at`, `surfaces`) VALUES
(1, 7, 7, 'filling', '', '2026-05-07 10:38:50', 'C'),
(2, 7, 28, 'crown', '', '2026-05-07 10:21:05', ''),
(3, 7, 18, 'filling', '', '2026-05-07 10:32:33', ',C'),
(4, 6, 2, 'extraction', 'Because of Hevy Pain it has been Removed ', '2026-05-07 10:23:17', ''),
(5, 7, 5, 'healthy', '', '2026-05-07 10:33:05', ''),
(17, 7, 8, 'root-canal', '', '2026-05-07 19:24:27', ',R,L,C'),
(18, 7, 23, 'braces', '', '2026-05-07 10:34:24', 'C,R,B,L,T'),
(20, 7, 12, 'filling', '', '2026-05-07 16:46:20', 'R'),
(28, 7, 9, 'crown', '', '2026-05-07 19:24:36', 'C'),
(29, 7, 10, 'extraction', '', '2026-05-08 07:34:55', 'C'),
(30, 7, 61, 'implant', '', '2026-05-08 07:35:19', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT '0',
  `unit` varchar(20) DEFAULT NULL,
  `low_stock_threshold` int(11) DEFAULT '5',
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `branch_id`, `item_name`, `category`, `quantity`, `unit`, `low_stock_threshold`, `last_updated`) VALUES
(1, 1, 'Siring', 'Clinical Supplies', 110, 'Box', 5, '2026-05-07 17:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('add','consume') NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `branch_id`, `inventory_id`, `patient_id`, `user_id`, `type`, `quantity`, `notes`, `created_at`) VALUES
(1, 1, 1, 0, 1, 'add', 50.00, 'Purchased from Rifa', '2026-05-07 17:07:24'),
(2, 1, 1, 6, 1, 'consume', 5.00, 'For the Patient', '2026-05-07 17:07:40'),
(3, 1, 1, 0, 1, 'add', 20.00, 'New Item Received', '2026-05-07 17:10:25'),
(4, 1, 1, 6, 1, 'consume', 5.00, 'For Testing', '2026-05-07 17:10:37'),
(5, 1, 1, 6, 1, 'consume', 10.00, 'Sample', '2026-05-07 17:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `final_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('Unpaid','Partially Paid','Paid') DEFAULT 'Unpaid',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `doctor_id` int(11) DEFAULT NULL,
  `assistant_id` int(11) DEFAULT NULL,
  `technician_id` int(11) DEFAULT NULL,
  `nurse_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `branch_id`, `patient_id`, `invoice_number`, `total_amount`, `discount`, `tax_amount`, `final_amount`, `status`, `created_at`, `doctor_id`, `assistant_id`, `technician_id`, `nurse_id`) VALUES
(1, 1, 6, 'INV-2026-2594', 7000.00, 0.00, 1260.00, 8260.00, 'Paid', '2026-05-06 20:13:24', 2, NULL, 4, 5),
(2, 1, 7, 'INV-2026-1093', 7000.00, 0.00, 1260.00, 8260.00, 'Paid', '2026-05-07 07:21:39', 2, NULL, 4, 0),
(3, 1, 6, 'INV-2026-9315', 5000.00, 0.00, 900.00, 5900.00, 'Paid', '2026-05-07 07:32:48', 2, NULL, 4, 5),
(4, 1, 6, 'INV-2026-6639', 2000.00, 0.00, 360.00, 2360.00, 'Paid', '2026-05-07 07:34:46', 2, NULL, 4, 5),
(5, 2, 6, 'INV-2026-6996', 250.00, 0.00, 45.00, 295.00, 'Paid', '2026-05-07 07:46:10', 8, NULL, 9, 0),
(6, 2, 7, 'INV-2026-2450', 250.00, 0.00, 45.00, 295.00, 'Paid', '2026-05-07 07:48:25', 8, NULL, 0, 0),
(7, 2, 7, 'INV-2026-5639', 250.00, 0.00, 25.00, 275.00, 'Paid', '2026-05-07 10:12:05', 8, NULL, 9, 0),
(8, 1, 7, 'INV-2026-9131', 2000.00, 0.00, 200.00, 2200.00, 'Paid', '2026-05-08 07:31:56', 2, NULL, 0, 0),
(10, 1, 6, 'INV-2026-3761', 5000.00, 0.00, 500.00, 5500.00, 'Paid', '2026-05-08 09:59:05', 2, NULL, 0, 0),
(12, 1, 6, 'INV-2026-9316', 5000.00, 0.00, 500.00, 5500.00, 'Paid', '2026-05-08 10:04:18', 2, NULL, 0, 0),
(14, 1, 6, 'INV-2026-9317', 5000.00, 0.00, 500.00, 5500.00, 'Paid', '2026-05-08 10:47:41', 2, NULL, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT '1',
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `service_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 4, 1, 2000.00, 2000.00),
(2, 1, 3, 1, 5000.00, 5000.00),
(3, 2, 4, 1, 2000.00, 2000.00),
(4, 3, 3, 1, 5000.00, 5000.00),
(5, 4, 4, 1, 2000.00, 2000.00),
(6, 5, 5, 1, 250.00, 250.00),
(7, 6, 5, 1, 250.00, 250.00),
(8, 7, 5, 1, 250.00, 250.00),
(9, 8, 4, 1, 2000.00, 2000.00),
(12, 10, 3, 1, 5000.00, 5000.00),
(14, 12, 3, 1, 5000.00, 5000.00),
(16, 14, 3, 1, 5000.00, 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `unique_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `medical_history` text,
  `dental_history` text,
  `medical_alerts` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `branch_id`, `unique_id`, `name`, `age`, `gender`, `contact`, `email`, `photo`, `medical_history`, `dental_history`, `medical_alerts`, `created_at`) VALUES
(6, 1, 'P-4629', 'Ajit K.V', 12, 'Male', '+918075501121', 'ajitsands@gmail.com', NULL, 'Diabetes, Allergies, BP, etc', 'Notnig', '', '2026-05-05 19:36:27'),
(7, 2, 'P-2908', 'Anisha', 32, 'Female', '9895016611', 'care@sandslab.com', NULL, 'She has Diabetes, and Pressure readed 120 160 omn  07-05-2026', 'L2 Rootcanal', '', '2026-05-06 19:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_mode` enum('Cash','Card','UPI','Benefit') DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `amount`, `payment_mode`, `transaction_date`) VALUES
(1, 1, 8260.00, 'Cash', '2026-05-06 20:13:40'),
(2, 2, 8260.00, 'Cash', '2026-05-07 07:22:14'),
(3, 3, 5900.00, 'Cash', '2026-05-07 07:34:12'),
(4, 6, 295.00, 'Cash', '2026-05-07 07:48:36'),
(5, 4, 2360.00, 'Card', '2026-05-07 08:52:13'),
(6, 7, 275.00, 'Cash', '2026-05-07 10:12:12'),
(7, 5, 295.00, 'Cash', '2026-05-07 10:12:16'),
(8, 8, 2200.00, 'Cash', '2026-05-08 07:32:04'),
(10, 12, 4000.00, 'Cash', '2026-05-08 10:37:57'),
(11, 12, 500.00, 'Cash', '2026-05-08 10:46:21'),
(12, 12, 500.00, 'Cash', '2026-05-08 10:46:35'),
(13, 12, 5500.00, 'Card', '2026-05-08 10:46:54'),
(14, 14, 5500.00, 'Cash', '2026-05-08 11:04:09'),
(15, 10, 5500.00, 'Cash', '2026-05-08 11:04:22');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `medicines` text,
  `instructions` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`id`, `appointment_id`, `patient_id`, `doctor_id`, `medicines`, `instructions`, `created_at`) VALUES
(1, 4, 6, 1, 'M2 1-0-1\r\nAmoxin 0-1-0', 'Sofft Dite for 3 Days Dont Take Hard Foods', '2026-05-06 20:51:17'),
(2, 8, 7, 1, '1) Amoxin  1-0-1\r\n2) Parasatamol 1-1-1\r\n', '1) Soft Dite for 4 Days ', '2026-05-07 09:56:19'),
(3, 10, 7, 1, 'Amoxin Tab 1-0-1\r\nParacetamol 1-0-1', 'Tak rest for 3 Days', '2026-05-07 16:44:35'),
(4, 9, 6, 1, 'Noting ot', '', '2026-05-07 16:47:45'),
(5, 14, 7, 1, 'Paraa centamol 1-0-1', 'OK Fine', '2026-05-08 07:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `procedures`
--

CREATE TABLE `procedures` (
  `id` int(11) NOT NULL,
  `treatment_plan_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `description` text,
  `cost` decimal(10,2) DEFAULT NULL,
  `notes` text,
  `before_image` varchar(255) DEFAULT NULL,
  `after_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Dentist'),
(5, 'Nurse'),
(3, 'Receptionist'),
(6, 'Super Admin'),
(4, 'Technician');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `doc_comm_pct` decimal(5,2) DEFAULT '0.00',
  `tech_comm_pct` decimal(5,2) DEFAULT '0.00',
  `nurse_comm_pct` decimal(5,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `branch_id`, `name`, `cost`, `doc_comm_pct`, `tech_comm_pct`, `nurse_comm_pct`, `created_at`, `status`) VALUES
(2, 2, '', 5000.00, 30.00, 10.00, 5.00, '2026-05-06 18:57:27', 'Active'),
(3, 1, 'Root Canel', 5000.00, 50.00, 10.00, 10.00, '2026-05-06 18:59:59', 'Active'),
(4, 1, 'Cleaning', 2000.00, 50.00, 20.00, 10.00, '2026-05-06 19:00:29', 'Active'),
(5, 2, 'Cleaning and Polishing', 250.00, 50.00, 10.00, 10.00, '2026-05-07 07:45:49', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `service_inventory`
--

CREATE TABLE `service_inventory` (
  `id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  `quantity_used` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tooth_chart`
--

CREATE TABLE `tooth_chart` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `tooth_number` int(11) NOT NULL,
  `condition_name` varchar(100) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treatment_plans`
--

CREATE TABLE `treatment_plans` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `status` enum('Draft','Accepted','Completed') DEFAULT 'Draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `commission_pct` decimal(5,2) DEFAULT '0.00',
  `wallet_balance` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `role_id`, `name`, `email`, `password`, `phone`, `status`, `created_at`, `commission_pct`, `wallet_balance`) VALUES
(1, 1, 1, 'Administrator', 'admin@densmart.com', '$2y$10$wDYITOamHObd/W4ZW9uTuuJHCmoOjtWyiEvLiSdXDBXLPiVdZWevu', NULL, 'active', '2026-05-05 19:42:03', 0.00, 0.00),
(2, 1, 2, 'Ancy', 'ancy@sandslab.com', '$2y$10$zcya3oNXiiMnAwjZwbrIme/wYdUg1i5qHIwlyl6JaNpBVBlDffFVG', '9895765626', 'active', '2026-05-06 19:11:02', 50.00, 0.00),
(3, 1, 3, 'Ajit', 'ajit@sandslab.com', '$2y$10$uhwcBYFXXRnjJrSloqE0HuaDMjzd/.G7AMLC.QYBLQc1GHelSj8k6', '9895662233', 'active', '2026-05-06 19:11:33', 10.00, 0.00),
(4, 1, 4, 'Anisha', 'anisha@sandslab.com', '$2y$10$mtA65lMw/O0qjRhkIgDSWOccTCy7ee71iKu/Beky3T7vWHjDAJVc6', '985662563', 'active', '2026-05-06 19:12:02', 10.00, 1800.00),
(5, 1, 5, 'Ebine Rose', 'ebine@sandslab.com', '$2y$10$n7y.UTRRCq0abRE7Q0oPwu/iL8NLOx9OBcnnFhw/DOA..267GnjL2', '985562242', 'active', '2026-05-06 19:12:33', 20.00, 1400.00),
(6, 1, 6, 'Super admin', 'superadmin@sandslab.com', '$2y$10$Hl6AKx8biQbWoEoIVGo0xOXSJI9qaIaCjk7O/RLL2ZL6c.UR3xCiq', '9895765626', 'active', '2026-05-06 19:42:03', 0.00, 0.00),
(7, 2, 1, 'Staff Koratty 1', 'staf@koratty.com', '$2y$10$JsbmzKaUh.z6.A2wjLPDKeABdntioTxb3PciDGGg3dXn7.pGlOF8C', '', 'active', '2026-05-06 19:58:59', 0.00, 0.00),
(8, 2, 2, 'Kumar', 'kumar@koratty.com', '$2y$10$31koHlEasnGBNSiV6vMwteyyINXDwh7QOOKfCoUAAxZehz49Ek1z6', '989501661', 'active', '2026-05-06 19:59:35', 50.00, 250.00),
(9, 2, 4, 'Tech Sanu', 'tech1@koratty.com', '$2y$10$NVn1nekrWig0HCp0mxnYw.l2/4el6Pskny64sEJiel3zrvt/h5SWK', '', 'active', '2026-05-06 20:00:06', 20.00, 50.00),
(10, 2, 3, 'Anagha', 'anaga@sandslab.com', '$2y$10$PIlQz1ONrHhW.6B2cGQI8e72ImI7lJ6bWKa1s2kDwx0s/KrIpW56i', '+918891376774', 'active', '2026-05-07 08:12:38', 0.00, 0.00),
(11, 2, 3, 'Anagha', 'anagaha@sandslab.com', '$2y$10$OA8Dp8eL53d1RszWSSb0Q.knacs/lxYZ3qr71fsBTdxBkg3cyWX0m', '+918891376774', 'active', '2026-05-07 08:13:25', 0.00, 0.00),
(12, 3, 2, 'Sinu KK', 'sinu@sandslab.com', '$2y$10$9y3nqtRZ/LebIk0gF8zVwuP8ceh.MMT2Jqfwkba25O9NvzqKf4I56', '+918075033808', 'active', '2026-05-07 10:09:08', 50.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('Credit','Debit') NOT NULL,
  `description` text,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `user_id`, `amount`, `type`, `description`, `reference_id`, `created_at`) VALUES
(1, 2, 3500.00, 'Credit', 'Commission from Invoice #INV-2026-2594', 1, '2026-05-06 20:13:40'),
(2, 4, 900.00, 'Credit', 'Technician Commission from Invoice #INV-2026-2594', 1, '2026-05-06 20:13:40'),
(3, 5, 700.00, 'Credit', 'Nurse Commission from Invoice #INV-2026-2594', 1, '2026-05-06 20:13:40'),
(4, 4, 500.00, 'Debit', 'Commission', NULL, '2026-05-06 20:29:30'),
(5, 2, 2500.00, 'Credit', 'Commission from Invoice #INV-2026-9315', 3, '2026-05-07 07:34:12'),
(6, 4, 500.00, 'Credit', 'Technician Commission from Invoice #INV-2026-9315', 3, '2026-05-07 07:34:12'),
(7, 5, 500.00, 'Credit', 'Nurse Commission from Invoice #INV-2026-9315', 3, '2026-05-07 07:34:12'),
(8, 2, 5000.00, 'Debit', 'Setile Ment Up to 07-05-2026', NULL, '2026-05-07 07:35:41'),
(9, 8, 125.00, 'Credit', 'Commission from Invoice #INV-2026-2450', 4, '2026-05-07 07:48:36'),
(10, 2, 1000.00, 'Credit', 'Commission from Invoice #INV-2026-6639', 5, '2026-05-07 08:52:13'),
(11, 4, 400.00, 'Credit', 'Technician Commission from Invoice #INV-2026-6639', 5, '2026-05-07 08:52:13'),
(12, 5, 200.00, 'Credit', 'Nurse Commission from Invoice #INV-2026-6639', 5, '2026-05-07 08:52:13'),
(13, 8, 125.00, 'Debit', 'Paid All', NULL, '2026-05-07 10:10:51'),
(14, 8, 125.00, 'Credit', 'Commission from Invoice #INV-2026-5639', 6, '2026-05-07 10:12:12'),
(15, 9, 25.00, 'Credit', 'Technician Commission from Invoice #INV-2026-5639', 6, '2026-05-07 10:12:12'),
(16, 8, 125.00, 'Credit', 'Commission from Invoice #INV-2026-6996', 7, '2026-05-07 10:12:16'),
(17, 9, 25.00, 'Credit', 'Technician Commission from Invoice #INV-2026-6996', 7, '2026-05-07 10:12:16'),
(18, 2, 1000.00, 'Credit', 'Commission from Invoice #INV-2026-9131', 8, '2026-05-08 07:32:04'),
(20, 2, 4000.00, 'Debit', 'Payment', NULL, '2026-05-08 07:33:50'),
(21, 2, 1818.18, 'Credit', 'Commission from Invoice #INV-2026-9316', 10, '2026-05-08 10:37:57'),
(22, 2, 227.27, 'Credit', 'Commission from Invoice #INV-2026-9316', 11, '2026-05-08 10:46:21'),
(23, 2, 227.27, 'Credit', 'Commission from Invoice #INV-2026-9316', 12, '2026-05-08 10:46:35'),
(24, 2, 2500.00, 'Credit', 'Commission from Invoice #INV-2026-9316', 13, '2026-05-08 10:46:54'),
(25, 2, 2500.00, 'Credit', 'Commission from Invoice #INV-2026-9317', 14, '2026-05-08 11:04:09'),
(26, 4, 500.00, 'Credit', 'Technician Commission from Invoice #INV-2026-9317', 14, '2026-05-08 11:04:09'),
(27, 2, 2500.00, 'Credit', 'Commission from Invoice #INV-2026-3761', 15, '2026-05-08 11:04:22'),
(28, 2, 8772.72, 'Debit', 'Cash Payout', NULL, '2026-05-08 11:05:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `chair_id` (`chair_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chairs`
--
ALTER TABLE `chairs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `dental_charts`
--
ALTER TABLE `dental_charts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_id` (`patient_id`,`tooth_number`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `procedures`
--
ALTER TABLE `procedures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treatment_plan_id` (`treatment_plan_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indexes for table `service_inventory`
--
ALTER TABLE `service_inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `inventory_id` (`inventory_id`);

--
-- Indexes for table `tooth_chart`
--
ALTER TABLE `tooth_chart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `treatment_plans`
--
ALTER TABLE `treatment_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chairs`
--
ALTER TABLE `chairs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dental_charts`
--
ALTER TABLE `dental_charts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `procedures`
--
ALTER TABLE `procedures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_inventory`
--
ALTER TABLE `service_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tooth_chart`
--
ALTER TABLE `tooth_chart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `treatment_plans`
--
ALTER TABLE `treatment_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`chair_id`) REFERENCES `chairs` (`id`);

--
-- Constraints for table `chairs`
--
ALTER TABLE `chairs`
  ADD CONSTRAINT `chairs_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`);

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `prescriptions_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `procedures`
--
ALTER TABLE `procedures`
  ADD CONSTRAINT `procedures_ibfk_1` FOREIGN KEY (`treatment_plan_id`) REFERENCES `treatment_plans` (`id`),
  ADD CONSTRAINT `procedures_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Constraints for table `service_inventory`
--
ALTER TABLE `service_inventory`
  ADD CONSTRAINT `service_inventory_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `service_inventory_ibfk_2` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`);

--
-- Constraints for table `tooth_chart`
--
ALTER TABLE `tooth_chart`
  ADD CONSTRAINT `tooth_chart_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `treatment_plans`
--
ALTER TABLE `treatment_plans`
  ADD CONSTRAINT `treatment_plans_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
