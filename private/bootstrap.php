<?php
// private/bootstrap.php – arranque del sistema
declare(strict_types=1);

// Iniciar sesión segura
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
      'httponly' => true,
      'secure' => isset($_SERVER['HTTPS']),
      'samesite' => 'Lax',
    ]);
    session_start();
}

// Cargar config
require __DIR__ . '/config/app.php';
require __DIR__ . '/config/database.php';

// Autocarga simple (PSR-4 reducido)
spl_autoload_register(function ($class) {
    $prefixes = [
        'Core\\' => __DIR__ . '/Core/',
        'Controllers\\' => __DIR__ . '/Controllers/',
        'Models\\' => __DIR__ . '/Models/',
    ];
    foreach ($prefixes as $prefix => $dir) {
        if (str_starts_with($class, $prefix)) {
            $rel = substr($class, strlen($prefix));
            $file = $dir . str_replace('\\', '/', $rel) . '.php';
            if (is_file($file)) require $file;
        }
    }
});

// Exponer $pdo global (opcional)
global $pdo;
$pdo = (function () {
    return DB\connect(); // ver config/database.php
})();
