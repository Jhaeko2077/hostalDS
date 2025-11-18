USE hostalds;

-- ==========================================================
-- TABLA: Contadores
-- ==========================================================
CREATE TABLE IF NOT EXISTS Contadores(
    tabla VARCHAR(50) PRIMARY KEY,
    ultimo_numero INT NOT NULL DEFAULT 0
);

-- Insertar registros iniciales para cada tabla que usa contadores
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

