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