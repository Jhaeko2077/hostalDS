<?php
require_once("../includes/functions.php");
check_permission(['empleado', 'admin']);

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = sanitize_input($_POST['id'] ?? '');
    $idHab = sanitize_input($_POST['idHab']);
    $idEmp = sanitize_input($_POST['idEmp']);
    $idServicio = sanitize_input($_POST['idServicio']);
    $pago = isset($_POST['pago']) ? 1 : 0;
    
    if (empty($idHab) || empty($idEmp) || empty($idServicio)) {
        show_error_and_redirect("Todos los campos son obligatorios", "detallesServicios.php");
    }

    // Si el ID está vacío, se generará automáticamente por el trigger
    if (empty($id)) {
        $sql = "INSERT INTO detalleServicioHob (idHab, idEmp, idServicio, pago) VALUES (?, ?, ?, ?)";
        $types = "sssi";
        $params = [$idHab, $idEmp, $idServicio, $pago];
    } else {
        $sql = "INSERT INTO detalleServicioHob (id, idHab, idEmp, idServicio, pago) VALUES (?, ?, ?, ?, ?)";
        $types = "ssssi";
        $params = [$id, $idHab, $idEmp, $idServicio, $pago];
    }
    
    execute_crud($conn, $sql, $types, $params, "detallesServicios.php", "Error al crear el detalle de servicio");
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = sanitize_input($_POST['id']);
    $idHab = sanitize_input($_POST['idHab']);
    $idEmp = sanitize_input($_POST['idEmp']);
    $idServicio = sanitize_input($_POST['idServicio']);
    $pago = isset($_POST['pago']) ? 1 : 0;
    
    if (empty($idHab) || empty($idEmp) || empty($idServicio)) {
        show_error_and_redirect("Todos los campos son obligatorios", "detallesServicios.php");
    }

    $sql = "UPDATE detalleServicioHob SET idHab=?, idEmp=?, idServicio=?, pago=? WHERE id=?";
    $types = "sssii";
    $params = [$idHab, $idEmp, $idServicio, $pago, $id];
    
    execute_crud($conn, $sql, $types, $params, "detallesServicios.php", "Error al actualizar el detalle de servicio");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = sanitize_input($_GET['eliminar']);
    
    $sql = "DELETE FROM detalleServicioHob WHERE id=?";
    $types = "s";
    $params = [$id];
    
    execute_crud($conn, $sql, $types, $params, "detallesServicios.php", "Error al eliminar el detalle de servicio");
}
?>