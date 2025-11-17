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
    $idHab = trim($_POST['idHab']);
    $idEmp = trim($_POST['idEmp']);
    $idServicio = trim($_POST['idServicio']);
    $pago = isset($_POST['pago']) ? 1 : 0;

    $stmt = $conn->prepare("INSERT INTO detalleServicioHob (id, idHab, idEmp, idServicio, pago)
            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $id, $idHab, $idEmp, $idServicio, $pago);
    $stmt->execute();
    $stmt->close();
    header("Location: detallesServicios.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = trim($_POST['id']);
    $idHab = trim($_POST['idHab']);
    $idEmp = trim($_POST['idEmp']);
    $idServicio = trim($_POST['idServicio']);
    $pago = isset($_POST['pago']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE detalleServicioHob 
            SET idHab=?, idEmp=?, idServicio=?, pago=?
            WHERE id=?");
    $stmt->bind_param("sssii", $idHab, $idEmp, $idServicio, $pago, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: detallesServicios.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = trim($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM detalleServicioHob WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: detallesServicios.php");
    exit();
}
?>