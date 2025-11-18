USE hostalds;

-- Eliminar triggers adicionales si existen
DROP TRIGGER IF EXISTS trg_servicio_id;
DROP TRIGGER IF EXISTS trg_tipoPago_id;
DROP TRIGGER IF EXISTS trg_detalleServicio_id;
DROP TRIGGER IF EXISTS trg_administrador_validar_empleado;
DROP TRIGGER IF EXISTS trg_prevenir_eliminacion_cliente;
DROP TRIGGER IF EXISTS trg_prevenir_eliminacion_habitacion;
DROP TRIGGER IF EXISTS trg_prevenir_eliminacion_servicio;
DROP TRIGGER IF EXISTS trg_prevenir_eliminacion_tipoPago;

-- Trigger para generar ID automático de servicio
DELIMITER //
CREATE TRIGGER trg_servicio_id
BEFORE INSERT ON Servicios
FOR EACH ROW
BEGIN
    DECLARE nuevoNumero INT;
    DECLARE nuevoID VARCHAR(20);
    DECLARE prefijo VARCHAR(3) DEFAULT 'SER';

    IF NEW.id IS NULL OR NEW.id = '' THEN
        SELECT ultimo_numero + 1 INTO nuevoNumero FROM Contadores WHERE tabla = 'Servicios' FOR UPDATE;
        IF nuevoNumero IS NULL THEN
            INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('Servicios', 1)
            ON DUPLICATE KEY UPDATE ultimo_numero = 1;
            SET nuevoNumero = 1;
        END IF;
        SET nuevoID = CONCAT(prefijo, LPAD(nuevoNumero, 5, '0'));
        SET NEW.id = nuevoID;
        UPDATE Contadores SET ultimo_numero = nuevoNumero WHERE tabla = 'Servicios';
    END IF;
END//
DELIMITER ;

-- Trigger para generar ID automático de tipoPago
DELIMITER //
CREATE TRIGGER trg_tipoPago_id
BEFORE INSERT ON tipoPago
FOR EACH ROW
BEGIN
    DECLARE nuevoNumero INT;
    DECLARE nuevoID VARCHAR(20);
    DECLARE prefijo VARCHAR(3) DEFAULT 'TPG';

    IF NEW.id IS NULL OR NEW.id = '' THEN
        SELECT ultimo_numero + 1 INTO nuevoNumero FROM Contadores WHERE tabla = 'tipoPago' FOR UPDATE;
        IF nuevoNumero IS NULL THEN
            INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('tipoPago', 1)
            ON DUPLICATE KEY UPDATE ultimo_numero = 1;
            SET nuevoNumero = 1;
        END IF;
        SET nuevoID = CONCAT(prefijo, LPAD(nuevoNumero, 5, '0'));
        SET NEW.id = nuevoID;
        UPDATE Contadores SET ultimo_numero = nuevoNumero WHERE tabla = 'tipoPago';
    END IF;
END//
DELIMITER ;

-- Trigger para generar ID automático de detalleServicioHob
DELIMITER //
CREATE TRIGGER trg_detalleServicio_id
BEFORE INSERT ON detalleServicioHob
FOR EACH ROW
BEGIN
    DECLARE nuevoNumero INT;
    DECLARE nuevoID VARCHAR(20);
    DECLARE prefijo VARCHAR(3) DEFAULT 'DSH';

    IF NEW.id IS NULL OR NEW.id = '' THEN
        SELECT ultimo_numero + 1 INTO nuevoNumero FROM Contadores WHERE tabla = 'detalleServicioHob' FOR UPDATE;
        IF nuevoNumero IS NULL THEN
            INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('detalleServicioHob', 1)
            ON DUPLICATE KEY UPDATE ultimo_numero = 1;
            SET nuevoNumero = 1;
        END IF;
        SET nuevoID = CONCAT(prefijo, LPAD(nuevoNumero, 5, '0'));
        SET NEW.id = nuevoID;
        UPDATE Contadores SET ultimo_numero = nuevoNumero WHERE tabla = 'detalleServicioHob';
    END IF;
END//
DELIMITER ;

-- Trigger para validar que el empleado existe antes de crear administrador
DELIMITER //
CREATE TRIGGER trg_administrador_validar_empleado
BEFORE INSERT ON Administrador
FOR EACH ROW
BEGIN
    DECLARE empleado_existe INT;
    
    SELECT COUNT(*) INTO empleado_existe 
    FROM Empleado 
    WHERE usuario = NEW.usuario;
    
    IF empleado_existe = 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'El usuario del empleado debe existir antes de crear un administrador';
    END IF;
END//
DELIMITER ;

-- Trigger para prevenir eliminación de cliente con reservas activas
DELIMITER //
CREATE TRIGGER trg_prevenir_eliminacion_cliente
BEFORE DELETE ON Cliente
FOR EACH ROW
BEGIN
    DECLARE tiene_reservas INT;
    
    SELECT COUNT(*) INTO tiene_reservas 
    FROM detalleReserva 
    WHERE idCli = OLD.id;
    
    IF tiene_reservas > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'No se puede eliminar un cliente que tiene reservas asociadas';
    END IF;
END//
DELIMITER ;

-- Trigger para prevenir eliminación de habitación con reservas o servicios
DELIMITER //
CREATE TRIGGER trg_prevenir_eliminacion_habitacion
BEFORE DELETE ON Habitaciones
FOR EACH ROW
BEGIN
    DECLARE tiene_reservas INT;
    DECLARE tiene_servicios INT;
    
    SELECT COUNT(*) INTO tiene_reservas 
    FROM detalleReserva 
    WHERE idHab = OLD.codigo;
    
    SELECT COUNT(*) INTO tiene_servicios 
    FROM detalleServicioHob 
    WHERE idHab = OLD.codigo;
    
    IF tiene_reservas > 0 OR tiene_servicios > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'No se puede eliminar una habitación que tiene reservas o servicios asociados';
    END IF;
END//
DELIMITER ;

-- Trigger para prevenir eliminación de servicio con detalles asociados
DELIMITER //
CREATE TRIGGER trg_prevenir_eliminacion_servicio
BEFORE DELETE ON Servicios
FOR EACH ROW
BEGIN
    DECLARE tiene_detalles INT;
    
    SELECT COUNT(*) INTO tiene_detalles 
    FROM detalleServicioHob 
    WHERE idServicio = OLD.id;
    
    IF tiene_detalles > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'No se puede eliminar un servicio que tiene detalles asociados';
    END IF;
END//
DELIMITER ;

-- Trigger para prevenir eliminación de tipoPago con reservas asociadas
DELIMITER //
CREATE TRIGGER trg_prevenir_eliminacion_tipoPago
BEFORE DELETE ON tipoPago
FOR EACH ROW
BEGIN
    DECLARE tiene_reservas INT;
    
    SELECT COUNT(*) INTO tiene_reservas 
    FROM detalleReserva 
    WHERE idTipoPago = OLD.id;
    
    IF tiene_reservas > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'No se puede eliminar un tipo de pago que tiene reservas asociadas';
    END IF;
END//
DELIMITER ;

