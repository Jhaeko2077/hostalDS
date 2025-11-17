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
    SELECT d.id, h.tipo AS habitacion, e.nombres AS empleado, s.descripcion AS servicio, d.pago,
           d.idHab, d.idEmp, d.idServicio
    FROM detalleServicioHob d
    JOIN Habitaciones h ON d.idHab = h.codigo
    JOIN Empleado e ON d.idEmp = e.id
    JOIN Servicios s ON d.idServicio = s.id
    ORDER BY d.id ASC
");

// Para llenar los selects
$habitaciones = $conn->query("SELECT codigo, tipo FROM Habitaciones");
$empleados = $conn->query("SELECT id, nombres FROM Empleado");
$servicios = $conn->query("SELECT id, descripcion FROM Servicios");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión Detalle Servicio Habitación</title>
  <link rel="stylesheet" href="../estilos.css">
</head>
<body>
  <?php include("../includes/navegacion.php"); ?>
  <div class="container">
    <h1>Gestión de Detalle de Servicios por Habitación</h1>

    <form action="detalleServicio_crud.php" method="POST" class="formulario">
      <h2>Registrar nuevo detalle</h2>
      <div class="input-group">
        <input type="text" name="id" placeholder="ID Detalle" required>

        <select name="idHab" required>
          <option value="">Seleccionar Habitación</option>
          <?php while ($hab = $habitaciones->fetch_assoc()): ?>
            <option value="<?= $hab['codigo'] ?>"><?= $hab['tipo'] ?></option>
          <?php endwhile; ?>
        </select>

        <select name="idEmp" required>
          <option value="">Seleccionar Empleado</option>
          <?php while ($emp = $empleados->fetch_assoc()): ?>
            <option value="<?= $emp['id'] ?>"><?= $emp['nombres'] ?></option>
          <?php endwhile; ?>
        </select>

        <select name="idServicio" required>
          <option value="">Seleccionar Servicio</option>
          <?php while ($serv = $servicios->fetch_assoc()): ?>
            <option value="<?= $serv['id'] ?>"><?= $serv['descripcion'] ?></option>
          <?php endwhile; ?>
        </select>

        <label><input type="checkbox" name="pago"> Pago Realizado</label>
      </div>

      <button type="submit" name="crear" class="btn">Agregar Detalle</button>
    </form>

    <h2>Lista de Detalles</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Habitación</th>
          <th>Empleado</th>
          <th>Servicio</th>
          <th>Pago</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <form action="detalleServicio_crud.php" method="POST">
            <td><input type="text" name="id" value="<?= $row['id'] ?>" readonly></td>

            <td>
              <select name="idHab">
                <?php
                $habs = $conn->query("SELECT codigo, tipo FROM Habitaciones");
                while ($h = $habs->fetch_assoc()) {
                    $sel = ($h['codigo'] == $row['idHab']) ? "selected" : "";
                    echo "<option value='{$h['codigo']}' $sel>{$h['tipo']}</option>";
                }
                ?>
              </select>
            </td>

            <td>
              <select name="idEmp">
                <?php
                $emps = $conn->query("SELECT id, nombres FROM Empleado");
                while ($e = $emps->fetch_assoc()) {
                    $sel = ($e['id'] == $row['idEmp']) ? "selected" : "";
                    echo "<option value='{$e['id']}' $sel>{$e['nombres']}</option>";
                }
                ?>
              </select>
            </td>

            <td>
              <select name="idServicio">
                <?php
                $sers = $conn->query("SELECT id, descripcion FROM Servicios");
                while ($s = $sers->fetch_assoc()) {
                    $sel = ($s['id'] == $row['idServicio']) ? "selected" : "";
                    echo "<option value='{$s['id']}' $sel>{$s['descripcion']}</option>";
                }
                ?>
              </select>
            </td>
                
            <td><input type="checkbox" name="pago" <?= $row['pago'] ? "checked" : "" ?>></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar">✏️</button>
              <a href="detalleServicio_crud.php?eliminar=<?= $row['id'] ?>" class="btn eliminar">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>