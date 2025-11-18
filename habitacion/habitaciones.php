<?php
// Verificar sesión de empleado o administrador
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");
$result = $conn->query("SELECT * FROM Habitaciones ORDER BY codigo ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Habitaciones</title>
  <link rel="stylesheet" href="../estilos.css">
</head>
<body>
  <?php include("../includes/navegacion.php"); ?>
  <div class="container">
    <h1>Gestión de Habitaciones</h1>

    <form action="habitacion_crud.php" method="POST" class="formulario">
      <h2>Registrar nueva habitación</h2>
      <div class="input-group">
        <input type="text" name="codigo" placeholder="Código" required>
        <input type="text" name="tipo" placeholder="Tipo de habitación" required>
      </div>
      <div class="input-group">
        <select name="estado" required>
          <option value="Disponible">Disponible</option>
          <option value="Ocupado">Ocupado</option>
          <option value="Mantenimiento">Mantenimiento</option>
          <option value="Reservado">Reservado</option>
        </select>
        <input type="text" name="descripcion" placeholder="Descripción">
      </div>
      <button type="submit" name="crear" class="btn">Agregar Habitación</button>
    </form>

    <h2>Lista de Habitaciones</h2>
    <div style="margin-bottom: 15px;">
      <button onclick="location.reload()" class="btn" style="background: #22c55e;">🔄 Actualizar</button>
    </div>
    <table>
      <thead>
        <tr>
          <th>Código</th>
          <th>Tipo</th>
          <th>Estado</th>
          <th>Descripción</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()): 
        ?>
        <tr>
          <form action="habitacion_crud.php" method="POST">
            <td><input type="text" name="codigo" value="<?= htmlspecialchars($row['codigo']) ?>" readonly></td>
            <td><input type="text" name="tipo" value="<?= htmlspecialchars($row['tipo']) ?>"></td>
            <td>
              <select name="estado" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); color: white;">
                <option value="Disponible" <?= $row['estado'] == 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="Ocupado" <?= $row['estado'] == 'Ocupado' ? 'selected' : '' ?>>Ocupado</option>
                <option value="Mantenimiento" <?= $row['estado'] == 'Mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
                <option value="Reservado" <?= $row['estado'] == 'Reservado' ? 'selected' : '' ?>>Reservado</option>
              </select>
            </td>
            <td><input type="text" name="descripcion" value="<?= htmlspecialchars($row['descripcion'] ?? '') ?>"></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar" title="Actualizar">✏️</button>
              <a href="habitacion_crud.php?eliminar=<?= htmlspecialchars($row['codigo']) ?>" class="btn eliminar" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta habitación?')">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    
    <script>
    // Auto-actualizar cada 30 segundos
    setTimeout(function() {
        location.reload();
    }, 30000);
    </script>
  </div>
</body>
</html>