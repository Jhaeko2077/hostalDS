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
    $id = trim($_POST['id']);
    $descripcion = trim($_POST['descripcion']);

    $stmt = $conn->prepare("INSERT INTO tipoPago (id, descripcion)
            VALUES (?, ?)");
    $stmt->bind_param("ss", $id, $descripcion);
    $stmt->execute();
    $stmt->close();
    header("Location: tipoPagos.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = trim($_POST['id']);
    $descripcion = trim($_POST['descripcion']);

    $stmt = $conn->prepare("UPDATE tipoPago 
            SET descripcion=?
            WHERE id=?");
    $stmt->bind_param("ss", $descripcion, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: tipoPagos.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = trim($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM tipoPago WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: tipoPagos.php");
    exit();
}
?>