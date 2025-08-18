<?php
// private/Models/Prestamo.php
namespace Models;
use PDO;

class Prestamo {
    public static function all(): array {
        global $pdo; /** @var PDO $pdo */
        $stmt = $pdo->query("SELECT p.id, p.monto, p.interes, p.plazo_meses, p.estado, p.fecha_inicio, c.nombre AS cliente
                             FROM prestamos p JOIN clientes c ON c.id = p.cliente_id
                             ORDER BY p.id DESC LIMIT 100");
        return $stmt->fetchAll() ?: [];
    }
    
    public static function create(array $data): int {
        global $pdo; /** @var PDO $pdo */
        
        $stmt = $pdo->prepare("
            INSERT INTO prestamos (cliente_id, empresa_id, monto, interes, plazo_meses, estado, fecha_inicio)
            VALUES (:cliente_id, :empresa_id, :monto, :interes, :plazo_meses, :estado, :fecha_inicio)
            RETURNING id
        ");
        
        $stmt->execute([
            ':cliente_id' => $data['cliente_id'],
            ':empresa_id' => $data['empresa_id'] ?? null,
            ':monto' => $data['monto'],
            ':interes' => $data['interes'],
            ':plazo_meses' => $data['plazo_meses'],
            ':estado' => $data['estado'] ?? 'pendiente',
            ':fecha_inicio' => $data['fecha_inicio'] ?? date('Y-m-d')
        ]);
        
        return $stmt->fetchColumn();
    }
}
