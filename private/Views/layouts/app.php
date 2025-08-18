<?php
// private/Views/layouts/app.php – layout con CDNs
$pageTitle = isset($title) ? $title . ' · ' . APP_NAME : APP_NAME;
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?></title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">

  <!-- Tailwind (comentado temporalmente para evitar conflictos) -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

  <!-- CSS propio -->
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-light">

  <!-- Menú principal -->
  <?php include __DIR__ . '/menu.php'; ?>

  <main class="container my-4">
    <?php if (isset($content)) { echo $content; } else { ?>
      <div class="card shadow-sm">
        <div class="card-header">Dashboard</div>
        <div class="card-body">
          <p class="mb-3">Bienvenido al sistema. Construye tus módulos aquí.</p>

          <!-- CONTENEDOR CON ALTURA FIJA PARA EVITAR “ESTIRAMIENTO” -->
          <div class="chart-box" style="position: relative; height: 300px;">
            <canvas id="chartCartera"></canvas>
          </div>
        </div>
      </div>
    <?php } ?>
  </main>

  <!-- JS CDNs -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <!-- JS propio -->
  <script src="/assets/js/app.js"></script>

  <script>
  // Chart con altura controlada por el contenedor .chart-box
  const ctx = document.getElementById('chartCartera');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun'],
        datasets: [{
          label: 'Cartera (RD$)',
          data: [12000,13500,15000,14200,16000,17500],
          backgroundColor: 'rgba(25,135,84,0.6)' // verde bootstrap semi transparente
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false // respeta la altura del contenedor
      }
    });
  }
  </script>
</body>
</html>
