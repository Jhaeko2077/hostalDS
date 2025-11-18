<?php
require_once("../includes/functions.php");
check_permission(['empleado', 'admin']);

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $codigo = sanitize_input($_POST['codigo']);
    $tipo = sanitize_input($_POST['tipo']);
    $estado = sanitize_input($_POST['estado']);
    $descripcion = sanitize_input($_POST['descripcion'] ?? '');
    
    if (empty($codigo) || empty($tipo) || empty($estado)) {
        show_error_and_redirect("Código, tipo y estado son obligatorios", "habitaciones.php");
    }

    $sql = "INSERT INTO Habitaciones (codigo, tipo, estado, descripcion) VALUES (?, ?, ?, ?)";
    $types = "ssss";
    $params = [$codigo, $tipo, $estado, $descripcion];
    
    execute_crud($conn, $sql, $types, $params, "habitaciones.php", "Error al crear la habitación");
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $codigo = sanitize_input($_POST['codigo']);
    $tipo = sanitize_input($_POST['tipo']);
    $estado = sanitize_input($_POST['estado']);
    $descripcion = sanitize_input($_POST['descripcion'] ?? '');
    
    if (empty($codigo) || empty($tipo) || empty($estado)) {
        show_error_and_redirect("Código, tipo y estado son obligatorios", "habitaciones.php");
    }

    $sql = "UPDATE Habitaciones SET tipo=?, estado=?, descripcion=? WHERE codigo=?";
    $types = "ssss";
    $params = [$tipo, $estado, $descripcion, $codigo];
    
    execute_crud($conn, $sql, $types, $params, "habitaciones.php", "Error al actualizar la habitación");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $codigo = sanitize_input($_GET['eliminar']);
    
    // Verificar si tiene relaciones
    if (has_relations($conn, 'Habitaciones', 'codigo', $codigo)) {
        show_error_and_redirect("No se puede eliminar la habitación porque tiene reservas o servicios asociados", "habitaciones.php");
    }
    
    $sql = "DELETE FROM Habitaciones WHERE codigo=?";
    $types = "s";
    $params = [$codigo];
    
    execute_crud($conn, $sql, $types, $params, "habitaciones.php", "Error al eliminar la habitación");
}
?>