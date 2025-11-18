<?php 
require_once("../includes/functions.php");
include("../conexion.php");

$mensaje = "";
$mensaje_tipo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = sanitize_input($_POST["nombres"]);
    $apellidos = sanitize_input($_POST["apellidos"]);
    $dni = sanitize_input($_POST["dni"]);
    $email = sanitize_input($_POST["email"]);
    $telefono = sanitize_input($_POST["telefono"] ?? '');
    $usuario = sanitize_input($_POST["usuario"]);
    $contrasena = $_POST["contrasena"];

    // Validar campos vacíos
    if(empty($nombres) || empty($apellidos) || empty($dni) || empty($email) || empty($usuario) || empty($contrasena)){
        $mensaje = "Por favor completa todos los campos obligatorios.";
        $mensaje_tipo = "error";
    } else {
        // Validaciones adicionales
        if (!validate_email($email)) {
            $mensaje = "El email no es válido.";
            $mensaje_tipo = "error";
        } elseif (!validate_dni($dni)) {
            $mensaje = "El DNI debe tener 8 dígitos.";
            $mensaje_tipo = "error";
        } else {
            // Verificar que el usuario exista en Empleado
            if (!user_exists($conn, 'Empleado', 'usuario', $usuario)) {
                $mensaje = "El usuario no existe en la tabla Empleado. Primero debe registrarse como empleado.";
                $mensaje_tipo = "error";
            } else {
                // Verificar si ya existe un administrador con ese usuario, email o DNI
                if (user_exists($conn, 'Administrador', 'usuario', $usuario) || 
                    user_exists($conn, 'Administrador', 'email', $email) || 
                    user_exists($conn, 'Administrador', 'dni', $dni)) {
                    $mensaje = "Ya existe un administrador con ese usuario, email o DNI.";
                    $mensaje_tipo = "error";
                } else {
                    // Hash de la contraseña
                    $contrasena_hash = password_hash($contrasena, PASSWORD_BCRYPT);

                    // El ID se generará automáticamente por el trigger
                    $sql = "INSERT INTO Administrador (nombres, apellidos, dni, email, telefono, contrasena, usuario)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("sssssss", $nombres, $apellidos, $dni, $email, $telefono, $contrasena_hash, $usuario);
                        if ($stmt->execute()) {
                            $mensaje = "Administrador registrado exitosamente. ¡Bienvenido, " . htmlspecialchars($nombres) . "!";
                            $mensaje_tipo = "success";
                        } else {
                            $mensaje = "Error al registrar administrador: " . htmlspecialchars($stmt->error);
                            $mensaje_tipo = "error";
                        }
                        $stmt->close();
                    } else {
                        $mensaje = "Error en la conexión con la base de datos.";
                        $mensaje_tipo = "error";
                    }
                }
            }
        }
    }
}

$page_title = "Registro de Administrador";
include("../includes/head.php");
?>

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-primary/10 via-white to-primary/5 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 animate-fade-in">
        <div class="bg-white rounded-2xl shadow-2xl p-8 animate-scale-in">
            <!-- Logo y Título -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <div class="bg-primary/10 p-4 rounded-full">
                        <i class="ph ph-shield-check text-primary text-6xl animate-bounce-subtle"></i>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-gray-dark mb-2">Registrar Administrador</h2>
                <p class="text-gray-dark/70">Crea tu cuenta de administrador</p>
            </div>

            <!-- Mensaje -->
            <?php if ($mensaje != ""): ?>
                <div class="mb-6 p-4 rounded-lg <?= $mensaje_tipo == 'success' ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-red-100 text-red-700 border border-red-300' ?> animate-slide-down">
                    <div class="flex items-center space-x-2">
                        <i class="ph <?= $mensaje_tipo == 'success' ? 'ph-check-circle' : 'ph-warning-circle' ?> text-xl"></i>
                        <p class="font-medium"><?= $mensaje ?></p>
                    </div>
                    <?php if ($mensaje_tipo == 'success'): ?>
                        <a href="login_admin.php" class="block mt-3 text-primary hover:text-primary-dark font-semibold transition-colors duration-300 group">
                            <i class="ph ph-arrow-right inline mr-2 group-hover:animate-bounce-subtle"></i>
                            Inicia sesión aquí
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form method="POST" action="" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">
                            <i class="ph ph-user text-primary"></i> Nombres
                        </label>
                        <input type="text" name="nombres" placeholder="Nombres" required
                            class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">
                            <i class="ph ph-user text-primary"></i> Apellidos
                        </label>
                        <input type="text" name="apellidos" placeholder="Apellidos" required
                            class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-identification-card text-primary"></i> DNI
                    </label>
                    <input type="text" name="dni" placeholder="DNI (8 dígitos)" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-envelope text-primary"></i> Correo electrónico
                    </label>
                    <input type="email" name="email" placeholder="tu@email.com" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-phone text-primary"></i> Teléfono
                    </label>
                    <input type="text" name="telefono" placeholder="Teléfono (opcional)"
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-user-circle text-primary"></i> Usuario existente en Empleado
                    </label>
                    <input type="text" name="usuario" placeholder="Usuario" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                    <p class="text-xs text-gray-dark/60 mt-1">El usuario debe existir previamente en la tabla Empleado</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-lock text-primary"></i> Contraseña
                    </label>
                    <input type="password" name="contrasena" placeholder="Contraseña" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>

                <button type="submit" 
                    class="w-full flex items-center justify-center space-x-2 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
                    <i class="ph ph-user-plus text-xl group-hover:animate-bounce-subtle"></i>
                    <span>Crear cuenta</span>
                </button>
            </form>

            <!-- Enlaces -->
            <div class="mt-6 text-center">
                <a href="login_admin.php" 
                    class="block text-primary hover:text-primary-dark font-medium transition-colors duration-300 group">
                    <i class="ph ph-sign-in inline mr-2 group-hover:animate-bounce-subtle"></i>
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
