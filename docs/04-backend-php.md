# 04. Backend PHP

## Conexión PDO (ejemplo `config/database.php`)
```php
<?php
$DB_HOST = getenv('DB_HOST') ?: '127.0.0.1';
$DB_NAME = getenv('DB_NAME') ?: 'sistema_prestamos';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4", $DB_USER, $DB_PASS, $options);
} catch (Throwable $e) {
    http_response_code(500);
    echo "Error de conexión";
    exit;
}
```

## Patrón Controller simple
- Cada Controller recibe `$_GET['action']` y decide método.
- Usar **prepared statements** siempre.
- Responder JSON en endpoints AJAX y HTML en vistas.

## Sesiones
- `session_start();` al inicio.
- Cookies `httponly` y `secure` bajo HTTPS.
- Regenerar ID tras login.
