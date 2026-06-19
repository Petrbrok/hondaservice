<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

hs_admin_require_login();
hs_admin_check_csrf();

$pdo = hs_admin_db_or_fail();

try {
    $path = hs_admin_upload_file('image');
    if (!$path) {
        throw new RuntimeException('Файл не выбран.');
    }

    $imageKey = (string) ($_POST['image_key'] ?? 'work');
    if (!in_array($imageKey, ['hero', 'work'], true)) {
        $imageKey = 'work';
    }

    if ($imageKey === 'hero') {
        $pdo->exec("UPDATE hs_new_images SET is_active = 0 WHERE image_key = 'hero'");
    } else {
        $title = trim((string) ($_POST['title'] ?? ''));
        if ($title !== '') {
            $stmt = $pdo->prepare("UPDATE hs_new_images SET is_active = 0 WHERE image_key = 'work' AND title = ?");
            $stmt->execute([$title]);
        }
    }

    $stmt = $pdo->prepare('INSERT INTO hs_new_images (image_key, title, alt, file_path, sort_order, is_active) VALUES (?, ?, ?, ?, ?, 1)');
    $stmt->execute([
        $imageKey,
        trim((string) ($_POST['title'] ?? '')),
        trim((string) ($_POST['alt'] ?? '')),
        $path,
        (int) ($_POST['sort_order'] ?? 100),
    ]);

    hs_admin_flash('Изображение загружено.');
} catch (Throwable $e) {
    hs_admin_flash($e->getMessage());
}

hs_admin_redirect('index.php');
