<?php
require_once("../includes/functions.php");
check_permission(['admin', 'empleado']);

include("../conexion.php");

// Obtener estadísticas de reservas
$stats = $conn->query("SELECT * FROM vista_estadisticas_reservas")->fetch_assoc();

// Obtener habitaciones disponibles
$habitaciones_disponibles = $conn->query("
    SELECT COUNT(*) as total FROM vista_habitaciones_disponibles 
    WHERE disponible_ahora = 'Sí'
")->fetch_assoc();

// Obtener habitaciones en limpieza
$habitaciones_limpieza = $conn->query("
    SELECT COUNT(*) as total FROM Habitaciones WHERE estado = 'Limpieza'
")->fetch_assoc();

// Reservas que terminan hoy
$reservas_hoy = $conn->query("
    SELECT COUNT(*) as total FROM detalleReserva 
    WHERE fecha_fin = CURDATE()
")->fetch_assoc();

// Reservas pendientes de pago
$reservas_pendientes = $conn->query("
    SELECT COUNT(*) as total FROM detalleReserva 
    WHERE pago = 0 AND fecha_fin >= CURDATE()
")->fetch_assoc();

// Últimas 5 reservas
$ultimas_reservas = $conn->query("
    SELECT dr.*, c.nombres, c.apellidos, h.tipo as habitacion_tipo
    FROM detalleReserva dr
    JOIN Cliente c ON dr.idCli = c.id
    JOIN Habitaciones h ON dr.idHab = h.codigo
    ORDER BY dr.fecha_inicio DESC
    LIMIT 5
");

// Cambios recientes en auditoría
$cambios_recientes = $conn->query("
    SELECT * FROM auditoria_reservas 
    ORDER BY fecha_hora DESC 
    LIMIT 10
");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Panel de Control</title>
    <link rel="stylesheet" href="../estilos.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, rgba(245, 197, 66, 0.1), rgba(245, 197, 66, 0.05));
            border: 2px solid rgba(245, 197, 66, 0.3);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(245, 197, 66, 0.2);
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            color: #f5c542;
            font-size: 1.1em;
        }
        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            color: #fff;
            margin: 10px 0;
        }
        .stat-card.paid { border-color: rgba(34, 197, 94, 0.5); }
        .stat-card.pending { border-color: rgba(239, 68, 68, 0.5); }
        .stat-card.available { border-color: rgba(59, 130, 246, 0.5); }
        .stat-card.cleaning { border-color: rgba(251, 191, 36, 0.5); }
        
        .section {
            background: rgba(26, 26, 26, 0.6);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section h2 {
            color: #f5c542;
            margin-top: 0;
            border-bottom: 2px solid rgba(245, 197, 66, 0.3);
            padding-bottom: 10px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        .badge.paid {
            background: #22c55e;
            color: white;
        }
        .badge.pending {
            background: #ef4444;
            color: white;
        }
        .badge.direct {
            background: #3b82f6;
            color: white;
        }
        .refresh-btn {
            background: #22c55e;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .refresh-btn:hover {
            background: #16a34a;
        }
    </style>
</head>
<body>
    <?php include("../includes/navegacion.php"); ?>
    <div class="container">
        <h1>📊 Dashboard - Panel de Control</h1>
        
        <button onclick="location.reload()" class="refresh-btn">🔄 Actualizar Datos</button>
        
        <!-- Estadísticas Principales -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <h3>📋 Total Reservas</h3>
                <div class="number"><?= htmlspecialchars($stats['total_reservas'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card paid">
                <h3>✅ Reservas Pagadas</h3>
                <div class="number"><?= htmlspecialchars($stats['reservas_pagadas'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card pending">
                <h3>⏳ Pendientes de Pago</h3>
                <div class="number"><?= htmlspecialchars($reservas_pendientes['total'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card available">
                <h3>🏨 Habitaciones Disponibles</h3>
                <div class="number"><?= htmlspecialchars($habitaciones_disponibles['total'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card cleaning">
                <h3>🧹 En Limpieza</h3>
                <div class="number"><?= htmlspecialchars($habitaciones_limpieza['total'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card">
                <h3>📅 Reservas Activas Hoy</h3>
                <div class="number"><?= htmlspecialchars($stats['reservas_activas'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card">
                <h3>🚪 Check-ins Directos</h3>
                <div class="number"><?= htmlspecialchars($stats['checkins_directos'] ?? 0) ?></div>
            </div>
            
            <div class="stat-card">
                <h3>✅ Reservas Completadas</h3>
                <div class="number"><?= htmlspecialchars($stats['reservas_completadas'] ?? 0) ?></div>
            </div>
        </div>
        
        <!-- Últimas Reservas -->
        <div class="section">
            <h2>📋 Últimas Reservas</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Habitación</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado Pago</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($ultimas_reservas && $ultimas_reservas->num_rows > 0): ?>
                            <?php while($reserva = $ultimas_reservas->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($reserva['id']) ?></td>
                                <td><?= htmlspecialchars($reserva['nombres'] . ' ' . $reserva['apellidos']) ?></td>
                                <td><?= htmlspecialchars($reserva['habitacion_tipo']) ?></td>
                                <td><?= date('d/m/Y', strtotime($reserva['fecha_inicio'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($reserva['fecha_fin'])) ?></td>
                                <td>
                                    <span class="badge <?= $reserva['pago'] ? 'paid' : 'pending' ?>">
                                        <?= $reserva['pago'] ? 'Pagado' : 'Pendiente' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if($reserva['es_checkin_directo']): ?>
                                        <span class="badge direct">Check-in Directo</span>
                                    <?php else: ?>
                                        <span>Reserva Normal</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">No hay reservas registradas</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Cambios Recientes (si existe la tabla de auditoría) -->
        <?php 
        $auditoria_exists = false;
        try {
            $test = $conn->query("SELECT 1 FROM auditoria_reservas LIMIT 1");
            $auditoria_exists = true;
        } catch (Exception $e) {
            // La tabla no existe aún
        }
        ?>
        
        <?php if($auditoria_exists): ?>
        <div class="section">
            <h2>📝 Cambios Recientes (Auditoría)</h2>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Reserva ID</th>
                            <th>Acción</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($cambios_recientes && $cambios_recientes->num_rows > 0): ?>
                            <?php while($cambio = $cambios_recientes->fetch_assoc()): ?>
                            <tr>
                                <td><?= date('d/m/Y H:i', strtotime($cambio['fecha_hora'])) ?></td>
                                <td><?= htmlspecialchars($cambio['reserva_id']) ?></td>
                                <td>
                                    <span class="badge <?= $cambio['accion'] == 'INSERT' ? 'paid' : ($cambio['accion'] == 'UPDATE' ? 'direct' : 'pending') ?>">
                                        <?= htmlspecialchars($cambio['accion']) ?>
                                    </span>
                                </td>
                                <td style="font-size: 0.9em;">
                                    <?= htmlspecialchars(substr($cambio['datos_nuevos'] ?? $cambio['datos_anteriores'] ?? 'N/A', 0, 80)) ?>...
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center;">No hay cambios registrados</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Acciones Rápidas -->
        <div class="section">
            <h2>⚡ Acciones Rápidas</h2>
            <div class="input-group">
                <a href="../detalleReserva/detallesReservas.php" class="btn">📋 Ver Todas las Reservas</a>
                <a href="../habitacion/habitaciones.php" class="btn">🏨 Gestionar Habitaciones</a>
                <a href="../cliente/clientes.php" class="btn">👥 Gestionar Clientes</a>
                <?php if(isset($_SESSION['usuario_admin'])): ?>
                <a href="indexAdmin.php" class="btn">🏠 Volver al Panel</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Auto-refresh cada 30 segundos
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>

