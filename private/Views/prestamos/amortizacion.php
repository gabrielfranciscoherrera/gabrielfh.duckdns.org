<?php
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h1 class="h4 m-0">Tabla de amortización</h1>
    <div class="text-muted small">
      <strong>Cliente:</strong> <?= htmlspecialchars($prestamo['cliente']) ?> ·
      <strong>Monto:</strong> RD$ <?= number_format((float)$prestamo['monto'], 2) ?> ·
      <strong>Tasa mensual:</strong> <?= $prestamo['tasa_mensual'] ?> ·
      <strong>Plazo:</strong> <?= (int)$prestamo['plazo_meses'] ?> meses
    </div>
  </div>
  <form method="post" action="/prestamos/generar-cuotas?id=<?= (int)$prestamo['id'] ?>">
    <button class="btn btn-primary"><i class="fa-solid fa-rotate me-2"></i>Generar/Regenerar cuotas</button>
  </form>
</div>

<div class="card shadow-sm">
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th><th>Vencimiento</th><th>Capital</th><th>Interés</th><th>Total</th><th>Pagada</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($cuotas)): ?>
          <tr><td colspan="6" class="text-center py-4">No hay cuotas generadas.</td></tr>
        <?php else: foreach ($cuotas as $q): ?>
          <tr>
            <td><?= $q['numero'] ?></td>
            <td><?= $q['fecha_vencimiento'] ?></td>
            <td>RD$ <?= number_format($q['capital'],2) ?></td>
            <td>RD$ <?= number_format($q['interes'],2) ?></td>
            <td>RD$ <?= number_format($q['total'],2) ?></td>
            <td><?= $q['pagada'] ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-warning text-dark">No</span>' ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php $content = ob_get_clean(); $title = 'Amortización'; include __DIR__ . '/../layouts/app.php';
