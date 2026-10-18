CREATE DATABASE IF NOT EXISTS payroll_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE payroll_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('Admin','HR') NOT NULL DEFAULT 'Admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  employee_code VARCHAR(30) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  dob DATE DEFAULT NULL,
  email VARCHAR(120) DEFAULT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  address VARCHAR(255) DEFAULT NULL,
  department VARCHAR(80) NOT NULL,
  designation VARCHAR(80) NOT NULL,
  joining_date DATE NOT NULL,
  basic_salary DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  employee_id INT NOT NULL,
  attendance_date DATE NOT NULL,
  status ENUM('Present','Absent','Leave') NOT NULL DEFAULT 'Present',
  remarks VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_attendance_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  UNIQUE KEY unique_employee_day (employee_id, attendance_date)
);

CREATE TABLE IF NOT EXISTS leave_records (
  id INT AUTO_INCREMENT PRIMARY KEY,
  employee_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  leave_type VARCHAR(60) NOT NULL DEFAULT 'Casual',
  status ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  reason VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_leave_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS payroll (
  id INT AUTO_INCREMENT PRIMARY KEY,
  employee_id INT NOT NULL,
  pay_month CHAR(7) NOT NULL,
  basic_salary DECIMAL(10,2) NOT NULL,
  allowance DECIMAL(10,2) NOT NULL DEFAULT 0,
  bonus DECIMAL(10,2) NOT NULL DEFAULT 0,
  deduction DECIMAL(10,2) NOT NULL DEFAULT 0,
  unpaid_days INT NOT NULL DEFAULT 0,
  unpaid_deduction DECIMAL(10,2) NOT NULL DEFAULT 0,
  net_salary DECIMAL(10,2) NOT NULL,
  payment_status ENUM('Pending','Paid') NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payroll_employee FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
  UNIQUE KEY unique_employee_month (employee_id, pay_month)
);

-- Demo account: username admin / password admin123
INSERT IGNORE INTO users (username, password_hash, role)
VALUES ('admin', '$2y$12$16u9EbmdoGAR2IRFkln3xeCgnWNVJhcxfe1MfTJA.BC7Xuw7Dx.Rm', 'Admin');

INSERT IGNORE INTO employees (employee_code, full_name, dob, email, phone, address, department, designation, joining_date, basic_salary)
VALUES ('EMP001', 'Aarav Patel', '2001-05-12', 'aarav@example.com', '9876543210', 'Vadodara, Gujarat', 'IT', 'Software Trainee', '2025-06-01', 25000.00);
