<?php
// Archivo de navegación con Tailwind CSS y Phosphor Icons
if (!isset($_SESSION)) {
    session_start();
}
$es_cliente = isset($_SESSION['usuario_cliente']);
$es_empleado = isset($_SESSION['usuario_empleado']);
$es_admin = isset($_SESSION['usuario_admin']);

if($es_cliente) {
    $panel_url = "../index/clienteIndex.php";
    $logout_url = "../cliente/logout_cliente.php";
    $usuario = $_SESSION['usuario_cliente'];
    $rol = "Cliente";
} elseif($es_empleado) {
    $panel_url = "../index/empleadoIndex.php";
    $logout_url = "../empleado/logout_empleado.php";
    $usuario = $_SESSION['usuario_empleado'];
    $rol = "Empleado";
} elseif($es_admin) {
    $panel_url = "../index/indexAdmin.php";
    $logout_url = "../administrador/logout_admin.php";
    $usuario = $_SESSION['usuario_admin'];
    $rol = "Administrador";
} else {
    $panel_url = "../index.html";
    $logout_url = null;
    $usuario = null;
    $rol = null;
}
?>

<?php if($usuario): ?>
<nav class="bg-white shadow-lg sticky top-0 z-50 animate-slide-down">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo y Panel -->
            <div class="flex items-center space-x-4">
                <a href="<?php echo $panel_url; ?>" class="flex items-center space-x-2 group">
                    <i class="ph ph-house text-primary text-2xl group-hover:animate-bounce-subtle transition-all duration-300"></i>
                    <span class="text-primary font-semibold text-lg">Panel Principal</span>
                </a>
            </div>
            
            <!-- Información del Usuario -->
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 bg-gray-light px-4 py-2 rounded-lg">
                    <i class="ph ph-user-circle text-primary text-xl animate-pulse-slow"></i>
                    <div class="flex flex-col">
                        <span class="text-gray-dark text-sm font-medium"><?php echo htmlspecialchars($usuario); ?></span>
                        <span class="text-primary text-xs"><?php echo htmlspecialchars($rol); ?></span>
                    </div>
                </div>
                
                <!-- Botón Cerrar Sesión -->
                <?php if($logout_url): ?>
                <form action="<?php echo $logout_url; ?>" method="POST" class="inline">
                    <button type="submit" class="flex items-center space-x-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg group">
                        <i class="ph ph-sign-out text-lg group-hover:animate-bounce-subtle"></i>
                        <span class="font-medium">Cerrar Sesión</span>
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<?php endif; ?>

