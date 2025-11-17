<?php
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
        <form action="validar_empleado.php" method="POST" autocomplete="off">
            <input type="text" name="usuario" placeholder="Usuario" required value="<?php echo htmlspecialchars($usuarioGuardado); ?>" autocomplete="username">
            <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required autocomplete="new-password">
            <label>
                <input type="checkbox" name="recordar"> Recordarme
            </label>
            <button type="submit">Entrar</button>
            <input type="password" id="claveAdmin" placeholder="Ingresa la contraseña" autocomplete="off">

            <button type="button" id="btnAdmin" class="btn btn-register">
                <i class="fas fa-user-plus"></i>
                Ir a Registrar Empleado
            </button>
        </form>
    </div>
    
    <script src="login_empleado.js"></script>
</body>
</html>