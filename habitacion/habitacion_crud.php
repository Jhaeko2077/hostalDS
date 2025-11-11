<?php
include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO Habitaciones (codigo, tipo, estado, descripcion)
            VALUES ('$codigo', '$tipo', '$estado', '$descripcion')";
    $conn->query($sql);
    header("Location: habitaciones.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE Habitaciones 
            SET tipo='$tipo', estado='$estado', descripcion='$descripcion'
            WHERE codigo='$codigo'";
    $conn->query($sql);
    header("Location: habitaciones.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $codigo = $_GET['eliminar'];
    $conn->query("DELETE FROM Habitaciones WHERE codigo='$codigo'");
    header("Location: habitaciones.php");
    exit();
}
?>