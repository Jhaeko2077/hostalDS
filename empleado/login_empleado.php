<?php
session_start();

// Si ya está logueado, redirigir al panel
if(isset($_SESSION['usuario_empleado'])){
    header("Location: ../index/empleadoIndex.php");
    exit();
}

if(isset($_COOKIE['usuario_empleado'])){
    $usuarioGuardado = $_COOKIE['usuario_empleado'];
} else {
    $usuarioGuardado = "";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Empleado</title>
    <link rel="stylesheet" href="../loginStyle.css">
</head>
<body>
    <div class="login-container">
        <h2>Acceso Empleado</h2>
        <form action="validar_empleado.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required value="<?php echo $usuarioGuardado; ?>">
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <label>
                <input type="checkbox" name="recordar"> Recordarme
            </label>
            <button type="submit">Entrar</button>
                        <input type="password" id="claveAdmin" placeholder="Ingresa la contraseña">

            <button id="btnAdmin" class="btn btn-register">
                <i class="fas fa-user-plus"></i>
                Ir a Registrar Empleado
            </button>

            <script>
            document.getElementById("btnAdmin").addEventListener("click", function() {
                const clave = document.getElementById("claveAdmin").value;
                if (clave === "dulc3d3sc4ns0") {
                    // Redirige a la página
                    window.location.href = "registrarEmpleado.php";
                } else {
                    alert("Contraseña incorrecta. No puedes continuar.");
                }
            });
            </script>
        </form>
    </div>
</body>
</html>