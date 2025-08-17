<?php
// private/Models/Cliente.php
namespace Models;
use PDO;

class Cliente {
    public static function all(): array {
        global $pdo; /** @var PDO $pdo */
        $stmt = $pdo->query("SELECT id, cedula, nombre, telefono, email FROM clientes ORDER BY id DESC LIMIT 100");
        return $stmt->fetchAll() ?: [];
    }
    public static function create(array $data): bool {
        global $pdo; /** @var PDO $pdo */
        $sql = "INSERT INTO clientes (cedula, nombre, telefono, direccion, email) VALUES (:cedula, :nombre, :telefono, :direccion, :email)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':cedula' => $data['cedula'] ?? null,
            ':nombre' => $data['nombre'] ?? null,
            ':telefono' => $data['telefono'] ?? null,
            ':direccion' => $data['direccion'] ?? null,
            ':email' => $data['email'] ?? null,
        ]);
    }
}
