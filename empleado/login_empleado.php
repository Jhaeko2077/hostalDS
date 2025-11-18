<?php
session_start();

if(isset($_SESSION['usuario_empleado'])){
    header("Location: ../index/empleadoIndex.php");
    exit();
}

if(isset($_COOKIE['usuario_empleado'])){
    $usuarioGuardado = htmlspecialchars($_COOKIE['usuario_empleado'], ENT_QUOTES, 'UTF-8');
} else {
    $usuarioGuardado = "";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Empleado</title>
    <link rel="stylesheet" href="../loginStyle.css">
</head>
<body>
    <div class="login-container">
        <h2>Acceso Empleado</h2>
        <form action="validar_empleado.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required value="<?php echo $usuarioGuardado; ?>">
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <label>
                <input type="checkbox" name="recordar"> Recordarme
            </label>
            <button type="submit">Entrar</button>
        </form>
        
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1);">
            <input type="password" id="claveAdmin" placeholder="Ingresa la contraseña para registrar" style="width: 100%; margin-bottom: 10px; padding: 10px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); color: white;">
            <button id="btnAdmin" type="button" style="width: 100%; padding: 10px; background: #22c55e; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600;">
                Ir a Registrar Empleado
            </button>
            <script>
            document.getElementById("btnAdmin").addEventListener("click", function(e) {
                e.preventDefault();
                const clave = document.getElementById("claveAdmin").value;
                if (clave === "dulc3d3sc4ns0") {
                    window.location.href = "registrarEmpleado.php";
                } else {
                    alert("Contraseña incorrecta. No puedes continuar.");
                }
            });
            </script>
        </div>
        
        <a href="../index.html" style="display: block; text-align: center; color: #f5c542; text-decoration: none; margin-top: 15px; font-size: 0.9em;">Volver al inicio</a>
    </div>
</body>
</html>