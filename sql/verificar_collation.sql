-- ============================================================
-- SCRIPT PARA VERIFICAR Y CORREGIR COLLATION
-- ============================================================
-- Ejecuta este script para verificar que todas las tablas
-- tienen el collation correcto
-- ============================================================

USE hostalds;

-- Verificar collation de la base de datos
SELECT 
    SCHEMA_NAME as 'Base de Datos',
    DEFAULT_CHARACTER_SET_NAME as 'Charset',
    DEFAULT_COLLATION_NAME as 'Collation'
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME = 'hostalds';

-- Verificar collation de todas las tablas
SELECT 
    TABLE_NAME as 'Tabla',
    TABLE_COLLATION as 'Collation'
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'hostalds'
ORDER BY TABLE_NAME;

-- Verificar collation de columnas VARCHAR/TEXT
SELECT 
    TABLE_NAME as 'Tabla',
    COLUMN_NAME as 'Columna',
    CHARACTER_SET_NAME as 'Charset',
    COLLATION_NAME as 'Collation'
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'hostalds'
AND DATA_TYPE IN ('varchar', 'text', 'char')
ORDER BY TABLE_NAME, COLUMN_NAME;

-- Si encuentras collations diferentes, puedes corregirlas con:
-- ALTER TABLE nombre_tabla CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

