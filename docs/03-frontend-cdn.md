# 03. Frontend por CDN (enlaces listos)

> Copia estos enlaces en tu plantilla base (`public/index.php`).

## Bootstrap 5
```html
<!-- Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="" crossorigin="anonymous">

<!-- Bootstrap 5 Bundle (JS + Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="" crossorigin="anonymous"></script>
```

## Tailwind CSS (CDN)
> Opción A (Tailwind 4 browser, recomendado por simplicidad en CDN):
```html
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
```

> Opción B (Tailwind v3 Play CDN):
```html
<script src="https://cdn.tailwindcss.com"></script>
```

## Utilidades extra
```html
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- SweetAlert2 (alerts) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toastify (toasts) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<!-- Iconos: Font Awesome (CSS) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
```

### Buenas prácticas al mezclar Bootstrap + Tailwind
- Evita usar ambos **en el mismo elemento**.
- Usa Bootstrap para componentes prefabricados (navbars, modales, offcanvas).
- Usa Tailwind para layout/espaciados utilitarios.
