<?php 
include("../conexion.php"); // conexión a la BD

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST["nombres"]);
    $apellidos = trim($_POST["apellidos"]);
    $dni = trim($_POST["dni"]);
    $email = trim($_POST["email"]);
    $telefono = trim($_POST["telefono"]);
    $usuario = trim($_POST["usuario"]); // Debe existir en Empleado
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO Administrador (id, nombres, apellidos, dni, email, telefono, contrasena, usuario)
            VALUES (UUID(), ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena, $usuario);
        if ($stmt->execute()) {
            $mensaje = "Administrador registrado exitosamente. ¡Bienvenido, $nombres!";
        } else {
            $mensaje = "Error al registrar administrador: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $mensaje = "Error en la conexión con la base de datos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Administrador</title>
    <link rel="stylesheet" href="../loginStyle.css">
    <style>
        .mensaje {
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
            color: #f5c542;
        }
        .login-container a {
            display: block;
            text-align: center;
            color: #f5c542;
            text-decoration: none;
            margin-top: 15px;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Registrar Administrador</h2>

        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="nombres" placeholder="Nombres" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="text" name="dni" placeholder="DNI" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="text" name="telefono" placeholder="Teléfono">
            <input type="text" name="usuario" placeholder="Usuario existente en Empleado" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            
            <button type="submit">Crear cuenta</button>
            <a href="login_admin.php">¿Ya tienes cuenta? Inicia sesión</a>
        </form>
    </div>
</body>
</html>