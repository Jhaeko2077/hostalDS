<?php
// Verificar sesión de empleado o administrador (solo ellos pueden gestionar servicios)
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = trim($_POST['id']);
    $descripcion = trim($_POST['descripcion']);
    $costo = floatval($_POST['costo']);

    $stmt = $conn->prepare("INSERT INTO Servicios (id, descripcion, costo)
            VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $id, $descripcion, $costo);
    $stmt->execute();
    $stmt->close();
    header("Location: servicios.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = trim($_POST['id']);
    $descripcion = trim($_POST['descripcion']);
    $costo = floatval($_POST['costo']);

    $stmt = $conn->prepare("UPDATE Servicios 
            SET descripcion=?, costo=?
            WHERE id=?");
    $stmt->bind_param("sds", $descripcion, $costo, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: servicios.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = trim($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM Servicios WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: servicios.php");
    exit();
}
?>