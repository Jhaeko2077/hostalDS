<?php
// Verificar sesión (clientes, empleados y administradores pueden acceder)
session_start();
if(!isset($_SESSION['usuario_cliente']) && !isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");

// Traer datos relacionados
$reservas = $conn->query("
    SELECT dr.*, c.nombres AS cliente, h.tipo AS habitacion, h.estado AS estado_habitacion, tp.descripcion AS tipoPago
    FROM detalleReserva dr
    JOIN Cliente c ON dr.idCli = c.id
    JOIN Habitaciones h ON dr.idHab = h.codigo
    JOIN tipoPago tp ON dr.idTipoPago = tp.id
    ORDER BY dr.fecha_inicio DESC, dr.id DESC
");

// Listas para selects - solo habitaciones disponibles o la que se está editando
$habitaciones_disponibles = $conn->query("
    SELECT codigo, tipo, estado 
    FROM Habitaciones 
    WHERE estado = 'Disponible' 
    ORDER BY codigo ASC
");
$habitaciones_todas = $conn->query("SELECT codigo, tipo, estado FROM Habitaciones ORDER BY codigo ASC");
$clientes = $conn->query("SELECT id, CONCAT(nombres, ' ', apellidos) AS nombre_completo FROM Cliente ORDER BY nombres ASC");
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
  <?php include("../includes/navegacion.php"); ?>
  <div class="container">
    <h1>Gestión de Reservas</h1>

    <?php if(isset($_SESSION['usuario_empleado']) || isset($_SESSION['usuario_admin'])): ?>
    <form action="detalleReserva_crud.php" method="POST" class="formulario">
      <h2>Registrar nueva reserva</h2>

      <div class="input-group">
        <select name="idCli" required>
          <option value="">Seleccione Cliente</option>
          <?php 
          $clientes->data_seek(0);
          while ($c = $clientes->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['nombre_completo']) ?></option>
          <?php endwhile; ?>
        </select>
        <select name="idHab" id="selectHabitacion" required>
          <option value="">Seleccione Habitación</option>
          <?php 
          $habitaciones_todas->data_seek(0);
          while ($h = $habitaciones_todas->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($h['codigo']) ?>" data-estado="<?= htmlspecialchars($h['estado']) ?>">
              <?= htmlspecialchars($h['tipo']) ?> - <?= htmlspecialchars($h['codigo']) ?> (<?= htmlspecialchars($h['estado']) ?>)
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="input-group">
        <input type="date" name="fecha_inicio" id="fecha_inicio" required value="<?= date('Y-m-d') ?>">
        <input type="date" name="fecha_fin" id="fecha_fin" required value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
      </div>
      <div class="input-group">
        <select name="idTipoPago" required>
          <option value="">Seleccione Tipo de Pago</option>
          <?php 
          $tiposPago->data_seek(0);
          while ($tp = $tiposPago->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($tp['id']) ?>"><?= htmlspecialchars($tp['descripcion']) ?></option>
          <?php endwhile; ?>
        </select>
        <label><input type="checkbox" name="pago"> Pagado</label>
      </div>
      <div class="input-group">
        <label><input type="checkbox" name="es_checkin_directo" id="checkin_directo"> Check-in directo (usar habitación hoy sin reserva previa)</label>
      </div>
      <button type="submit" name="crear" class="btn">Agregar Reserva</button>
    </form>
    <?php endif; ?>

    <h2>Lista de Reservas</h2>
    <div style="margin-bottom: 15px;">
      <button onclick="location.reload()" class="btn" style="background: #22c55e;">🔄 Actualizar</button>
    </div>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Fecha Inicio</th>
          <th>Fecha Fin</th>
          <th>Cliente</th>
          <th>Habitación</th>
          <th>Estado Hab.</th>
          <th>Tipo Pago</th>
          <th>Pagado</th>
          <th>Check-in Directo</th>
          <?php if(isset($_SESSION['usuario_empleado']) || isset($_SESSION['usuario_admin'])): ?>
          <th>Acciones</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php 
        $reservas->data_seek(0);
        while ($row = $reservas->fetch_assoc()): 
        ?>
        <tr>
          <?php if(isset($_SESSION['usuario_empleado']) || isset($_SESSION['usuario_admin'])): ?>
          <form action="detalleReserva_crud.php" method="POST">
            <td><input type="text" name="id" value="<?= htmlspecialchars($row['id']) ?>" readonly style="width: 80px;"></td>
            <td><input type="date" name="fecha_inicio" value="<?= $row['fecha_inicio'] ?>" required></td>
            <td><input type="date" name="fecha_fin" value="<?= $row['fecha_fin'] ?>" required></td>
            <td>
              <input type="text" value="<?= htmlspecialchars($row['cliente']) ?>" readonly style="width: 120px;">
              <input type="hidden" name="idCli" value="<?= htmlspecialchars($row['idCli']) ?>">
            </td>
            <td>
              <input type="text" value="<?= htmlspecialchars($row['habitacion']) ?>" readonly style="width: 100px;">
              <input type="hidden" name="idHab" value="<?= htmlspecialchars($row['idHab']) ?>">
            </td>
            <td>
              <span style="padding: 5px 10px; border-radius: 5px; background: <?= $row['estado_habitacion'] == 'Ocupado' ? '#ef4444' : '#22c55e' ?>; color: white; font-size: 0.85em;">
                <?= htmlspecialchars($row['estado_habitacion']) ?>
              </span>
            </td>
            <td>
              <input type="text" value="<?= htmlspecialchars($row['tipoPago']) ?>" readonly style="width: 100px;">
              <input type="hidden" name="idTipoPago" value="<?= htmlspecialchars($row['idTipoPago']) ?>">
            </td>
            <td><input type="checkbox" name="pago" <?= $row['pago'] ? 'checked' : '' ?>></td>
            <td>
              <input type="checkbox" name="es_checkin_directo" <?= $row['es_checkin_directo'] ? 'checked' : '' ?>>
            </td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar" title="Actualizar">✏️</button>
              <a href="detalleReserva_crud.php?eliminar=<?= htmlspecialchars($row['id']) ?>" class="btn eliminar" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta reserva?')">🗑️</a>
            </td>
          </form>
          <?php else: ?>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= $row['fecha_inicio'] ?></td>
          <td><?= $row['fecha_fin'] ?></td>
          <td><?= htmlspecialchars($row['cliente']) ?></td>
          <td><?= htmlspecialchars($row['habitacion']) ?></td>
          <td>
            <span style="padding: 5px 10px; border-radius: 5px; background: <?= $row['estado_habitacion'] == 'Ocupado' ? '#ef4444' : '#22c55e' ?>; color: white; font-size: 0.85em;">
              <?= htmlspecialchars($row['estado_habitacion']) ?>
            </span>
          </td>
          <td><?= htmlspecialchars($row['tipoPago']) ?></td>
          <td><?= $row['pago'] ? 'Sí' : 'No' ?></td>
          <td><?= $row['es_checkin_directo'] ? 'Sí' : 'No' ?></td>
          <?php endif; ?>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    
    <script>
    // Auto-actualizar cada 30 segundos
    setTimeout(function() {
        location.reload();
    }, 30000);
    
    // Validar fecha fin >= fecha inicio
    document.getElementById('fecha_fin').addEventListener('change', function() {
        var fechaInicio = document.getElementById('fecha_inicio').value;
        var fechaFin = this.value;
        if (fechaFin < fechaInicio) {
            alert('La fecha de fin debe ser mayor o igual a la fecha de inicio');
            this.value = fechaInicio;
        }
    });
    
    // Si es check-in directo, establecer fecha inicio como hoy
    document.getElementById('checkin_directo').addEventListener('change', function() {
        if (this.checked) {
            document.getElementById('fecha_inicio').value = '<?= date('Y-m-d') ?>';
        }
    });
    </script>
  </div>
</body>
</html>