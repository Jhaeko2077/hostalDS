-- ============================================================
-- SCRIPT PARA CORREGIR PROBLEMAS DE COLLATION
-- ============================================================
-- Ejecuta este script si tienes errores de collation
-- ============================================================

USE hostalds;

-- Recrear la vista con collation correcta
DROP VIEW IF EXISTS vista_habitaciones_disponibles;

CREATE VIEW vista_habitaciones_disponibles AS
SELECT 
    h.codigo,
    h.tipo,
    h.estado,
    CASE 
        WHEN h.estado COLLATE utf8mb4_unicode_ci = 'Disponible' COLLATE utf8mb4_unicode_ci 
        THEN 'Sí' COLLATE utf8mb4_unicode_ci
        WHEN EXISTS (
            SELECT 1 FROM detalleReserva dr 
            WHERE dr.idHab = h.codigo 
            AND CURDATE() BETWEEN dr.fecha_inicio AND dr.fecha_fin
        ) THEN 'No - Reservada' COLLATE utf8mb4_unicode_ci
        ELSE CONCAT('No - ', h.estado) COLLATE utf8mb4_unicode_ci
    END as disponible_ahora
FROM Habitaciones h;

-- Verificar que la vista funciona
SELECT * FROM vista_habitaciones_disponibles LIMIT 5;

