<?php

namespace Models;

class Cuota
{
    /**
     * Genera la tabla de amortización para un préstamo.
     *
     * @param \PDO $pdo La conexión a la base de datos.
     * @param array $p Los datos del préstamo.
     */
    public static function generarParaPrestamo(\PDO $pdo, array $p): void
    {
        // Limpiar cuotas anteriores si existen para este préstamo
        $pdo->prepare("DELETE FROM amortizaciones WHERE prestamo_id = :pid")->execute([':pid' => $p['id']]);

        // Datos del préstamo
        $P = (float)$p['monto'];
        $n = (int)$p['plazo_meses'];
        
        // --- LÓGICA MEJORADA ---
        // 1. Asumimos que el interés guardado en la BD es ANUAL.
        //    Ej: Si se guarda 24, representa 24% anual.
        $tasa_anual = (float)$p['interes'] / 100;
        $r = $tasa_anual / 12; // Tasa de interés mensual efectiva

        // Fórmula de la cuota fija (Sistema de Amortización Francés)
        // Se calcula una sola vez
        $A = $P * ($r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
        
        $saldo = $P;
        $fecha_pago = new \DateTime($p['fecha_primer_pago']);

        $stmt = $pdo->prepare(
            "INSERT INTO amortizaciones (prestamo_id, empresa_id, numero_cuota, fecha_pago, capital, interes, total_cuota, saldo_pendiente, estado) 
             VALUES (:pid, :eid, :ncu, :fp, :cap, :int, :tot, :saldo, 'pendiente')"
        );

        for ($k = 1; $k <= $n; $k++) {
            // --- CÁLCULOS PRECISOS ---
            $interes_calculado = $saldo * $r;
            $capital_calculado = $A - $interes_calculado;
            $saldo_anterior = $saldo;
            $saldo -= $capital_calculado;

            // 2. AJUSTE DE LA ÚLTIMA CUOTA
            // Para asegurar que el saldo final sea exactamente 0, ajustamos la última cuota.
            if ($k == $n) {
                // Si queda un pequeño saldo residual por el redondeo de los decimales,
                // se lo sumamos al capital de la última cuota.
                $capital_calculado += $saldo;
                $A = $capital_calculado + $interes_calculado; // Recalculamos el total de la cuota final
                $saldo = 0; // Forzamos el saldo a cero
            }

            $stmt->execute([
                ':pid' => $p['id'],
                ':eid' => $p['empresa_id'],
                ':ncu' => $k,
                ':fp' => $fecha_pago->format('Y-m-d'),
                // 3. REDONDEO SOLO AL GUARDAR
                ':cap' => round($capital_calculado, 2),
                ':int' => round($interes_calculado, 2),
                ':tot' => round($A, 2),
                ':saldo' => round($saldo, 2)
            ]);

            // Avanzar al siguiente mes para la próxima cuota
            $fecha_pago->add(new \DateInterval('P1M'));
        }
    }
}
