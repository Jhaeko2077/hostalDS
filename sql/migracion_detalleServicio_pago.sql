-- ============================================================
-- MIGRACIÓN: Agregar columnas faltantes a las tablas
-- ============================================================
-- Este script agrega las columnas faltantes a las tablas para
-- bases de datos que ya fueron creadas sin estas columnas.
-- 
-- Ejecuta este script en phpMyAdmin si ya tienes una base de datos
-- creada y necesitas agregar estas funcionalidades.
-- 
-- NOTA: Si alguna columna ya existe, el comando ALTER TABLE fallará
-- con un error, pero puedes ignorarlo y continuar.
-- ============================================================

USE hostalds;

-- ============================================================
-- 1. Agregar columna 'pago' a detalleServicioHob
-- ============================================================
-- Si la columna ya existe, este comando fallará - puedes ignorar el error
ALTER TABLE detalleServicioHob 
ADD COLUMN pago TINYINT(1) DEFAULT 0 AFTER fecha;

-- Actualizar todos los registros existentes a 'no pagado' (0) por defecto
UPDATE detalleServicioHob 
SET pago = 0 
WHERE pago IS NULL;

-- ============================================================
-- 2. Agregar columna 'descripcion' a Habitaciones
-- ============================================================
-- Si la columna ya existe, este comando fallará - puedes ignorar el error
ALTER TABLE Habitaciones 
ADD COLUMN descripcion VARCHAR(200) AFTER estado;

-- ============================================================
-- 3. Agregar columna 'costo' a Servicios
-- ============================================================
-- Si la columna ya existe, este comando fallará - puedes ignorar el error
ALTER TABLE Servicios 
ADD COLUMN costo DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER descripcion;

-- Actualizar servicios existentes sin costo a 0.00
UPDATE Servicios 
SET costo = 0.00 
WHERE costo IS NULL;

-- ============================================================
-- Verificación: Ver todas las columnas agregadas
-- ============================================================
SELECT 
    'detalleServicioHob' as Tabla,
    COLUMN_NAME, 
    DATA_TYPE, 
    COLUMN_DEFAULT, 
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'hostalds' 
  AND TABLE_NAME = 'detalleServicioHob' 
  AND COLUMN_NAME = 'pago'

UNION ALL

SELECT 
    'Habitaciones' as Tabla,
    COLUMN_NAME, 
    DATA_TYPE, 
    COLUMN_DEFAULT, 
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'hostalds' 
  AND TABLE_NAME = 'Habitaciones' 
  AND COLUMN_NAME = 'descripcion'

UNION ALL

SELECT 
    'Servicios' as Tabla,
    COLUMN_NAME, 
    DATA_TYPE, 
    COLUMN_DEFAULT, 
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'hostalds' 
  AND TABLE_NAME = 'Servicios' 
  AND COLUMN_NAME = 'costo';

-- Mensaje de confirmación
SELECT 'Migración completada: Todas las columnas faltantes han sido agregadas' as Resultado;
