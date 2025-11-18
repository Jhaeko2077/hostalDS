<?php
// Verificar sesión de empleado o administrador
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");
$result = $conn->query("SELECT * FROM Habitaciones ORDER BY codigo ASC");

$page_title = "Gestión de Habitaciones";
include("../includes/head.php");
include("../includes/navegacion_tailwind.php");
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center animate-slide-down">
        <div>
            <h1 class="text-4xl font-bold text-gray-dark mb-2 flex items-center space-x-3">
                <i class="ph ph-house text-primary animate-bounce-subtle"></i>
                <span>Gestión de Habitaciones</span>
            </h1>
            <p class="text-gray-dark/70">Administra las habitaciones del hostal</p>
        </div>
        <button onclick="location.reload()" 
            class="flex items-center space-x-2 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
            <i class="ph ph-arrow-clockwise text-xl group-hover:animate-spin"></i>
            <span class="font-medium">Actualizar</span>
        </button>
    </div>

    <!-- Formulario de Registro -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 animate-scale-in">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-plus-circle text-primary"></i>
            <span>Registrar nueva habitación</span>
        </h2>
        <form action="habitacion_crud.php" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-hash text-primary"></i> Código
                    </label>
                    <input type="text" name="codigo" placeholder="Código" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-buildings text-primary"></i> Tipo de habitación
                    </label>
                    <input type="text" name="tipo" placeholder="Tipo de habitación" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-circle-dashed text-primary"></i> Estado
                    </label>
                    <select name="estado" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark">
                        <option value="Disponible">Disponible</option>
                        <option value="Ocupado">Ocupado</option>
                        <option value="Mantenimiento">Mantenimiento</option>
                        <option value="Reservado">Reservado</option>
                        <option value="Limpieza">Limpieza</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-text-aa text-primary"></i> Descripción
                    </label>
                    <input type="text" name="descripcion" placeholder="Descripción"
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
            </div>
            <button type="submit" name="crear" 
                class="w-full md:w-auto flex items-center justify-center space-x-2 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
                <i class="ph ph-plus-circle text-xl group-hover:animate-bounce-subtle"></i>
                <span>Agregar Habitación</span>
            </button>
        </form>
    </div>

    <!-- Lista de Habitaciones -->
    <div class="bg-white rounded-xl shadow-lg p-6 animate-slide-up">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-list-bullets text-primary"></i>
            <span>Lista de Habitaciones</span>
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-light">
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Código</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Tipo</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Estado</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Descripción</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()): 
                    ?>
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors duration-200">
                        <form action="habitacion_crud.php" method="POST" class="contents">
                            <td class="py-3 px-4">
                                <input type="text" name="codigo" value="<?= htmlspecialchars($row['codigo']) ?>" readonly
                                    class="w-full px-2 py-1 border border-gray-light rounded text-gray-dark bg-gray-light/50 font-mono text-sm">
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" name="tipo" value="<?= htmlspecialchars($row['tipo']) ?>"
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                            </td>
                            <td class="py-3 px-4">
                                <select name="estado"
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                                    <option value="Disponible" <?= $row['estado'] == 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                                    <option value="Ocupado" <?= $row['estado'] == 'Ocupado' ? 'selected' : '' ?>>Ocupado</option>
                                    <option value="Mantenimiento" <?= $row['estado'] == 'Mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
                                    <option value="Reservado" <?= $row['estado'] == 'Reservado' ? 'selected' : '' ?>>Reservado</option>
                                    <option value="Limpieza" <?= $row['estado'] == 'Limpieza' ? 'selected' : '' ?>>Limpieza</option>
                                </select>
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" name="descripcion" value="<?= htmlspecialchars($row['descripcion'] ?? '') ?>"
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    <button type="submit" name="actualizar" 
                                        class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 group"
                                        title="Actualizar">
                                        <i class="ph ph-pencil text-lg group-hover:animate-bounce-subtle"></i>
                                    </button>
                                    <a href="habitacion_crud.php?eliminar=<?= htmlspecialchars($row['codigo']) ?>" 
                                        onclick="return confirm('¿Estás seguro de eliminar esta habitación?')"
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
    
    <script>
    // Auto-actualizar cada 30 segundos
    setTimeout(function() {
        location.reload();
    }, 30000);
    </script>
</div>

</body>
</html>
