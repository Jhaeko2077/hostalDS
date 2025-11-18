-- ============================================================
-- DATOS DE EJEMPLO PARA PROBAR EL SISTEMA
-- ============================================================
-- Ejecuta este script DESPUÉS de INSTALACION_COMPLETA.sql
-- para insertar datos de prueba y verificar que todo funciona
-- ============================================================

USE hostalds;

-- ============================================================
-- 1. EMPLEADOS (Los IDs se generan automáticamente)
-- ============================================================
-- Nota: Los IDs se generan automáticamente con el formato: Iniciales + número
-- Ejemplo: Juan Pérez → JP001

INSERT INTO Empleado (nombres, apellidos, dni, email, telefono, contrasena, usuario, tipo) VALUES
('Juan', 'Pérez', '12345678', 'juan.perez@hostal.com', '987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jperez', 'Recepcionista'),
('María', 'González', '23456789', 'maria.gonzalez@hostal.com', '987654322', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mgonzalez', 'Limpieza'),
('Carlos', 'Ramírez', '34567890', 'carlos.ramirez@hostal.com', '987654323', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cramirez', 'Mantenimiento'),
('Ana', 'Martínez', '45678901', 'ana.martinez@hostal.com', '987654324', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'amartinez', 'Recepcionista');

-- Nota: La contraseña hasheada es "password" para todos los usuarios de prueba

-- ============================================================
-- 2. ADMINISTRADORES (Debe existir el empleado primero)
-- ============================================================
-- Nota: El usuario debe existir en la tabla Empleado

INSERT INTO Administrador (nombres, apellidos, dni, email, telefono, contrasena, usuario) VALUES
('Juan', 'Pérez', '12345678', 'juan.admin@hostal.com', '987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jperez'),
('Ana', 'Martínez', '45678901', 'ana.admin@hostal.com', '987654324', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'amartinez');

-- ============================================================
-- 3. CLIENTES (Los IDs se generan automáticamente)
-- ============================================================

INSERT INTO Cliente (nombres, apellidos, dni, email, telefono, contrasena, usuario) VALUES
('Pedro', 'Sánchez', '56789012', 'pedro.sanchez@email.com', '987654325', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'psanchez'),
('Laura', 'López', '67890123', 'laura.lopez@email.com', '987654326', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'llopez'),
('Roberto', 'Fernández', '78901234', 'roberto.fernandez@email.com', '987654327', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rfernandez'),
('Carmen', 'Torres', '89012345', 'carmen.torres@email.com', '987654328', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ctorres'),
('Luis', 'Vargas', '90123456', 'luis.vargas@email.com', '987654329', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lvargas');

-- ============================================================
-- 4. HABITACIONES
-- ============================================================

INSERT INTO Habitaciones (codigo, tipo, estado) VALUES
('HAB001', 'Suite Deluxe', 'Disponible'),
('HAB002', 'Suite Premium', 'Disponible'),
('HAB003', 'Suite Presidencial', 'Disponible'),
('HAB004', 'Habitación Estándar', 'Disponible'),
('HAB005', 'Habitación Doble', 'Disponible'),
('HAB006', 'Suite Deluxe', 'Disponible'),
('HAB007', 'Habitación Estándar', 'Disponible'),
('HAB008', 'Suite Premium', 'Disponible'),
('HAB009', 'Habitación Doble', 'Disponible'),
('HAB010', 'Suite Presidencial', 'Disponible');

-- ============================================================
-- 5. SERVICIOS (Los IDs se generan automáticamente: SER00001, SER00002, etc.)
-- ============================================================

INSERT INTO Servicios (descripcion) VALUES
('WiFi de Alta Velocidad'),
('Spa & Wellness'),
('Restaurante Gourmet'),
('Servicio de Conserjería'),
('Gimnasio Equipado'),
('Estacionamiento'),
('Lavandería'),
('Room Service 24/7'),
('Piscina'),
('Servicio de Masajes');

-- ============================================================
-- 6. TIPOS DE PAGO (Los IDs se generan automáticamente: TPG00001, TPG00002, etc.)
-- ============================================================

INSERT INTO tipoPago (descripcion) VALUES
('Efectivo'),
('Tarjeta de Crédito'),
('Tarjeta de Débito'),
('Transferencia Bancaria'),
('PayPal'),
('Cheque');

-- ============================================================
-- 7. RESERVAS (Los IDs se generan automáticamente: RES00001, RES00002, etc.)
-- ============================================================
-- Nota: Necesitas los IDs reales de Cliente, Habitaciones y tipoPago
-- Los IDs de Cliente y Administrador se generan automáticamente
-- Los IDs de Servicios y tipoPago también se generan automáticamente

-- Primero, obtenemos los IDs generados (esto es solo para referencia, los triggers los generan)
-- Insertamos reservas usando los datos que acabamos de crear

-- Reserva 1: Pedro Sánchez en HAB001 (Pagada)
INSERT INTO detalleReserva (fecha_inicio, fecha_fin, idCli, idHab, pago, idTipoPago, es_checkin_directo) 
SELECT 
    CURDATE() - INTERVAL 5 DAY as fecha_inicio,
    CURDATE() - INTERVAL 2 DAY as fecha_fin,
    c.id as idCli,
    'HAB001' as idHab,
    1 as pago,
    (SELECT id FROM tipoPago LIMIT 1) as idTipoPago,
    0 as es_checkin_directo
FROM Cliente c 
WHERE c.usuario = 'psanchez' 
LIMIT 1;

-- Reserva 2: Laura López en HAB002 (Pendiente de pago, activa ahora)
INSERT INTO detalleReserva (fecha_inicio, fecha_fin, idCli, idHab, pago, idTipoPago, es_checkin_directo) 
SELECT 
    CURDATE() as fecha_inicio,
    CURDATE() + INTERVAL 3 DAY as fecha_fin,
    c.id as idCli,
    'HAB002' as idHab,
    0 as pago,
    (SELECT id FROM tipoPago LIMIT 1 OFFSET 1) as idTipoPago,
    0 as es_checkin_directo
FROM Cliente c 
WHERE c.usuario = 'llopez' 
LIMIT 1;

-- Reserva 3: Roberto Fernández en HAB003 (Check-in directo, pagada)
INSERT INTO detalleReserva (fecha_inicio, fecha_fin, idCli, idHab, pago, idTipoPago, es_checkin_directo) 
SELECT 
    CURDATE() as fecha_inicio,
    CURDATE() + INTERVAL 1 DAY as fecha_fin,
    c.id as idCli,
    'HAB003' as idHab,
    1 as pago,
    (SELECT id FROM tipoPago LIMIT 1 OFFSET 2) as idTipoPago,
    1 as es_checkin_directo
FROM Cliente c 
WHERE c.usuario = 'rfernandez' 
LIMIT 1;

-- Reserva 4: Carmen Torres en HAB004 (Futura, pagada)
INSERT INTO detalleReserva (fecha_inicio, fecha_fin, idCli, idHab, pago, idTipoPago, es_checkin_directo) 
SELECT 
    CURDATE() + INTERVAL 5 DAY as fecha_inicio,
    CURDATE() + INTERVAL 8 DAY as fecha_fin,
    c.id as idCli,
    'HAB004' as idHab,
    1 as pago,
    (SELECT id FROM tipoPago LIMIT 1 OFFSET 3) as idTipoPago,
    0 as es_checkin_directo
FROM Cliente c 
WHERE c.usuario = 'ctorres' 
LIMIT 1;

-- Reserva 5: Luis Vargas en HAB005 (Futura, pendiente)
INSERT INTO detalleReserva (fecha_inicio, fecha_fin, idCli, idHab, pago, idTipoPago, es_checkin_directo) 
SELECT 
    CURDATE() + INTERVAL 10 DAY as fecha_inicio,
    CURDATE() + INTERVAL 12 DAY as fecha_fin,
    c.id as idCli,
    'HAB005' as idHab,
    0 as pago,
    (SELECT id FROM tipoPago LIMIT 1 OFFSET 4) as idTipoPago,
    0 as es_checkin_directo
FROM Cliente c 
WHERE c.usuario = 'lvargas' 
LIMIT 1;

-- ============================================================
-- 8. DETALLES DE SERVICIO HABITACIÓN (Los IDs se generan automáticamente: DSH00001, etc.)
-- ============================================================
-- Nota: Necesitamos IDs reales de Servicios, Habitaciones y Empleados

-- Servicio 1: WiFi en HAB001 por empleado jperez
INSERT INTO detalleServicioHob (idServicio, idHab, idEmp, fecha) 
SELECT 
    (SELECT id FROM Servicios LIMIT 1) as idServicio,
    'HAB001' as idHab,
    e.id as idEmp,
    CURDATE() - INTERVAL 3 DAY as fecha
FROM Empleado e 
WHERE e.usuario = 'jperez' 
LIMIT 1;

-- Servicio 2: Spa en HAB002 por empleado mgonzalez
INSERT INTO detalleServicioHob (idServicio, idHab, idEmp, fecha) 
SELECT 
    (SELECT id FROM Servicios LIMIT 1 OFFSET 1) as idServicio,
    'HAB002' as idHab,
    e.id as idEmp,
    CURDATE() as fecha
FROM Empleado e 
WHERE e.usuario = 'mgonzalez' 
LIMIT 1;

-- Servicio 3: Room Service en HAB003 por empleado cramirez
INSERT INTO detalleServicioHob (idServicio, idHab, idEmp, fecha) 
SELECT 
    (SELECT id FROM Servicios LIMIT 1 OFFSET 7) as idServicio,
    'HAB003' as idHab,
    e.id as idEmp,
    CURDATE() as fecha
FROM Empleado e 
WHERE e.usuario = 'cramirez' 
LIMIT 1;

-- ============================================================
-- VERIFICACIÓN: Consultas para comprobar que todo funciona
-- ============================================================

-- Ver todos los empleados con sus IDs generados
SELECT '=== EMPLEADOS ===' as Info;
SELECT id, nombres, apellidos, usuario, tipo FROM Empleado;

-- Ver todos los administradores
SELECT '=== ADMINISTRADORES ===' as Info;
SELECT id, nombres, apellidos, usuario FROM Administrador;

-- Ver todos los clientes con sus IDs generados
SELECT '=== CLIENTES ===' as Info;
SELECT id, nombres, apellidos, usuario, email FROM Cliente;

-- Ver habitaciones y su estado
SELECT '=== HABITACIONES ===' as Info;
SELECT codigo, tipo, estado FROM Habitaciones ORDER BY codigo;

-- Ver servicios con sus IDs generados
SELECT '=== SERVICIOS ===' as Info;
SELECT id, descripcion FROM Servicios;

-- Ver tipos de pago con sus IDs generados
SELECT '=== TIPOS DE PAGO ===' as Info;
SELECT id, descripcion FROM tipoPago;

-- Ver reservas con información completa
SELECT '=== RESERVAS ===' as Info;
SELECT 
    dr.id,
    dr.fecha_inicio,
    dr.fecha_fin,
    CONCAT(c.nombres, ' ', c.apellidos) as cliente,
    h.tipo as habitacion,
    h.estado as estado_habitacion,
    tp.descripcion as tipo_pago,
    CASE WHEN dr.pago = 1 THEN 'Pagado' ELSE 'Pendiente' END as estado_pago,
    CASE WHEN dr.es_checkin_directo = 1 THEN 'Sí' ELSE 'No' END as checkin_directo
FROM detalleReserva dr
JOIN Cliente c ON dr.idCli = c.id
JOIN Habitaciones h ON dr.idHab = h.codigo
JOIN tipoPago tp ON dr.idTipoPago = tp.id
ORDER BY dr.fecha_inicio DESC;

-- Ver detalles de servicio
SELECT '=== DETALLES DE SERVICIO ===' as Info;
SELECT 
    dsh.id,
    s.descripcion as servicio,
    h.codigo as habitacion,
    h.tipo as tipo_habitacion,
    CONCAT(e.nombres, ' ', e.apellidos) as empleado,
    dsh.fecha
FROM detalleServicioHob dsh
JOIN Servicios s ON dsh.idServicio = s.id
JOIN Habitaciones h ON dsh.idHab = h.codigo
JOIN Empleado e ON dsh.idEmp = e.id
ORDER BY dsh.fecha DESC;

-- Ver contadores (para verificar que los triggers funcionan)
SELECT '=== CONTADORES ===' as Info;
SELECT * FROM Contadores ORDER BY tabla;

-- Ver estadísticas (si ejecutaste triggers_mejoras.sql)
-- SELECT '=== ESTADÍSTICAS ===' as Info;
-- SELECT * FROM vista_estadisticas_reservas;

-- ============================================================
-- DATOS DE PRUEBA PARA LOGIN
-- ============================================================
-- Todos los usuarios tienen la contraseña: "password"
-- 
-- EMPLEADOS:
--   Usuario: jperez / Contraseña: password
--   Usuario: mgonzalez / Contraseña: password
--   Usuario: cramirez / Contraseña: password
--   Usuario: amartinez / Contraseña: password
--
-- ADMINISTRADORES:
--   Usuario: jperez / Contraseña: password (es empleado y admin)
--   Usuario: amartinez / Contraseña: password (es empleado y admin)
--
-- CLIENTES:
--   Usuario: psanchez / Contraseña: password
--   Usuario: llopez / Contraseña: password
--   Usuario: rfernandez / Contraseña: password
--   Usuario: ctorres / Contraseña: password
--   Usuario: lvargas / Contraseña: password
-- ============================================================

