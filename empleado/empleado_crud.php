<?php
include("../conexion.php");

// Crear
if (isset($_POST['crear'])) {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_BCRYPT);
    $tipo = $_POST['tipo'];

    $sql = "INSERT INTO Empleado (nombres, apellidos, dni, email, telefono, contrasena, usuario, tipo)
            VALUES ('$nombres', '$apellidos', '$dni', '$email', '$telefono', '$contrasena', '$usuario', '$tipo')";
    $conn->query($sql);
    header("Location: empleados.php");
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
    $tipo = $_POST['tipo'];

    $sql = "UPDATE Empleado 
            SET nombres='$nombres', apellidos='$apellidos', dni='$dni', email='$email', telefono='$telefono', usuario='$usuario', tipo='$tipo'
            WHERE id='$id'";
    $conn->query($sql);
    header("Location: empleados.php");
    exit();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $conn->query("DELETE FROM Empleado WHERE id='$id'");
    header("Location: empleados.php");
    exit();
}
?>