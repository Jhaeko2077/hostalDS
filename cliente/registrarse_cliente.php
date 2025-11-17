<?php
include("../conexion.php"); // conexión a la BD

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST["nombres"]);
    $apellidos = trim($_POST["apellidos"]);
    $dni = trim($_POST["dni"]);
    $email = trim($_POST["email"]);
    $telefono = trim($_POST["telefono"]);
    $usuario = trim($_POST["usuario"]);
    $contrasena = $_POST["contrasena"];

    // Validar campos vacíos
    if(empty($nombres) || empty($apellidos) || empty($dni) || empty($email) || empty($usuario) || empty($contrasena)){
        $mensaje = "Por favor completa todos los campos obligatorios.";
    } else {
        // Verificar si el usuario ya existe
        $stmt_check = $conn->prepare("SELECT id FROM Cliente WHERE usuario = ? OR email = ? OR dni = ?");
        $stmt_check->bind_param("sss", $usuario, $email, $dni);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if($result_check->num_rows > 0){
            $mensaje = "El usuario, email o DNI ya está registrado. Por favor usa otros datos.";
            $stmt_check->close();
        } else {
            $stmt_check->close();
            
            // Hash de la contraseña
            $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

            $sql = "INSERT INTO Cliente (nombres, apellidos, dni, email, telefono, contrasena, usuario)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("sssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario);
                if ($stmt->execute()) {
                    $mensaje = "Registro exitoso. ¡Bienvenido, " . htmlspecialchars($nombres) . "! <a href='login_cliente.php'>Inicia sesión aquí</a>";
                } else {
                    $mensaje = "Error al registrar: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $mensaje = "Error en la conexión con la base de datos.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Cliente</title>
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
        <h2>Registrarse</h2>

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
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            
            <button type="submit">Crear cuenta</button>

            <a href="login_cliente.php">¿Ya tienes cuenta? Inicia sesión</a>
        </form>
    </div>
</body>
</html>