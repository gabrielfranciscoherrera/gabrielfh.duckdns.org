<?php
// private/Views/prestamos/crear.php
ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 m-0">Crear Nuevo Préstamo</h1>
  <a href="/prestamos" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Volver</a>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="mb-0"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Información del Préstamo</h5>
      </div>
      <div class="card-body">
        <form action="/prestamos" method="POST" id="formPrestamo">
          <!-- Cliente -->
          <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente *</label>
            <select class="form-select" id="cliente_id" name="cliente_id" required>
              <option value="">Seleccionar cliente...</option>
              <?php foreach (($clientes ?? []) as $cliente): ?>
                <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre']) ?> - <?= htmlspecialchars($cliente['identificacion'] ?? '') ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Empresa -->
          <div class="mb-3">
            <label for="empresa_id" class="form-label">Empresa</label>
            <select class="form-select" id="empresa_id" name="empresa_id">
              <option value="">Sin empresa específica</option>
              <?php foreach (($empresas ?? []) as $empresa): ?>
                <option value="<?= $empresa['id'] ?>"><?= htmlspecialchars($empresa['nombre']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Monto -->
          <div class="mb-3">
            <label for="monto" class="form-label">Monto del Préstamo (RD$) *</label>
            <div class="input-group">
              <span class="input-group-text">RD$</span>
              <input type="number" class="form-control" id="monto" name="monto" 
                     step="0.01" min="100" max="10000000" required
                     placeholder="0.00">
            </div>
            <div class="form-text">Monto mínimo: RD$ 100.00</div>
          </div>

          <!-- Interés -->
          <div class="mb-3">
            <label for="interes" class="form-label">Tasa de Interés Anual (%) *</label>
            <div class="input-group">
              <input type="number" class="form-control" id="interes" name="interes" 
                     step="0.01" min="0" max="100" required
                     placeholder="0.00">
              <span class="input-group-text">%</span>
            </div>
            <div class="form-text">Tasa anual (se convertirá automáticamente a mensual)</div>
          </div>

          <!-- Plazo -->
          <div class="mb-3">
            <label for="plazo_meses" class="form-label">Plazo en Meses *</label>
            <input type="number" class="form-control" id="plazo_meses" name="plazo_meses" 
                   min="1" max="360" required
                   placeholder="12">
            <div class="form-text">Mínimo: 1 mes, Máximo: 30 años</div>
          </div>

          <!-- Fecha de Inicio -->
          <div class="mb-3">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                   value="<?= date('Y-m-d') ?>">
            <div class="form-text">Por defecto: hoy</div>
          </div>

          <!-- Estado -->
          <div class="mb-3">
            <label for="estado" class="form-label">Estado Inicial</label>
            <select class="form-select" id="estado" name="estado">
              <option value="pendiente">Pendiente</option>
              <option value="activo">Activo</option>
              <option value="suspendido">Suspendido</option>
            </select>
          </div>

          <!-- Botones -->
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-save me-2"></i>Crear Préstamo
            </button>
            <button type="button" class="btn btn-outline-secondary" onclick="calcularPreview()">
              <i class="fa-solid fa-calculator me-2"></i>Vista Previa
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Panel de Vista Previa -->
  <div class="col-lg-4">
    <div class="card shadow-sm">
      <div class="card-header">
        <h5 class="mb-0"><i class="fa-solid fa-eye me-2"></i>Vista Previa</h5>
      </div>
      <div class="card-body">
        <div id="previewContent">
          <p class="text-muted">Complete el formulario y haga clic en "Vista Previa" para ver los cálculos.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function calcularPreview() {
  const monto = parseFloat(document.getElementById('monto').value) || 0;
  const interesAnual = parseFloat(document.getElementById('interes').value) || 0;
  const plazoMeses = parseInt(document.getElementById('plazo_meses').value) || 0;

  if (monto <= 0 || plazoMeses <= 0) {
    alert('Por favor complete el monto y plazo para ver la vista previa.');
    return;
  }

  const interesMensual = interesAnual / 100 / 12;
  let cuotaMensual = 0;
  
  if (interesMensual > 0) {
    // Fórmula de cuota fija (sistema francés)
    cuotaMensual = monto * (interesMensual * Math.pow(1 + interesMensual, plazoMeses)) / 
                   (Math.pow(1 + interesMensual, plazoMeses) - 1);
  } else {
    cuotaMensual = monto / plazoMeses;
  }

  const totalIntereses = (cuotaMensual * plazoMeses) - monto;
  const totalPagar = cuotaMensual * plazoMeses;

  const previewHTML = `
    <div class="small">
      <div class="row mb-2">
        <div class="col-6"><strong>Cuota Mensual:</strong></div>
        <div class="col-6 text-end">RD$ ${cuotaMensual.toFixed(2)}</div>
      </div>
      <div class="row mb-2">
        <div class="col-6"><strong>Total Intereses:</strong></div>
        <div class="col-6 text-end">RD$ ${totalIntereses.toFixed(2)}</div>
      </div>
      <div class="row mb-2">
        <div class="col-6"><strong>Total a Pagar:</strong></div>
        <div class="col-6 text-end">RD$ ${totalPagar.toFixed(2)}</div>
      </div>
      <hr>
      <div class="row">
        <div class="col-6"><strong>Tasa Mensual:</strong></div>
        <div class="col-6 text-end">${(interesMensual * 100).toFixed(4)}%</div>
      </div>
    </div>
  `;

  document.getElementById('previewContent').innerHTML = previewHTML;
}

// Validación del formulario
document.getElementById('formPrestamo').addEventListener('submit', function(e) {
  const monto = parseFloat(document.getElementById('monto').value);
  const interes = parseFloat(document.getElementById('interes').value);
  const plazo = parseInt(document.getElementById('plazo_meses').value);
  const cliente = document.getElementById('cliente_id').value;

  if (!cliente) {
    e.preventDefault();
    alert('Debe seleccionar un cliente.');
    return;
  }

  if (monto < 100) {
    e.preventDefault();
    alert('El monto mínimo es RD$ 100.00');
    return;
  }

  if (interes < 0 || interes > 100) {
    e.preventDefault();
    alert('La tasa de interés debe estar entre 0% y 100%');
    return;
  }

  if (plazo < 1 || plazo > 360) {
    e.preventDefault();
    alert('El plazo debe estar entre 1 y 360 meses');
    return;
  }
});
</script>

<?php $content = ob_get_clean(); ?>
