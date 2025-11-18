<?php
// Verificar sesión (clientes, empleados y administradores pueden acceder)
session_start();
if(!isset($_SESSION['usuario_cliente']) && !isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");

// Traer datos relacionados
$reservas = $conn->query("
    SELECT dr.*, c.nombres AS cliente, h.codigo AS habitacion_codigo, h.tipo AS habitacion_tipo, CONCAT(h.codigo, ' - ', h.tipo) AS habitacion, h.estado AS estado_habitacion, tp.descripcion AS tipoPago
    FROM detalleReserva dr
    JOIN Cliente c ON dr.idCli = c.id
    JOIN Habitaciones h ON dr.idHab = h.codigo
    JOIN tipoPago tp ON dr.idTipoPago = tp.id
    ORDER BY dr.fecha_inicio DESC, dr.id DESC
");

// Listas para selects - solo habitaciones disponibles o la que se está editando
$habitaciones_disponibles = $conn->query("
    SELECT codigo, tipo, estado 
    FROM Habitaciones 
    WHERE estado COLLATE utf8mb4_unicode_ci = 'Disponible' COLLATE utf8mb4_unicode_ci
    ORDER BY codigo ASC
");
$habitaciones_todas = $conn->query("SELECT codigo, tipo, estado FROM Habitaciones ORDER BY codigo ASC");
$clientes = $conn->query("SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM Cliente ORDER BY nombres ASC");
$tiposPago = $conn->query("SELECT id, descripcion FROM tipoPago");

$page_title = "Gestión de Reservas";
include("../includes/head.php");
include("../includes/navegacion_tailwind.php");
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center animate-slide-down">
        <div>
            <h1 class="text-4xl font-bold text-gray-dark mb-2 flex items-center space-x-3">
                <i class="ph ph-calendar-check text-primary animate-bounce-subtle"></i>
                <span>Gestión de Reservas</span>
            </h1>
            <p class="text-gray-dark/70">Administra las reservas del hostal</p>
        </div>
        <button onclick="location.reload()" 
            class="flex items-center space-x-2 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
            <i class="ph ph-arrow-clockwise text-xl group-hover:animate-spin"></i>
            <span class="font-medium">Actualizar</span>
        </button>
    </div>

    <?php if(isset($_SESSION['usuario_empleado']) || isset($_SESSION['usuario_admin'])): ?>
    <!-- Formulario de Registro -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 animate-scale-in">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-plus-circle text-primary"></i>
            <span>Registrar nueva reserva</span>
        </h2>
        <form action="detalleReserva_crud.php" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-user text-primary"></i> Cliente
                    </label>
                    <select name="idCli" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                        <option value="">Seleccione Cliente</option>
                        <?php 
                        $clientes->data_seek(0);
                        while ($c = $clientes->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['nombre_completo']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-house text-primary"></i> Habitación
                    </label>
                    <select name="idHab" id="selectHabitacion" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                        <option value="">Seleccione Habitación</option>
                        <?php 
                        $habitaciones_todas->data_seek(0);
                        while ($h = $habitaciones_todas->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($h['codigo']) ?>" data-estado="<?= htmlspecialchars($h['estado']) ?>">
                                <?= htmlspecialchars($h['codigo']) ?> - <?= htmlspecialchars($h['tipo']) ?> (<?= htmlspecialchars($h['estado']) ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-calendar-blank text-primary"></i> Fecha Inicio
                    </label>
                    <input type="date" name="fecha_inicio" id="fecha_inicio" required value="<?= date('Y-m-d') ?>"
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-calendar-check text-primary"></i> Fecha Fin
                    </label>
                    <input type="date" name="fecha_fin" id="fecha_fin" required value="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-credit-card text-primary"></i> Tipo de Pago
                    </label>
                    <select name="idTipoPago" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                        <option value="">Seleccione Tipo de Pago</option>
                        <?php 
                        $tiposPago->data_seek(0);
                        while ($tp = $tiposPago->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($tp['id']) ?>"><?= htmlspecialchars($tp['descripcion']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="flex items-center space-x-4 pt-8">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="pago" 
                            class="h-5 w-5 text-primary focus:ring-primary border-gray-dark rounded">
                        <span class="text-gray-dark font-medium">
                            <i class="ph ph-check-circle text-green-500"></i> Pagado
                        </span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="es_checkin_directo" id="checkin_directo"
                            class="h-5 w-5 text-primary focus:ring-primary border-gray-dark rounded">
                        <span class="text-gray-dark font-medium">
                            <i class="ph ph-door text-blue-500"></i> Check-in directo
                        </span>
                    </label>
                </div>
            </div>
            <button type="submit" name="crear" 
                class="w-full md:w-auto flex items-center justify-center space-x-2 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
                <i class="ph ph-plus-circle text-xl group-hover:animate-bounce-subtle"></i>
                <span>Agregar Reserva</span>
            </button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Lista de Reservas -->
    <div class="bg-white rounded-xl shadow-lg p-6 animate-slide-up">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-list-bullets text-primary"></i>
            <span>Lista de Reservas</span>
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-light">
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">ID</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Fecha Inicio</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Fecha Fin</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Cliente</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Habitación</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Estado Hab.</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Tipo Pago</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Pagado</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Check-in Directo</th>
                        <?php if(isset($_SESSION['usuario_empleado']) || isset($_SESSION['usuario_admin'])): ?>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $reservas->data_seek(0);
                    while ($row = $reservas->fetch_assoc()): 
                    ?>
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors duration-200">
                        <?php if(isset($_SESSION['usuario_empleado']) || isset($_SESSION['usuario_admin'])): ?>
                        <form action="detalleReserva_crud.php" method="POST" class="contents">
                            <td class="py-3 px-4">
                                <input type="text" name="id" value="<?= htmlspecialchars($row['id']) ?>" readonly
                                    class="w-20 px-2 py-1 border border-gray-light rounded text-gray-dark bg-gray-light/50 font-mono text-sm">
                            </td>
                            <td class="py-3 px-4">
                                <input type="date" name="fecha_inicio" value="<?= $row['fecha_inicio'] ?>" required
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                            </td>
                            <td class="py-3 px-4">
                                <input type="date" name="fecha_fin" value="<?= $row['fecha_fin'] ?>" required
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" value="<?= htmlspecialchars($row['cliente']) ?>" readonly
                                    class="w-full px-2 py-1 border border-gray-light rounded text-gray-dark bg-gray-light/50">
                                <input type="hidden" name="idCli" value="<?= htmlspecialchars($row['idCli']) ?>">
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" value="<?= htmlspecialchars($row['habitacion']) ?>" readonly
                                    class="w-full px-2 py-1 border border-gray-light rounded text-gray-dark bg-gray-light/50">
                                <input type="hidden" name="idHab" value="<?= htmlspecialchars($row['idHab']) ?>">
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-sm font-medium <?= $row['estado_habitacion'] == 'Ocupado' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                                    <i class="ph <?= $row['estado_habitacion'] == 'Ocupado' ? 'ph-x-circle' : 'ph-check-circle' ?>"></i>
                                    <span><?= htmlspecialchars($row['estado_habitacion']) ?></span>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" value="<?= htmlspecialchars($row['tipoPago']) ?>" readonly
                                    class="w-full px-2 py-1 border border-gray-light rounded text-gray-dark bg-gray-light/50">
                                <input type="hidden" name="idTipoPago" value="<?= htmlspecialchars($row['idTipoPago']) ?>">
                            </td>
                            <td class="py-3 px-4">
                                <input type="checkbox" name="pago" <?= $row['pago'] ? 'checked' : '' ?>
                                    class="h-5 w-5 text-primary focus:ring-primary border-gray-dark rounded">
                            </td>
                            <td class="py-3 px-4">
                                <input type="checkbox" name="es_checkin_directo" <?= $row['es_checkin_directo'] ? 'checked' : '' ?>
                                    class="h-5 w-5 text-primary focus:ring-primary border-gray-dark rounded">
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    <button type="submit" name="actualizar" 
                                        class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 group"
                                        title="Actualizar">
                                        <i class="ph ph-pencil text-lg group-hover:animate-bounce-subtle"></i>
                                    </button>
                                    <a href="detalleReserva_crud.php?eliminar=<?= htmlspecialchars($row['id']) ?>" 
                                        onclick="return confirm('¿Estás seguro de eliminar esta reserva?')"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 group"
                                        title="Eliminar">
                                        <i class="ph ph-trash text-lg group-hover:animate-bounce-subtle"></i>
                                    </a>
                                </div>
                            </td>
                        </form>
                        <?php else: ?>
                        <td class="py-3 px-4 text-gray-dark font-mono text-sm"><?= htmlspecialchars($row['id']) ?></td>
                        <td class="py-3 px-4 text-gray-dark"><?= date('d/m/Y', strtotime($row['fecha_inicio'])) ?></td>
                        <td class="py-3 px-4 text-gray-dark"><?= date('d/m/Y', strtotime($row['fecha_fin'])) ?></td>
                        <td class="py-3 px-4 text-gray-dark"><?= htmlspecialchars($row['cliente']) ?></td>
                        <td class="py-3 px-4 text-gray-dark"><?= htmlspecialchars($row['habitacion']) ?></td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-sm font-medium <?= $row['estado_habitacion'] == 'Ocupado' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                                <i class="ph <?= $row['estado_habitacion'] == 'Ocupado' ? 'ph-x-circle' : 'ph-check-circle' ?>"></i>
                                <span><?= htmlspecialchars($row['estado_habitacion']) ?></span>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-dark"><?= htmlspecialchars($row['tipoPago']) ?></td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-sm font-medium <?= $row['pago'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <i class="ph <?= $row['pago'] ? 'ph-check-circle' : 'ph-x-circle' ?>"></i>
                                <span><?= $row['pago'] ? 'Sí' : 'No' ?></span>
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center space-x-1 px-3 py-1 rounded-full text-sm font-medium <?= $row['es_checkin_directo'] ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' ?>">
                                <i class="ph <?= $row['es_checkin_directo'] ? 'ph-door' : 'ph-calendar' ?>"></i>
                                <span><?= $row['es_checkin_directo'] ? 'Sí' : 'No' ?></span>
                            </span>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    // Auto-actualizar cada 30 segundos
    setTimeout(function() {
        location.reload();
    }, 30000);
    
    // Validar fecha fin >= fecha inicio
    document.getElementById('fecha_fin').addEventListener('change', function() {
        var fechaInicio = document.getElementById('fecha_inicio').value;
        var fechaFin = this.value;
        if (fechaFin < fechaInicio) {
            alert('La fecha de fin debe ser mayor o igual a la fecha de inicio');
            this.value = fechaInicio;
        }
    });
    
    // Si es check-in directo, establecer fecha inicio como hoy
    document.getElementById('checkin_directo').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('fecha_inicio').value = '<?= date('Y-m-d') ?>';
        }
    });
    </script>
</div>

</body>
</html>
