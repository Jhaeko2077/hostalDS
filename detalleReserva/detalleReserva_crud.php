<?php
require_once("../includes/functions.php");
check_permission(['empleado', 'admin']);

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = sanitize_input($_POST['id'] ?? '');
    $fecha_inicio = sanitize_input($_POST['fecha_inicio']);
    $fecha_fin = sanitize_input($_POST['fecha_fin']);
    $idCli = sanitize_input($_POST['idCli']);
    $idHab = sanitize_input($_POST['idHab']);
    $pago = isset($_POST['pago']) ? 1 : 0;
    $idTipoPago = sanitize_input($_POST['idTipoPago']);
    $es_checkin_directo = isset($_POST['es_checkin_directo']) ? 1 : 0;

    // Validar que fecha_fin sea mayor o igual a fecha_inicio
    if (!validate_date_range($fecha_inicio, $fecha_fin)) {
        show_error_and_redirect("La fecha de fin debe ser mayor o igual a la fecha de inicio", "detallesReservas.php");
    }

    // Verificar disponibilidad de la habitación
    if (!check_habitacion_disponible($conn, $idHab, $fecha_inicio, $fecha_fin)) {
        show_error_and_redirect("La habitación ya está reservada en esas fechas", "detallesReservas.php");
    }

    // Si el ID está vacío, se generará automáticamente por el trigger
    if (empty($id)) {
        $sql = "INSERT INTO detalleReserva (fecha_inicio, fecha_fin, idCli, idHab, pago, idTipoPago, es_checkin_directo) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $types = "ssssisi";
        $params = [$fecha_inicio, $fecha_fin, $idCli, $idHab, $pago, $idTipoPago, $es_checkin_directo];
    } else {
        $sql = "INSERT INTO detalleReserva (id, fecha_inicio, fecha_fin, idCli, idHab, pago, idTipoPago, es_checkin_directo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $types = "sssssisi";
        $params = [$id, $fecha_inicio, $fecha_fin, $idCli, $idHab, $pago, $idTipoPago, $es_checkin_directo];
    }
    
    execute_crud($conn, $sql, $types, $params, "detallesReservas.php", "Error al crear la reserva");
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = sanitize_input($_POST['id']);
    $fecha_inicio = sanitize_input($_POST['fecha_inicio']);
    $fecha_fin = sanitize_input($_POST['fecha_fin']);
    $idCli = sanitize_input($_POST['idCli']);
    $idHab = sanitize_input($_POST['idHab']);
    $pago = isset($_POST['pago']) ? 1 : 0;
    $idTipoPago = sanitize_input($_POST['idTipoPago']);
    $es_checkin_directo = isset($_POST['es_checkin_directo']) ? 1 : 0;

    // Validar que fecha_fin sea mayor o igual a fecha_inicio
    if (!validate_date_range($fecha_inicio, $fecha_fin)) {
        show_error_and_redirect("La fecha de fin debe ser mayor o igual a la fecha de inicio", "detallesReservas.php");
    }

    // Verificar disponibilidad de la habitación (excluyendo la reserva actual)
    if (!check_habitacion_disponible($conn, $idHab, $fecha_inicio, $fecha_fin, $id)) {
        show_error_and_redirect("La habitación ya está reservada en esas fechas", "detallesReservas.php");
    }

    $sql = "UPDATE detalleReserva SET fecha_inicio=?, fecha_fin=?, idCli=?, idHab=?, pago=?, idTipoPago=?, es_checkin_directo=? WHERE id=?";
    $types = "ssssisss";
    $params = [$fecha_inicio, $fecha_fin, $idCli, $idHab, $pago, $idTipoPago, $es_checkin_directo, $id];
    
    execute_crud($conn, $sql, $types, $params, "detallesReservas.php", "Error al actualizar la reserva");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = sanitize_input($_GET['eliminar']);
    
    $sql = "DELETE FROM detalleReserva WHERE id=?";
    $types = "s";
    $params = [$id];
    
    execute_crud($conn, $sql, $types, $params, "detallesReservas.php", "Error al eliminar la reserva");
}
?>