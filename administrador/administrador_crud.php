<?php
// Verificar sesión de administrador (solo administradores pueden gestionar administradores)
session_start();
if(!isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $dni = trim($_POST['dni']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $usuario = trim($_POST['usuario']);
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO Administrador (nombres, apellidos, dni, email, telefono, contrasena, usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena, $usuario);
    $stmt->execute();
    $stmt->close();
    header("Location: administradores.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = trim($_POST['id']);
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $dni = trim($_POST['dni']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $usuario = trim($_POST['usuario']);

    $stmt = $conn->prepare("UPDATE Administrador 
            SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, usuario=?
            WHERE id=?");
    $stmt->bind_param("sssssss", $nombres, $apellidos, $dni, $email, $telefono, $usuario, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: administradores.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = trim($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM Administrador WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: administradores.php");
    exit();
}
?>