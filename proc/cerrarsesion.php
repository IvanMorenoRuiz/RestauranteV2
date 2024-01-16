<?php
// Inicia la sesion si no está iniciada
session_start();
// Elimina todas las variables de sesion
session_unset();
// Destruye la sesion
session_destroy();
// Redirige al usuario a la pagina de inicio de sesion
header("Location: borrarAlert.php");
exit();
?>