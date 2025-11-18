<?php
require_once("../includes/functions.php");
check_permission(['empleado', 'admin']);

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = sanitize_input($_POST['id'] ?? '');
    $descripcion = sanitize_input($_POST['descripcion']);
    
    if (empty($descripcion)) {
        show_error_and_redirect("La descripción es obligatoria", "tipoPagos.php");
    }

    // Si el ID está vacío, se generará automáticamente por el trigger
    if (empty($id)) {
        $sql = "INSERT INTO tipoPago (descripcion) VALUES (?)";
        $types = "s";
        $params = [$descripcion];
    } else {
        $sql = "INSERT INTO tipoPago (id, descripcion) VALUES (?, ?)";
        $types = "ss";
        $params = [$id, $descripcion];
    }
    
    execute_crud($conn, $sql, $types, $params, "tipoPagos.php", "Error al crear el tipo de pago");
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = sanitize_input($_POST['id']);
    $descripcion = sanitize_input($_POST['descripcion']);
    
    if (empty($descripcion)) {
        show_error_and_redirect("La descripción es obligatoria", "tipoPagos.php");
    }

    $sql = "UPDATE tipoPago SET descripcion=? WHERE id=?";
    $types = "ss";
    $params = [$descripcion, $id];
    
    execute_crud($conn, $sql, $types, $params, "tipoPagos.php", "Error al actualizar el tipo de pago");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = sanitize_input($_GET['eliminar']);
    
    // Verificar si tiene relaciones
    if (has_relations($conn, 'tipoPago', 'id', $id)) {
        show_error_and_redirect("No se puede eliminar el tipo de pago porque tiene reservas asociadas", "tipoPagos.php");
    }
    
    $sql = "DELETE FROM tipoPago WHERE id=?";
    $types = "s";
    $params = [$id];
    
    execute_crud($conn, $sql, $types, $params, "tipoPagos.php", "Error al eliminar el tipo de pago");
}
?>