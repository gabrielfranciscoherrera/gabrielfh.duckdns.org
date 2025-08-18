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
  
  <style>
    .dashboard-dark {
      background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 100%);
      color: #e0e0e0;
      min-height: 100vh;
    }
    
    .dashboard-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      transition: all 0.3s ease;
    }
    
    .dashboard-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    
    .stats-card {
      background: linear-gradient(135deg, rgba(25, 135, 84, 0.1) 0%, rgba(25, 135, 84, 0.05) 100%);
      border: 1px solid rgba(25, 135, 84, 0.2);
    }
    
    .stats-card.warning {
      background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
      border: 1px solid rgba(255, 193, 7, 0.2);
    }
    
    .stats-card.danger {
      background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
      border: 1px solid rgba(220, 53, 69, 0.2);
    }
    
    .stats-card.info {
      background: linear-gradient(135deg, rgba(13, 202, 240, 0.1) 0%, rgba(13, 202, 240, 0.05) 100%);
      border: 1px solid rgba(13, 202, 240, 0.2);
    }
    
    .chart-container {
      position: relative;
      height: 300px;
      margin: 20px 0;
    }
    
    .stats-number {
      font-size: 2.5rem;
      font-weight: 700;
      color: #ffffff;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    .stats-label {
      font-size: 0.9rem;
      color: #b0b0b0;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    
    .chart-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    
    @media (max-width: 768px) {
      .chart-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="dashboard-dark">

  <!-- Menú principal -->
  <?php include __DIR__ . '/menu.php'; ?>

  <main class="container-fluid py-4">
    <?php if (isset($content)) { echo $content; } else { ?>
      
      <!-- Header del Dashboard -->
      <div class="row mb-4">
        <div class="col-12">
          <h1 class="text-white mb-2">
            <i class="fa-solid fa-chart-line me-3"></i>Dashboard Financiero
          </h1>
          <p class="text-muted">Vista general del sistema de préstamos y cartera</p>
        </div>
      </div>

      <!-- Tarjetas de Estadísticas -->
      <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="card dashboard-card stats-card h-100">
            <div class="card-body text-center">
              <i class="fa-solid fa-coins fa-2x text-success mb-3"></i>
              <div class="stats-number">RD$ 175,000</div>
              <div class="stats-label">Cartera Total</div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="card dashboard-card stats-card warning h-100">
            <div class="card-body text-center">
              <i class="fa-solid fa-users fa-2x text-warning mb-3"></i>
              <div class="stats-number">48</div>
              <div class="stats-label">Clientes Activos</div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="card dashboard-card stats-card danger h-100">
            <div class="card-body text-center">
              <i class="fa-solid fa-exclamation-triangle fa-2x text-danger mb-3"></i>
              <div class="stats-number">12</div>
              <div class="stats-label">Préstamos Vencidos</div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
          <div class="card dashboard-card stats-card info h-100">
            <div class="card-body text-center">
              <i class="fa-solid fa-handshake fa-2x text-info mb-3"></i>
              <div class="stats-number">156</div>
              <div class="stats-label">Préstamos Activos</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Gráficas del Dashboard -->
      <div class="chart-grid">
        <!-- Gráfica de Cartera Mensual -->
        <div class="card dashboard-card">
          <div class="card-header bg-transparent border-0">
            <h5 class="text-white mb-0">
              <i class="fa-solid fa-chart-bar me-2"></i>Cartera Mensual
            </h5>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartCartera"></canvas>
            </div>
          </div>
        </div>

        <!-- Gráfica de Pagos vs Cobranzas -->
        <div class="card dashboard-card">
          <div class="card-header bg-transparent border-0">
            <h5 class="text-white mb-0">
              <i class="fa-solid fa-chart-line me-2"></i>Pagos vs Cobranzas
            </h5>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartPagos"></canvas>
            </div>
          </div>
        </div>

        <!-- Gráfica de Morosidad -->
        <div class="card dashboard-card">
          <div class="card-header bg-transparent border-0">
            <h5 class="text-white mb-0">
              <i class="fa-solid fa-chart-pie me-2"></i>Estado de Morosidad
            </h5>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartMorosidad"></canvas>
            </div>
          </div>
        </div>

        <!-- Gráfica de Tipos de Préstamos -->
        <div class="card dashboard-card">
          <div class="card-header bg-transparent border-0">
            <h5 class="text-white mb-0">
              <i class="fa-solid fa-chart-donut me-2"></i>Distribución por Tipo
            </h5>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartTipos"></canvas>
            </div>
          </div>
        </div>

        <!-- Gráfica de Flujo de Caja -->
        <div class="card dashboard-card">
          <div class="card-header bg-transparent border-0">
            <h5 class="text-white mb-0">
              <i class="fa-solid fa-chart-area me-2"></i>Flujo de Caja
            </h5>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartFlujo"></canvas>
            </div>
          </div>
        </div>

        <!-- Gráfica de Rendimiento por Sucursal -->
        <div class="card dashboard-card">
          <div class="card-header bg-transparent border-0">
            <h5 class="text-white mb-0">
              <i class="fa-solid fa-chart-column me-2"></i>Rendimiento por Sucursal
            </h5>
          </div>
          <div class="card-body">
            <div class="chart-container">
              <canvas id="chartSucursales"></canvas>
            </div>
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
  // Configuración global de Chart.js para tema oscuro
  Chart.defaults.color = '#e0e0e0';
  Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';

  // 1. Gráfica de Cartera Mensual
  const ctxCartera = document.getElementById('chartCartera');
  if (ctxCartera) {
    new Chart(ctxCartera, {
      type: 'bar',
      data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun'],
        datasets: [{
          label: 'Cartera (RD$)',
          data: [120000,135000,150000,142000,160000,175000],
          backgroundColor: 'rgba(25,135,84,0.6)',
          borderColor: 'rgba(25,135,84,1)',
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: '#e0e0e0' }
          }
        },
        scales: {
          y: {
            ticks: { color: '#e0e0e0' },
            grid: { color: 'rgba(255,255,255,0.1)' }
          },
          x: {
            ticks: { color: '#e0e0e0' },
            grid: { color: 'rgba(255,255,255,0.1)' }
          }
        }
      }
    });
  }

  // 2. Gráfica de Pagos vs Cobranzas
  const ctxPagos = document.getElementById('chartPagos');
  if (ctxPagos) {
    new Chart(ctxPagos, {
      type: 'line',
      data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun'],
        datasets: [{
          label: 'Pagos Recibidos',
          data: [25000,28000,32000,29000,35000,38000],
          borderColor: 'rgba(25,135,84,1)',
          backgroundColor: 'rgba(25,135,84,0.1)',
          tension: 0.4,
          fill: true
        }, {
          label: 'Cobranzas Realizadas',
          data: [22000,25000,30000,27000,32000,35000],
          borderColor: 'rgba(13,202,240,1)',
          backgroundColor: 'rgba(13,202,240,0.1)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: '#e0e0e0' }
          }
        },
        scales: {
          y: {
            ticks: { color: '#e0e0e0' },
            grid: { color: 'rgba(255,255,255,0.1)' }
          },
          x: {
            ticks: { color: '#e0e0e0' },
            grid: { color: 'rgba(255,255,255,0.1)' }
          }
        }
      }
    });
  }

  // 3. Gráfica de Morosidad (Dona)
  const ctxMorosidad = document.getElementById('chartMorosidad');
  if (ctxMorosidad) {
    new Chart(ctxMorosidad, {
      type: 'doughnut',
      data: {
        labels: ['Al día', '1-30 días', '31-60 días', '60+ días'],
        datasets: [{
          data: [65, 20, 10, 5],
          backgroundColor: [
            'rgba(25,135,84,0.8)',
            'rgba(255,193,7,0.8)',
            'rgba(255,152,0,0.8)',
            'rgba(220,53,69,0.8)'
          ],
          borderWidth: 2,
          borderColor: 'rgba(255,255,255,0.2)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { color: '#e0e0e0' }
          }
        }
      }
    });
  }

  // 4. Gráfica de Tipos de Préstamos
  const ctxTipos = document.getElementById('chartTipos');
  if (ctxTipos) {
    new Chart(ctxTipos, {
      type: 'pie',
      data: {
        labels: ['Personal', 'Hipotecario', 'Automotriz', 'Negocios', 'Otros'],
        datasets: [{
          data: [40, 25, 20, 10, 5],
          backgroundColor: [
            'rgba(25,135,84,0.8)',
            'rgba(13,202,240,0.8)',
            'rgba(255,193,7,0.8)',
            'rgba(220,53,69,0.8)',
            'rgba(108,117,125,0.8)'
          ],
          borderWidth: 2,
          borderColor: 'rgba(255,255,255,0.2)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { color: '#e0e0e0' }
          }
        }
      }
    });
  }

  // 5. Gráfica de Flujo de Caja
  const ctxFlujo = document.getElementById('chartFlujo');
  if (ctxFlujo) {
    new Chart(ctxFlujo, {
      type: 'area',
      data: {
        labels: ['Ene','Feb','Mar','Abr','May','Jun'],
        datasets: [{
          label: 'Ingresos',
          data: [45000,52000,58000,54000,62000,68000],
          borderColor: 'rgba(25,135,84,1)',
          backgroundColor: 'rgba(25,135,84,0.3)',
          tension: 0.4,
          fill: true
        }, {
          label: 'Egresos',
          data: [38000,42000,45000,41000,48000,52000],
          borderColor: 'rgba(220,53,69,1)',
          backgroundColor: 'rgba(220,53,69,0.3)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: '#e0e0e0' }
          }
        },
        scales: {
          y: {
            ticks: { color: '#e0e0e0' },
            grid: { color: 'rgba(255,255,255,0.1)' }
          },
          x: {
            ticks: { color: '#e0e0e0' },
            grid: { color: 'rgba(255,255,255,0.1)' }
          }
        }
      }
    });
  }

  // 6. Gráfica de Rendimiento por Sucursal
  const ctxSucursales = document.getElementById('chartSucursales');
  if (ctxSucursales) {
    new Chart(ctxSucursales, {
      type: 'radar',
      data: {
        labels: ['Cartera', 'Cobranza', 'Clientes', 'Eficiencia', 'Rentabilidad'],
        datasets: [{
          label: 'Sucursal Centro',
          data: [85, 78, 90, 82, 88],
          borderColor: 'rgba(25,135,84,1)',
          backgroundColor: 'rgba(25,135,84,0.2)',
          pointBackgroundColor: 'rgba(25,135,84,1)'
        }, {
          label: 'Sucursal Norte',
          data: [72, 85, 78, 88, 75],
          borderColor: 'rgba(13,202,240,1)',
          backgroundColor: 'rgba(13,202,240,0.2)',
          pointBackgroundColor: 'rgba(13,202,240,1)'
        }, {
          label: 'Sucursal Sur',
          data: [90, 82, 85, 78, 92],
          borderColor: 'rgba(255,193,7,1)',
          backgroundColor: 'rgba(255,193,7,0.2)',
          pointBackgroundColor: 'rgba(255,193,7,1)'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: '#e0e0e0' }
          }
        },
        scales: {
          r: {
            angleLines: { color: 'rgba(255,255,255,0.1)' },
            grid: { color: 'rgba(255,255,255,0.1)' },
            pointLabels: { color: '#e0e0e0' },
            ticks: { 
              color: '#e0e0e0',
              backdropColor: 'transparent'
            }
          }
        }
      }
    });
  }
  </script>
</body>
</html>
