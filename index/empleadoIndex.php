<?php
session_start();

// Verificar si el usuario está logueado
if(!isset($_SESSION['usuario_empleado'])){
    header("Location: ../empleado/login_empleado.php");
    exit();
}

$usuario = $_SESSION['usuario_empleado'];
$tipo = $_SESSION['tipo_empleado'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Empleado</title>
    <link rel="stylesheet" href="../estilos.css">
    <style>
        .user-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(245, 197, 66, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(245, 197, 66, 0.3);
        }
        .user-info p {
            margin: 5px 0;
            color: #f5c542;
        }
        .btn-logout {
            background: #ef4444;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: all 0.3s ease;
        }
        .btn-logout:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Panel Empleado</h1>
        
        <div class="user-info">
            <p>Bienvenido, <strong><?php echo htmlspecialchars($usuario); ?></strong></p>
            <?php if($tipo): ?>
                <p>Tipo: <strong><?php echo htmlspecialchars($tipo); ?></strong></p>
            <?php endif; ?>
            <form action="../empleado/logout_empleado.php" method="POST" style="display: inline;">
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>

        <div class="formulario">
            <div class="input-group">
                <a href="dashboard.php" class="btn" style="background: #3b82f6;">📊 Dashboard</a>
                <a href="../cliente/clientes.php" class="btn">Clientes</a>
                <a href="../detalleReserva/detallesReservas.php" class="btn">Reservas</a>
                <a href="../detalleServicio/detallesServicios.php" class="btn">Servicios Detallados</a>
                <a href="../tipoPago/tipoPagos.php" class="btn">Tipos de Pago</a>
                <a href="../habitacion/habitaciones.php" class="btn">Habitaciones</a>
            </div>
        </div>
    </div>
</body>
</html>

