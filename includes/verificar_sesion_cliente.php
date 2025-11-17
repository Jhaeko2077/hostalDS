<?php
session_start();

// Verificar si el usuario cliente está logueado
if(!isset($_SESSION['usuario_cliente'])){
    header("Location: ../cliente/login_cliente.php");
    exit();
}
?>

