<?php
session_start();

// Si ya está logueado, redirigir al panel
if(isset($_SESSION['usuario_admin'])){
    header("Location: ../index/indexAdmin.php");
    exit();
}

if(isset($_COOKIE['usuario_admin'])){
    $usuarioGuardado = htmlspecialchars($_COOKIE['usuario_admin'], ENT_QUOTES, 'UTF-8');
} else {
    $usuarioGuardado = "";
}

$page_title = "Login Administrador";
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
                <h2 class="text-3xl font-bold text-gray-dark mb-2">Panel del Administrador</h2>
                <p class="text-gray-dark/70">Acceso exclusivo para administradores</p>
            </div>

            <!-- Formulario -->
            <form action="validar_admin.php" method="POST" class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">
                            <i class="ph ph-user text-primary"></i> Usuario
                        </label>
                        <input 
                            type="text" 
                            name="usuario" 
                            placeholder="Ingresa tu usuario" 
                            required 
                            value="<?php echo $usuarioGuardado; ?>"
                            class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-dark mb-2">
                            <i class="ph ph-lock text-primary"></i> Contraseña
                        </label>
                        <input 
                            type="password" 
                            name="contrasena" 
                            placeholder="Ingresa tu contraseña" 
                            required
                            class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50"
                        >
                    </div>
                </div>

                <div class="flex items-center">
                    <input 
                        id="recordar" 
                        name="recordar" 
                        type="checkbox" 
                        class="h-4 w-4 text-primary focus:ring-primary border-gray-dark rounded"
                    >
                    <label for="recordar" class="ml-2 block text-sm text-gray-dark">
                        Recordarme
                    </label>
                </div>

                <button 
                    type="submit" 
                    class="w-full flex items-center justify-center space-x-2 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group"
                >
                    <i class="ph ph-sign-in text-xl group-hover:animate-bounce-subtle"></i>
                    <span>Acceder</span>
                </button>
            </form>

            <!-- Enlaces -->
            <div class="mt-6 space-y-3 text-center">
                <a 
                    href="registrarAdmin.php" 
                    class="block text-primary hover:text-primary-dark font-medium transition-colors duration-300 group"
                >
                    <i class="ph ph-user-plus inline mr-2 group-hover:animate-bounce-subtle"></i>
                    ¿No tienes cuenta? Regístrate aquí
                </a>
                <a 
                    href="../index.html" 
                    class="block text-gray-dark/70 hover:text-primary font-medium transition-colors duration-300 group"
                >
                    <i class="ph ph-arrow-left inline mr-2 group-hover:animate-bounce-subtle"></i>
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
