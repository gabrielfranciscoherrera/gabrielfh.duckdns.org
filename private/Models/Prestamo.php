<?php
// private/Models/Prestamo.php
namespace Models;
use PDO;

class Prestamo {
    public static function all(): array {
        global $pdo; /** @var PDO $pdo */
        $stmt = $pdo->query("SELECT p.id, p.monto, p.tasa_mensual, p.plazo_meses, c.nombre AS cliente
                             FROM prestamos p JOIN clientes c ON c.id = p.cliente_id
                             ORDER BY p.id DESC LIMIT 100");
        return $stmt->fetchAll() ?: [];
    }
}
