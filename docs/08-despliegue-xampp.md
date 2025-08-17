# 08. Despliegue (XAMPP/Apache)

1. Coloca el contenido de `/public` dentro de `htdocs/sistema_prestamos` (XAMPP).
2. Configura la BD con `docs/05-bd-modelo.sql`.
3. Crea `/config/database.php` con tus credenciales (ver ejemplo en doc 04).
4. Accede a `http://localhost/sistema_prestamos/`.

### Producción
- Habilita HTTPS.
- Activa `display_errors=Off` y log a archivo.
- Usa `open_basedir` y `disable_functions` prudentes.
