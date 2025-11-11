<?php
session_start();
include("../conexion.php");

// Recibir y limpiar datos
$usuario = trim($_POST['usuario'] ?? '');
$contrasena = trim($_POST['contrasena'] ?? '');

// Validar que los campos no estén vacíos
if(empty($usuario) || empty($contrasena)){
    echo "<script>alert('Por favor completa todos los campos'); window.history.back();</script>";
    exit();
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
        header("Location: ../index/clienteIndex.html");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.history.back();</script>";
    exit();
}

// Cerrar statement y conexión
$stmt->close();
$conn->close();
?>