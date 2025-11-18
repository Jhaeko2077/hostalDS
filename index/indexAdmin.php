<?php
session_start();

// Verificar si el usuario está logueado
if(!isset($_SESSION['usuario_admin'])){
    header("Location: ../administrador/login_admin.php");
    exit();
}

$usuario = $_SESSION['usuario_admin'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador</title>
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
        <h1>Panel Administrador</h1>
        
        <div class="user-info">
            <p>Bienvenido, <strong><?php echo htmlspecialchars($usuario); ?></strong></p>
            <form action="../administrador/logout_admin.php" method="POST" style="display: inline;">
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>

        <div class="formulario">
            <div class="input-group">
                <a href="dashboard.php" class="btn" style="background: #3b82f6;">📊 Dashboard</a>
                <a href="../servicio/servicios.php" class="btn">Servicios</a>
                <a href="../cliente/clientes.php" class="btn">Clientes</a>
                <a href="../detalleReserva/detallesReservas.php" class="btn">Reservas</a>
                <a href="../detalleServicio/detallesServicios.php" class="btn">Servicios Detallados</a>
                <a href="../tipoPago/tipoPagos.php" class="btn">Tipos de Pago</a>
                <a href="../habitacion/habitaciones.php" class="btn">Habitaciones</a>
                <a href="../administrador/administradores.php" class="btn">Administradores</a>
                <a href="../empleado/empleados.php" class="btn">Empleados</a>
            </div>
        </div>
    </div>
</body>
</html>

