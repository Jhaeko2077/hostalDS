<?php
// Verificar sesión de empleado o administrador
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $codigo = trim($_POST['codigo']);
    $tipo = trim($_POST['tipo']);
    $estado = trim($_POST['estado']);
    $descripcion = trim($_POST['descripcion']);

    $stmt = $conn->prepare("INSERT INTO Habitaciones (codigo, tipo, estado, descripcion)
            VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $codigo, $tipo, $estado, $descripcion);
    $stmt->execute();
    $stmt->close();
    header("Location: habitaciones.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $codigo = trim($_POST['codigo']);
    $tipo = trim($_POST['tipo']);
    $estado = trim($_POST['estado']);
    $descripcion = trim($_POST['descripcion']);

    $stmt = $conn->prepare("UPDATE Habitaciones 
            SET tipo=?, estado=?, descripcion=?
            WHERE codigo=?");
    $stmt->bind_param("ssss", $tipo, $estado, $descripcion, $codigo);
    $stmt->execute();
    $stmt->close();
    header("Location: habitaciones.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $codigo = trim($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM Habitaciones WHERE codigo=?");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $stmt->close();
    header("Location: habitaciones.php");
    exit();
}
?>