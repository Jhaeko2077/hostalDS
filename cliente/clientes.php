<?php
// Verificar sesión de empleado o administrador (solo ellos pueden gestionar clientes)
session_start();
if(!isset($_SESSION['usuario_empleado']) && !isset($_SESSION['usuario_admin'])){
    header("Location: ../index.html");
    exit();
}

include("../conexion.php");
$result = $conn->query("SELECT * FROM Cliente ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap'">
  <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #1a1a1a, #2e2e2e);
    color: #fff;
    min-height: 100vh;
}

/* ======== CONTENEDOR PRINCIPAL ======== */
.container {
    width: 90%;
    max-width: 1000px;
    margin: 50px auto;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    animation: fadeIn 1s ease;
}

/* ======== TITULOS ======== */
h1, h2 {
    text-align: center;
    color: #f5c542; /* dorado elegante */
    margin-bottom: 30px;
}

/* ======== FORMULARIOS ======== */
.formulario {
    background: rgba(255,255,255,0.05);
    padding: 25px;
    border-radius: 16px;
    border: 1px solid rgba(245,197,66,0.3);
    margin-bottom: 30px;
    backdrop-filter: blur(10px);
}

.input-group {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 15px;
}

input, select {
    width: 100%;
    padding: 12px 15px;
    border-radius: 10px;
    border: none;
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 1em;
    transition: all 0.3s ease;
}

input::placeholder, select option {
    color: #ccc;
}

input:focus, select:focus {
    outline: none;
    border: 1px solid #f5c542;
    background: rgba(255,255,255,0.2);
    box-shadow: 0 0 10px #f5c54255;
}

/* ======== BOTONES ======== */
.btn {
    display: inline-block;
    background: #f5c542;
    color: #1a1a1a;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1em;
    text-align: center;
    transition: all 0.3s ease;
}

.btn:hover {
    background: #ffd966;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(245,197,66,0.3);
}

/* ======== TABLAS ======== */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    border-bottom: 1px solid rgba(255,255,255,0.2);
    padding: 12px;
    text-align: center;
    color: #fff;
}

.btn.actualizar {
    background: #22c55e;
}

.btn.eliminar {
    background: #ef4444;
}

.btn.actualizar:hover {
    background: #16a34a;
}

.btn.eliminar:hover {
    background: #dc2626;
}

/* ======== EFECTOS ======== */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ======== RESPONSIVE ======== */
@media (max-width: 768px) {
    .container {
        padding: 30px;
    }
}
  </style>
</head>
<body>
  <?php include("../includes/navegacion.php"); ?>
  <div class="container">
    <h1>Gestión de Clientes</h1>

    <form action="cliente_crud.php" method="POST" class="formulario">
      <h2>Registrar nuevo cliente</h2>
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
      <input type="password" name="contrasena" placeholder="Contraseña" required>
      <button type="submit" name="crear" class="btn">Agregar Cliente</button>
    </form>

    <h2>Lista de Clientes</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombres</th>
          <th>Apellidos</th>
          <th>DNI</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Contraseña</th>
          <th>Usuario</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <form action="cliente_crud.php" method="POST">
            <td><input type="text" name="id" value="<?= htmlspecialchars($row['id']) ?>" readonly></td>
            <td><input type="text" name="nombres" value="<?= htmlspecialchars($row['nombres']) ?>"></td>
            <td><input type="text" name="apellidos" value="<?= htmlspecialchars($row['apellidos']) ?>"></td>
            <td><input type="text" name="dni" value="<?= htmlspecialchars($row['dni']) ?>"></td>
            <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
            <td><input type="text" name="telefono" value="<?= htmlspecialchars($row['telefono'] ?? '') ?>"></td>
            <td><input type="password" name="contrasena" placeholder="Dejar vacío para no cambiar"></td>
            <td><input type="text" name="usuario" value="<?= htmlspecialchars($row['usuario'] ?? '') ?>"></td>
            <td>
              <button type="submit" name="actualizar" class="btn actualizar">✏️</button>
              <a href="cliente_crud.php?eliminar=<?= htmlspecialchars($row['id']) ?>" class="btn eliminar" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">🗑️</a>
            </td>
          </form>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>