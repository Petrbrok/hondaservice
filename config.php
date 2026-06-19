<?php

declare(strict_types=1);

const HS_DB_HOST = 'mysql_host_here';
const HS_DB_NAME = 'mysql_database_here';
const HS_DB_USER = 'mysql_username_here';
const HS_DB_PASS = 'mysql_password_here';
const HS_DB_CHARSET = 'utf8mb4';

function hs_db(): ?PDO
{
    static $pdo = null;
    static $failed = false;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    if ($failed) {
        return null;
    }

    $dsn = 'mysql:host=' . HS_DB_HOST . ';dbname=' . HS_DB_NAME . ';charset=' . HS_DB_CHARSET;

    try {
        $pdo = new PDO($dsn, HS_DB_USER, HS_DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (Throwable $e) {
        $failed = true;
        return null;
    }

    return $pdo;
}
