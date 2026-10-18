<?php
require_once __DIR__.'/includes/db.php'; require_once __DIR__.'/includes/auth.php';
if (!empty($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $stmt=$pdo->prepare('SELECT id,username,password_hash FROM users WHERE username=?'); $stmt->execute([trim($_POST['username']??'')]); $u=$stmt->fetch();
  if ($u && password_verify($_POST['password']??'', $u['password_hash'])) {
    session_regenerate_id(true); $_SESSION['user_id']=$u['id']; $_SESSION['username']=$u['username']; header('Location: dashboard.php'); exit;
  } $error='Invalid username or password.';
}
$page_title='Login | EIPS'; require __DIR__.'/includes/header.php'; ?>
<section class="login-wrap"><div class="login-card"><div class="eyebrow">EMPLOYEE INFORMATION & PAYROLL</div><h1>Welcome back</h1><p class="muted">Manage employee records, attendance and salary processing in one place.</p>
<?php if($error):?><div class="alert error"><?=e($error)?></div><?php endif;?>
<form method="post" class="form-stack"><label>Username<input name="username" required placeholder="Enter username"></label><label>Password<input type="password" name="password" required placeholder="Enter password"></label><button class="btn primary full">Sign in</button></form>
<div class="demo-note"><strong>Demo login</strong><br>Username: admin<br>Password: admin123</div></div></section>
<?php require __DIR__.'/includes/footer.php'; ?>
