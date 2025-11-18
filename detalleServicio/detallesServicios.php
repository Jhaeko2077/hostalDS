<?php
// Verificar sesión de empleado o administrador
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");

// Obtenemos los datos relacionados
$result = $conn->query("
    SELECT d.id, h.codigo AS habitacion_codigo, h.tipo AS habitacion_tipo, CONCAT(h.codigo, ' - ', h.tipo) AS habitacion, e.nombres AS empleado, s.descripcion AS servicio, d.pago,
           d.idHab, d.idEmp, d.idServicio
    FROM detalleServicioHob d
    JOIN Habitaciones h ON d.idHab = h.codigo
    JOIN Empleado e ON d.idEmp = e.id
    JOIN Servicios s ON d.idServicio = s.id
    ORDER BY d.id ASC
");

// Para llenar los selects
$habitaciones = $conn->query("SELECT codigo, tipo FROM Habitaciones ORDER BY codigo ASC");
$empleados = $conn->query("SELECT id, nombres FROM Empleado");
$servicios = $conn->query("SELECT id, descripcion, costo FROM Servicios ORDER BY descripcion ASC");

$page_title = "Gestión Detalle Servicio Habitación";
include("../includes/head.php");
include("../includes/navegacion_tailwind.php");
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center animate-slide-down">
        <div>
            <h1 class="text-4xl font-bold text-gray-dark mb-2 flex items-center space-x-3">
                <i class="ph ph-list-dashes text-primary animate-bounce-subtle"></i>
                <span>Gestión de Detalle de Servicios por Habitación</span>
            </h1>
            <p class="text-gray-dark/70">Administra los servicios asignados a cada habitación</p>
        </div>
    </div>

    <!-- Formulario de Registro -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 animate-scale-in">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-plus-circle text-primary"></i>
            <span>Registrar nuevo detalle</span>
        </h2>
        <form action="detalleServicio_crud.php" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-hash text-primary"></i> ID Detalle (opcional, se genera automáticamente)
                    </label>
                    <input type="text" name="id" placeholder="ID Detalle"
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-house text-primary"></i> Habitación
                    </label>
                    <select name="idHab" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                        <option value="">Seleccionar Habitación</option>
                        <?php 
                        $habitaciones->data_seek(0);
                        while ($hab = $habitaciones->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($hab['codigo']) ?>"><?= htmlspecialchars($hab['codigo']) ?> - <?= htmlspecialchars($hab['tipo']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-user text-primary"></i> Empleado
                    </label>
                    <select name="idEmp" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                        <option value="">Seleccionar Empleado</option>
                        <?php 
                        $empleados->data_seek(0);
                        while ($emp = $empleados->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($emp['id']) ?>"><?= htmlspecialchars($emp['nombres']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-wrench text-primary"></i> Servicio
                    </label>
                    <select name="idServicio" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                        <option value="">Seleccionar Servicio</option>
                        <?php 
                        $servicios->data_seek(0);
                        while ($serv = $servicios->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($serv['id']) ?>"><?= htmlspecialchars($serv['descripcion']) ?> - S/. <?= number_format($serv['costo'], 2) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="flex items-center">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="pago" 
                        class="h-5 w-5 text-primary focus:ring-primary border-gray-dark rounded">
                    <span class="text-gray-dark font-medium">
                        <i class="ph ph-check-circle text-green-500"></i> Pago Realizado
                    </span>
                </label>
            </div>
            <button type="submit" name="crear" 
                class="w-full md:w-auto flex items-center justify-center space-x-2 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
                <i class="ph ph-plus-circle text-xl group-hover:animate-bounce-subtle"></i>
                <span>Agregar Detalle</span>
            </button>
        </form>
    </div>

    <!-- Lista de Detalles -->
    <div class="bg-white rounded-xl shadow-lg p-6 animate-slide-up">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-list-bullets text-primary"></i>
            <span>Lista de Detalles</span>
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-light">
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">ID</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Habitación</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Empleado</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Servicio</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Pago</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors duration-200">
                        <form action="detalleServicio_crud.php" method="POST" class="contents">
                            <td class="py-3 px-4">
                                <input type="text" name="id" value="<?= htmlspecialchars($row['id']) ?>" readonly
                                    class="w-20 px-2 py-1 border border-gray-light rounded text-gray-dark bg-gray-light/50 font-mono text-sm">
                            </td>
                            <td class="py-3 px-4">
                                <select name="idHab"
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                                    <?php
                                    $habs = $conn->query("SELECT codigo, tipo FROM Habitaciones ORDER BY codigo ASC");
                                    while ($h = $habs->fetch_assoc()) {
                                        $sel = ($h['codigo'] == $row['idHab']) ? "selected" : "";
                                        $codigo = htmlspecialchars($h['codigo']);
                                        $tipo = htmlspecialchars($h['tipo']);
                                        echo "<option value='$codigo' $sel>$codigo - $tipo</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="py-3 px-4">
                                <select name="idEmp"
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                                    <?php
                                    $emps = $conn->query("SELECT id, nombres FROM Empleado");
                                    while ($e = $emps->fetch_assoc()) {
                                        $sel = ($e['id'] == $row['idEmp']) ? "selected" : "";
                                        $id_emp = htmlspecialchars($e['id']);
                                        $nombres_emp = htmlspecialchars($e['nombres']);
                                        echo "<option value='$id_emp' $sel>$nombres_emp</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="py-3 px-4">
                                <select name="idServicio"
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                                    <?php
                                    $sers = $conn->query("SELECT id, descripcion, costo FROM Servicios ORDER BY descripcion ASC");
                                    while ($s = $sers->fetch_assoc()) {
                                        $sel = ($s['id'] == $row['idServicio']) ? "selected" : "";
                                        $id_serv = htmlspecialchars($s['id']);
                                        $desc_serv = htmlspecialchars($s['descripcion']);
                                        $costo_serv = number_format($s['costo'], 2);
                                        echo "<option value='$id_serv' $sel>$desc_serv - S/. $costo_serv</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="py-3 px-4">
                                <input type="checkbox" name="pago" <?= $row['pago'] ? "checked" : "" ?>
                                    class="h-5 w-5 text-primary focus:ring-primary border-gray-dark rounded">
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    <button type="submit" name="actualizar" 
                                        class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 group"
                                        title="Actualizar">
                                        <i class="ph ph-pencil text-lg group-hover:animate-bounce-subtle"></i>
                                    </button>
                                    <a href="detalleServicio_crud.php?eliminar=<?= htmlspecialchars($row['id']) ?>" 
                                        onclick="return confirm('¿Estás seguro de eliminar este detalle de servicio?')"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 group"
                                        title="Eliminar">
                                        <i class="ph ph-trash text-lg group-hover:animate-bounce-subtle"></i>
                                    </a>
                                </div>
                            </td>
                        </form>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
