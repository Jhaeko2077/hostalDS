<?php
session_start();

// Verificar si el usuario administrador está logueado
if(!isset($_SESSION['usuario_admin'])){
    header("Location: ../administrador/login_admin.php");
    exit();
}
?>

