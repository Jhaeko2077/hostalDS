<?php
session_start();
if(!isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");
$result = $conn->query("SELECT * FROM Empleado ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Empleados</title>
  <link rel="stylesheet" href="../estilos.css">
</head>
<body>
  <?php include("../includes/navegacion.php"); ?>
  <div class="container">
    <h1>Gestión de Empleados</h1>

    <form action="empleado_crud.php" method="POST" class="formulario">
      <h2>Registrar nuevo empleado</h2>
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
        <input type="text" name="usuario" placeholder="Usuario" required>
      </div>
      <div class="input-group">
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="text" name="tipo" placeholder="Tipo (ej. Recepcionista, Mucama, Admin)" required>
      </div>
      <button type="submit" name="crear" class="btn">Agregar Empleado</button>
    </form>

    <h2>Lista de Empleados</h2>
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
          <th>Contraseña</th>
          <th>Tipo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <form action="empleado_crud.php" method="POST">
            <td><input type="text" name="id" value="<?= htmlspecialchars($row['id']) ?>" readonly></td>
            <td><input type="text" name="nombres" value="<?= htmlspecialchars($row['nombres']) ?>"></td>
            <td><input type="text" name="apellidos" value="<?= htmlspecialchars($row['apellidos']) ?>"></td>
            <td><input type="text" name="dni" value="<?= htmlspecialchars($row['dni']) ?>"></td>
            <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
            <td><input type="text" name="telefono" value="<?= htmlspecialchars($row['telefono'] ?? '') ?>"></td>
            <td><input type="text" name="usuario" value="<?= htmlspecialchars($row['usuario']) ?>"></td>
            <td><input type="password" name="contrasena" placeholder="Dejar vacío para no cambiar"></td>
            <td><input type="text" name="tipo" value="<?= htmlspecialchars($row['tipo']) ?>"></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar">✏️</button>
              <a href="empleado_crud.php?eliminar=<?= htmlspecialchars($row['id']) ?>" class="btn eliminar" onclick="return confirm('¿Estás seguro de eliminar este empleado?')">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>