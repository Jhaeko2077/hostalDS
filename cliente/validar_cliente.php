<?php
require_once("../includes/functions.php");
session_start();
include("../conexion.php");

// Recibir y limpiar datos
$usuario = sanitize_input($_POST['usuario'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';

// Validar que los campos no estén vacíos
if(empty($usuario) || empty($contrasena)){
    show_error_and_redirect("Por favor completa todos los campos", "login_cliente.php");
}

// Preparar consulta segura
$stmt = $conn->prepare("SELECT * FROM Cliente WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if($resultado->num_rows > 0){
    $cliente = $resultado->fetch_assoc();

    // Verificar contraseña
    if(password_verify($contrasena, $cliente['contrasena'])){
        // Cookie "Recordar usuario" opcional
        if(isset($_POST['recordar'])){
            setcookie('usuario_cliente', $usuario, time() + (86400 * 30), "/"); // 30 días
        }

        // Iniciar sesión
        $_SESSION['usuario_cliente'] = $usuario;

        // Redirección al panel del cliente
        header("Location: ../index/clienteIndex.php");
        exit();
    } else {
        show_error_and_redirect("Contraseña incorrecta", "login_cliente.php");
    }
} else {
    show_error_and_redirect("Usuario no encontrado", "login_cliente.php");
}

// Cerrar statement y conexión
$stmt->close();
$conn->close();
?>