<?php
// Verificar sesión de empleado o administrador
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
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

    $stmt = $conn->prepare("INSERT INTO Cliente (nombres, apellidos, dni, email, telefono, contrasena, usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena, $usuario);
    $stmt->execute();
    $stmt->close();
    header("Location: clientes.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $dni = trim($_POST['dni']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $contrasena = trim($_POST['contrasena']);
    $usuario = trim($_POST['usuario']);

    // Si la contraseña está vacía, no actualizarla
    if(empty($contrasena)){
        $stmt = $conn->prepare("UPDATE Cliente 
                SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, usuario=?
                WHERE id=?");
        $stmt->bind_param("ssssssi", $nombres, $apellidos, $dni, $email, $telefono, $usuario, $id);
    } else {
        // Si hay contraseña, hashearla
        $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE Cliente 
                SET nombres=?, apellidos=?, dni=?, email=?, telefono=?, contrasena=?, usuario=?
                WHERE id=?");
        $stmt->bind_param("sssssssi", $nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario, $id);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: clientes.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM Cliente WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: clientes.php");
    exit();
}
?>