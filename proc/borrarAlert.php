<?php
session_start();
if (isset($_SESSION["username"])) {
    header('Location: ./cerrarsesion.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script>
        // Borra la entrada 'alertBienvenido' del localStorage
        localStorage.removeItem('alertBienvenido');
        // Redirige al usuario a la pagina del login
        window.location.href = '../login.php';
    </script>
</head>
</html>