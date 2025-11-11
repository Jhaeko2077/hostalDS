<?php
include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = $_POST['id'];
    $idHab = $_POST['idHab'];
    $idEmp = $_POST['idEmp'];
    $idServicio = $_POST['idServicio'];
    $pago = isset($_POST['pago']) ? 1 : 0;

    $sql = "INSERT INTO detalleServicioHob (id, idHab, idEmp, idServicio, pago)
            VALUES ('$id', '$idHab', '$idEmp', '$idServicio', '$pago')";
    $conn->query($sql);
    header("Location: detallesServicios.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $idHab = $_POST['idHab'];
    $idEmp = $_POST['idEmp'];
    $idServicio = $_POST['idServicio'];
    $pago = isset($_POST['pago']) ? 1 : 0;

    $sql = "UPDATE detalleServicioHob 
            SET idHab='$idHab', idEmp='$idEmp', idServicio='$idServicio', pago='$pago'
            WHERE id='$id'";
    $conn->query($sql);
    header("Location: detallesServicios.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM detalleServicioHob WHERE id='$id'");
    header("Location: detallesServicios.php");
    exit();
}
?>