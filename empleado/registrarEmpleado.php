<?php 
require_once("../includes/functions.php");
include("../conexion.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = sanitize_input($_POST["nombres"]);
    $apellidos = sanitize_input($_POST["apellidos"]);
    $dni = sanitize_input($_POST["dni"]);
    $email = sanitize_input($_POST["email"]);
    $telefono = sanitize_input($_POST["telefono"] ?? '');
    $usuario = sanitize_input($_POST["usuario"]);
    $tipo = sanitize_input($_POST["tipo"]);
    $contrasena = $_POST["contrasena"];

    // Validar campos vacíos
    if(empty($nombres) || empty($apellidos) || empty($dni) || empty($email) || empty($usuario) || empty($contrasena) || empty($tipo)){
        $mensaje = "Por favor completa todos los campos obligatorios.";
    } else {
        // Validaciones adicionales
        if (!validate_email($email)) {
            $mensaje = "El email no es válido.";
        } elseif (!validate_dni($dni)) {
            $mensaje = "El DNI debe tener 8 dígitos.";
        } else {
            // Verificar si el usuario, email o DNI ya existe
            if (user_exists($conn, 'Empleado', 'usuario', $usuario) || 
                user_exists($conn, 'Empleado', 'email', $email) || 
                user_exists($conn, 'Empleado', 'dni', $dni)) {
                $mensaje = "El usuario, email o DNI ya está registrado. Por favor usa otros datos.";
            } else {
                // Hash de la contraseña
                $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

                // El ID se generará automáticamente por el trigger
                $sql = "INSERT INTO Empleado (nombres, apellidos, dni, email, telefono, contrasena, usuario, tipo)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("ssssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario, $tipo);
                    if ($stmt->execute()) {
                        $mensaje = "Empleado registrado exitosamente. ¡Bienvenido, " . htmlspecialchars($nombres) . "! <a href='login_empleado.php'>Inicia sesión aquí</a>";
                    } else {
                        $mensaje = "Error al registrar empleado: " . htmlspecialchars($stmt->error);
                    }
                    $stmt->close();
                } else {
                    $mensaje = "Error en la conexión con la base de datos.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Empleado</title>
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
        <h2>Registrar Empleado</h2>

        <?php if ($mensaje != ""): ?>
            <div class="mensaje"><?= $mensaje ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="nombres" placeholder="Nombres" required>
            <input type="text" name="apellidos" placeholder="Apellidos" required>
            <input type="text" name="dni" placeholder="DNI" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="text" name="telefono" placeholder="Teléfono">
            <input type="text" name="usuario" placeholder="Nombre de usuario" required>
            <input type="text" name="tipo" placeholder="Tipo de empleado" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            
            <button type="submit">Crear cuenta</button>
            <a href="login_empleado.php">¿Ya tienes cuenta? Inicia sesión</a>
        </form>
    </div>
</body>
</html>