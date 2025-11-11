<?php
include("../conexion.php");
$result = $conn->query("SELECT * FROM tipoPago ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Tipos de Pago</title>
  <link rel="stylesheet" href="../estilos.css">
</head>
<body>
  <div class="container">
    <h1>Gestión de Tipos de Pago</h1>

    <form action="tipoPago_crud.php" method="POST" class="formulario">
      <h2>Registrar nuevo tipo de pago</h2>
      <div class="input-group">
        <input type="text" name="id" placeholder="ID del tipo de pago" required>
        <input type="text" name="descripcion" placeholder="Descripción" required>
      </div>
      <button type="submit" name="crear" class="btn">Agregar Tipo de Pago</button>
    </form>

    <h2>Lista de Tipos de Pago</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Descripción</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <form action="tipoPago_crud.php" method="POST">
            <td><input type="text" name="id" value="<?= $row['id'] ?>" readonly></td>
            <td><input type="text" name="descripcion" value="<?= $row['descripcion'] ?>"></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar">✏️</button>
              <a href="tipoPago_crud.php?eliminar=<?= $row['id'] ?>" class="btn eliminar">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>