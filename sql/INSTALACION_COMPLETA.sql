-- ============================================================
-- SCRIPT DE INSTALACIÓN COMPLETA - BASE DE DATOS HOSTALDS
-- ============================================================
-- Ejecuta este script completo en phpMyAdmin para crear
-- toda la base de datos desde cero en el orden correcto.
-- ============================================================

-- PASO 1: Crear la base de datos
CREATE DATABASE IF NOT EXISTS hostalds CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seleccionar la base de datos
USE hostalds;

-- ============================================================
-- PASO 2: CREAR TODAS LAS TABLAS
-- ============================================================

-- TABLA: Empleado
CREATE TABLE IF NOT EXISTS Empleado(
    id VARCHAR(20) PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    contrasena VARCHAR(255) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    tipo VARCHAR(20) NOT NULL
);

-- TABLA: Administrador
CREATE TABLE IF NOT EXISTS Administrador(
    id VARCHAR(20) PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    contrasena VARCHAR(255) NOT NULL,
    usuario VARCHAR(50),
    FOREIGN KEY(usuario) REFERENCES Empleado(usuario)
);

-- TABLA: Cliente
CREATE TABLE IF NOT EXISTS Cliente(
    id VARCHAR(20) PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    contrasena VARCHAR(255) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE
);

-- TABLA: Habitaciones
CREATE TABLE IF NOT EXISTS Habitaciones(
    codigo VARCHAR(20) PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'Disponible',
    descripcion VARCHAR(200)
);

-- TABLA: Servicios
CREATE TABLE IF NOT EXISTS Servicios(
    id VARCHAR(20) PRIMARY KEY,
    descripcion VARCHAR(200) NOT NULL,
    costo DECIMAL(10,2) NOT NULL DEFAULT 0.00
);

-- TABLA: tipoPago
CREATE TABLE IF NOT EXISTS tipoPago(
    id VARCHAR(20) PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL
);

-- TABLA: detalleReserva
CREATE TABLE IF NOT EXISTS detalleReserva(
    id VARCHAR(20) PRIMARY KEY,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    idCli VARCHAR(20) NOT NULL,
    idHab VARCHAR(20) NOT NULL,
    pago TINYINT(1) DEFAULT 0,
    idTipoPago VARCHAR(20) NOT NULL,
    es_checkin_directo TINYINT(1) DEFAULT 0,
    FOREIGN KEY(idCli) REFERENCES Cliente(id),
    FOREIGN KEY(idHab) REFERENCES Habitaciones(codigo),
    FOREIGN KEY(idTipoPago) REFERENCES tipoPago(id),
    CHECK (fecha_fin >= fecha_inicio)
);

-- TABLA: detalleServicioHob
CREATE TABLE IF NOT EXISTS detalleServicioHob(
    id VARCHAR(20) PRIMARY KEY,
    idServicio VARCHAR(20) NOT NULL,
    idHab VARCHAR(20) NOT NULL,
    idEmp VARCHAR(20) NOT NULL,
    fecha DATE NOT NULL,
    pago TINYINT(1) DEFAULT 0,
    FOREIGN KEY(idServicio) REFERENCES Servicios(id),
    FOREIGN KEY(idHab) REFERENCES Habitaciones(codigo),
    FOREIGN KEY(idEmp) REFERENCES Empleado(id)
);

-- ============================================================
-- PASO 3: CREAR TABLA DE CONTADORES
-- ============================================================

CREATE TABLE IF NOT EXISTS Contadores(
    tabla VARCHAR(50) PRIMARY KEY,
    ultimo_numero INT NOT NULL DEFAULT 0
);

-- Insertar registros iniciales
INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('Empleado', 0)
ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero;

INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('Cliente', 0)
ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero;

INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('Administrador', 0)
ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero;

INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('detalleReserva', 0)
ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero;

INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('Servicios', 0)
ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero;

INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('tipoPago', 0)
ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero;

