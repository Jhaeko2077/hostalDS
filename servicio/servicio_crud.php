<?php
require_once("../includes/functions.php");
check_permission(['empleado', 'admin']);

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $id = sanitize_input($_POST['id'] ?? '');
    $descripcion = sanitize_input($_POST['descripcion']);
    $costo = floatval($_POST['costo']);
    
    if (empty($descripcion) || $costo <= 0) {
        show_error_and_redirect("La descripción es obligatoria y el costo debe ser mayor a 0", "servicios.php");
    }

    // Si el ID está vacío, se generará automáticamente por el trigger
    if (empty($id)) {
        $sql = "INSERT INTO Servicios (descripcion, costo) VALUES (?, ?)";
        $types = "sd";
        $params = [$descripcion, $costo];
    } else {
        $sql = "INSERT INTO Servicios (id, descripcion, costo) VALUES (?, ?, ?)";
        $types = "ssd";
        $params = [$id, $descripcion, $costo];
    }
    
    execute_crud($conn, $sql, $types, $params, "servicios.php", "Error al crear el servicio");
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = sanitize_input($_POST['id']);
    $descripcion = sanitize_input($_POST['descripcion']);
    $costo = floatval($_POST['costo']);
    
    if (empty($descripcion) || $costo <= 0) {
        show_error_and_redirect("La descripción es obligatoria y el costo debe ser mayor a 0", "servicios.php");
    }

    $sql = "UPDATE Servicios SET descripcion=?, costo=? WHERE id=?";
    $types = "sds";
    $params = [$descripcion, $costo, $id];
    
    execute_crud($conn, $sql, $types, $params, "servicios.php", "Error al actualizar el servicio");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = sanitize_input($_GET['eliminar']);
    
    // Verificar si tiene relaciones
    if (has_relations($conn, 'Servicios', 'id', $id)) {
        show_error_and_redirect("No se puede eliminar el servicio porque tiene detalles asociados", "servicios.php");
    }
    
    $sql = "DELETE FROM Servicios WHERE id=?";
    $types = "s";
    $params = [$id];
    
    execute_crud($conn, $sql, $types, $params, "servicios.php", "Error al eliminar el servicio");
}
?>