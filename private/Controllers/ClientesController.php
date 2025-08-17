<?php
// private/Controllers/ClientesController.php
namespace Controllers;
use Core\Response;
use Models\Cliente;

class ClientesController {
    public function index(): void {
        $clientes = Cliente::all();
        $title = 'Clientes';
        include __DIR__ . '/../Views/clientes/index.php';
    }
    public function crear(): void {
        $title = 'Crear cliente';
        include __DIR__ . '/../Views/clientes/crear.php';
    }
    public function store(): void {
        $data = [
            'cedula' => $_POST['cedula'] ?? null,
            'nombre' => $_POST['nombre'] ?? null,
            'telefono' => $_POST['telefono'] ?? null,
            'direccion' => $_POST['direccion'] ?? null,
            'email' => $_POST['email'] ?? null,
        ];
        $ok = Cliente::create($data);
        header('Location: /clientes');
    }
    // API
    public function apiList(): void {
        $clientes = Cliente::all();
        Response::json(['data' => $clientes]);
    }
    public function apiCreate(): void {
        $payload = json_decode(file_get_contents('php://input'), true) ?? [];
        $ok = Cliente::create($payload);
        Response::json(['ok' => $ok], $ok ? 201 : 400);
    }
}
