<?php
session_start();

if(!isset($_SESSION['usuario_cliente'])){
    header("Location: ../cliente/login_cliente.php");
    exit();
}

$usuario = $_SESSION['usuario_cliente'];
$page_title = "Panel Cliente";
include("../includes/head.php");
include("../includes/navegacion_tailwind.php");
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <!-- Header -->
    <div class="text-center mb-12 animate-slide-down">
        <div class="flex justify-center mb-4">
            <div class="bg-primary/10 p-6 rounded-full">
                <i class="ph ph-user-circle text-primary text-7xl animate-bounce-subtle"></i>
            </div>
        </div>
        <h1 class="text-5xl font-bold text-gray-dark mb-4">Panel Cliente</h1>
        <p class="text-xl text-gray-dark/70">Bienvenido, <span class="font-semibold text-primary"><?php echo htmlspecialchars($usuario); ?></span></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        <a href="../detalleReserva/detallesReservas.php" class="group bg-gradient-to-br from-primary to-primary-dark text-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in">
            <div class="flex flex-col items-center text-center">
                <div class="bg-white/20 p-6 rounded-full mb-4">
                    <i class="ph ph-calendar-check text-6xl group-hover:animate-bounce-subtle"></i>
                </div>
                <h3 class="text-3xl font-bold mb-2">Ver Reservas</h3>
                <p class="text-white/90">Consulta tus reservas activas y pasadas</p>
            </div>
        </a>

        <a href="../servicio/servicios.php" class="group bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.1s">
            <div class="flex flex-col items-center text-center">
                <div class="bg-white/20 p-6 rounded-full mb-4">
                    <i class="ph ph-wrench text-6xl group-hover:animate-bounce-subtle"></i>
                </div>
                <h3 class="text-3xl font-bold mb-2">Ver Servicios</h3>
                <p class="text-white/90">Explora los servicios disponibles</p>
            </div>
        </a>
    </div>
</div>

</body>
</html>
