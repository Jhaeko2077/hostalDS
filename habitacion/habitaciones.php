<?php
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
  <div class="container">
    <h1>Gestión de Habitaciones</h1>

    <form action="habitacion_crud.php" method="POST" class="formulario">
      <h2>Registrar nueva habitación</h2>
      <div class="input-group">
        <input type="text" name="codigo" placeholder="Código" required>
        <input type="text" name="tipo" placeholder="Tipo de habitación" required>
      </div>
      <div class="input-group">
        <input type="text" name="estado" placeholder="Estado (Disponible, Ocupada...)" required>
        <input type="text" name="descripcion" placeholder="Descripción">
      </div>
      <button type="submit" name="crear" class="btn">Agregar Habitación</button>
    </form>

    <h2>Lista de Habitaciones</h2>
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
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <form action="habitacion_crud.php" method="POST">
            <td><input type="text" name="codigo" value="<?= $row['codigo'] ?>" readonly></td>
            <td><input type="text" name="tipo" value="<?= $row['tipo'] ?>"></td>
            <td><input type="text" name="estado" value="<?= $row['estado'] ?>"></td>
            <td><input type="text" name="descripcion" value="<?= $row['descripcion'] ?>"></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar">✏️</button>
              <a href="habitacion_crud.php?eliminar=<?= $row['codigo'] ?>" class="btn eliminar">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>