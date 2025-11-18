<?php
// Archivo de navegación reutilizable
// Determinar el tipo de usuario y mostrar navegación apropiada
if (!isset($_SESSION)) {
    session_start();
}
$es_cliente = isset($_SESSION['usuario_cliente']);
$es_empleado = isset($_SESSION['usuario_empleado']);
$es_admin = isset($_SESSION['usuario_admin']);

if($es_cliente) {
    $panel_url = "../index/clienteIndex.php";
    $logout_url = "../cliente/logout_cliente.php";
    $usuario = $_SESSION['usuario_cliente'];
} elseif($es_empleado) {
    $panel_url = "../index/empleadoIndex.php";
    $logout_url = "../empleado/logout_empleado.php";
    $usuario = $_SESSION['usuario_empleado'];
} elseif($es_admin) {
    $panel_url = "../index/indexAdmin.php";
    $logout_url = "../administrador/logout_admin.php";
    $usuario = $_SESSION['usuario_admin'];
} else {
    $panel_url = "../index.html";
    $logout_url = null;
    $usuario = null;
}
?>

<style>
.nav-bar {
    background: rgba(26, 26, 26, 0.9);
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.nav-bar .nav-left {
    display: flex;
    gap: 10px;
    align-items: center;
}

.nav-bar .nav-right {
    display: flex;
    gap: 10px;
    align-items: center;
}

.nav-bar .btn-nav {
    background: #f5c542;
    color: #1a1a1a;
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    font-size: 0.9em;
}

.nav-bar .btn-nav:hover {
    background: #ffd966;
    transform: translateY(-2px);
}

.nav-bar .btn-nav.btn-home {
    background: #22c55e;
    color: white;
}

.nav-bar .btn-nav.btn-home:hover {
    background: #16a34a;
}

.nav-bar .btn-nav.btn-logout {
    background: #ef4444;
    color: white;
}

.nav-bar .btn-nav.btn-logout:hover {
    background: #dc2626;
}

.nav-bar .user-info {
    color: #f5c542;
    font-weight: 600;
    margin-right: 10px;
}
</style>

<?php if($usuario): ?>
<div class="nav-bar">
    <div class="nav-left">
        <a href="<?php echo $panel_url; ?>" class="btn-nav btn-home">🏠 Panel Principal</a>
        <span class="user-info">Usuario: <?php echo htmlspecialchars($usuario); ?></span>
    </div>
    <div class="nav-right">
        <?php if($logout_url): ?>
            <form action="<?php echo $logout_url; ?>" method="POST" style="display: inline;">
                <button type="submit" class="btn-nav btn-logout">Cerrar Sesión</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

