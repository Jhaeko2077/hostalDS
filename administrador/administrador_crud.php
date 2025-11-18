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
    
    // Validaciones
    if (empty($nombres) || empty($apellidos) || empty($dni) || empty($email) || empty($usuario) || empty($contrasena)) {
        show_error_and_redirect("Todos los campos obligatorios deben completarse", "administradores.php");
    }
    
    if (!validate_email($email)) {
        show_error_and_redirect("El email no es válido", "administradores.php");
    }
    
    if (!validate_dni($dni)) {
        show_error_and_redirect("El DNI debe tener 8 dígitos", "administradores.php");
    }
    
    // Verificar que el empleado existe
    if (!user_exists($conn, 'Empleado', 'usuario', $usuario)) {
        show_error_and_redirect("El usuario debe existir primero como empleado", "administradores.php");
    }
    
    // Verificar si ya existe un administrador con ese usuario, email o DNI
    if (user_exists($conn, 'Administrador', 'usuario', $usuario) || 
        user_exists($conn, 'Administrador', 'email', $email) || 
        user_exists($conn, 'Administrador', 'dni', $dni)) {
        show_error_and_redirect("Ya existe un administrador con ese usuario, email o DNI", "administradores.php");
    }
    
    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO Administrador (nombres, apellidos, dni, email, telefono, contrasena, usuario) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $types = "sssssss";
    $params = [$nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario];
    
    execute_crud($conn, $sql, $types, $params, "administradores.php", "Error al crear el administrador");
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

    // Validaciones
    if (!validate_email($email)) {
        show_error_and_redirect("El email no es válido", "administradores.php");
    }
    
    if (!validate_dni($dni)) {
        show_error_and_redirect("El DNI debe tener 8 dígitos", "administradores.php");
    }
    
    // Verificar que el empleado existe
    if (!user_exists($conn, 'Empleado', 'usuario', $usuario)) {
        show_error_and_redirect("El usuario debe existir primero como empleado", "administradores.php");
    }

    $sql = "UPDATE Administrador SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, usuario=? WHERE id=?";
    $types = "sssssss";
    $params = [$nombres, $apellidos, $dni, $email, $telefono, $usuario, $id];
    
    execute_crud($conn, $sql, $types, $params, "administradores.php", "Error al actualizar el administrador");
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = sanitize_input($_GET['eliminar']);
    
    $sql = "DELETE FROM Administrador WHERE id=?";
    $types = "s";
    $params = [$id];
    
    execute_crud($conn, $sql, $types, $params, "administradores.php", "Error al eliminar el administrador");
}
?>