INSERT INTO Contadores (tabla, ultimo_numero) VALUES ('detalleServicioHob', 0)
ON DUPLICATE KEY UPDATE ultimo_numero = ultimo_numero;

-- ============================================================
-- PASO 4: CREAR TRIGGERS BÁSICOS
-- ============================================================

-- Eliminar triggers existentes si existen
DROP TRIGGER IF EXISTS trg_empleado_id;
DROP TRIGGER IF EXISTS trg_cliente_id;
DROP TRIGGER IF EXISTS trg_administrador_id;
DROP TRIGGER IF EXISTS trg_reserva_habitacion_ocupada;
DROP TRIGGER IF EXISTS trg_reserva_id;
DROP TRIGGER IF EXISTS trg_reserva_habitacion_actualizada;
DROP TRIGGER IF EXISTS trg_reserva_habitacion_disponible;

DELIMITER //

-- Trigger para generar ID automático de Empleado
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

-- Trigger para generar ID automático de Cliente
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

-- Trigger para generar ID automático de Administrador
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

-- Trigger para generar ID automático de reserva
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

-- Trigger para cambiar estado de habitación a ocupado cuando se crea una reserva
CREATE TRIGGER trg_reserva_habitacion_ocupada
AFTER INSERT ON detalleReserva
FOR EACH ROW
BEGIN
    UPDATE Habitaciones 
    SET estado = 'Ocupado' 
    WHERE codigo = NEW.idHab;
END//

-- Trigger para cambiar estado de habitación cuando se actualiza una reserva
CREATE TRIGGER trg_reserva_habitacion_actualizada
AFTER UPDATE ON detalleReserva
FOR EACH ROW
BEGIN
    IF OLD.idHab != NEW.idHab THEN
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
        
        UPDATE Habitaciones 
        SET estado = 'Ocupado' 
        WHERE codigo = NEW.idHab;
    ELSE
        IF CURDATE() BETWEEN NEW.fecha_inicio AND NEW.fecha_fin THEN
            UPDATE Habitaciones 
            SET estado = 'Ocupado' 
            WHERE codigo = NEW.idHab;
        ELSE
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

-- Trigger para cambiar estado de habitación a disponible cuando se elimina una reserva
CREATE TRIGGER trg_reserva_habitacion_disponible
AFTER DELETE ON detalleReserva
FOR EACH ROW
BEGIN
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

-- ============================================================
-- PASO 5: CREAR TRIGGERS ADICIONALES
-- ============================================================

DROP TRIGGER IF EXISTS trg_servicio_id;
DROP TRIGGER IF EXISTS trg_tipoPago_id;
DROP TRIGGER IF EXISTS trg_detalleServicio_id;
DROP TRIGGER IF EXISTS trg_administrador_validar_empleado;
DROP TRIGGER IF EXISTS trg_prevenir_eliminacion_cliente;
DROP TRIGGER IF EXISTS trg_prevenir_eliminacion_habitacion;
DROP TRIGGER IF EXISTS trg_prevenir_eliminacion_servicio;
DROP TRIGGER IF EXISTS trg_prevenir_eliminacion_tipoPago;

DELIMITER //

-- Trigger para generar ID automático de servicio
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

-- Trigger para generar ID automático de tipoPago
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

-- Trigger para generar ID automático de detalleServicioHob
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

-- Trigger para validar que el empleado existe antes de crear administrador
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

-- Trigger para prevenir eliminación de cliente con reservas activas
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

-- Trigger para prevenir eliminación de habitación con reservas o servicios
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

-- Trigger para prevenir eliminación de servicio con detalles asociados
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

-- Trigger para prevenir eliminación de tipoPago con reservas asociadas
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

-- ============================================================
-- FIN DE LA INSTALACIÓN BÁSICA
-- ============================================================
-- La base de datos está lista para usar.
-- 
-- Si quieres las mejoras adicionales (auditoría, eventos, vistas),
-- ejecuta también: sql/triggers_mejoras.sql
-- ============================================================

