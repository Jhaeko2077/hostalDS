<?php
session_start();

// Si ya está logueado, redirigir al panel
if(isset($_SESSION['usuario_admin'])){
    header("Location: ../index/indexAdmin.php");
    exit();
}

if(isset($_COOKIE['usuario_admin'])){
    $usuarioGuardado = $_COOKIE['usuario_admin'];
} else {
    $usuarioGuardado = "";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrador</title>
    <link rel="stylesheet" href="../loginStyle.css">
</head>
<body>
    <div class="login-container">
        <h2>Panel del Administrador</h2>
        <form action="validar_admin.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required value="<?php echo $usuarioGuardado; ?>">
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <label>
                <input type="checkbox" name="recordar"> Recordarme
            </label>
            <button type="submit">Acceder</button>
            <input type="password" id="claveAdmin" placeholder="Ingresa la contraseña">

            <button id="btnAdmin" class="btn btn-register">
                <i class="fas fa-user-plus"></i>
                Ir a Registrar Admin
            </button>

            <script>
            document.getElementById("btnAdmin").addEventListener("click", function() {
                const clave = document.getElementById("claveAdmin").value;
                if (clave === "dulc3d3sc4ns0") {
                    // Redirige a la página
                    window.location.href = "registrarAdmin.php";
                } else {
                    alert("Contraseña incorrecta. No puedes continuar.");
                }
            });
            </script>
        </form>
    </div>
</body>
</html>