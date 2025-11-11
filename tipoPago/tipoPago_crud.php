<?php
include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO tipoPago (id, descripcion)
            VALUES ('$id', '$descripcion')";
    $conn->query($sql);
    header("Location: tipoPagos.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE tipoPago 
            SET descripcion='$descripcion'
            WHERE id='$id'";
    $conn->query($sql);
    header("Location: tipoPagos.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM tipoPago WHERE id='$id'");
    header("Location: tipoPagos.php");
    exit();
}
?>