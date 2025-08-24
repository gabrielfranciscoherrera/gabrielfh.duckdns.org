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

// Amortización (¡mover arriba del dispatch!)
$router->get('/prestamos/amortizacion', [PrestamosController::class, 'amortizacion']);
$router->post('/prestamos/generar-cuotas', [PrestamosController::class, 'generarCuotas']);

// API (JSON)
$router->get('/api/clientes', [ClientesController::class, 'apiList']);
$router->post('/api/clientes', [ClientesController::class, 'apiCreate']);

// Auth
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Despacho final
$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');


/**
 * Front Controller del sistema (public_html/index.php).
 *
 * Este archivo centraliza todas las peticiones entrantes al servidor
 * y las redirige al controlador correspondiente mediante el enrutador.
 *
 * Flujo principal:
 * 1. Configura la visualización de errores y el nivel de reporte (desarrollo/producción).
 * 2. Define la constante BASE_PATH para ubicar correctamente los recursos del proyecto.
 * 3. Carga el bootstrap de la aplicación para inicializar dependencias y configuraciones.
 * 4. Crea una instancia del Router.
 * 5. Registra las rutas disponibles, separadas en secciones:
 *    - Web (HTML): rutas para vistas principales de clientes y préstamos.
 *    - Amortización: rutas específicas para generación de cuotas.
 *    - API (JSON): endpoints para manejar datos vía JSON (ej. clientes).
 *    - Auth: rutas de autenticación (login, logout).
 * 6. Ejecuta el método dispatch() para resolver la ruta solicitada,
 *    en función del método HTTP y la URI recibida.
 *
 * En resumen:
 * Este archivo actúa como el punto de entrada único de la aplicación,
 * asegurando un control centralizado de las solicitudes y respuestas.
 */
