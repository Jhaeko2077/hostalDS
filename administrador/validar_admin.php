<?php
require_once("../includes/functions.php");
session_start();
include("../conexion.php");

// Recibir y limpiar datos
$usuario = sanitize_input($_POST['usuario'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

// Validar que los campos no estén vacíos
if(empty($usuario) || empty($contrasena)){
    show_error_and_redirect("Por favor completa todos los campos", "login_admin.php");
}

// Preparar consulta segura
$stmt = $conn->prepare("SELECT a.*, e.contrasena FROM Administrador a
          INNER JOIN Empleado e ON a.usuario = e.usuario
          WHERE a.usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows > 0){
    $admin = $resultado->fetch_assoc();

    if(password_verify($contrasena, $admin['contrasena'])){
        // Cookie "Recordar usuario" opcional
        if(isset($_POST['recordar'])){ 
            setcookie('usuario_admin', $usuario, time() + (86400 * 30), "/"); // 30 días
        }

        // Iniciar sesión
        $_SESSION['usuario_admin'] = $usuario;

        // Redirección al panel del administrador
        header("Location: ../index/indexAdmin.php");
        exit();
    } else {
        show_error_and_redirect("Contraseña incorrecta", "login_admin.php");
    }
} else {
    show_error_and_redirect("Usuario no encontrado", "login_admin.php");
}

// Cerrar statement y conexión
$stmt->close();
$conn->close();
?>