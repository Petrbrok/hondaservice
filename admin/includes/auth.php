<?php

declare(strict_types=1);

require_once __DIR__ . '/../../includes/site.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function hs_admin_is_logged_in(): bool
{
    return !empty($_SESSION['hs_admin_user_id']);
}

function hs_admin_require_login(): void
{
    if (!hs_admin_is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function hs_admin_csrf(): string
{
    if (empty($_SESSION['hs_admin_csrf'])) {
        $_SESSION['hs_admin_csrf'] = bin2hex(random_bytes(24));
    }

    return $_SESSION['hs_admin_csrf'];
}

function hs_admin_check_csrf(): void
{
    $token = (string) ($_POST['csrf'] ?? '');
    if (!$token || !hash_equals(hs_admin_csrf(), $token)) {
        http_response_code(403);
        exit('CSRF check failed');
    }
}

function hs_admin_flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['hs_admin_flash'] = $message;
        return null;
    }

    $value = $_SESSION['hs_admin_flash'] ?? null;
    unset($_SESSION['hs_admin_flash']);
    return $value;
}

function hs_admin_db_or_fail(): PDO
{
    $pdo = hs_db();
    if (!$pdo) {
        http_response_code(500);
        exit('Не удалось подключиться к MySQL. Проверьте config.php.');
    }

    return $pdo;
}
