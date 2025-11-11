<?php
include("../conexion.php");
$result = $conn->query("SELECT * FROM Administrador ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Administradores</title>
  <link rel="stylesheet" href="../estilos.css">
</head>
<body>
  <div class="container">
    <h1>Gestión de Administradores</h1>

    <form action="administrador_crud.php" method="POST" class="formulario">
      <h2>Registrar nuevo administrador</h2>
      <div class="input-group">
        <input type="text" name="nombres" placeholder="Nombres" required>
        <input type="text" name="apellidos" placeholder="Apellidos" required>
      </div>
      <div class="input-group">
        <input type="text" name="dni" placeholder="DNI" required>
        <input type="email" name="email" placeholder="Email" required>
      </div>
      <div class="input-group">
        <input type="text" name="telefono" placeholder="Teléfono">
        <input type="text" name="usuario" placeholder="Usuario (debe existir en Empleado)" required>
      </div>
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <button type="submit" name="crear" class="btn">Agregar Administrador</button>
    </form>

    <h2>Lista de Administradores</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombres</th>
          <th>Apellidos</th>
          <th>DNI</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Usuario</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <form action="administrador_crud.php" method="POST">
            <td><input type="text" name="id" value="<?= $row['id'] ?>" readonly></td>
            <td><input type="text" name="nombres" value="<?= $row['nombres'] ?>"></td>
            <td><input type="text" name="apellidos" value="<?= $row['apellidos'] ?>"></td>
            <td><input type="text" name="dni" value="<?= $row['dni'] ?>"></td>
            <td><input type="email" name="email" value="<?= $row['email'] ?>"></td>
            <td><input type="text" name="telefono" value="<?= $row['telefono'] ?>"></td>
            <td><input type="text" name="usuario" value="<?= $row['usuario'] ?>"></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar">✏️</button>
              <a href="administrador_crud.php?eliminar=<?= $row['id'] ?>" class="btn eliminar">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>