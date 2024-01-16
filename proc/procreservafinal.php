<?php

$tipoSalaSeleccionado = $_GET['tipo_sala'] ?? '';

// Recuperar los datos del formulario
$mesaId = isset($_POST['mesa_id']) ? $_POST['mesa_id'] : null;
$estadoMesa = isset($_POST['estado_mesa']) ? $_POST['estado_mesa'] : null;
$horaFinal = isset($_POST['hora_final']) ? $_POST['hora_final'] : null;
$idCamarero = isset($_POST['id_camarero']) ? $_POST['id_camarero'] : null;

if (!$mesaId || !$estadoMesa || !$horaFinal) {
    echo "Error: No se proporcionaron todos los datos necesarios.";
} else {
    // Incluir archivo de conexión
    include 'conexion.php';

    // Iniciar la transacción
    mysqli_autocommit($conn, false);

    try {
        // Convertir la hora de finalización a formato de base de datos
        $horaFinalFormatoDB = date('Y-m-d H:i:s', strtotime($horaFinal));

        // Actualizar la mesa a estado libre
        $queryUpdateMesa = "UPDATE tbl_mesas SET estado_mesa = 'Libre' WHERE id_mesa = ?";
        $stmtUpdateMesa = mysqli_prepare($conn, $queryUpdateMesa);
        mysqli_stmt_bind_param($stmtUpdateMesa, 'i', $mesaId);
        mysqli_stmt_execute($stmtUpdateMesa);

        // Verificar si la actualización fue exitosa
        if (mysqli_affected_rows($conn) > 0) {
            // Actualizar la hora final en la reserva
            $queryUpdateReserva = "UPDATE tbl_reservas SET hora_final_reserva = ? WHERE id_mesa_reserva = ? AND hora_final_reserva IS NULL";
            $stmtUpdateReserva = mysqli_prepare($conn, $queryUpdateReserva);
            mysqli_stmt_bind_param($stmtUpdateReserva, 'si', $horaFinalFormatoDB, $mesaId);
            mysqli_stmt_execute($stmtUpdateReserva);

            // Verificar si la actualización de la reserva fue exitosa
            if (mysqli_affected_rows($conn) > 0) {
                // Confirmar la transacción
                mysqli_commit($conn);
                echo "Reserva finalizada con éxito.";
            } else {
                throw new Exception("Error al actualizar la hora final de la reserva: " . mysqli_error($conn));
            }
        } else {
            throw new Exception("Error al actualizar el estado de la mesa: " . mysqli_error($conn));
        }
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        mysqli_rollback($conn);
        echo "Error en la transacción: " . $e->getMessage();
    } finally {
        // Cerrar la conexión
        mysqli_stmt_close($stmtUpdateMesa);
        mysqli_stmt_close($stmtUpdateReserva);
        mysqli_close($conn);
    }
}

// Redireccionar a visualT1.php después de procesar la finalización de la reserva
header('Location: ../modovisual.php?tipo_sala=' . $tipoSalaSeleccionado);
exit(); // Asegurarse de que el script se detenga después de la redirección
?>
