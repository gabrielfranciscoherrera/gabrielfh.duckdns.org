<?php
$pageTitle = isset($title) ? $title . ' · ' . APP_NAME : APP_NAME;
?><!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?></title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

  <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- CSS propio -->
  <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>

  <!-- Menú principal -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-semibold" href="/"><i class="fa-solid fa-coins me-2"></i><?= APP_NAME ?></a>

      <!-- Botón hamburguesa -->
      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse" data-bs-target="#navbarMain"
              aria-controls="navbarMain" aria-expanded="false" aria-label="Alternar navegación">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Contenedor colapsable -->
      <div id="navbarMain" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="/">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="/clientes">Clientes</a></li>
          <li class="nav-item"><a class="nav-link" href="/prestamos">Préstamos</a></li>
          <li class="nav-item"><a class="nav-link" href="/prestamos/amortizacion?id=1">Amortización</a></li>
          <li class="nav-item">
            <form action="/logout" method="post" class="d-inline">
              <button class="btn btn-sm btn-outline-light ms-lg-2">Salir</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container-fluid py-4">