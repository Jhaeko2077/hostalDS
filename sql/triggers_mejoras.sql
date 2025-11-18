USE hostalds;

-- ============================================
-- TRIGGERS ADICIONALES PARA AUTOMATIZACIÓN
-- ============================================

-- Eliminar triggers si existen
DROP TRIGGER IF EXISTS trg_auto_liberar_habitaciones;
DROP TRIGGER IF EXISTS trg_validar_fechas_reserva;
DROP TRIGGER IF EXISTS trg_auditoria_reservas;
DROP TRIGGER IF EXISTS trg_auditoria_clientes;
DROP TRIGGER IF EXISTS trg_auditoria_habitaciones;

-- ============================================
-- 1. TRIGGER: Auto-liberar habitaciones cuando termina la reserva
-- ============================================
-- Este trigger se ejecuta diariamente (requiere evento programado)
-- O se puede ejecutar manualmente cuando se consulta el estado

DELIMITER //
CREATE TRIGGER trg_auto_liberar_habitaciones
AFTER UPDATE ON detalleReserva
FOR EACH ROW
BEGIN
    -- Si la fecha_fin pasó y la reserva no está pagada o ya terminó
    IF NEW.fecha_fin < CURDATE() AND NEW.pago = 0 THEN
        -- Marcar habitación como "Limpieza" si la reserva terminó sin pagar
        UPDATE Habitaciones 
        SET estado = 'Limpieza' 
        WHERE codigo = NEW.idHab;
    END IF;
    
    -- Si la fecha_fin pasó y está pagada, liberar la habitación
    IF NEW.fecha_fin < CURDATE() AND NEW.pago = 1 THEN
        -- Verificar si no hay otras reservas activas
        IF NOT EXISTS (
            SELECT 1 FROM detalleReserva 
            WHERE idHab = NEW.idHab 
            AND id != NEW.id
            AND CURDATE() BETWEEN fecha_inicio AND fecha_fin
        ) THEN
            UPDATE Habitaciones 
            SET estado = 'Disponible' 
            WHERE codigo = NEW.idHab;
        END IF;
    END IF;
END//
DELIMITER ;

-- ============================================
-- 2. TRIGGER: Validar fechas de reserva
-- ============================================
DELIMITER //
CREATE TRIGGER trg_validar_fechas_reserva
BEFORE INSERT ON detalleReserva
FOR EACH ROW
BEGIN
    -- Validar que fecha_inicio no sea en el pasado (excepto check-in directo)
    IF NEW.es_checkin_directo = 0 AND NEW.fecha_inicio < CURDATE() THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'No se pueden crear reservas con fecha de inicio en el pasado. Use check-in directo si necesita reservar para hoy.';
    END IF;
    
    -- Validar que fecha_fin sea mayor o igual a fecha_inicio
    IF NEW.fecha_fin < NEW.fecha_inicio THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'La fecha de fin debe ser mayor o igual a la fecha de inicio';
    END IF;
    
    -- Validar que no se reserve por más de 1 año
    IF DATEDIFF(NEW.fecha_fin, NEW.fecha_inicio) > 365 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'No se pueden hacer reservas por más de 365 días';
    END IF;
END//
DELIMITER ;

