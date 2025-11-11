<?php
include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $idCli = $_POST['idCli'];
    $idHab = $_POST['idHab'];
    $pago = isset($_POST['pago']) ? 1 : 0;
    $idTipoPago = $_POST['idTipoPago'];

    $sql = "INSERT INTO detalleReserva (id, fecha, idCli, idHab, pago, idTipoPago)
            VALUES ('$id', '$fecha', '$idCli', '$idHab', '$pago', '$idTipoPago')";
    $conn->query($sql);
    header("Location: detallesReservas.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $fecha = $_POST['fecha'];
    $idCli = $_POST['idCli'];
    $idHab = $_POST['idHab'];
    $pago = isset($_POST['pago']) ? 1 : 0;
    $idTipoPago = $_POST['idTipoPago'];

    $sql = "UPDATE detalleReserva 
            SET fecha='$fecha', idCli='$idCli', idHab='$idHab', pago='$pago', idTipoPago='$idTipoPago'
            WHERE id='$id'";
    $conn->query($sql);
    header("Location: detallesReservas.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM detalleReserva WHERE id='$id'");
    header("Location: detallesReservas.php");
    exit();
}
?>