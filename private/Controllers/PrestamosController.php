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
    public function amortizacion(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); echo "ID inválido"; return; }
        $title = 'Tabla de amortización';
    
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.*, c.nombre AS cliente 
                               FROM prestamos p JOIN clientes c ON c.id = p.cliente_id 
                               WHERE p.id = :id");
        $stmt->execute([':id' => $id]);
        $prestamo = $stmt->fetch();
        if (!$prestamo) { http_response_code(404); echo "Préstamo no encontrado"; return; }
    
        $cuotas = \Models\Cuota::byPrestamo($id);
        include __DIR__ . '/../Views/prestamos/amortizacion.php';
    }
    
    public function generarCuotas(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { http_response_code(400); echo "ID inválido"; return; }
        $res = \Models\Cuota::generarParaPrestamo($id);
        header('Location: /prestamos/amortizacion?id=' . $id);
    }
    
}
