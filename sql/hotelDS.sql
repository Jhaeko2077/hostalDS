USE hostalds;
-- ==========================================================
-- TABLA: Empleado
-- ==========================================================
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
-- ==========================================================
-- TABLA: Administrador
-- ==========================================================
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
-- ==========================================================
-- TABLA: Cliente
-- ==========================================================
CREATE TABLE IF NOT EXISTS Cliente(
    id VARCHAR(20) PRIMARY KEY,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    contrasena VARCHAR(255) NOT NULL,
    usuario VARCHAR(50)
);
-- ==========================================================
-- TABLA: Habitaciones
-- ==========================================================
CREATE TABLE IF NOT EXISTS Habitaciones(
    codigo VARCHAR(20) PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'Disponible',
    descripcion VARCHAR(255)
);
-- ==========================================================
-- TABLA: Servicios
-- ==========================================================
CREATE TABLE IF NOT EXISTS Servicios(
    id VARCHAR(20) PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    costo DECIMAL(10, 2) NOT NULL
);
-- ==========================================================
-- TABLA: tipoPago
-- ==========================================================
CREATE TABLE IF NOT EXISTS tipoPago(
    id VARCHAR(20) PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL
);
-- ==========================================================
-- TABLA: detalleReserva
-- ==========================================================
CREATE TABLE IF NOT EXISTS detalleReserva(
    id VARCHAR(20) PRIMARY KEY,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    idCli VARCHAR(20) NOT NULL,
    idHab VARCHAR(20) NOT NULL,
    pago BOOLEAN DEFAULT FALSE,
    idTipoPago VARCHAR(20) NOT NULL,
    es_checkin_directo BOOLEAN DEFAULT FALSE,
    FOREIGN KEY(idCli) REFERENCES Cliente(id),
    FOREIGN KEY(idHab) REFERENCES Habitaciones(codigo),
    FOREIGN KEY(idTipoPago) REFERENCES tipoPago(id),
    CHECK (fecha_fin >= fecha_inicio)
);
-- ==========================================================
-- TABLA: detalleServicioHob
-- ==========================================================
CREATE TABLE IF NOT EXISTS detalleServicioHob(
    id VARCHAR(20) PRIMARY KEY,
    idHab VARCHAR(20) NOT NULL,
    idEmp VARCHAR(20) NOT NULL,
    idServicio VARCHAR(20) NOT NULL,
    pago BOOLEAN DEFAULT FALSE,
    FOREIGN KEY(idHab) REFERENCES Habitaciones(codigo),
    FOREIGN KEY(idEmp) REFERENCES Empleado(id),
    FOREIGN KEY(idServicio) REFERENCES Servicios(id)
);