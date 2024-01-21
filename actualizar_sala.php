<?php
include_once("proc/conexion.php");

// Verificar si se envió el formulario de actualización
if (isset($_POST['idSala']) && isset($_POST['nombreSala']) && isset($_POST['tipoSala'])) {
    // Obtener datos del formulario
    $idSala = $_POST['idSala'];
    $nombreSala = $_POST['nombreSala'];
    $tipoSala = $_POST['tipoSala'];

    // Actualizar los datos de la sala en la base de datos
    $sqlActualizarSala = "UPDATE tbl_salas SET nombre_sala='$nombreSala', tipo_sala='$tipoSala' WHERE id_sala=$idSala";

    if ($conn->query($sqlActualizarSala) === TRUE) {
        // Redirigir con mensajes de éxito
        header("Location: adminindex.php?success=true&mensaje=Sala actualizada con éxito");
        exit();
    } else {
        // Redirigir con mensajes de error
        header("Location: adminindex.php?error=true&mensajeError=Error al actualizar la sala");
        exit();
    }
} else {
    // Redirigir con mensajes de error si no se reciben los parámetros esperados
    header("Location: adminindex.php?error=true&mensajeError=Parámetros no válidos para la actualización");
    exit();
}

// No cerrar la conexión aquí
?>
