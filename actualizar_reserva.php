<?php
include_once("proc/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["idReserva"])) {
    // Obtener datos del formulario
    $idReserva = $_POST["idReserva"];
    $horaInicio = $_POST["horaInicio"];
    $horaFinal = $_POST["horaFinal"];
    $idCamarero = $_POST["idCamarero"];
    $idMesa = $_POST["idMesa"];

    // Validar las fechas
    $horaInicioTimestamp = strtotime($horaInicio);
    $horaFinalTimestamp = strtotime($horaFinal);

    if ($horaInicioTimestamp === false || $horaFinalTimestamp === false || $horaInicioTimestamp >= $horaFinalTimestamp) {
        // Las fechas no son válidas
        header("Location: adminindex.php?error=true&mensajeError=Las fechas de inicio y final no son válidas.");
        exit();
    }

    // Actualizar la reserva en la base de datos
    $sql = "UPDATE tbl_reservas SET hora_inicio_reserva = '$horaInicio', hora_final_reserva = '$horaFinal', id_camarero_reserva = '$idCamarero', id_mesa_reserva = '$idMesa' WHERE id_reserva = '$idReserva'";

    if ($conn->query($sql) === TRUE) {
        // Redirigir con mensajes de éxito
        header("Location: adminindex.php?success=true&mensaje=Reserva actualizada correctamente.");
        exit();
    } else {
        // Redirigir con mensajes de error
        header("Location: adminindex.php?error=true&mensajeError=Error al actualizar la reserva: " . $conn->error);
        exit();
    }
} else {
    // Si se intenta acceder al script sin enviar el formulario, redirigir a la página principal con mensaje de error
    header("Location: adminindex.php?error=true&mensajeError=Parámetros no válidos para la actualización.");
    exit();
}

// No cerrar la conexión aquí
?>
