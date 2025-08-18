<?php
// private/config/database.php – conexión PDO a PostgreSQL
namespace DB;
use PDO;

function connect(): PDO {
    $DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
    $DB_PORT = getenv('DB_PORT') ?: '5432';
    $DB_NAME = getenv('DB_NAME') ?: 'postgres';
    $DB_USER = getenv('DB_USER') ?: 'gabriel_fh_prestamos';
    $DB_PASS = getenv('DB_PASS') ?: '';

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $dsn = "pgsql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME};options='--client_encoding=UTF8'";
    return new PDO($dsn, $DB_USER, $DB_PASS, $options);
}