-- ============================================
-- 3. TRIGGER: Auditoría de reservas (crear tabla de logs primero)
-- ============================================
-- Primero crear la tabla de auditoría si no existe
CREATE TABLE IF NOT EXISTS auditoria_reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id VARCHAR(20),
    accion VARCHAR(20), -- INSERT, UPDATE, DELETE
    usuario VARCHAR(50),
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    datos_anteriores TEXT,
    datos_nuevos TEXT,
    INDEX idx_reserva (reserva_id),
    INDEX idx_fecha (fecha_hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DELIMITER //
CREATE TRIGGER trg_auditoria_reservas_insert
AFTER INSERT ON detalleReserva
FOR EACH ROW
BEGIN
    INSERT INTO auditoria_reservas (reserva_id, accion, datos_nuevos)
    VALUES (
        NEW.id,
        'INSERT',
        CONCAT('Cliente: ', NEW.idCli, ', Habitación: ', NEW.idHab, ', Fechas: ', NEW.fecha_inicio, ' - ', NEW.fecha_fin)
    );
END//

CREATE TRIGGER trg_auditoria_reservas_update
AFTER UPDATE ON detalleReserva
FOR EACH ROW
BEGIN
    INSERT INTO auditoria_reservas (reserva_id, accion, datos_anteriores, datos_nuevos)
    VALUES (
        NEW.id,
        'UPDATE',
        CONCAT('Cliente: ', OLD.idCli, ', Habitación: ', OLD.idHab, ', Fechas: ', OLD.fecha_inicio, ' - ', OLD.fecha_fin, ', Pago: ', OLD.pago),
        CONCAT('Cliente: ', NEW.idCli, ', Habitación: ', NEW.idHab, ', Fechas: ', NEW.fecha_inicio, ' - ', NEW.fecha_fin, ', Pago: ', NEW.pago)
    );
END//

CREATE TRIGGER trg_auditoria_reservas_delete
AFTER DELETE ON detalleReserva
FOR EACH ROW
BEGIN
    INSERT INTO auditoria_reservas (reserva_id, accion, datos_anteriores)
    VALUES (
        OLD.id,
        'DELETE',
        CONCAT('Cliente: ', OLD.idCli, ', Habitación: ', OLD.idHab, ', Fechas: ', OLD.fecha_inicio, ' - ', OLD.fecha_fin)
    );
END//
DELIMITER ;

-- ============================================
-- 4. TRIGGER: Auditoría de clientes
-- ============================================
CREATE TABLE IF NOT EXISTS auditoria_clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id VARCHAR(20),
    accion VARCHAR(20),
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    datos_cambio TEXT,
    INDEX idx_cliente (cliente_id),
    INDEX idx_fecha (fecha_hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DELIMITER //
CREATE TRIGGER trg_auditoria_clientes
AFTER UPDATE ON Cliente
FOR EACH ROW
BEGIN
    DECLARE cambios TEXT DEFAULT '';
    
    IF OLD.nombres != NEW.nombres OR OLD.apellidos != NEW.apellidos THEN
        SET cambios = CONCAT(cambios, 'Nombre: ', OLD.nombres, ' ', OLD.apellidos, ' -> ', NEW.nombres, ' ', NEW.apellidos, '; ');
    END IF;
    
    IF OLD.email != NEW.email THEN
        SET cambios = CONCAT(cambios, 'Email: ', OLD.email, ' -> ', NEW.email, '; ');
    END IF;
    
    IF OLD.telefono != NEW.telefono THEN
        SET cambios = CONCAT(cambios, 'Teléfono: ', OLD.telefono, ' -> ', NEW.telefono, '; ');
    END IF;
    
    IF cambios != '' THEN
        INSERT INTO auditoria_clientes (cliente_id, accion, datos_cambio)
        VALUES (NEW.id, 'UPDATE', cambios);
    END IF;
END//
DELIMITER ;

-- ============================================
-- 5. TRIGGER: Auditoría de habitaciones
-- ============================================
CREATE TABLE IF NOT EXISTS auditoria_habitaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    habitacion_codigo VARCHAR(20),
    accion VARCHAR(20),
    estado_anterior VARCHAR(20),
    estado_nuevo VARCHAR(20),
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_habitacion (habitacion_codigo),
    INDEX idx_fecha (fecha_hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DELIMITER //
CREATE TRIGGER trg_auditoria_habitaciones
AFTER UPDATE ON Habitaciones
FOR EACH ROW
BEGIN
    IF OLD.estado != NEW.estado THEN
        INSERT INTO auditoria_habitaciones (habitacion_codigo, accion, estado_anterior, estado_nuevo)
        VALUES (NEW.codigo, 'UPDATE', OLD.estado, NEW.estado);
    END IF;
END//
DELIMITER ;

-- ============================================
-- 6. EVENTO PROGRAMADO: Auto-liberar habitaciones diariamente
-- ============================================
-- Este evento se ejecuta automáticamente cada día a las 2 AM
-- para liberar habitaciones cuya reserva ya terminó

SET GLOBAL event_scheduler = ON;

DROP EVENT IF EXISTS evt_liberar_habitaciones_diario;

DELIMITER //
CREATE EVENT evt_liberar_habitaciones_diario
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_DATE + INTERVAL 1 DAY + INTERVAL 2 HOUR
DO
BEGIN
    -- Liberar habitaciones de reservas que ya terminaron y están pagadas
    UPDATE Habitaciones h
    INNER JOIN detalleReserva dr ON h.codigo = dr.idHab
    SET h.estado = 'Disponible'
    WHERE dr.fecha_fin < CURDATE()
    AND dr.pago = 1
    AND h.estado = 'Ocupado'
    AND NOT EXISTS (
        SELECT 1 FROM detalleReserva dr2
        WHERE dr2.idHab = h.codigo
        AND dr2.id != dr.id
        AND CURDATE() BETWEEN dr2.fecha_inicio AND dr2.fecha_fin
    );
    
    -- Marcar habitaciones como "Limpieza" si la reserva terminó sin pagar
    UPDATE Habitaciones h
    INNER JOIN detalleReserva dr ON h.codigo = dr.idHab
    SET h.estado = 'Limpieza'
    WHERE dr.fecha_fin < CURDATE()
    AND dr.pago = 0
    AND h.estado = 'Ocupado';
END//
DELIMITER ;

-- ============================================
-- 7. VISTA: Estadísticas de reservas
-- ============================================
CREATE OR REPLACE VIEW vista_estadisticas_reservas AS
SELECT 
    COUNT(*) as total_reservas,
    COUNT(CASE WHEN pago = 1 THEN 1 END) as reservas_pagadas,
    COUNT(CASE WHEN pago = 0 THEN 1 END) as reservas_pendientes,
    COUNT(CASE WHEN es_checkin_directo = 1 THEN 1 END) as checkins_directos,
    COUNT(CASE WHEN CURDATE() BETWEEN fecha_inicio AND fecha_fin THEN 1 END) as reservas_activas,
    COUNT(CASE WHEN fecha_fin < CURDATE() THEN 1 END) as reservas_completadas
FROM detalleReserva;

-- ============================================
-- 8. VISTA: Habitaciones disponibles ahora
-- ============================================
CREATE OR REPLACE VIEW vista_habitaciones_disponibles AS
SELECT 
    h.codigo,
    h.tipo,
    h.estado,
    CASE 
        WHEN h.estado = 'Disponible' THEN 'Sí'
        WHEN EXISTS (
            SELECT 1 FROM detalleReserva dr 
            WHERE dr.idHab = h.codigo 
            AND CURDATE() BETWEEN dr.fecha_inicio AND dr.fecha_fin
        ) THEN 'No - Reservada'
        ELSE 'No - ' || h.estado
    END as disponible_ahora
FROM Habitaciones h;

