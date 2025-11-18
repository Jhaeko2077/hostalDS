# 📋 Orden de Ejecución de Scripts SQL en phpMyAdmin

## ⚠️ IMPORTANTE: Ejecuta los scripts en este orden exacto

---

## 🔢 PASO 1: Crear la Base de Datos

**En phpMyAdmin:**
1. Ve a la pestaña **"SQL"**
2. Ejecuta este comando primero:

```sql
CREATE DATABASE IF NOT EXISTS hostalds CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O simplemente crea la base de datos manualmente desde la interfaz de phpMyAdmin con el nombre `hostalds`.

---

## 🔢 PASO 2: Ejecutar Instalación Completa

**Archivo:** `sql/INSTALACION_COMPLETA.sql`

1. Selecciona la base de datos `hostalds` en el panel izquierdo (o créala primero)
2. Ve a la pestaña **"SQL"**
3. Copia y pega **TODO** el contenido de `sql/INSTALACION_COMPLETA.sql`
4. Haz clic en **"Continuar"** o **"Ejecutar"**

**Este script incluye TODO:**
- ✅ Creación de la base de datos `hostalds`
- ✅ Todas las tablas (Empleado, Cliente, Administrador, Habitaciones, Servicios, tipoPago, detalleReserva, detalleServicioHob)
- ✅ Tabla `Contadores` con registros iniciales
- ✅ Todos los triggers básicos (generación de IDs, gestión de habitaciones)
- ✅ Todos los triggers adicionales (validaciones, prevención de eliminaciones)

**⚠️ IMPORTANTE:** Este script reemplaza a los archivos individuales que ya no existen:
- ~~hotelDS.sql~~ → Incluido
- ~~crear_contadores.sql~~ → Incluido
- ~~triggers.sql~~ → Incluido
- ~~triggers_adicionales.sql~~ → Incluido

---

## 🔢 PASO 3: (OPCIONAL) Insertar Datos de Ejemplo

**Archivo:** `sql/datos_ejemplo.sql`

**Ejecuta este script si quieres:**
- Probar el sistema con datos de ejemplo
- Verificar que todos los triggers funcionan correctamente
- Tener usuarios de prueba para hacer login

**Este script incluye:**
- 4 Empleados de ejemplo
- 2 Administradores
- 5 Clientes
- 10 Habitaciones
- 10 Servicios
- 6 Tipos de Pago
- 5 Reservas de ejemplo
- 3 Detalles de Servicio

**Credenciales de prueba:** Todos los usuarios tienen la contraseña `password`

**Ver más detalles en:** `sql/README_DATOS_EJEMPLO.md`

---

## 🔢 PASO 4: Crear los Triggers de Mejoras (Opcional pero Recomendado)

**Archivo:** `sql/triggers_mejoras.sql`

1. Asegúrate de estar en la base de datos `hostalds`
2. Ve a la pestaña **"SQL"**
3. Copia y pega todo el contenido de `sql/triggers_mejoras.sql`
4. Haz clic en **"Ejecutar"**

**Este script crea:**
- Triggers de auditoría (registra todos los cambios)
- Triggers de validación de fechas
- Triggers para auto-liberar habitaciones
- Evento programado diario
- Vistas útiles (estadísticas, habitaciones disponibles)

**⚠️ IMPORTANTE:** Después de ejecutar este script, activa el programador de eventos:

```sql
SET GLOBAL event_scheduler = ON;
```

---

## 🔢 PASO 5: (OPCIONAL) Migración de Datos Existentes

**Archivo:** `sql/migracion_reservas.sql`

**Solo ejecuta este script si:**
- Ya tienes datos en la base de datos antigua
- Necesitas migrar reservas de la estructura antigua a la nueva

Si estás creando la base de datos desde cero, **NO necesitas ejecutar este script**.

---

## ✅ Verificación Final

Después de ejecutar todos los scripts, verifica que todo esté correcto:

### 1. Verificar Tablas
```sql
SHOW TABLES;
```
Deberías ver: `Cliente`, `Empleado`, `Administrador`, `Habitaciones`, `Servicios`, `tipoPago`, `detalleReserva`, `detalleServicioHob`, `Contadores`, y las tablas de auditoría.

### 2. Verificar Triggers
```sql
SHOW TRIGGERS;
```
Deberías ver todos los triggers creados.

### 3. Verificar Eventos (si ejecutaste triggers_mejoras.sql)
```sql
SHOW EVENTS;
```
Deberías ver el evento `evt_liberar_habitaciones_diario`.

### 4. Verificar Vistas (si ejecutaste triggers_mejoras.sql)
```sql
SHOW FULL TABLES WHERE Table_type = 'VIEW';
```
Deberías ver las vistas creadas.

### 5. Probar las Vistas
```sql
SELECT * FROM vista_estadisticas_reservas;
SELECT * FROM vista_habitaciones_disponibles;
```

---

## 📝 Resumen del Orden

```
1. Crear base de datos "hostalds" (o se crea automáticamente)
   ↓
2. INSTALACION_COMPLETA.sql (TODO: tablas, contadores, triggers básicos y adicionales)
   ↓
3. datos_ejemplo.sql (datos de prueba - OPCIONAL pero recomendado)
   ↓
4. triggers_mejoras.sql (triggers de mejoras - OPCIONAL)
   ↓
5. Activar event_scheduler (si ejecutaste paso 4)
```

**Nota:** Los archivos individuales (hotelDS.sql, crear_contadores.sql, triggers.sql, triggers_adicionales.sql) ya no existen porque todo está incluido en `INSTALACION_COMPLETA.sql`.

---

## ⚠️ Errores Comunes y Soluciones

### Error: "Table already exists"
- **Solución:** Elimina la base de datos y créala de nuevo, o usa `DROP TABLE IF EXISTS` antes de crear.

### Error: "Trigger already exists"
- **Solución:** Los scripts ya tienen `DROP TRIGGER IF EXISTS`, pero si persiste, elimina manualmente los triggers.

### Error: "Event scheduler is OFF"
- **Solución:** Ejecuta `SET GLOBAL event_scheduler = ON;` (requiere privilegios de administrador).

### Error: "Access denied for event_scheduler"
- **Solución:** Necesitas permisos de administrador. Si no los tienes, el evento no funcionará pero el resto sí.

---

## 🎯 Orden Simplificado (Copia y Pega)

**Opción 1: Instalación Básica (Recomendada)**
```sql
-- 1. Ejecutar INSTALACION_COMPLETA.sql (copiar TODO su contenido)
--    Esto incluye: base de datos, tablas, contadores, triggers básicos y adicionales
```

**Opción 2: Con Datos de Ejemplo**
```sql
-- 1. Ejecutar INSTALACION_COMPLETA.sql (copiar TODO su contenido)

-- 2. Ejecutar datos_ejemplo.sql (copiar TODO su contenido)
--    Esto inserta datos de prueba para poder probar el sistema
```

**Opción 3: Con Mejoras Avanzadas**
```sql
-- 1. Ejecutar INSTALACION_COMPLETA.sql (copiar TODO su contenido)

-- 2. (Opcional) Ejecutar datos_ejemplo.sql para tener datos de prueba

-- 3. Ejecutar triggers_mejoras.sql (copiar TODO su contenido)

-- 4. Activar eventos
SET GLOBAL event_scheduler = ON;
```

---

## ✅ ¡Listo!

Una vez ejecutados todos los scripts en orden, tu base de datos estará completamente configurada y lista para usar. 🚀

