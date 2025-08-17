# 06. Endpoints (referencia rápida)

> Sufijo JSON para peticiones AJAX desde Axios. Las vistas HTML usan Controladores y `include`.

## Clientes
- `GET /clientes?search=` listar/buscar
- `POST /clientes` crear
- `GET /clientes/{id}` detalle
- `PUT /clientes/{id}` actualizar
- `DELETE /clientes/{id}` eliminar

## Préstamos
- `POST /prestamos` crear (genera cuotas)
- `GET /prestamos/{id}` detalle + plan
- `PUT /prestamos/{id}` actualizar
- `PATCH /prestamos/{id}/cancelar` cancelar

## Pagos
- `POST /pagos` registrar pago (asignar a cuota/s)
- `GET /pagos?prestamo_id=` listar por préstamo

## Reportes
- `GET /reportes/cartera`
- `GET /reportes/vencimientos?desde=&hasta=`
- `GET /reportes/morosidad`
