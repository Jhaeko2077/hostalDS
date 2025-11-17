<?php
session_start();

// Si ya está logueado, redirigir al panel
if(isset($_SESSION['usuario_cliente'])){
    header("Location: ../index/clienteIndex.php");
    exit();
}

if(isset($_COOKIE['usuario_cliente'])){
    $usuarioGuardado = $_COOKIE['usuario_cliente'];
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
    </div>
</body>
</html>