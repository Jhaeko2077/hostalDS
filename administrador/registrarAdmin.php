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
    $contrasena = $_POST["contrasena"];

    // Validar campos vacíos
    if(empty($nombres) || empty($apellidos) || empty($dni) || empty($email) || empty($usuario) || empty($contrasena)){
        $mensaje = "Por favor completa todos los campos obligatorios.";
    } else {
        // Verificar que el usuario exista en Empleado
        $stmt_check_emp = $conn->prepare("SELECT id FROM Empleado WHERE usuario = ?");
        $stmt_check_emp->bind_param("s", $usuario);
        $stmt_check_emp->execute();
        $result_check_emp = $stmt_check_emp->get_result();
        
        if($result_check_emp->num_rows == 0){
            $mensaje = "El usuario no existe en la tabla Empleado. Primero debe registrarse como empleado.";
            $stmt_check_emp->close();
        } else {
            $stmt_check_emp->close();
            
            // Verificar si ya existe un administrador con ese usuario, email o DNI
            $stmt_check = $conn->prepare("SELECT id FROM Administrador WHERE usuario = ? OR email = ? OR dni = ?");
            $stmt_check->bind_param("sss", $usuario, $email, $dni);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            
            if($result_check->num_rows > 0){
                $mensaje = "Ya existe un administrador con ese usuario, email o DNI.";
                $stmt_check->close();
            } else {
                $stmt_check->close();
                
                // Hash de la contraseña
                $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

                // El ID se generará automáticamente por el trigger
                $sql = "INSERT INTO Administrador (nombres, apellidos, dni, email, telefono, contrasena, usuario)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("sssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario);
                    if ($stmt->execute()) {
                        $mensaje = "Administrador registrado exitosamente. ¡Bienvenido, " . htmlspecialchars($nombres) . "! <a href='login_admin.php'>Inicia sesión aquí</a>";
                    } else {
                        $mensaje = "Error al registrar administrador: " . $stmt->error;
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