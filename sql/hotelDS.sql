USE hotelds;
    -- ==========================================================
    -- TABLA: Empleado
    -- ==========================================================
CREATE TABLE Empleado(
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
CREATE TABLE Administrador(
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
CREATE TABLE Cliente(
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
CREATE TABLE Habitaciones(
    codigo VARCHAR(20) PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL,
    estado VARCHAR(20) NOT NULL,
    descripcion VARCHAR(255)
);
-- ==========================================================
-- TABLA: Servicios
-- ==========================================================
CREATE TABLE Servicios(
    id VARCHAR(20) PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL,
    costo DECIMAL(10, 2) NOT NULL
);
-- ==========================================================
-- TABLA: tipoPago
-- ==========================================================
CREATE TABLE tipoPago(
    id VARCHAR(20) PRIMARY KEY,
    descripcion VARCHAR(255) NOT NULL
);
-- ==========================================================
-- TABLA: detalleReserva
-- ==========================================================
CREATE TABLE detalleReserva(
    id VARCHAR(20) PRIMARY KEY,
    fecha DATE NOT NULL,
    idCli VARCHAR(20) NOT NULL,
    idHab VARCHAR(20) NOT NULL,
    pago BOOLEAN DEFAULT FALSE,
    idTipoPago VARCHAR(20) NOT NULL,
    FOREIGN KEY(idCli) REFERENCES Cliente(id),
    FOREIGN KEY(idHab) REFERENCES Habitaciones(codigo),
    FOREIGN KEY(idTipoPago) REFERENCES tipoPago(id)
);
-- ==========================================================
-- TABLA: detalleServicioHob
-- ==========================================================
CREATE TABLE detalleServicioHob(
    id VARCHAR(20) PRIMARY KEY,
    idHab VARCHAR(20) NOT NULL,
    idEmp VARCHAR(20) NOT NULL,
    idServicio VARCHAR(20) NOT NULL,
    pago BOOLEAN DEFAULT FALSE,
    FOREIGN KEY(idHab) REFERENCES Habitaciones(codigo),
    FOREIGN KEY(idEmp) REFERENCES Empleado(id),
    FOREIGN KEY(idServicio) REFERENCES Servicios(id)
)