# 07. Seguridad básica

- Usar `password_hash()` y `password_verify()`.
- **CSRF**: token por sesión y verificación en POST.
- **Validación** del lado del servidor (filtros y límites).
- **Rate-limit** en login (simple contador por IP/usuario).
- **Regenerar** `session_id()` al iniciar sesión.
- **Escapar** salida en HTML (`htmlspecialchars`).

> Mantén PHP y servidor actualizados; restringe permisos de carpetas (`/config` fuera de `/public`).
