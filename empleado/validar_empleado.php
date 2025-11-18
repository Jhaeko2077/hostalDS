<?php
require_once("../includes/functions.php");
session_start();
include("../conexion.php");

// Recibir y limpiar datos
$usuario = sanitize_input($_POST['usuario'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

// Validar que los campos no estén vacíos
if(empty($usuario) || empty($contrasena)){
    show_error_and_redirect("Por favor completa todos los campos", "login_empleado.php");
}

// Preparar consulta segura
$stmt = $conn->prepare("SELECT * FROM Empleado WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows > 0){
    $empleado = $resultado->fetch_assoc();

    if(password_verify($contrasena, $empleado['contrasena'])){
        // Cookie "Recordar usuario" opcional
        if(isset($_POST['recordar'])){
            setcookie('usuario_empleado', $usuario, time() + (86400 * 30), "/"); // 30 días
        }

        // Iniciar sesión
        $_SESSION['usuario_empleado'] = $usuario;
        $_SESSION['tipo_empleado'] = $empleado['tipo'];

        // Redirección al panel del empleado
        header("Location: ../index/empleadoIndex.php");
        exit();
    } else {
        show_error_and_redirect("Contraseña incorrecta", "login_empleado.php");
    }
} else {
    show_error_and_redirect("Usuario no encontrado", "login_empleado.php");
}

// Cerrar statement y conexión
$stmt->close();
$conn->close();
?>