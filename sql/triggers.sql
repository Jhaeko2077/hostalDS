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
BEFORE INSERT ON cliente
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
BEFORE INSERT ON administrador
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
