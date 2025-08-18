<?php
// Navbar principal
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm" data-tw-ignore>
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
