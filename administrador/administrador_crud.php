<?php
include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['usuario'];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO Administrador (nombres, apellidos, dni, email, telefono, contrasena, usuario)
            VALUES ('$nombres', '$apellidos', '$dni', '$email', '$telefono', '$contrasena', '$usuario')";
    $conn->query($sql);
    header("Location: administradores.php");
    exit();
}

// Actualizar
if (isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['usuario'];

    $sql = "UPDATE Administrador 
            SET nombres='$nombres', apellidos='$apellidos', dni='$dni', email='$email', telefono='$telefono', usuario='$usuario'
            WHERE id='$id'";
    $conn->query($sql);
    header("Location: administradores.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM Administrador WHERE id='$id'");
    header("Location: administradores.php");
    exit();
}
?>