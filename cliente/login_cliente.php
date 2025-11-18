<?php
session_start();

// Si ya está logueado, redirigir al panel
if(isset($_SESSION['usuario_cliente'])){
    header("Location: ../index/clienteIndex.php");
    exit();
}

if(isset($_COOKIE['usuario_cliente'])){
    $usuarioGuardado = htmlspecialchars($_COOKIE['usuario_cliente'], ENT_QUOTES, 'UTF-8');
} else {
    $usuarioGuardado = "";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Cliente</title>
    <link rel="stylesheet" href="../loginStyle.css">
</head>
<body>
    <div class="login-container">
        <h2>Bienvenido Cliente</h2>
        <form action="validar_cliente.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required value="<?php echo $usuarioGuardado; ?>">
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <label>
                <input type="checkbox" name="recordar"> Recordarme
            </label>
            <button type="submit">Ingresar</button>
        </form>
        <a href="registrarse_cliente.php" style="display: block; text-align: center; color: #f5c542; text-decoration: none; margin-top: 15px; font-size: 0.9em;">¿No tienes cuenta? Regístrate aquí</a>
        <a href="../index.html" style="display: block; text-align: center; color: #f5c542; text-decoration: none; margin-top: 10px; font-size: 0.9em;">Volver al inicio</a>
    </div>
</body>
</html>