-- database/schema.sql

CREATE DATABASE IF NOT EXISTS densmart_db;
USE densmart_db;

-- Branches (Tenants)
CREATE TABLE IF NOT EXISTS branches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    logo VARCHAR(255),
    address TEXT,
    contact VARCHAR(50),
    email VARCHAR(100),
    tax_number VARCHAR(50), -- VAT/GST Number
    country VARCHAR(100),
    timezone VARCHAR(100) DEFAULT 'Asia/Kolkata',
    tax_type ENUM('GST', 'VAT') DEFAULT 'GST',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO branches (id, name, country, timezone, tax_type) 
VALUES (1, 'DenSmart Main Clinic', 'India', 'Asia/Kolkata', 'GST');

-- Roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

INSERT IGNORE INTO roles (id, name) VALUES (1, 'Admin'), (2, 'Dentist'), (3, 'Receptionist');

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_id INT,
    role_id INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES branches(id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Patients
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_id INT,
    unique_id VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    age INT,
    gender ENUM('Male', 'Female', 'Other'),
    contact VARCHAR(20),
    email VARCHAR(100),
    photo VARCHAR(255),
    medical_history TEXT, -- diabetes, allergies, medications
    dental_history TEXT,
    medical_alerts TEXT, -- diabetes, BP
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES branches(id)
);

-- Chairs
CREATE TABLE IF NOT EXISTS chairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_id INT,
    name VARCHAR(50) NOT NULL,
    FOREIGN KEY (branch_id) REFERENCES branches(id)
);

-- Appointments
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    user_id INT, -- Doctor
    chair_id INT,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM('Booked', 'Confirmed', 'Completed', 'Cancelled') DEFAULT 'Booked',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (chair_id) REFERENCES chairs(id)
);

-- Tooth Chart (Odontogram)
CREATE TABLE IF NOT EXISTS tooth_chart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    tooth_number INT NOT NULL, -- 1-32 or ISO 11-48
    condition_name VARCHAR(100), -- Cavity, Filling, Extraction, Crown
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id)
);

-- Treatment Plans
CREATE TABLE IF NOT EXISTS treatment_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    name VARCHAR(255),
    total_cost DECIMAL(10, 2),
    status ENUM('Draft', 'Accepted', 'Completed') DEFAULT 'Draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id)
);

-- Procedures & Visits
CREATE TABLE IF NOT EXISTS procedures (
    id INT AUTO_INCREMENT PRIMARY KEY,
    treatment_plan_id INT,
    appointment_id INT,
    description TEXT,
    cost DECIMAL(10, 2),
    notes TEXT,
    before_image VARCHAR(255),
    after_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (treatment_plan_id) REFERENCES treatment_plans(id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Invoices
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_id INT,
    patient_id INT,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    total_amount DECIMAL(10, 2),
    discount DECIMAL(10, 2) DEFAULT 0,
    tax_amount DECIMAL(10, 2),
    final_amount DECIMAL(10, 2),
    status ENUM('Unpaid', 'Partially Paid', 'Paid') DEFAULT 'Unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES branches(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id)
);

-- Payments
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT,
    amount DECIMAL(10, 2),
    payment_mode ENUM('Cash', 'Card', 'UPI', 'Benefit'),
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id)
);

-- Inventory
CREATE TABLE IF NOT EXISTS inventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_id INT,
    item_name VARCHAR(255),
    category VARCHAR(100),
    quantity INT DEFAULT 0,
    unit VARCHAR(20),
    low_stock_threshold INT DEFAULT 5,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES branches(id)
);
-- Services & Procedures
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    branch_id INT,
    name VARCHAR(255) NOT NULL,
    cost DECIMAL(10, 2) NOT NULL,
    doc_comm_pct DECIMAL(5, 2) DEFAULT 0,
    tech_comm_pct DECIMAL(5, 2) DEFAULT 0,
    nurse_comm_pct DECIMAL(5, 2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (branch_id) REFERENCES branches(id)
);

-- Wallet Transactions
CREATE TABLE IF NOT EXISTS wallet_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    type ENUM('Credit', 'Debit') NOT NULL,
    description TEXT,
    reference_id INT, -- Payment ID
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Update users for individual commission and wallet balance
ALTER TABLE users ADD COLUMN commission_pct DECIMAL(5, 2) DEFAULT 0;
ALTER TABLE users ADD COLUMN wallet_balance DECIMAL(10, 2) DEFAULT 0;

-- Update branches for global commission model
ALTER TABLE branches ADD COLUMN commission_model ENUM('service', 'individual') DEFAULT 'service';

-- Update invoices to link performing staff
ALTER TABLE invoices ADD COLUMN doctor_id INT;
ALTER TABLE invoices ADD COLUMN assistant_id INT;
ALTER TABLE invoices ADD FOREIGN KEY (doctor_id) REFERENCES users(id);
ALTER TABLE invoices ADD FOREIGN KEY (assistant_id) REFERENCES users(id);
