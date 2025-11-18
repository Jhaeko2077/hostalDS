<?php
// Verificar sesión de empleado o administrador
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");
$result = $conn->query("SELECT * FROM tipoPago ORDER BY id ASC");

$page_title = "Gestión de Tipos de Pago";
include("../includes/head.php");
include("../includes/navegacion_tailwind.php");
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center animate-slide-down">
        <div>
            <h1 class="text-4xl font-bold text-gray-dark mb-2 flex items-center space-x-3">
                <i class="ph ph-credit-card text-primary animate-bounce-subtle"></i>
                <span>Gestión de Tipos de Pago</span>
            </h1>
            <p class="text-gray-dark/70">Administra los métodos de pago disponibles</p>
        </div>
    </div>

    <!-- Formulario de Registro -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 animate-scale-in">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-plus-circle text-primary"></i>
            <span>Registrar nuevo tipo de pago</span>
        </h2>
        <form action="tipoPago_crud.php" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-hash text-primary"></i> ID (opcional, se genera automáticamente)
                    </label>
                    <input type="text" name="id" placeholder="ID del tipo de pago"
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-dark mb-2">
                        <i class="ph ph-text-aa text-primary"></i> Descripción
                    </label>
                    <input type="text" name="descripcion" placeholder="Descripción" required
                        class="w-full px-4 py-3 border-2 border-gray-light rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all duration-300 text-gray-dark placeholder-gray-dark/50">
                </div>
            </div>
            <button type="submit" name="crear" 
                class="w-full md:w-auto flex items-center justify-center space-x-2 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl group">
                <i class="ph ph-plus-circle text-xl group-hover:animate-bounce-subtle"></i>
                <span>Agregar Tipo de Pago</span>
            </button>
        </form>
    </div>

    <!-- Lista de Tipos de Pago -->
    <div class="bg-white rounded-xl shadow-lg p-6 animate-slide-up">
        <h2 class="text-2xl font-bold text-gray-dark mb-6 flex items-center space-x-2">
            <i class="ph ph-list-bullets text-primary"></i>
            <span>Lista de Tipos de Pago</span>
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-light">
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">ID</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Descripción</th>
                        <th class="text-left py-3 px-4 text-gray-dark font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="border-b border-gray-light hover:bg-gray-light/50 transition-colors duration-200">
                        <form action="tipoPago_crud.php" method="POST" class="contents">
                            <td class="py-3 px-4">
                                <input type="text" name="id" value="<?= htmlspecialchars($row['id']) ?>" readonly
                                    class="w-20 px-2 py-1 border border-gray-light rounded text-gray-dark bg-gray-light/50 font-mono text-sm">
                            </td>
                            <td class="py-3 px-4">
                                <input type="text" name="descripcion" value="<?= htmlspecialchars($row['descripcion']) ?>"
                                    class="w-full px-2 py-1 border border-gray-light rounded focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none text-gray-dark">
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-2">
                                    <button type="submit" name="actualizar" 
                                        class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-all duration-300 transform hover:scale-110 group"
                                        title="Actualizar">
                                        <i class="ph ph-pencil text-lg group-hover:animate-bounce-subtle"></i>
                                    </button>
                                    <a href="tipoPago_crud.php?eliminar=<?= htmlspecialchars($row['id']) ?>" 
                                        onclick="return confirm('¿Estás seguro de eliminar este tipo de pago?')"
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
