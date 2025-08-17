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
  <!-- Tailwind (elige una) -->
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/"><i class="fa-solid fa-coins me-2"></i><?= APP_NAME ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/clientes">Clientes</a></li>
        <li class="nav-item"><a class="nav-link" href="/prestamos">Préstamos</a></li>
      </ul>
      <form action="/logout" method="post" class="ms-auto">
        <button class="btn btn-sm btn-outline-light">Salir</button>
      </form>
    </div>
  </div>
</nav>

<main class="container my-4">
  <?php if (isset($content)) { echo $content; } else { ?>
  <div class="card shadow-sm">
    <div class="card-header">Dashboard</div>
    <div class="card-body">
      <p class="mb-0">Bienvenido al sistema. Construye tus módulos aquí.</p>
      <canvas id="chartCartera" height="120" class="mt-3"></canvas>
    </div>
  </div>
  <?php } ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="/assets/js/app.js"></script>
<script>
const ctx = document.getElementById('chartCartera');
if (ctx) {
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Ene','Feb','Mar','Abr','May','Jun'],
      datasets: [{ label: 'Cartera (RD$)', data: [12000,13500,15000,14200,16000,17500] }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });
}
</script>
</body>
</html>
