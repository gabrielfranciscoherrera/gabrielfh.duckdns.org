<?php
// private/Views/prestamos/crear.php
ob_start(); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Crear Nuevo Préstamo</h1>
    <a href="/prestamos" class="btn btn-secondary"><i class="fa-solid fa-arrow-left me-2"></i>Volver</a>
</div>

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0"><i class="fa-solid fa-file-invoice-dollar me-2"></i>Información del Préstamo</h5>
    </div>
    <div class="card-body">
        <form action="/prestamos/guardar" method="POST">
            <div class="mb-3">
                <label for="cliente_id" class="form-label">Buscar Cliente (por nombre o cédula)</label>
                <select class="form-control" id="cliente_id" name="cliente_id" required>
                </select>
            </div>

            <div class="mb-3">
                <label for="monto" class="form-label">Monto del Préstamo</label>
                <input type="number" class="form-control" id="monto" name="monto" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="interes" class="form-label">Tasa de Interés Anual (%)</label>
                <input type="number" class="form-control" id="interes" name="interes" step="0.01" required>
            </div>

            <div class="mb-3">
                <label for="plazo_meses" class="form-label">Plazo (en meses)</label>
                <input type="number" class="form-control" id="plazo_meses" name="plazo_meses" required>
            </div>

            <div class="mb-3">
                <label for="fecha_primer_pago" class="form-label">Fecha del Primer Pago</label>
                <input type="date" class="form-control" id="fecha_primer_pago" name="fecha_primer_pago" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Préstamo</button>
            <a href="/prestamos" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<script>
// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa Select2 en el campo de cliente
    $('#cliente_id').select2({
        placeholder: 'Escribe para buscar un cliente...',
        minimumInputLength: 2, // Empezar a buscar después de 2 caracteres
        ajax: {
            url: '/api/clientes', // La URL de nuestra API
            dataType: 'json',
            delay: 250, // Espera 250ms después de que el usuario deja de escribir
            data: function (params) {
                return {
                    q: params.term // El término de búsqueda
                };
            },
            processResults: function (data) {
                // Transforma los datos recibidos de la API al formato que Select2 espera
                return {
                    results: data.data.map(item => {
                        return {
                            id: item.id,
                            text: `${item.nombre} (Cédula: ${item.cedula})`
                        }
                    })
                };
            },
            cache: true
        }
    });
});
</script>

<?php 
$content = ob_get_clean(); 
$title = 'Crear préstamo';
include __DIR__ . '/../layouts/app.php';
?>
