<?php
require_once __DIR__.'/includes/db.php';require_once __DIR__.'/includes/auth.php';require_login();
$error='';$success='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $eid=(int)($_POST['employee_id']??0);$date=$_POST['attendance_date']??'';$status=$_POST['status']??'Present';$remarks=trim($_POST['remarks']??'');
 if(!$eid||!$date||!in_array($status,['Present','Absent','Leave'],true))$error='Choose employee, date and valid status.';
 else try{$q=$pdo->prepare('INSERT INTO attendance(employee_id,attendance_date,status,remarks) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE status=VALUES(status),remarks=VALUES(remarks)');$q->execute([$eid,$date,$status,$remarks?:null]);$success='Attendance saved.';}catch(PDOException $e){$error='Could not save attendance.';}
}
$employees=$pdo->query('SELECT id,employee_code,full_name FROM employees ORDER BY full_name')->fetchAll();
$dateFilter=$_GET['date']??date('Y-m-d');$q=$pdo->prepare('SELECT a.*,e.full_name,e.employee_code FROM attendance a JOIN employees e ON e.id=a.employee_id WHERE a.attendance_date=? ORDER BY e.full_name');$q->execute([$dateFilter]);$rows=$q->fetchAll();
$page_title='Attendance | EIPS';require __DIR__.'/includes/header.php';?>
<div class="page-heading"><div><div class="eyebrow">TIME & ATTENDANCE</div><h1>Attendance register</h1><p class="muted">Record daily present, absent and leave status for each employee.</p></div></div>
<?php if($error):?><div class="alert error"><?=e($error)?></div><?php endif;?><?php if($success):?><div class="alert success"><?=e($success)?></div><?php endif;?>
<div class="two-col"><section class="panel"><div class="panel-heading"><div><h2>Mark attendance</h2><p class="muted">If a record already exists for that employee and date, it will be updated.</p></div></div>
<?php if(!$employees):?><p>Add an employee first.</p><a class="btn primary" href="employees.php">Add employee</a><?php else:?><form method="post" class="form-stack"><label>Employee *<select name="employee_id" required><option value="">Select employee</option><?php foreach($employees as $e1):?><option value="<?=(int)$e1['id']?>"><?=e($e1['employee_code'].' — '.$e1['full_name'])?></option><?php endforeach;?></select></label><label>Date *<input type="date" name="attendance_date" required value="<?=e(date('Y-m-d'))?>"></label><label>Status *<select name="status"><option>Present</option><option>Absent</option><option>Leave</option></select></label><label>Remarks<input name="remarks" placeholder="Optional notes"></label><button class="btn primary">Save attendance</button></form><?php endif;?></section>
<section class="panel"><div class="panel-heading"><div><h2>Daily attendance</h2><p class="muted"><?=e($dateFilter)?></p></div></div><form method="get" class="searchbar"><input type="date" name="date" value="<?=e($dateFilter)?>"><button class="btn secondary">View date</button></form>
<?php if(!$rows):?><div class="empty-state"><p>No attendance records for this date.</p></div><?php else:?><div class="table-wrap"><table><thead><tr><th>Employee</th><th>Status</th><th>Remarks</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><strong><?=e($r['full_name'])?></strong><small class="table-sub"><?=e($r['employee_code'])?></small></td><td><span class="status <?=strtolower($r['status'])?>"><?=e($r['status'])?></span></td><td><?=e($r['remarks'])?></td></tr><?php endforeach;?></tbody></table></div><?php endif;?></section></div>
<?php require __DIR__.'/includes/footer.php';?>
