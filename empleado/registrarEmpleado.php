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
    $tipo = trim($_POST["tipo"]); // tipo de empleado (ej: "Recepcionista", "Secretaria")
    $contrasena = $_POST["contrasena"];

    // Validar campos vacíos
    if(empty($nombres) || empty($apellidos) || empty($dni) || empty($email) || empty($usuario) || empty($contrasena) || empty($tipo)){
        $mensaje = "Por favor completa todos los campos obligatorios.";
    } else {
        // Verificar si el usuario, email o DNI ya existe
        $stmt_check = $conn->prepare("SELECT id FROM Empleado WHERE usuario = ? OR email = ? OR dni = ?");
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

            // El ID se generará automáticamente por el trigger
            $sql = "INSERT INTO Empleado (nombres, apellidos, dni, email, telefono, contrasena, usuario, tipo)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ssssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario, $tipo);
                if ($stmt->execute()) {
                    $mensaje = "Empleado registrado exitosamente. ¡Bienvenido, " . htmlspecialchars($nombres) . "! <a href='login_empleado.php'>Inicia sesión aquí</a>";
                } else {
                    $mensaje = "Error al registrar empleado: " . $stmt->error;
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