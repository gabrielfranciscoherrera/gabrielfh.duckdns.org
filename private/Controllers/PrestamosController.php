<?php
// private/Controllers/PrestamosController.php
namespace Controllers;
use Models\Prestamo;

class PrestamosController {
    public function dashboard(): void {
        $title = 'Dashboard';
        include __DIR__ . '/../Views/layouts/app.php';
    }
    public function index(): void {
        $prestamos = Prestamo::all();
        $title = 'Préstamos';
        include __DIR__ . '/../Views/prestamos/index.php';
    }
    public function crear(): void {
        $title = 'Crear préstamo';
        echo "<div class='container my-4'><h1>Crear préstamo</h1><p>Pendiente de implementar.</p></div>";
    }
    public function store(): void {
        echo "Guardar préstamo (pendiente)";
    }
}
