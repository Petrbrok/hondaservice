<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

hs_admin_check_csrf();

$pdo = hs_admin_db_or_fail();
$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

$stmt = $pdo->prepare('SELECT * FROM hs_new_admin_users WHERE username = ? AND is_active = 1 LIMIT 1');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, (string) $user['password_hash'])) {
    hs_admin_flash('Неверный логин или пароль.');
    hs_admin_redirect('index.php');
}

$_SESSION['hs_admin_user_id'] = (int) $user['id'];
$_SESSION['hs_admin_username'] = (string) $user['username'];
hs_admin_redirect('index.php');
