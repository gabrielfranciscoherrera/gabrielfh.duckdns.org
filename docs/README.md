# Sistema de Préstamos Financieros (PHP + CDNs)

Esta documentación define el proyecto **solo con PHP** en el backend y **frameworks vía CDN (enlaces)** en el frontend (Bootstrap, Tailwind, y utilidades extras). No se usan empaquetadores ni pasos de build.

> Objetivo: entregar un sistema completo de préstamos (clientes, préstamos, cuotas, pagos, reportes y bitácora) con una interfaz moderna, manteniendo instalación simple (copiar al servidor PHP y usar).

## Tecnologías (sin build, solo por enlace)
- **PHP 8.x** (recomendado 8.1+).
- **MySQL/MariaDB**.
- **Bootstrap 5** (CDN) para componentes UI.
- **Tailwind CSS (CDN)** para utilidades de diseño.
- **Alpine.js** (CDN) para interactividad simple.
- **Axios** (CDN) para llamadas HTTP/AJAX.
- **Chart.js** (CDN) para gráficos.
- **Toastify o SweetAlert2** (CDN) para notificaciones y alerts.
- **Font Awesome** (CDN) para íconos.

> Nota: Evita mezclar **Bootstrap** y **Tailwind** en el **mismo componente**. Úsalos por secciones o vistas para reducir estilos inesperados.

## Estructura sugerida
```
/public              # raíz pública del servidor
  index.php          # plantilla base con enlaces CDN
  /img               # imágenes estáticas
/docs                # documentación (este folder)
/src
  /Controllers
  /Models
  /Views             # vistas parciales .php (render con include/require)
/config
  database.php       # conexión PDO
  app.php            # variables de config
```

## Módulos del sistema
- **Clientes**: alta/edición/baja, búsqueda y estado.
- **Préstamos**: monto, tasa mensual, plazo, fecha de inicio, estado.
- **Cuotas**: generación automática, calendario, intereses, saldo.
- **Pagos**: abonos parciales/extra, recibos.
- **Reportes**: cartera, vencimientos, morosidad, flujo.
- **Bitácora**: cambios y acciones (usuario/fecha/origen).
- **Usuarios**: login seguro (hash), roles (admin/cobranzas).
- **Parámetros**: tasas por defecto, moras, formatos NCF (si aplica).

## Próximos pasos
1) Leer `/docs/01-requisitos.md` y `/docs/02-arquitectura.md`.
2) Copiar `public/` a tu servidor PHP o XAMPP.
3) Crear BD con `/docs/05-bd-modelo.sql`.
4) Configurar conexión en `/config/database.php`.
5) Arrancar con `public/index.php` y construir vistas en `/src/Views/`.
