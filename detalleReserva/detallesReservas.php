<?php
include("../conexion.php");

// Traer datos relacionados
$reservas = $conn->query("
    SELECT dr.*, c.nombres AS cliente, h.tipo AS habitacion, tp.descripcion AS tipoPago
    FROM detalleReserva dr
    JOIN Cliente c ON dr.idCli = c.id
    JOIN Habitaciones h ON dr.idHab = h.codigo
    JOIN tipoPago tp ON dr.idTipoPago = tp.id
    ORDER BY dr.id ASC
");

// Listas para selects
$clientes = $conn->query("SELECT id, nombres FROM Cliente");
$habitaciones = $conn->query("SELECT codigo, tipo FROM Habitaciones");
$tiposPago = $conn->query("SELECT id, descripcion FROM tipoPago");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Reservas</title>
  <link rel="stylesheet" href="../estilos.css">
</head>
<body>
  <div class="container">
    <h1>Gestión de Reservas</h1>

    <form action="detalleReserva_crud.php" method="POST" class="formulario">
      <h2>Registrar nueva reserva</h2>

      <div class="input-group">
        <select name="idCli" required>
          <option value="">Seleccione Cliente</option>
          <?php while ($c = $clientes->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>"><?= $c['nombres'] ?></option>
          <?php endwhile; ?>
        </select>
        <select name="idHab" required>
          <option value="">Seleccione Habitación</option>
          <?php while ($h = $habitaciones->fetch_assoc()): ?>
            <option value="<?= $h['codigo'] ?>"><?= $h['tipo'] ?></option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="input-group">
        <select name="idTipoPago" required>
          <option value="">Seleccione Tipo de Pago</option>
          <?php while ($tp = $tiposPago->fetch_assoc()): ?>
            <option value="<?= $tp['id'] ?>"><?= $tp['descripcion'] ?></option>
          <?php endwhile; ?>
        </select>
        <label><input type="checkbox" name="pago"> Pagado</label>
      </div>
      <button type="submit" name="crear" class="btn">Agregar Reserva</button>
    </form>

    <h2>Lista de Reservas</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Habitación</th>
          <th>Tipo Pago</th>
          <th>Pagado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $reservas->fetch_assoc()): ?>
        <tr>
          <form action="detalleReserva_crud.php" method="POST">
            <td><input type="text" name="id" value="<?= $row['id'] ?>" readonly></td>
            <td><input type="date" name="fecha" value="<?= $row['fecha'] ?>"></td>
            <td>
              <input type="text" value="<?= $row['cliente'] ?>" readonly>
              <input type="hidden" name="idCli" value="<?= $row['idCli'] ?>">
            </td>
            <td>
              <input type="text" value="<?= $row['habitacion'] ?>" readonly>
              <input type="hidden" name="idHab" value="<?= $row['idHab'] ?>">
            </td>
            <td>
              <input type="text" value="<?= $row['tipoPago'] ?>" readonly>
              <input type="hidden" name="idTipoPago" value="<?= $row['idTipoPago'] ?>">
            </td>
            <td><input type="checkbox" name="pago" <?= $row['pago'] ? 'checked' : '' ?>></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar">✏️</button>
              <a href="detalleReserva_crud.php?eliminar=<?= $row['id'] ?>" class="btn eliminar">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>