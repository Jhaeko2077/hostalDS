USE hostalds;

-- Script de migración para actualizar la estructura de detalleReserva
-- Ejecutar solo si la tabla ya existe con el campo 'fecha'

-- Verificar si existe el campo 'fecha' y migrar a fecha_inicio y fecha_fin
SET @col_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = 'hostalds' 
    AND TABLE_NAME = 'detalleReserva' 
    AND COLUMN_NAME = 'fecha'
);

SET @sql = IF(@col_exists > 0,
    'ALTER TABLE detalleReserva 
     ADD COLUMN fecha_inicio DATE AFTER id,
     ADD COLUMN fecha_fin DATE AFTER fecha_inicio,
     ADD COLUMN es_checkin_directo BOOLEAN DEFAULT FALSE AFTER idTipoPago,
     ADD CONSTRAINT chk_fechas CHECK (fecha_fin >= fecha_inicio);
     
     -- Migrar datos existentes
     UPDATE detalleReserva SET fecha_inicio = fecha, fecha_fin = DATE_ADD(fecha, INTERVAL 1 DAY) WHERE fecha_inicio IS NULL;
     
     -- Eliminar el campo fecha antiguo
     ALTER TABLE detalleReserva DROP COLUMN fecha;',
    'SELECT "La tabla ya tiene la estructura correcta" AS mensaje;'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Agregar contador para reservas si no existe
INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('detalleReserva', 0)
ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero;

