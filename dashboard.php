<?php
require_once __DIR__.'/includes/db.php'; require_once __DIR__.'/includes/auth.php'; require_login();
$emp=(int)$pdo->query('SELECT COUNT(*) FROM employees')->fetchColumn();
$att=(int)$pdo->query("SELECT COUNT(*) FROM attendance WHERE attendance_date=CURDATE() AND status='Present'")->fetchColumn();
$pay=(int)$pdo->query('SELECT COUNT(*) FROM payroll')->fetchColumn();
$pending=(int)$pdo->query("SELECT COUNT(*) FROM payroll WHERE payment_status='Pending'")->fetchColumn();
$total=(float)$pdo->query('SELECT COALESCE(SUM(net_salary),0) FROM payroll')->fetchColumn();
$recent=$pdo->query("SELECT p.id,p.pay_month,p.net_salary,p.payment_status,e.full_name,e.employee_code FROM payroll p JOIN employees e ON e.id=p.employee_id ORDER BY p.created_at DESC LIMIT 5")->fetchAll();
$page_title='Dashboard | EIPS'; require __DIR__.'/includes/header.php'; ?>
<div class="page-heading"><div><div class="eyebrow">OVERVIEW</div><h1>Dashboard</h1><p class="muted">Welcome, <?=e($_SESSION['username']??'Admin')?>. Here is your organization snapshot.</p></div><a class="btn primary" href="payroll.php">＋ Process payroll</a></div>
<div class="stats-grid">
<div class="stat-card"><span class="stat-icon purple">♙</span><p>Total employees</p><h2><?=$emp?></h2><a href="employees.php">Manage employees →</a></div>
<div class="stat-card"><span class="stat-icon blue">✓</span><p>Present today</p><h2><?=$att?></h2><a href="attendance.php">Attendance register →</a></div>
<div class="stat-card"><span class="stat-icon green">₹</span><p>Total payroll recorded</p><h2><?=money($total)?></h2><small><?=$pay?> payroll record(s)</small></div>
<div class="stat-card"><span class="stat-icon orange">◷</span><p>Payments pending</p><h2><?=$pending?></h2><a href="payroll.php">Review payroll →</a></div></div>
<div class="quick-grid"><a class="quick-card" href="employees.php"><b>＋</b><span><strong>Employee information</strong><small>Add, edit and search employee records</small></span><span>→</span></a>
<a class="quick-card" href="attendance.php"><b>✓</b><span><strong>Attendance management</strong><small>Record present, absent and leave days</small></span><span>→</span></a>
<a class="quick-card" href="leave.php"><b>▤</b><span><strong>Leave records</strong><small>Track leave requests and decisions</small></span><span>→</span></a>
<a class="quick-card" href="reports.php"><b>▥</b><span><strong>Reports</strong><small>View employee and salary summaries</small></span><span>→</span></a></div>
<section class="panel"><div class="panel-heading"><div><h2>Recent payroll</h2><p class="muted">Latest salary records</p></div><a class="text-link" href="payroll.php">View all</a></div>
<?php if(!$recent):?><div class="empty-state"><h3>No payroll records yet</h3><p>Create a payroll record to see it here.</p></div><?php else:?><div class="table-wrap"><table><thead><tr><th>Employee</th><th>Month</th><th>Net salary</th><th>Status</th><th></th></tr></thead><tbody><?php foreach($recent as $r):?><tr><td><strong><?=e($r['full_name'])?></strong><small class="table-sub"><?=e($r['employee_code'])?></small></td><td><?=e($r['pay_month'])?></td><td><?=money($r['net_salary'])?></td><td><span class="status <?=$r['payment_status']==='Paid'?'paid':'pending'?>"><?=e($r['payment_status'])?></span></td><td><a class="text-link" href="payslip.php?id=<?=(int)$r['id']?>">Payslip</a></td></tr><?php endforeach;?></tbody></table></div><?php endif;?></section>
<?php require __DIR__.'/includes/footer.php'; ?>
