<?php
// private/Views/clientes/crear.php
ob_start(); ?>
<h1 class="h4 mb-3">Nuevo cliente</h1>
<form method="post" action="/clientes" class="card shadow-sm p-3">
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Cédula</label>
      <input name="cedula" class="form-control">
    </div>
    <div class="col-md-8">
      <label class="form-label">Nombre</label>
      <input name="nombre" required class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label">Teléfono</label>
      <input name="telefono" class="form-control">
    </div>
    <div class="col-md-8">
      <label class="form-label">Email</label>
      <input name="email" type="email" class="form-control">
    </div>
    <div class="col-12">
      <label class="form-label">Dirección</label>
      <input name="direccion" class="form-control">
    </div>
  </div>
  <div class="d-flex gap-2 mt-3">
    <button class="btn btn-primary">Guardar</button>
    <a href="/clientes" class="btn btn-outline-secondary">Cancelar</a>
  </div>
</form>
<?php $content = ob_get_clean(); $title = 'Nuevo cliente'; include __DIR__ . '/../layouts/app.php';
