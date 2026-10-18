<?php
require_once __DIR__.'/includes/db.php'; require_once __DIR__.'/includes/auth.php'; require_login();
$error='';$success='';$edit=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
 $act=$_POST['action']??'';
 if($act==='save'){
  $id=(int)($_POST['id']??0);$code=trim($_POST['employee_code']??'');$name=trim($_POST['full_name']??'');$dob=$_POST['dob']?:null;$email=trim($_POST['email']??'');$phone=trim($_POST['phone']??'');$address=trim($_POST['address']??'');$dept=trim($_POST['department']??'');$designation=trim($_POST['designation']??'');$join=$_POST['joining_date']??'';$salary=(float)($_POST['basic_salary']??0);
  if(!$code||!$name||!$dept||!$designation||!$join||$salary<0)$error='Fill all required fields and enter a valid salary.';
  else try{
   if($id){$q=$pdo->prepare('UPDATE employees SET employee_code=?,full_name=?,dob=?,email=?,phone=?,address=?,department=?,designation=?,joining_date=?,basic_salary=? WHERE id=?');$q->execute([$code,$name,$dob,$email?:null,$phone?:null,$address?:null,$dept,$designation,$join,$salary,$id]);$success='Employee updated.';}
   else{$q=$pdo->prepare('INSERT INTO employees(employee_code,full_name,dob,email,phone,address,department,designation,joining_date,basic_salary) VALUES(?,?,?,?,?,?,?,?,?,?)');$q->execute([$code,$name,$dob,$email?:null,$phone?:null,$address?:null,$dept,$designation,$join,$salary]);$success='Employee added.';}
  }catch(PDOException $ex){$error=$ex->getCode()==='23000'?'Employee code already exists.':'Could not save employee. Check the details.';}
 }elseif($act==='delete'){$q=$pdo->prepare('DELETE FROM employees WHERE id=?');$q->execute([(int)$_POST['id']]);$success='Employee deleted.';}
}
if(isset($_GET['edit'])){$q=$pdo->prepare('SELECT * FROM employees WHERE id=?');$q->execute([(int)$_GET['edit']]);$edit=$q->fetch()?:null;}
$search=trim($_GET['q']??'');$q=$pdo->prepare('SELECT * FROM employees WHERE full_name LIKE ? OR employee_code LIKE ? OR department LIKE ? ORDER BY id DESC');$like="%$search%";$q->execute([$like,$like,$like]);$rows=$q->fetchAll();
$page_title='Employees | EIPS';require __DIR__.'/includes/header.php';?>
<div class="page-heading"><div><div class="eyebrow">PEOPLE & RECORDS</div><h1>Employee information</h1><p class="muted">Maintain employee personal and professional details.</p></div></div>
<?php if($error):?><div class="alert error"><?=e($error)?></div><?php endif;?><?php if($success):?><div class="alert success"><?=e($success)?></div><?php endif;?>
<div class="two-col"><section class="panel"><div class="panel-heading"><div><h2><?=$edit?'Edit employee':'Add employee'?></h2><p class="muted">Complete the employee information form.</p></div></div>
<form method="post" class="form-grid"><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?=(int)($edit['id']??0)?>">
<label>Employee ID / Code *<input name="employee_code" required value="<?=e($edit['employee_code']??'')?> " placeholder="EMP002"></label>
<label>Full name *<input name="full_name" required value="<?=e($edit['full_name']??'')?>" placeholder="Full name"></label>
<label>Date of birth<input type="date" name="dob" value="<?=e($edit['dob']??'')?>"></label><label>Email<input type="email" name="email" value="<?=e($edit['email']??'')?>"></label>
<label>Contact number<input name="phone" value="<?=e($edit['phone']??'')?>"></label><label>Department *<input name="department" required value="<?=e($edit['department']??'')?>" placeholder="IT"></label>
<label>Designation *<input name="designation" required value="<?=e($edit['designation']??'')?>" placeholder="Developer"></label><label>Joining date *<input type="date" name="joining_date" required value="<?=e($edit['joining_date']??date('Y-m-d'))?>"></label>
<label>Basic salary (₹) *<input type="number" min="0" step=".01" name="basic_salary" required value="<?=e($edit['basic_salary']??'')?>" placeholder="25000"></label>
<label class="span-2">Address<input name="address" value="<?=e($edit['address']??'')?>" placeholder="City, state"></label>
<div class="form-actions"><button class="btn primary"><?= $edit?'Save changes':'Add employee'?></button><?php if($edit):?><a class="btn secondary" href="employees.php">Cancel</a><?php endif;?></div></form></section>
<section class="panel"><div class="panel-heading"><div><h2>Employee directory</h2><p class="muted"><?=count($rows)?> result(s)</p></div></div><form method="get" class="searchbar"><input name="q" value="<?=e($search)?>" placeholder="Search name, ID or department"><button class="btn secondary">Search</button></form>
<?php if(!$rows):?><div class="empty-state"><p>No matching employees.</p></div><?php else:?><div class="table-wrap"><table><thead><tr><th>Employee</th><th>Department</th><th>Salary</th><th>Actions</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><strong><?=e($r['full_name'])?></strong><small class="table-sub"><?=e($r['employee_code'])?> · <?=e($r['designation'])?></small><small class="table-sub"><?=e($r['phone'])?></small></td><td><?=e($r['department'])?></td><td><?=money($r['basic_salary'])?></td><td class="actions"><a class="text-link" href="employees.php?edit=<?=(int)$r['id']?>">Edit</a><form method="post" class="inline-form" onsubmit="return confirm('Delete employee and related records?')"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=(int)$r['id']?>"><button class="link-danger">Delete</button></form></td></tr><?php endforeach;?></tbody></table></div><?php endif;?></section></div>
<?php require __DIR__.'/includes/footer.php';?>
