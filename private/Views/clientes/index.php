<?php
// private/Views/clientes/index.php
ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 m-0 text-white">Clientes</h1>
  <a href="/clientes/crear" class="btn btn-primary">
    <i class="fa-solid fa-plus me-2"></i>Nuevo cliente
  </a>
</div>

<div class="card shadow-sm bg-dark text-white border-0">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-secondary text-white">
          <tr>
            <th>ID</th>
            <th>Cédula</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Email</th>
          </tr>
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

<style>
  /* Ajustes extra para contraste */
  .table thead th {
    font-weight: 600;
    background-color: #212529 !important;
    color: #f8f9fa !important;
  }
  .table tbody tr {
    background-color: #1a1d20;
  }
  .table tbody tr:hover {
    background-color: #2a2f33;
  }
</style>

<?php
$content = ob_get_clean();
$title = 'Clientes';
include __DIR__ . '/../layouts/app.php';
