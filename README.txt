EMPLOYEE INFORMATION AND PAYROLL SYSTEM
=======================================

This website is based on your project report. It uses PHP, MySQL, HTML, CSS, and JavaScript, and is designed for local testing with XAMPP.

FUNCTIONS INCLUDED
- Admin login and dashboard
- Employee information: employee code, name, date of birth, contact details, address, department, designation, joining date, salary
- Attendance and leave records (mark Present, Absent, Leave)
- Monthly payroll calculation using basic salary, allowance, bonus, deductions, and unpaid days
- Payslip view and print/save as PDF
- Employee list report and payroll report
- MySQL relational tables and sample data

IMPORTANT SCOPE NOTE
Your report describes attendance/leave, payroll, payslips, and reports. This starter website implements the core workflows in a student-project form. Tax, PF and statutory compliance rules are not automatically applied; add and verify them with your guide before claiming production payroll compliance.

SETUP ON WINDOWS
1. Install XAMPP from https://www.apachefriends.org/
2. Open XAMPP Control Panel. Start Apache and MySQL.
3. Extract the ZIP file.
4. Copy the folder employee_information_payroll_system into C:\xampp\htdocs\
   Final path: C:\xampp\htdocs\employee_information_payroll_system
5. Open http://localhost/phpmyadmin
6. Click Import and select database.sql from this folder, then click Go.
7. Visit http://localhost/employee_information_payroll_system/

DEMO LOGIN
Username: admin
Password: admin123

If login fails after importing, check the password hash in database.sql. The hash is generated for the demo password using PHP's password_hash on common PHP versions; if needed, create a new hash with PHP password_hash('admin123', PASSWORD_DEFAULT).

LOCAL DATABASE
Default XAMPP credentials in includes/db.php:
host localhost, database payroll_db, user root, password blank.
If your local MySQL password differs, edit that file.

SHARE WITH YOUR PROFESSOR
A localhost URL works only on your laptop. For a public link, deploy to a PHP/MySQL hosting provider, create the database there, import database.sql, and update includes/db.php with the provider's database credentials. Never publish your local database credentials or use real employee data in this demo without a security review.

SAFETY
This is an academic starter project, not production payroll software. Change the default password, protect the hosting environment, make backups, and validate local tax and labour rules before any real use.
