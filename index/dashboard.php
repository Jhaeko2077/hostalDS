<?php
require_once("../includes/functions.php");
check_permission(['admin', 'empleado']);

include("../conexion.php");

$page_title = "Dashboard - Panel de Control";

// Obtener estadísticas de reservas (con manejo de error si la vista no existe)
try {
    $stats = $conn->query("SELECT * FROM vista_estadisticas_reservas")->fetch_assoc();
} catch (Exception $e) {
    // Si la vista no existe, calcular manualmente
    $stats_result = $conn->query("
        SELECT 
            COUNT(*) as total_reservas,
            COUNT(CASE WHEN pago = 1 THEN 1 END) as reservas_pagadas,
            COUNT(CASE WHEN pago = 0 THEN 1 END) as reservas_pendientes,
            COUNT(CASE WHEN es_checkin_directo = 1 THEN 1 END) as checkins_directos,
            COUNT(CASE WHEN CURDATE() BETWEEN fecha_inicio AND fecha_fin THEN 1 END) as reservas_activas,
            COUNT(CASE WHEN fecha_fin < CURDATE() THEN 1 END) as reservas_completadas
        FROM detalleReserva
    ");
    $stats = $stats_result ? $stats_result->fetch_assoc() : [
        'total_reservas' => 0,
        'reservas_pagadas' => 0,
        'reservas_pendientes' => 0,
        'checkins_directos' => 0,
        'reservas_activas' => 0,
        'reservas_completadas' => 0
    ];
}

// Obtener habitaciones disponibles (con manejo de error si la vista no existe)
try {
    $habitaciones_disponibles = $conn->query("
        SELECT COUNT(*) as total FROM vista_habitaciones_disponibles 
        WHERE disponible_ahora COLLATE utf8mb4_unicode_ci = 'Sí' COLLATE utf8mb4_unicode_ci
    ")->fetch_assoc();
} catch (Exception $e) {
    // Si la vista no existe, calcular manualmente
    $hab_result = $conn->query("
        SELECT COUNT(*) as total FROM Habitaciones 
        WHERE estado COLLATE utf8mb4_unicode_ci = 'Disponible' COLLATE utf8mb4_unicode_ci
        AND NOT EXISTS (
            SELECT 1 FROM detalleReserva dr 
            WHERE dr.idHab = Habitaciones.codigo 
            AND CURDATE() BETWEEN dr.fecha_inicio AND dr.fecha_fin
        )
    ");
    $habitaciones_disponibles = $hab_result ? $hab_result->fetch_assoc() : ['total' => 0];
}

// Obtener habitaciones en limpieza
$hab_limpieza_result = $conn->query("
    SELECT COUNT(*) as total FROM Habitaciones 
    WHERE estado COLLATE utf8mb4_unicode_ci = 'Limpieza' COLLATE utf8mb4_unicode_ci
");
$habitaciones_limpieza = $hab_limpieza_result ? $hab_limpieza_result->fetch_assoc() : ['total' => 0];

// Reservas que terminan hoy
$reservas_hoy_result = $conn->query("
    SELECT COUNT(*) as total FROM detalleReserva 
    WHERE fecha_fin = CURDATE()
");
$reservas_hoy = $reservas_hoy_result ? $reservas_hoy_result->fetch_assoc() : ['total' => 0];

// Reservas pendientes de pago
$reservas_pendientes_result = $conn->query("
    SELECT COUNT(*) as total FROM detalleReserva 
    WHERE pago = 0 AND fecha_fin >= CURDATE()
");
$reservas_pendientes = $reservas_pendientes_result ? $reservas_pendientes_result->fetch_assoc() : ['total' => 0];

// Últimas 5 reservas
$ultimas_reservas = $conn->query("
    SELECT dr.*, c.nombres, c.apellidos, h.codigo as habitacion_codigo, h.tipo as habitacion_tipo, CONCAT(h.codigo, ' - ', h.tipo) as habitacion_display
    FROM detalleReserva dr
    JOIN Cliente c ON dr.idCli = c.id
    JOIN Habitaciones h ON dr.idHab = h.codigo
    ORDER BY dr.fecha_inicio DESC
    LIMIT 5
");

// Cambios recientes en auditoría (solo si la tabla existe)
$cambios_recientes = null;
try {
    $cambios_recientes = $conn->query("
        SELECT * FROM auditoria_reservas 
        ORDER BY fecha_hora DESC 
        LIMIT 10
    ");
} catch (Exception $e) {
    // La tabla de auditoría no existe, no es crítico
    $cambios_recientes = null;
}

include("../includes/head.php");
include("../includes/navegacion_tailwind.php");
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-dark mb-2 flex items-center space-x-3">
                <i class="ph ph-chart-line-up text-primary animate-bounce-subtle"></i>
                <span>Dashboard</span>
            </h1>
            <p class="text-gray-dark/70">Panel de control y estadísticas en tiempo real</p>
        </div>
        <button onclick="location.reload()" class="flex items-center space-x-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
            <i class="ph ph-arrow-clockwise text-xl group-hover:animate-spin"></i>
            <span class="font-medium">Actualizar</span>
        </button>
    </div>

    <!-- Estadísticas Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Reservas -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-primary/10 p-3 rounded-lg">
                    <i class="ph ph-calendar-check text-primary text-3xl animate-pulse-slow"></i>
                </div>
            </div>
            <h3 class="text-gray-dark/70 text-sm font-medium mb-1">Total Reservas</h3>
            <p class="text-3xl font-bold text-gray-dark"><?= htmlspecialchars($stats['total_reservas'] ?? 0) ?></p>
        </div>

        <!-- Reservas Pagadas -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.1s">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="ph ph-check-circle text-green-600 text-3xl animate-bounce-subtle"></i>
                </div>
            </div>
            <h3 class="text-gray-dark/70 text-sm font-medium mb-1">Reservas Pagadas</h3>
            <p class="text-3xl font-bold text-green-600"><?= htmlspecialchars($stats['reservas_pagadas'] ?? 0) ?></p>
        </div>

        <!-- Pendientes de Pago -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.2s">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-red-100 p-3 rounded-lg">
                    <i class="ph ph-clock-countdown text-red-600 text-3xl animate-pulse-slow"></i>
                </div>
            </div>
            <h3 class="text-gray-dark/70 text-sm font-medium mb-1">Pendientes de Pago</h3>
            <p class="text-3xl font-bold text-red-600"><?= htmlspecialchars($reservas_pendientes['total'] ?? 0) ?></p>
        </div>

        <!-- Habitaciones Disponibles -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.3s">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i class="ph ph-house text-blue-600 text-3xl animate-bounce-subtle"></i>
                </div>
            </div>
            <h3 class="text-gray-dark/70 text-sm font-medium mb-1">Habitaciones Disponibles</h3>
            <p class="text-3xl font-bold text-blue-600"><?= htmlspecialchars($habitaciones_disponibles['total'] ?? 0) ?></p>
        </div>

        <!-- En Limpieza -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="ph ph-broom text-yellow-600 text-3xl animate-pulse-slow"></i>
                </div>
            </div>
            <h3 class="text-gray-dark/70 text-sm font-medium mb-1">En Limpieza</h3>
            <p class="text-3xl font-bold text-yellow-600"><?= htmlspecialchars($habitaciones_limpieza['total'] ?? 0) ?></p>
        </div>

        <!-- Reservas Activas -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.5s">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i class="ph ph-calendar text-purple-600 text-3xl animate-bounce-subtle"></i>
                </div>
            </div>
            <h3 class="text-gray-dark/70 text-sm font-medium mb-1">Reservas Activas Hoy</h3>
            <p class="text-3xl font-bold text-purple-600"><?= htmlspecialchars($stats['reservas_activas'] ?? 0) ?></p>
        </div>

        <!-- Check-ins Directos -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.6s">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-indigo-100 p-3 rounded-lg">
                    <i class="ph ph-door text-indigo-600 text-3xl animate-pulse-slow"></i>
                </div>
            </div>
            <h3 class="text-gray-dark/70 text-sm font-medium mb-1">Check-ins Directos</h3>
            <p class="text-3xl font-bold text-indigo-600"><?= htmlspecialchars($stats['checkins_directos'] ?? 0) ?></p>
        </div>

        <!-- Reservas Completadas -->
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 animate-scale-in" style="animation-delay: 0.7s">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-teal-100 p-3 rounded-lg">
                    <i class="ph ph-check-square text-teal-600 text-3xl animate-bounce-subtle"></i>
                </div>
            </div>
            <h3 class="text-gray-dark/70 text-sm font-medium mb-1">Reservas Completadas</h3>
            <p class="text-3xl font-bold text-teal-600"><?= htmlspecialchars($stats['reservas_completadas'] ?? 0) ?></p>
        </div>
    </div>

    <!-- Últimas Reservas -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 animate-slide-up">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-dark flex items-center space-x-2">
                <i class="ph ph-list-bullets text-primary"></i>
                <span>Últimas Reservas</span>
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-light">
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">ID</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Cliente</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Habitación</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Fecha Inicio</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Fecha Fin</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Estado Pago</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($ultimas_reservas && $ultimas_reservas->num_rows > 0): ?>
                        <?php while($reserva = $ultimas_reservas->fetch_assoc()): ?>
                        <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors duration-200">
                            <td class="py-3 px-4 text-gray-dark font-mono text-sm"><?= htmlspecialchars($reserva['id']) ?></td>
                            <td class="py-3 px-4 text-gray-dark"><?= htmlspecialchars($reserva['nombres'] . ' ' . $reserva['apellidos']) ?></td>
                            <td class="py-3 px-4 text-gray-dark"><?= htmlspecialchars($reserva['habitacion_display']) ?></td>
                            <td class="py-3 px-4 text-gray-dark"><?= date('d/m/Y', strtotime($reserva['fecha_inicio'])) ?></td>
                            <td class="py-3 px-4 text-gray-dark"><?= date('d/m/Y', strtotime($reserva['fecha_fin'])) ?></td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-sm font-medium <?= $reserva['pago'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <i class="ph <?= $reserva['pago'] ? 'ph-check-circle' : 'ph-clock' ?>"></i>
                                    <span><?= $reserva['pago'] ? 'Pagado' : 'Pendiente' ?></span>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <?php if($reserva['es_checkin_directo']): ?>
                                    <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                                        <i class="ph ph-door"></i>
                                        <span>Check-in Directo</span>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-dark">Reserva Normal</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-gray-dark/70">
                                <i class="ph ph-inbox text-4xl mb-2 block"></i>
                                <p>No hay reservas registradas</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cambios Recientes (si existe la tabla de auditoría) -->
    <?php if($cambios_recientes !== null && $cambios_recientes->num_rows > 0): ?>
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 animate-slide-up">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-file-text text-primary"></i>
            <span>Cambios Recientes (Auditoría)</span>
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-light">
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Fecha/Hora</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Reserva ID</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Acción</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($cambio = $cambios_recientes->fetch_assoc()): ?>
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors duration-200">
                        <td class="py-3 px-4 text-gray-dark text-sm"><?= date('d/m/Y H:i', strtotime($cambio['fecha_hora'])) ?></td>
                        <td class="py-3 px-4 text-gray-dark font-mono text-sm"><?= htmlspecialchars($cambio['reserva_id']) ?></td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-sm font-medium <?= $cambio['accion'] == 'INSERT' ? 'bg-green-100 text-green-700' : ($cambio['accion'] == 'UPDATE' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') ?>">
                                <i class="ph <?= $cambio['accion'] == 'INSERT' ? 'ph-plus-circle' : ($cambio['accion'] == 'UPDATE' ? 'ph-pencil' : 'ph-trash') ?>"></i>
                                <span><?= htmlspecialchars($cambio['accion']) ?></span>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-dark text-sm"><?= htmlspecialchars(substr($cambio['datos_nuevos'] ?? $cambio['datos_anteriores'] ?? 'N/A', 0, 80)) ?>...</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-xl shadow-lg p-6 animate-slide-up">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-lightning text-primary animate-pulse-slow"></i>
            <span>Acciones Rápidas</span>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="../detalleReserva/detallesReservas.php" class="flex items-center space-x-3 bg-primary hover:bg-primary-dark text-white px-6 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-xl group">
                <i class="ph ph-calendar-check text-2xl group-hover:animate-bounce-subtle"></i>
                <span class="font-medium">Ver Reservas</span>
            </a>
            <a href="../habitacion/habitaciones.php" class="flex items-center space-x-3 bg-blue-500 hover:bg-blue-600 text-white px-6 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-xl group">
                <i class="ph ph-house text-2xl group-hover:animate-bounce-subtle"></i>
                <span class="font-medium">Habitaciones</span>
            </a>
            <a href="../cliente/clientes.php" class="flex items-center space-x-3 bg-green-500 hover:bg-green-600 text-white px-6 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-xl group">
                <i class="ph ph-users text-2xl group-hover:animate-bounce-subtle"></i>
                <span class="font-medium">Clientes</span>
            </a>
            <?php if(isset($_SESSION['usuario_admin'])): ?>
            <a href="indexAdmin.php" class="flex items-center space-x-3 bg-gray-dark hover:bg-gray-darker text-white px-6 py-4 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-xl group">
                <i class="ph ph-house-simple text-2xl group-hover:animate-bounce-subtle"></i>
                <span class="font-medium">Panel Principal</span>
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Auto-refresh cada 30 segundos
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>

</body>
</html>
