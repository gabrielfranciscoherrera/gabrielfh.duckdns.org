<?php
// private/Views/prestamos/index.php
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 m-0">Préstamos</h1>
  <div class="d-flex gap-2">
    <a href="/prestamos/crear" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Nuevo Préstamo</a>
    <a href="/clientes" class="btn btn-outline-secondary"><i class="fa-solid fa-users me-2"></i>Gestionar Clientes</a>
  </div>
</div>
<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr><th>ID</th><th>Cliente</th><th>Monto</th><th>Interés (%)</th><th>Plazo</th><th>Estado</th><th>Fecha</th></tr>
        </thead>
        <tbody>
          <?php foreach (($prestamos ?? []) as $p): ?>
          <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['cliente'] ?? '') ?></td>
            <td><?= htmlspecialchars(number_format((float)$p['monto'], 2)) ?></td>
            <td><?= htmlspecialchars((string)$p['interes']) ?>%</td>
            <td><?= htmlspecialchars((string)$p['plazo_meses']) ?> meses</td>
            <td><span class="badge bg-<?= $p['estado'] === 'pendiente' ? 'warning' : ($p['estado'] === 'activo' ? 'success' : 'secondary') ?>"><?= htmlspecialchars($p['estado']) ?></span></td>
            <td><?= htmlspecialchars($p['fecha_inicio'] ?? 'N/A') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); $title = 'Préstamos'; include __DIR__ . '/../layouts/app.php';
