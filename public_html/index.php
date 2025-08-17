<?php
// public_html/index.php – Front Controller
declare(strict_types=1);
ini_set('display_errors', '1'); // En producción, '0'
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/private/bootstrap.php';

use Core\Router;
use Controllers\ClientesController;
use Controllers\PrestamosController;
use Controllers\AuthController;

$router = new Router();

// Web (HTML)
$router->get('/', [PrestamosController::class, 'dashboard']);
$router->get('/clientes', [ClientesController::class, 'index']);
$router->get('/clientes/crear', [ClientesController::class, 'crear']);
$router->post('/clientes', [ClientesController::class, 'store']);

$router->get('/prestamos', [PrestamosController::class, 'index']);
$router->get('/prestamos/crear', [PrestamosController::class, 'crear']);
$router->post('/prestamos', [PrestamosController::class, 'store']);

// API (JSON)
$router->get('/api/clientes', [ClientesController::class, 'apiList']);
$router->post('/api/clientes', [ClientesController::class, 'apiCreate']);

// Auth
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
