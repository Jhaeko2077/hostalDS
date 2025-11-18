<?php
require_once("../includes/functions.php");
check_permission(['admin']);

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $nombres = sanitize_input($_POST['nombres']);
    $apellidos = sanitize_input($_POST['apellidos']);
    $dni = sanitize_input($_POST['dni']);
    $email = sanitize_input($_POST['email']);
    $telefono = sanitize_input($_POST['telefono'] ?? '');
    $usuario = sanitize_input($_POST['usuario']);
    $contrasena = $_POST['contrasena'];
    $tipo = sanitize_input($_POST['tipo']);
    
    // Validaciones
    if (empty($nombres) || empty($apellidos) || empty($dni) || empty($email) || empty($usuario) || empty($contrasena) || empty($tipo)) {
        show_error_and_redirect("Todos los campos obligatorios deben completarse", "empleados.php");
    }
    
    if (!validate_email($email)) {
        show_error_and_redirect("El email no es válido", "empleados.php");
    }
    
    if (!validate_dni($dni)) {
        show_error_and_redirect("El DNI debe tener 8 dígitos", "empleados.php");
    }
    
    // Verificar si el usuario, email o DNI ya existe
    if (user_exists($conn, 'Empleado', 'usuario', $usuario) || 
        user_exists($conn, 'Empleado', 'email', $email) || 
        user_exists($conn, 'Empleado', 'dni', $dni)) {
        show_error_and_redirect("El usuario, email o DNI ya está registrado", "empleados.php");
    }
    
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO Empleado (nombres, apellidos, dni, email, telefono, contrasena, usuario, tipo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $types = "ssssssss";
    $params = [$nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario, $tipo];
    
    execute_crud($conn, $sql, $types, $params, "empleados.php", "Error al crear el empleado");
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = sanitize_input($_POST['id']);
    $nombres = sanitize_input($_POST['nombres']);
    $apellidos = sanitize_input($_POST['apellidos']);
    $dni = sanitize_input($_POST['dni']);
    $email = sanitize_input($_POST['email']);
    $telefono = sanitize_input($_POST['telefono'] ?? '');
    $usuario = sanitize_input($_POST['usuario']);
    $tipo = sanitize_input($_POST['tipo']);
    $contrasena = sanitize_input($_POST['contrasena'] ?? '');

    // Validaciones
    if (!validate_email($email)) {
        show_error_and_redirect("El email no es válido", "empleados.php");
    }
    
    if (!validate_dni($dni)) {
        show_error_and_redirect("El DNI debe tener 8 dígitos", "empleados.php");
    }

    // Si la contraseña está vacía, no actualizarla
    if(empty($contrasena)){
        $sql = "UPDATE Empleado SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, usuario=?, tipo=? WHERE id=?";
        $types = "ssssssss";
        $params = [$nombres, $apellidos, $dni, $email, $telefono, $usuario, $tipo, $id];
    } else {
        // Si hay contraseña, hashearla
        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $sql = "UPDATE Empleado SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, usuario=?, tipo=?, contrasena=? WHERE id=?";
        $types = "sssssssss";
        $params = [$nombres, $apellidos, $dni, $email, $telefono, $usuario, $tipo, $contrasena_hash, $id];
    }
    
    execute_crud($conn, $sql, $types, $params, "empleados.php", "Error al actualizar el empleado");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = sanitize_input($_GET['eliminar']);
    
    // Verificar si tiene relaciones
    if (has_relations($conn, 'Empleado', 'id', $id)) {
        show_error_and_redirect("No se puede eliminar el empleado porque tiene servicios o es administrador", "empleados.php");
    }
    
    $sql = "DELETE FROM Empleado WHERE id=?";
    $types = "s";
    $params = [$id];
    
    execute_crud($conn, $sql, $types, $params, "empleados.php", "Error al eliminar el empleado");
}
?>