# 02. Arquitectura (PHP sin build)

**Backend:** PHP clásico (controladores, modelos, vistas).  
**Frontend:** HTML + Bootstrap + Tailwind vía CDN (sin Node).  
**Datos:** MySQL/MariaDB con PDO.  
**Sesiones:** PHP nativas, cookies `httponly` y `secure` si hay HTTPS.

### Flujo MVC simple
1. Ruta entra por `public/index.php` (Front Controller).
2. Carga `config/app.php` y `config/database.php`.
3. Resuelve ruta hacia un Controller (p. ej. `ClientesController`).
4. Controller usa Modelos (`Cliente`, `Prestamo`, `Pago`) vía PDO.
5. Render de vistas con `include`/`require` (componentes parciales).

### Estructura de carpetas
```
/public
  index.php
/src
  /Controllers
    ClientesController.php
    PrestamosController.php
    PagosController.php
  /Models
    Cliente.php
    Prestamo.php
    Pago.php
  /Views
    /layouts
      app.php
    /clientes
      index.php
      crear.php
      editar.php
    /prestamos
    /pagos
/config
  app.php
  database.php
/docs
```

### Convenciones
- **Bootstrap** para navegación, tablas, modales, formularios.
- **Tailwind** para espaciados, grids rápidos y layouts.
- **Alpine.js** para pequeñas interacciones (mostrar/ocultar, tabs).
- **Axios** para peticiones AJAX a endpoints PHP.
