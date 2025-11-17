<?php
// Verificar sesión de empleado o administrador (solo ellos pueden gestionar reservas)
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = trim($_POST['id']);
    $fecha = trim($_POST['fecha']);
    $idCli = trim($_POST['idCli']);
    $idHab = trim($_POST['idHab']);
    $pago = isset($_POST['pago']) ? 1 : 0;
    $idTipoPago = trim($_POST['idTipoPago']);

    $stmt = $conn->prepare("INSERT INTO detalleReserva (id, fecha, idCli, idHab, pago, idTipoPago)
            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $id, $fecha, $idCli, $idHab, $pago, $idTipoPago);
    $stmt->execute();
    $stmt->close();
    header("Location: detallesReservas.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = trim($_POST['id']);
    $fecha = trim($_POST['fecha']);
    $idCli = trim($_POST['idCli']);
    $idHab = trim($_POST['idHab']);
    $pago = isset($_POST['pago']) ? 1 : 0;
    $idTipoPago = trim($_POST['idTipoPago']);

    $stmt = $conn->prepare("UPDATE detalleReserva 
            SET fecha=?, idCli=?, idHab=?, pago=?, idTipoPago=?
            WHERE id=?");
    $stmt->bind_param("sssiss", $fecha, $idCli, $idHab, $pago, $idTipoPago, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: detallesReservas.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = trim($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM detalleReserva WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: detallesReservas.php");
    exit();
}
?>