<?php
require_once("../includes/functions.php");
check_permission(['empleado', 'admin']);

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
    
    // Validaciones
    if (empty($nombres) || empty($apellidos) || empty($dni) || empty($email) || empty($usuario) || empty($contrasena)) {
        show_error_and_redirect("Todos los campos obligatorios deben completarse", "clientes.php");
    }
    
    if (!validate_email($email)) {
        show_error_and_redirect("El email no es válido", "clientes.php");
    }
    
    if (!validate_dni($dni)) {
        show_error_and_redirect("El DNI debe tener 8 dígitos", "clientes.php");
    }
    
    // Verificar si el usuario, email o DNI ya existe
    if (user_exists($conn, 'Cliente', 'usuario', $usuario) || 
        user_exists($conn, 'Cliente', 'email', $email) || 
        user_exists($conn, 'Cliente', 'dni', $dni)) {
        show_error_and_redirect("El usuario, email o DNI ya está registrado", "clientes.php");
    }
    
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO Cliente (nombres, apellidos, dni, email, telefono, contrasena, usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $types = "sssssss";
    $params = [$nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario];
    
    execute_crud($conn, $sql, $types, $params, "clientes.php", "Error al crear el cliente");
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = sanitize_input($_POST['id']);
    $nombres = sanitize_input($_POST['nombres']);
    $apellidos = sanitize_input($_POST['apellidos']);
    $dni = sanitize_input($_POST['dni']);
    $email = sanitize_input($_POST['email']);
    $telefono = sanitize_input($_POST['telefono'] ?? '');
    $contrasena = sanitize_input($_POST['contrasena'] ?? '');
    $usuario = sanitize_input($_POST['usuario']);

    // Validaciones
    if (!validate_email($email)) {
        show_error_and_redirect("El email no es válido", "clientes.php");
    }
    
    if (!validate_dni($dni)) {
        show_error_and_redirect("El DNI debe tener 8 dígitos", "clientes.php");
    }

    // Si la contraseña está vacía, no actualizarla
    if(empty($contrasena)){
        $sql = "UPDATE Cliente SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, usuario=? WHERE id=?";
        $types = "sssssss";
        $params = [$nombres, $apellidos, $dni, $email, $telefono, $usuario, $id];
    } else {
        // Si hay contraseña, hashearla
        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $sql = "UPDATE Cliente SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, contrasena=?, usuario=? WHERE id=?";
        $types = "ssssssss";
        $params = [$nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario, $id];
    }
    
    execute_crud($conn, $sql, $types, $params, "clientes.php", "Error al actualizar el cliente");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = sanitize_input($_GET['eliminar']);
    
    // Verificar si tiene relaciones
    if (has_relations($conn, 'Cliente', 'id', $id)) {
        show_error_and_redirect("No se puede eliminar el cliente porque tiene reservas asociadas", "clientes.php");
    }
    
    $sql = "DELETE FROM Cliente WHERE id=?";
    $types = "s";
    $params = [$id];
    
    execute_crud($conn, $sql, $types, $params, "clientes.php", "Error al eliminar el cliente");
}
?>