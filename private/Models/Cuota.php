<?php
namespace Models;
use PDO;
use DateTime;
use DateInterval;

class Cuota {
    public static function byPrestamo(int $prestamo_id): array {
        global $pdo; /** @var PDO $pdo */
        $stmt = $pdo->prepare("SELECT id, numero, fecha_vencimiento, capital, interes, mora, total, pagada
                               FROM cuotas WHERE prestamo_id = :id ORDER BY numero ASC");
        $stmt->execute([':id' => $prestamo_id]);
        return $stmt->fetchAll() ?: [];
    }

    public static function generarParaPrestamo(int $prestamo_id): array {
        global $pdo; /** @var PDO $pdo */
        $stmt = $pdo->prepare("SELECT id, monto, tasa_mensual, plazo_meses, fecha_inicio FROM prestamos WHERE id = :id");
        $stmt->execute([':id' => $prestamo_id]);
        $p = $stmt->fetch();
        if (!$p) { return ['ok' => false, 'msg' => 'Préstamo no encontrado']; }

        $P = (float)$p['monto'];
        $r = (float)$p['tasa_mensual']; 
        $n = (int)$p['plazo_meses'];
        $fecha = new DateTime($p['fecha_inicio']);

        if ($P <= 0 || $n <= 0) {
            return ['ok' => false, 'msg' => 'Datos inválidos'];
        }

        // Sistema francés
        $A = ($r > 0) ? $P * ($r / (1 - pow(1 + $r, -$n))) : $P / $n;

        $pdo->beginTransaction();
        try {
            $pdo->prepare("DELETE FROM cuotas WHERE prestamo_id = :id")->execute([':id' => $prestamo_id]);

            $saldo = $P;
            for ($k = 1; $k <= $n; $k++) {
                $interes = ($r > 0) ? $saldo * $r : 0.0;
                $capital = $A - $interes;
                if ($k == $n) { $capital = $saldo; $A = $capital + $interes; }
                $saldo -= $capital;

                $fecha_venc = (clone $fecha)->add(new DateInterval('P' . ($k - 1) . 'M'))->format('Y-m-d');

                $pdo->prepare("INSERT INTO cuotas (prestamo_id, numero, fecha_vencimiento, capital, interes, mora, total, pagada)
                               VALUES (:pid, :num, :fec, :cap, :int, 0, :tot, false)")
                    ->execute([
                        ':pid' => $prestamo_id,
                        ':num' => $k,
                        ':fec' => $fecha_venc,
                        ':cap' => round($capital, 2),
                        ':int' => round($interes, 2),
                        ':tot' => round($A, 2),
                    ]);
            }
            $pdo->commit();
            return ['ok' => true, 'msg' => 'Cuotas generadas'];
        } catch (\Throwable $e) {
            $pdo->rollBack();
            return ['ok' => false, 'msg' => 'Error generando cuotas'];
        }
    }
}
