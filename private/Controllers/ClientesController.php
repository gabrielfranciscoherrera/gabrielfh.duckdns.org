<?php

namespace Controllers;

use Models\Cliente;
use Core\Response;

class ClientesController
{
    /**
     * Muestra la lista de clientes.
     */
    public function index(): void
    {
        global $pdo;
        $clientes = Cliente::all();
        $title = 'Lista de Clientes';
        include __DIR__ . '/../Views/clientes/index.php';
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function crear(): void
    {
        $title = 'Registrar Nuevo Cliente';
        include __DIR__ . '/../Views/clientes/crear.php';
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     */
    public function store(): void
    {
        global $pdo;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Datos mejorados con validación
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'cedula' => $_POST['cedula'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'email' => $_POST['email'] ?? ''
            ];

            try {
                Cliente::create($datos);
                // Redirigir a la lista de clientes con un mensaje de éxito
                header('Location: /clientes?success=true');
                exit;
            } catch (\PDOException $e) {
                // Manejar el error, mostrar formulario con error
                $title = 'Registrar Nuevo Cliente';
                $error = 'Error al guardar el cliente: ' . $e->getMessage();
                include __DIR__ . '/../Views/clientes/crear.php';
            }
        }
    }

    /**
     * Proporciona una lista de clientes en formato JSON para la API.
     * Acepta un parámetro de búsqueda 'q'.
     */
    public function apiList(): void
    {
        global $pdo;
        // Obtiene el término de búsqueda de la URL (?q=...)
        $search = $_GET['q'] ?? '';

        // Prepara la consulta para buscar por nombre o cédula
        // ILIKE es para búsquedas insensibles a mayúsculas en PostgreSQL
        // Si usas MySQL, cambia ILIKE por LIKE
        $sql = "SELECT id, nombre, cedula FROM clientes WHERE nombre ILIKE :search OR cedula LIKE :search LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':search' => "%{$search}%"]);
        $clientes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        Response::json(['data' => $clientes]);
    }
}
