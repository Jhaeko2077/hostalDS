USE hostalds;

-- Eliminar triggers existentes si existen
DROP TRIGGER IF EXISTS trg_empleado_id;
DROP TRIGGER IF EXISTS trg_cliente_id;
DROP TRIGGER IF EXISTS trg_administrador_id;
DROP TRIGGER IF EXISTS trg_reserva_habitacion_ocupada;
DROP TRIGGER IF EXISTS trg_reserva_id;
DROP TRIGGER IF EXISTS trg_reserva_habitacion_actualizada;
DROP TRIGGER IF EXISTS trg_reserva_habitacion_disponible;

DELIMITER //
CREATE TRIGGER trg_empleado_id
BEFORE INSERT ON Empleado
FOR EACH ROW
BEGIN
    DECLARE iniciales VARCHAR(2);
    DECLARE nuevoNumero INT;
    DECLARE nuevoID VARCHAR(20);

    SET iniciales = CONCAT(LEFT(NEW.nombres,1), LEFT(NEW.apellidos,1));
    SELECT ultimo_numero + 1 INTO nuevoNumero FROM Contadores WHERE tabla = 'Empleado' FOR UPDATE;
    SET nuevoID = CONCAT(UCASE(iniciales), LPAD(nuevoNumero, 3, '0'));
    SET NEW.id = nuevoID;
    UPDATE Contadores SET ultimo_numero = nuevoNumero WHERE tabla = 'Empleado';
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER trg_cliente_id
BEFORE INSERT ON Cliente
FOR EACH ROW
BEGIN
    DECLARE iniciales VARCHAR(2);
    DECLARE nuevoNumero INT;
    DECLARE nuevoID VARCHAR(20);

    SET iniciales = CONCAT(LEFT(NEW.nombres,1), LEFT(NEW.apellidos,1));
    SELECT ultimo_numero + 1 INTO nuevoNumero FROM Contadores WHERE tabla = 'Cliente' FOR UPDATE;
    SET nuevoID = CONCAT(UCASE(iniciales), LPAD(nuevoNumero, 3, '0'));
    SET NEW.id = nuevoID;
    UPDATE Contadores SET ultimo_numero = nuevoNumero WHERE tabla = 'Cliente';
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER trg_administrador_id
BEFORE INSERT ON Administrador
FOR EACH ROW
BEGIN
    DECLARE iniciales VARCHAR(2);
    DECLARE nuevoNumero INT;
    DECLARE nuevoID VARCHAR(20);

    SET iniciales = CONCAT(LEFT(NEW.nombres,1), LEFT(NEW.apellidos,1));
    SELECT ultimo_numero + 1 INTO nuevoNumero FROM Contadores WHERE tabla = 'Administrador' FOR UPDATE;
    SET nuevoID = CONCAT(UCASE(iniciales), LPAD(nuevoNumero, 3, '0'));
    SET NEW.id = nuevoID;
    UPDATE Contadores SET ultimo_numero = nuevoNumero WHERE tabla = 'Administrador';
END//
DELIMITER ;

-- Trigger para generar ID automático de reserva
DELIMITER //
CREATE TRIGGER trg_reserva_id
BEFORE INSERT ON detalleReserva
FOR EACH ROW
BEGIN
    DECLARE nuevoNumero INT;
    DECLARE nuevoID VARCHAR(20);
    DECLARE prefijo VARCHAR(3) DEFAULT 'RES';

    IF NEW.id IS NULL OR NEW.id = '' THEN
        SELECT ultimo_numero + 1 INTO nuevoNumero FROM Contadores WHERE tabla = 'detalleReserva' FOR UPDATE;
        IF nuevoNumero IS NULL THEN
            INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('detalleReserva', 1)
            ON DUPLICATE KEY UPDATE ultimo_numero = 1;
            SET nuevoNumero = 1;
        END IF;
        SET nuevoID = CONCAT(prefijo, LPAD(nuevoNumero, 5, '0'));
        SET NEW.id = nuevoID;
        UPDATE Contadores SET ultimo_numero = nuevoNumero WHERE tabla = 'detalleReserva';
    END IF;
END//
DELIMITER ;

-- Trigger para cambiar estado de habitación a ocupado cuando se crea una reserva
DELIMITER //
CREATE TRIGGER trg_reserva_habitacion_ocupada
AFTER INSERT ON detalleReserva
FOR EACH ROW
BEGIN
    UPDATE Habitaciones 
    SET estado = 'Ocupado' 
    WHERE codigo = NEW.idHab;
END//
DELIMITER ;

-- Trigger para cambiar estado de habitación cuando se actualiza una reserva
DELIMITER //
CREATE TRIGGER trg_reserva_habitacion_actualizada
AFTER UPDATE ON detalleReserva
FOR EACH ROW
BEGIN
    -- Si cambió la habitación, liberar la anterior y ocupar la nueva
    IF OLD.idHab != NEW.idHab THEN
        -- Liberar habitación anterior si no hay otras reservas activas
        IF NOT EXISTS (
            SELECT 1 FROM detalleReserva 
            WHERE idHab = OLD.idHab 
            AND id != NEW.id
            AND CURDATE() BETWEEN fecha_inicio AND fecha_fin
        ) THEN
            UPDATE Habitaciones 
            SET estado = 'Disponible' 
            WHERE codigo = OLD.idHab;
        END IF;
        
        -- Ocupar nueva habitación
        UPDATE Habitaciones 
        SET estado = 'Ocupado' 
        WHERE codigo = NEW.idHab;
    ELSE
        -- Si es la misma habitación, asegurar que esté ocupada si la fecha actual está en el rango
        IF CURDATE() BETWEEN NEW.fecha_inicio AND NEW.fecha_fin THEN
            UPDATE Habitaciones 
            SET estado = 'Ocupado' 
            WHERE codigo = NEW.idHab;
        ELSE
            -- Si la fecha actual no está en el rango, verificar si hay otras reservas
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
    END IF;
END//
DELIMITER ;

-- Trigger para cambiar estado de habitación a disponible cuando se elimina una reserva
DELIMITER //
CREATE TRIGGER trg_reserva_habitacion_disponible
AFTER DELETE ON detalleReserva
FOR EACH ROW
BEGIN
    -- Verificar si hay otras reservas activas para esta habitación
    IF NOT EXISTS (
        SELECT 1 FROM detalleReserva 
        WHERE idHab = OLD.idHab 
        AND CURDATE() BETWEEN fecha_inicio AND fecha_fin
    ) THEN
        UPDATE Habitaciones 
        SET estado = 'Disponible' 
        WHERE codigo = OLD.idHab;
    END IF;
END//
DELIMITER ;
