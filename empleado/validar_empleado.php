<?php
include("../conexion.php");

$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$query = "SELECT * FROM Empleado WHERE usuario = '$usuario'";
$resultado = mysqli_query($conn, $query);

if(mysqli_num_rows($resultado) > 0){
    $empleado = mysqli_fetch_assoc($resultado);

    if(password_verify($contrasena, $empleado['contrasena'])){
        if(isset($_POST['recordar'])){
            setcookie('usuario_empleado', $usuario, time() + (86400 * 30), "/");
        }

        session_start();
        $_SESSION['usuario_empleado'] = $usuario;
        $_SESSION['tipo_empleado'] = $empleado['tipo'];

        // 🔁 Redirección al panel del empleado
        header("Location: ../index/empleadoIndex.html");
        exit();
    } else {
        echo "<script>alert('Contraseña incorrecta'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Usuario no encontrado'); window.history.back();</script>";
}
?>