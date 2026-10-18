<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); $page_title=$page_title??'Employee Information and Payroll System'; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="topbar"><a class="brand" href="dashboard.php">E<span>IP</span>S</a>
<?php if (!empty($_SESSION['user_id'])): ?><nav><a href="dashboard.php">Dashboard</a><a href="employees.php">Employees</a><a href="attendance.php">Attendance</a><a href="leave.php">Leave</a><a href="payroll.php">Payroll</a><a href="reports.php">Reports</a><a href="logout.php">Logout</a></nav><?php endif; ?>
</header><main class="container">
