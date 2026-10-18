<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
function require_login(): void {
    if (empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }
}
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function money($v): string { return '₹' . number_format((float)$v, 2); }
