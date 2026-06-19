<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

hs_admin_require_login();
hs_admin_check_csrf();

$pdo = hs_admin_db_or_fail();
$id = (int) ($_POST['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare('UPDATE hs_new_images SET is_active = 0 WHERE id = ?');
    $stmt->execute([$id]);
    hs_admin_flash('Изображение скрыто. Файл физически не удалён.');
}

hs_admin_redirect('index.php');
