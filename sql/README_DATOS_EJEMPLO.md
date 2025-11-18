# 📋 Guía de Datos de Ejemplo

## 🎯 Propósito

Este archivo contiene datos de ejemplo para probar todas las funcionalidades del sistema después de ejecutar `INSTALACION_COMPLETA.sql`.

---

## 📝 Archivo: `sql/datos_ejemplo.sql`

### ¿Qué incluye?

1. **4 Empleados** con diferentes tipos (Recepcionista, Limpieza, Mantenimiento)
2. **2 Administradores** (que también son empleados)
3. **5 Clientes** para hacer reservas
4. **10 Habitaciones** de diferentes tipos
5. **10 Servicios** disponibles
6. **6 Tipos de Pago** diferentes
7. **5 Reservas** de ejemplo:
   - 1 reserva pasada (completada y pagada)
   - 1 reserva activa (hoy, pendiente de pago)
   - 1 check-in directo (hoy, pagada)
   - 1 reserva futura (pagada)
   - 1 reserva futura (pendiente)
8. **3 Detalles de Servicio** para habitaciones

---

## 🚀 Cómo Usar

### Paso 1: Ejecutar Instalación Completa
```sql
-- Ejecuta primero: sql/INSTALACION_COMPLETA.sql
```

### Paso 2: Insertar Datos de Ejemplo
```sql
-- Luego ejecuta: sql/datos_ejemplo.sql
```

### Paso 3: Verificar
El script incluye consultas de verificación al final que muestran todos los datos insertados.

---

## 🔐 Credenciales de Prueba

**Todas las contraseñas son:** `password`

### 👨‍💼 Empleados
- **Usuario:** `jperez` / **Contraseña:** `password` (Recepcionista)
- **Usuario:** `mgonzalez` / **Contraseña:** `password` (Limpieza)
- **Usuario:** `cramirez` / **Contraseña:** `password` (Mantenimiento)
- **Usuario:** `amartinez` / **Contraseña:** `password` (Recepcionista)

### 👔 Administradores
- **Usuario:** `jperez` / **Contraseña:** `password` (también es empleado)
- **Usuario:** `amartinez` / **Contraseña:** `password` (también es empleado)

### 👤 Clientes
- **Usuario:** `psanchez` / **Contraseña:** `password`
- **Usuario:** `llopez` / **Contraseña:** `password`
- **Usuario:** `rfernandez` / **Contraseña:** `password`
- **Usuario:** `ctorres` / **Contraseña:** `password`
- **Usuario:** `lvargas` / **Contraseña:** `password`

---

## ✅ Qué Verificar

Después de ejecutar el script, verifica:

### 1. IDs Generados Automáticamente
Los triggers deberían haber generado IDs automáticamente:
- **Empleados:** Formato `JP001`, `MG001`, `CR001`, `AM001`
- **Clientes:** Formato `PS001`, `LL001`, `RF001`, `CT001`, `LV001`
- **Reservas:** Formato `RES00001`, `RES00002`, etc.
- **Servicios:** Formato `SER00001`, `SER00002`, etc.
- **Tipos de Pago:** Formato `TPG00001`, `TPG00002`, etc.

### 2. Estados de Habitaciones
- `HAB001` debería estar **Disponible** (reserva pasada)
- `HAB002` debería estar **Ocupado** (reserva activa hoy)
- `HAB003` debería estar **Ocupado** (check-in directo hoy)
- `HAB004` debería estar **Disponible** (reserva futura)
- `HAB005` debería estar **Disponible** (reserva futura)

### 3. Contadores Actualizados
Ejecuta:
```sql
SELECT * FROM Contadores;
```

Deberías ver números incrementados en cada tabla.

### 4. Login Funcional
Prueba iniciar sesión con cualquiera de los usuarios de ejemplo.

---

## 🔍 Consultas Útiles

### Ver todas las reservas con detalles
```sql
SELECT 
    dr.id,
    dr.fecha_inicio,
    dr.fecha_fin,
    CONCAT(c.nombres, ' ', c.apellidos) as cliente,
    h.tipo as habitacion,
    h.estado as estado_habitacion,
    CASE WHEN dr.pago = 1 THEN 'Pagado' ELSE 'Pendiente' END as estado_pago
FROM detalleReserva dr
JOIN Cliente c ON dr.idCli = c.id
JOIN Habitaciones h ON dr.idHab = h.codigo
ORDER BY dr.fecha_inicio DESC;
```

### Ver habitaciones disponibles ahora
```sql
SELECT codigo, tipo, estado 
FROM Habitaciones 
WHERE estado = 'Disponible'
ORDER BY codigo;
```

### Ver reservas activas (hoy)
```sql
SELECT 
    dr.id,
    CONCAT(c.nombres, ' ', c.apellidos) as cliente,
    h.codigo as habitacion,
    h.tipo
FROM detalleReserva dr
JOIN Cliente c ON dr.idCli = c.id
JOIN Habitaciones h ON dr.idHab = h.codigo
WHERE CURDATE() BETWEEN dr.fecha_inicio AND dr.fecha_fin;
```

### Ver reservas pendientes de pago
```sql
SELECT 
    dr.id,
    CONCAT(c.nombres, ' ', c.apellidos) as cliente,
    h.tipo as habitacion,
    dr.fecha_inicio,
    dr.fecha_fin
FROM detalleReserva dr
JOIN Cliente c ON dr.idCli = c.id
JOIN Habitaciones h ON dr.idHab = h.codigo
WHERE dr.pago = 0 AND dr.fecha_fin >= CURDATE();
```

---

## ⚠️ Notas Importantes

1. **Contraseñas:** Todas las contraseñas están hasheadas con `password_hash()` usando `PASSWORD_BCRYPT`. La contraseña en texto plano es `password` para todos.

2. **Fechas:** Las reservas usan `CURDATE()` para que sean relativas a la fecha actual. Si ejecutas el script en diferentes días, las fechas cambiarán automáticamente.

3. **IDs Automáticos:** No necesitas especificar IDs al insertar. Los triggers los generan automáticamente.

4. **Foreign Keys:** El script usa subconsultas para obtener los IDs generados automáticamente, así que no necesitas conocer los IDs exactos.

5. **Estados de Habitaciones:** Los triggers actualizan automáticamente el estado de las habitaciones cuando se crean reservas.

---

## 🧪 Pruebas Recomendadas

1. ✅ **Login:** Prueba iniciar sesión con diferentes usuarios
2. ✅ **Crear Reserva:** Crea una nueva reserva y verifica que el ID se genera automáticamente
3. ✅ **Estado de Habitación:** Verifica que la habitación cambia a "Ocupado" al crear una reserva
4. ✅ **Eliminar Reserva:** Elimina una reserva y verifica que la habitación vuelve a "Disponible"
5. ✅ **Actualizar Reserva:** Cambia las fechas de una reserva y verifica que los triggers funcionan
6. ✅ **Dashboard:** Si ejecutaste `triggers_mejoras.sql`, verifica el dashboard con estas estadísticas

---

## 🎉 ¡Listo!

Con estos datos de ejemplo puedes probar todas las funcionalidades del sistema sin tener que crear datos manualmente. 🚀

