<?php
// Verificar sesión (clientes pueden ver, empleados y administradores pueden gestionar)
session_start();
if(!isset($_SESSION['usuario_cliente']) && !isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");
$result = $conn->query("SELECT * FROM Servicios ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Servicios</title>
  <link rel="stylesheet" href="../estilos.css">
</head>
<body>
  <?php include("../includes/navegacion.php"); ?>
  <div class="container">
    <h1>Gestión de Servicios</h1>

    <?php if(isset($_SESSION['usuario_empleado']) || isset($_SESSION['usuario_admin'])): ?>
    <form action="servicio_crud.php" method="POST" class="formulario">
      <h2>Registrar nuevo servicio</h2>
      <div class="input-group">
        <input type="text" name="id" placeholder="ID del servicio (opcional, se genera automáticamente)">
        <input type="text" name="descripcion" placeholder="Descripción" required>
      </div>
      <div class="input-group">
        <input type="number" step="0.01" min="0.01" name="costo" placeholder="Costo (S/.)" required>
      </div>
      <button type="submit" name="crear" class="btn">Agregar Servicio</button>
    </form>
    <?php endif; ?>

    <h2>Lista de Servicios</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Descripción</th>
          <th>Costo (S/.)</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <form action="servicio_crud.php" method="POST">
            <td><input type="text" name="id" value="<?= htmlspecialchars($row['id']) ?>" readonly></td>
            <td><input type="text" name="descripcion" value="<?= htmlspecialchars($row['descripcion']) ?>"></td>
            <td><input type="number" step="0.01" name="costo" value="<?= htmlspecialchars($row['costo']) ?>"></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar">✏️</button>
              <a href="servicio_crud.php?eliminar=<?= htmlspecialchars($row['id']) ?>" class="btn eliminar" onclick="return confirm('¿Estás seguro de eliminar este servicio?')">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>