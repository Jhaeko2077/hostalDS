<?php
/**
 * Funciones Helper para el Sistema de Hostal
 * Evita código duplicado y simplifica el mantenimiento
 */

/**
 * Sanitizar entrada de datos
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validar email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validar DNI (8 dígitos)
 */
function validate_dni($dni) {
    return preg_match('/^\d{8}$/', $dni);
}

/**
 * Mostrar mensaje de error y redirigir
 */
function show_error_and_redirect($message, $url = null) {
    echo "<script>
        alert('" . addslashes($message) . "');
        " . ($url ? "window.location.href = '$url';" : "window.history.back();") . "
    </script>";
    exit();
}

/**
 * Mostrar mensaje de éxito y redirigir
 */
function show_success_and_redirect($message, $url) {
    echo "<script>
        alert('" . addslashes($message) . "');
        window.location.href = '$url';
    </script>";
    exit();
}

/**
 * Verificar si un usuario existe
 */
function user_exists($conn, $table, $field, $value) {
    // Whitelist de tablas y campos permitidos para seguridad
    $allowed_tables = ['Cliente', 'Empleado', 'Administrador'];
    $allowed_fields = ['usuario', 'email', 'dni', 'id'];
    
    if (!in_array($table, $allowed_tables) || !in_array($field, $allowed_fields)) {
        return false;
    }
    
    $stmt = $conn->prepare("SELECT id FROM $table WHERE $field = ?");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

/**
 * Verificar si hay relaciones (registros que dependen de este)
 */
function has_relations($conn, $table, $field, $value) {
    $relations = [
        'Cliente' => ['detalleReserva' => 'idCli'],
        'Empleado' => [
            'detalleServicioHob' => 'idEmp',
            'Administrador' => 'usuario'
        ],
        'Habitaciones' => [
            'detalleReserva' => 'idHab',
            'detalleServicioHob' => 'idHab'
        ],
        'Servicios' => ['detalleServicioHob' => 'idServicio'],
        'tipoPago' => ['detalleReserva' => 'idTipoPago']
    ];
    
    if (!isset($relations[$table])) {
        return false;
    }
    
    foreach ($relations[$table] as $related_table => $related_field) {
        // Para Administrador que usa usuario en lugar de id
        if ($table == 'Empleado' && $related_table == 'Administrador') {
            // Primero obtener el usuario del empleado
            $stmt_emp = $conn->prepare("SELECT usuario FROM Empleado WHERE id = ?");
            $stmt_emp->bind_param("s", $value);
            $stmt_emp->execute();
            $result_emp = $stmt_emp->get_result();
            if ($result_emp->num_rows > 0) {
                $emp = $result_emp->fetch_assoc();
                $usuario_emp = $emp['usuario'];
                $stmt_emp->close();
                
                $stmt = $conn->prepare("SELECT id FROM $related_table WHERE $related_field = ?");
                $stmt->bind_param("s", $usuario_emp);
            } else {
                $stmt_emp->close();
                continue;
            }
        } else {
            // Para Habitaciones, usar codigo en lugar de id
            if ($table == 'Habitaciones') {
                $stmt = $conn->prepare("SELECT id FROM $related_table WHERE $related_field = ?");
            } else {
                $stmt = $conn->prepare("SELECT id FROM $related_table WHERE $related_field = ?");
            }
            $stmt->bind_param("s", $value);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $stmt->close();
            return true;
        }
        $stmt->close();
    }
    
    return false;
}

/**
 * Ejecutar operación CRUD con manejo de errores
 */
function execute_crud($conn, $sql, $types, $params, $success_url, $error_message = "Error en la operación") {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // No exponer detalles técnicos de la base de datos
        show_error_and_redirect($error_message, $success_url);
    }
    
    if (!empty($types) && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        $stmt->close();
        // No exponer detalles técnicos de la base de datos
        show_error_and_redirect($error_message, $success_url);
    }
    
    $stmt->close();
    show_success_and_redirect("Operación realizada exitosamente.", $success_url);
}

/**
 * Obtener datos de usuario logueado
 */
function get_logged_user($conn) {
    session_start();
    
    if (isset($_SESSION['usuario_cliente'])) {
        $stmt = $conn->prepare("SELECT * FROM Cliente WHERE usuario = ?");
        $usuario = $_SESSION['usuario_cliente'];
    } elseif (isset($_SESSION['usuario_empleado'])) {
        $stmt = $conn->prepare("SELECT * FROM Empleado WHERE usuario = ?");
        $usuario = $_SESSION['usuario_empleado'];
    } elseif (isset($_SESSION['usuario_admin'])) {
        $stmt = $conn->prepare("SELECT a.*, e.contrasena FROM Administrador a INNER JOIN Empleado e ON a.usuario = e.usuario WHERE a.usuario = ?");
        $usuario = $_SESSION['usuario_admin'];
    } else {
        return null;
    }
    
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    
    return $user;
}

/**
 * Verificar permisos
 */
function check_permission($required_roles = []) {
    session_start();
    
    $has_permission = false;
    
    if (in_array('cliente', $required_roles) && isset($_SESSION['usuario_cliente'])) {
        $has_permission = true;
    }
    if (in_array('empleado', $required_roles) && isset($_SESSION['usuario_empleado'])) {
        $has_permission = true;
    }
    if (in_array('admin', $required_roles) && isset($_SESSION['usuario_admin'])) {
        $has_permission = true;
    }
    
    if (!$has_permission) {
        header("Location: ../index.html");
        exit();
    }
    
    return $has_permission;
}

/**
 * Formatear fecha para mostrar
 */
function format_date($date) {
    if (empty($date)) return '';
    return date('d/m/Y', strtotime($date));
}

/**
 * Validar rango de fechas
 */
function validate_date_range($fecha_inicio, $fecha_fin) {
    if (empty($fecha_inicio) || empty($fecha_fin)) {
        return false;
    }
    
    $inicio = strtotime($fecha_inicio);
    $fin = strtotime($fecha_fin);
    
    return $fin >= $inicio;
}

/**
 * Verificar disponibilidad de habitación
 */
function check_habitacion_disponible($conn, $idHab, $fecha_inicio, $fecha_fin, $exclude_reserva_id = null) {
    $sql = "SELECT * FROM detalleReserva 
            WHERE idHab = ? 
            AND ((fecha_inicio <= ? AND fecha_fin >= ?) 
                 OR (fecha_inicio <= ? AND fecha_fin >= ?) 
                 OR (fecha_inicio >= ? AND fecha_fin <= ?))";
    
    if ($exclude_reserva_id) {
        $sql .= " AND id != ?";
    }
    
    $stmt = $conn->prepare($sql);
    
    if ($exclude_reserva_id) {
        $stmt->bind_param("ssssssss", $idHab, $fecha_inicio, $fecha_inicio, $fecha_fin, $fecha_fin, $fecha_inicio, $fecha_fin, $exclude_reserva_id);
    } else {
        $stmt->bind_param("sssssss", $idHab, $fecha_inicio, $fecha_inicio, $fecha_fin, $fecha_fin, $fecha_inicio, $fecha_fin);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $disponible = $result->num_rows == 0;
    $stmt->close();
    
    return $disponible;
}
?>

