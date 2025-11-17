<?php
session_start();

// Verificar si el usuario empleado está logueado
if(!isset($_SESSION['usuario_empleado'])){
    header("Location: ../empleado/login_empleado.php");
    exit();
}
?>

