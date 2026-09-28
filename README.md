# HostalDS · Sistema de gestión y reservas para hostal

Sistema web para administrar un hostal: **habitaciones, reservas, servicios adicionales y pagos**, con paneles separados para **administradores, empleados y clientes**.

![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-06B6D4?logo=tailwindcss&logoColor=white)

## Funcionalidades

- **Tres roles con login propio**: administrador, empleado y cliente, cada uno con su sesión y verificación de permisos.
- **Registro de clientes** en línea.
- **CRUD completo**: habitaciones, clientes, empleados, administradores, servicios, tipos de pago, reservas y detalle de servicios.
- **Automatización en la base de datos con triggers**:
  - generación automática de IDs;
  - la habitación pasa a *ocupada* o *disponible* según la reserva.
- **Seguridad**: contraseñas con `password_hash`, consultas preparadas contra inyección SQL, salida escapada con `htmlspecialchars` contra XSS y validación de email y DNI.
- Dashboard administrativo y confirmación antes de eliminar.

## Estructura

```
administrador/ empleado/ cliente/   Login, registro y CRUD por rol
habitacion/ servicio/ tipoPago/     Catálogos
detalleReserva/ detalleServicio/    Reservas y servicios consumidos
index/                              Paneles por rol (dashboard)
includes/                           Funciones helper, navegación y verificación de sesión
sql/                                Script de instalación, triggers, migraciones y datos de ejemplo
conexion.php                        Conexión a MySQL (mysqli)
```

## Instalación

1. Copia el proyecto en `htdocs/` (XAMPP) o en tu servidor PHP.
2. En MySQL ejecuta `sql/INSTALACION_COMPLETA.sql` (el orden está en `sql/ORDEN_EJECUCION.md`).
3. Opcional: carga `sql/datos_ejemplo.sql`.
4. Ajusta las credenciales en `conexion.php`.
5. Abre `http://localhost/hostalDS/`.

## Documentación del proceso

- `MEJORAS_IMPLEMENTADAS.md` y `MEJORAS_AUTOMATIZACION.md`: mejoras y triggers.
- `CORRECCIONES_COLLATION.md`: corrección de collation en MySQL.
- `REVISION_FINAL.md`: revisión de seguridad y consistencia.
