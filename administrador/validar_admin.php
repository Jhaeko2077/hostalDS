<?php
include("../conexion.php");

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$query = "SELECT a.*, e.contrasena FROM Administrador a
          INNER JOIN Empleado e ON a.usuario = e.usuario
          WHERE a.usuario = '$usuario'";

$resultado = mysqli_query($conn, $query);

if(mysqli_num_rows($resultado) > 0){
    $admin = mysqli_fetch_assoc($resultado);

    if(password_verify($contrasena, $admin['contrasena'])){
        if(isset($_POST['recordar'])){ 
            setcookie('usuario_admin', $usuario, time() + (86400 * 30), "/");
        }

        session_start();
        $_SESSION['usuario_admin'] = $usuario;

        // 🔁 Redirección al panel del administrador
        header("Location: ../index/indexAdmin.html");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.history.back();</script>";
}
?>