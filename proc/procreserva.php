<?php

$tipoSalaSeleccionado = $_GET['tipo_sala'] ?? '';

// Recuperar los datos del formulario
$mesaId = isset($_POST['mesa_id']) ? $_POST['mesa_id'] : null;
$estadoMesa = isset($_POST['estado_mesa']) ? $_POST['estado_mesa'] : null;
$horaInicio = isset($_POST['hora_inicio']) ? $_POST['hora_inicio'] : null;
$idCamarero = isset($_POST['id_camarero']) ? $_POST['id_camarero'] : null;

if (!$mesaId || !$estadoMesa || !$horaInicio || ($estadoMesa == 'Libre' && !$idCamarero)) {
    echo "Error: No se proporcionaron todos los datos necesarios.";
} else {
    // Incluir archivo de conexión
    include 'conexion.php';


    try {
        // Iniciar la transacción
        mysqli_autocommit($conn, false);
        
        // Convertir la hora de inicio a formato de base de datos
        $horaInicioFormatoDB = date('Y-m-d H:i:s', strtotime($horaInicio));

        // Actualizar la mesa a estado ocupada
        $queryUpdateMesa = "UPDATE tbl_mesas SET estado_mesa = 'Ocupada' WHERE id_mesa = ?";
        $stmtUpdateMesa = mysqli_prepare($conn, $queryUpdateMesa);
        mysqli_stmt_bind_param($stmtUpdateMesa, 'i', $mesaId);
        mysqli_stmt_execute($stmtUpdateMesa);

        // Verificar si la actualización fue exitosa
        if (mysqli_affected_rows($conn) > 0) {
            // Insertar reserva en la base de datos
            $queryInsertReserva = "INSERT INTO tbl_reservas (hora_inicio_reserva, id_camarero_reserva, id_mesa_reserva) VALUES (?, ?, ?)";
            $stmtInsertReserva = mysqli_prepare($conn, $queryInsertReserva);
            mysqli_stmt_bind_param($stmtInsertReserva, 'sii', $horaInicioFormatoDB, $idCamarero, $mesaId);
            mysqli_stmt_execute($stmtInsertReserva);

            // Verificar si la inserción fue exitosa
            if (mysqli_affected_rows($conn) > 0) {
                // Confirmar la transacción
                mysqli_commit($conn);
                echo "Reserva procesada con éxito.";
            } else {
                throw new Exception("Error al insertar la reserva: " . mysqli_error($conn));
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
        mysqli_stmt_close($stmtInsertReserva);
        mysqli_close($conn);
    }
}

// Redireccionar a visualT1.php después de procesar la reserva
header('Location: ../modovisual.php?tipo_sala=' . $tipoSalaSeleccionado);
exit(); // Asegurarse de que el script se detenga después de la redirección
?>
