<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

hs_admin_require_login();
hs_admin_check_csrf();

$pdo = hs_admin_db_or_fail();
$action = (string) ($_POST['action'] ?? '');
$defaults = hs_default_state();

if ($action === 'seed_defaults') {
    foreach ($defaults['settings'] as $key => $value) {
        hs_admin_upsert($pdo, 'hs_new_settings', 'setting_key', 'setting_value', $key, (string) $value);
    }
    foreach ($defaults['content'] as $key => $value) {
        hs_admin_upsert($pdo, 'hs_new_site_content', 'content_key', 'content_value', $key, (string) $value);
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM hs_new_services')->fetchColumn();
    if ($count === 0) {
        $stmt = $pdo->prepare('INSERT INTO hs_new_services (title, description, sort_order) VALUES (?, ?, ?)');
        foreach ($defaults['services'] as $index => $service) {
            $stmt->execute([$service['title'], $service['description'], ($index + 1) * 10]);
        }
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM hs_new_prices')->fetchColumn();
    if ($count === 0) {
        $stmt = $pdo->prepare('INSERT INTO hs_new_prices (title, price, sort_order) VALUES (?, ?, ?)');
        foreach ($defaults['prices'] as $index => $price) {
            $stmt->execute([$price['title'], $price['price'], ($index + 1) * 10]);
        }
    }

    hs_admin_flash('Дефолтные значения добавлены.');
    hs_admin_redirect('index.php');
}

if ($action === 'save_settings_content') {
    foreach (($_POST['settings'] ?? []) as $key => $value) {
        if (array_key_exists((string) $key, $defaults['settings'])) {
            hs_admin_upsert($pdo, 'hs_new_settings', 'setting_key', 'setting_value', (string) $key, trim((string) $value));
        }
    }
    foreach (($_POST['content'] ?? []) as $key => $value) {
        if (array_key_exists((string) $key, $defaults['content'])) {
            hs_admin_upsert($pdo, 'hs_new_site_content', 'content_key', 'content_value', (string) $key, trim((string) $value));
        }
    }

    hs_admin_flash('SEO, контакты и тексты сохранены.');
    hs_admin_redirect('index.php');
}

if ($action === 'save_service') {
    $id = (int) ($_POST['id'] ?? 0);
    $title = trim((string) ($_POST['title'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $sortOrder = (int) ($_POST['sort_order'] ?? 100);
    $isActive = (int) ($_POST['is_active'] ?? 1);

    if ($title === '' || $description === '') {
        hs_admin_flash('Заполните название и описание услуги.');
        hs_admin_redirect('index.php');
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE hs_new_services SET title = ?, description = ?, sort_order = ?, is_active = ? WHERE id = ?');
        $stmt->execute([$title, $description, $sortOrder, $isActive, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO hs_new_services (title, description, sort_order, is_active) VALUES (?, ?, ?, 1)');
        $stmt->execute([$title, $description, $sortOrder]);
    }

    hs_admin_flash('Услуга сохранена.');
    hs_admin_redirect('index.php');
}

if ($action === 'save_price') {
    $id = (int) ($_POST['id'] ?? 0);
    $title = trim((string) ($_POST['title'] ?? ''));
    $price = trim((string) ($_POST['price'] ?? ''));
    $sortOrder = (int) ($_POST['sort_order'] ?? 100);
    $isActive = (int) ($_POST['is_active'] ?? 1);

    if ($title === '' || $price === '') {
        hs_admin_flash('Заполните работу и цену.');
        hs_admin_redirect('index.php');
    }

    if ($id > 0) {
        $stmt = $pdo->prepare('UPDATE hs_new_prices SET title = ?, price = ?, sort_order = ?, is_active = ? WHERE id = ?');
        $stmt->execute([$title, $price, $sortOrder, $isActive, $id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO hs_new_prices (title, price, sort_order, is_active) VALUES (?, ?, ?, 1)');
        $stmt->execute([$title, $price, $sortOrder]);
    }

    hs_admin_flash('Цена сохранена.');
    hs_admin_redirect('index.php');
}

hs_admin_flash('Неизвестное действие.');
hs_admin_redirect('index.php');
