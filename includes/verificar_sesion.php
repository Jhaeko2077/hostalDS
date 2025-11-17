<?php
session_start();

// Verificar si al menos un tipo de usuario está logueado
$cliente_logueado = isset($_SESSION['usuario_cliente']);
$empleado_logueado = isset($_SESSION['usuario_empleado']);
$admin_logueado = isset($_SESSION['usuario_admin']);

// Si ningún usuario está logueado, redirigir al index
if(!$cliente_logueado && !$empleado_logueado && !$admin_logueado){
    header("Location: ../index.html");
    exit();
}
?>

