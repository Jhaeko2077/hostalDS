<?php
// Verificar sesión de administrador (solo administradores pueden gestionar empleados)
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
    $tipo = trim($_POST['tipo']);

    $stmt = $conn->prepare("INSERT INTO Empleado (nombres, apellidos, dni, email, telefono, contrasena, usuario, tipo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena, $usuario, $tipo);
    $stmt->execute();
    $stmt->close();
    header("Location: empleados.php");
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
    $tipo = trim($_POST['tipo']);

    $stmt = $conn->prepare("UPDATE Empleado 
            SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, usuario=?, tipo=?
            WHERE id=?");
    $stmt->bind_param("ssssssss", $nombres, $apellidos, $dni, $email, $telefono, $usuario, $tipo, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: empleados.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = trim($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM Empleado WHERE id=?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: empleados.php");
    exit();
}
?>