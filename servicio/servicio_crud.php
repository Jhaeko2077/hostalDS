<?php
include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];

    $sql = "INSERT INTO Servicios (id, descripcion, costo)
            VALUES ('$id', '$descripcion', '$costo')";
    $conn->query($sql);
    header("Location: servicios.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $costo = $_POST['costo'];

    $sql = "UPDATE Servicios 
            SET descripcion='$descripcion', costo='$costo'
            WHERE id='$id'";
    $conn->query($sql);
    header("Location: servicios.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM Servicios WHERE id='$id'");
    header("Location: servicios.php");
    exit();
}
?>