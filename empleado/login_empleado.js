// Limpiar campos de contraseña al cargar la página
window.addEventListener('load', function() {
    document.getElementById('contrasena').value = '';
    document.getElementById('claveAdmin').value = '';
});

// Limpiar campos de contraseña al volver a la página (botón atrás del navegador)
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        document.getElementById('contrasena').value = '';
        document.getElementById('claveAdmin').value = '';
    }
});

// Limpiar después del submit
document.querySelector('form').addEventListener('submit', function() {
    setTimeout(function() {
        document.getElementById('contrasena').value = '';
    }, 100);
});

// Validación de contraseña para acceder a registro de empleado
document.getElementById("btnAdmin").addEventListener("click", function(e) {
    e.preventDefault();
    const clave = document.getElementById("claveAdmin").value;
    if (clave === "dulc3d3sc4ns0") {
        window.location.href = "registrarEmpleado.php";
    } else {
        alert("Contraseña incorrecta. No puedes continuar.");
        document.getElementById('claveAdmin').value = '';
    }
});