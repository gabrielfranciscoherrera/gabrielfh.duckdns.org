<?php
// private/Views/clientes/index.php
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 m-0">Clientes</h1>
  <a href="/clientes/crear" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Nuevo cliente</a>
</div>
<div class="card shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover mb-0">
        <thead class="table-light">
          <tr><th>ID</th><th>Cédula</th><th>Nombre</th><th>Teléfono</th><th>Email</th></tr>
        </thead>
        <tbody>
          <?php foreach ($clientes as $c): ?>
          <tr>
            <td><?= htmlspecialchars($c['id']) ?></td>
            <td><?= htmlspecialchars($c['cedula'] ?? '') ?></td>
            <td><?= htmlspecialchars($c['nombre'] ?? '') ?></td>
            <td><?= htmlspecialchars($c['telefono'] ?? '') ?></td>
            <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php $content = ob_get_clean(); $title = 'Clientes'; include __DIR__ . '/../layouts/app.php';
