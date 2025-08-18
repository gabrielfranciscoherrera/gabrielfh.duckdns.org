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
        global $pdo;
        
        // Cargar clientes para el select
        $stmt = $pdo->query("SELECT id, nombre, identificacion FROM clientes ORDER BY nombre");
        $clientes = $stmt->fetchAll();
        
        // Cargar empresas para el select
        $stmt = $pdo->query("SELECT id, nombre FROM empresas ORDER BY nombre");
        $empresas = $stmt->fetchAll();
        
        $title = 'Crear préstamo';
        include __DIR__ . '/../Views/prestamos/crear.php';
    }
    
    public function store(): void {
        try {
            // Validar datos requeridos
            $cliente_id = (int)($_POST['cliente_id'] ?? 0);
            $monto = (float)($_POST['monto'] ?? 0);
            $interes = (float)($_POST['interes'] ?? 0);
            $plazo_meses = (int)($_POST['plazo_meses'] ?? 0);
            
            if ($cliente_id <= 0 || $monto <= 0 || $plazo_meses <= 0) {
                throw new Exception('Datos inválidos');
            }
            
            // Datos opcionales
            $empresa_id = !empty($_POST['empresa_id']) ? (int)$_POST['empresa_id'] : null;
            $fecha_inicio = !empty($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : date('Y-m-d');
            $estado = $_POST['estado'] ?? 'pendiente';
            
            // Crear préstamo usando el modelo
            $prestamo_id = Prestamo::create([
                'cliente_id' => $cliente_id,
                'empresa_id' => $empresa_id,
                'monto' => $monto,
                'interes' => $interes,
                'plazo_meses' => $plazo_meses,
                'estado' => $estado,
                'fecha_inicio' => $fecha_inicio
            ]);
            
            // Redirigir a la amortización del préstamo creado
            header('Location: /prestamos/amortizacion?id=' . $prestamo_id);
            exit;
            
        } catch (Exception $e) {
            http_response_code(400);
            echo "Error al crear el préstamo: " . htmlspecialchars($e->getMessage());
        }
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
