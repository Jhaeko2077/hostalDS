<?php
session_start();

if(!isset($_SESSION['usuario_admin'])){
    header("Location: ../administrador/login_admin.php");
    exit();
}

$usuario = $_SESSION['usuario_admin'];
$page_title = "Panel Administrador";
include("../includes/head.php");
include("../includes/navegacion_tailwind.php");
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <!-- Header -->
    <div class="text-center mb-12 animate-slide-down">
        <div class="flex justify-center mb-4">
            <div class="bg-primary/10 p-6 rounded-full">
                <i class="ph ph-shield-check text-primary text-7xl animate-bounce-subtle"></i>
            </div>
        </div>
        <h1 class="text-5xl font-bold text-gray-dark mb-4">Panel Administrador</h1>
        <p class="text-xl text-gray-dark/70">Bienvenido, <span class="font-semibold text-primary"><?php echo htmlspecialchars($usuario); ?></span></p>
    </div>

    <!-- Grid de Accesos Rápidos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Dashboard -->
        <a href="dashboard.php" class="group bg-gradient-to-br from-primary to-primary-dark text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-chart-line-up text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Dashboard</h3>
                    <p class="text-white/80 text-sm">Estadísticas y métricas</p>
                </div>
            </div>
            <p class="text-white/90">Ver estadísticas en tiempo real del sistema</p>
        </a>

        <!-- Servicios -->
        <a href="../servicio/servicios.php" class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.1s">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-wrench text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Servicios</h3>
                    <p class="text-white/80 text-sm">Gestionar servicios</p>
                </div>
            </div>
            <p class="text-white/90">Administrar servicios del hostal</p>
        </a>

        <!-- Clientes -->
        <a href="../cliente/clientes.php" class="group bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.2s">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-users text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Clientes</h3>
                    <p class="text-white/80 text-sm">Gestionar clientes</p>
                </div>
            </div>
            <p class="text-white/90">Administrar información de clientes</p>
        </a>

        <!-- Reservas -->
        <a href="../detalleReserva/detallesReservas.php" class="group bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.3s">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-calendar-check text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Reservas</h3>
                    <p class="text-white/80 text-sm">Gestionar reservas</p>
                </div>
            </div>
            <p class="text-white/90">Administrar todas las reservas</p>
        </a>

        <!-- Servicios Detallados -->
        <a href="../detalleServicio/detallesServicios.php" class="group bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.4s">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-list-dashes text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Servicios Detallados</h3>
                    <p class="text-white/80 text-sm">Detalles de servicios</p>
                </div>
            </div>
            <p class="text-white/90">Ver servicios por habitación</p>
        </a>

        <!-- Tipos de Pago -->
        <a href="../tipoPago/tipoPagos.php" class="group bg-gradient-to-br from-teal-500 to-teal-600 text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.5s">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-credit-card text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Tipos de Pago</h3>
                    <p class="text-white/80 text-sm">Métodos de pago</p>
                </div>
            </div>
            <p class="text-white/90">Gestionar métodos de pago</p>
        </a>

        <!-- Habitaciones -->
        <a href="../habitacion/habitaciones.php" class="group bg-gradient-to-br from-cyan-500 to-cyan-600 text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.6s">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-house text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Habitaciones</h3>
                    <p class="text-white/80 text-sm">Gestionar habitaciones</p>
                </div>
            </div>
            <p class="text-white/90">Administrar habitaciones del hostal</p>
        </a>

        <!-- Administradores -->
        <a href="../administrador/administradores.php" class="group bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.7s">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-shield-star text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Administradores</h3>
                    <p class="text-white/80 text-sm">Gestionar admins</p>
                </div>
            </div>
            <p class="text-white/90">Administrar cuentas de administradores</p>
        </a>

        <!-- Empleados -->
        <a href="../empleado/empleados.php" class="group bg-gradient-to-br from-pink-500 to-pink-600 text-white rounded-xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.8s">
            <div class="flex items-center space-x-4 mb-4">
                <div class="bg-white/20 p-4 rounded-lg">
                    <i class="ph ph-briefcase text-4xl group-hover:animate-bounce-subtle"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Empleados</h3>
                    <p class="text-white/80 text-sm">Gestionar empleados</p>
            </div>
        </div>
            <p class="text-white/90">Administrar personal del hostal</p>
        </a>
    </div>
</div>

</body>
</html>
