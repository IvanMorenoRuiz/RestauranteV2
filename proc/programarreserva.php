<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$mesaId = $_GET['mesa'];
$fechaReserva = $_GET['fecha'];
$horaReserva = $_GET['hora'];

require_once('conexion.php');

// Obtener el ID del camarero desde la sesión
$idCamarero = $_SESSION['user'];

// Verificar que el ID del camarero sea válido
if ($idCamarero !== null) {

    // Obtener la fecha y hora actuales
    $fechaHoraActual = date('Y-m-d H:i:s');

    // Obtener las horas de inicio y final de la opción seleccionada
    list($horaInicioReserva, $horaFinalReserva) = explode(" - ", $horaReserva);

    // Concatenar la fecha y la hora de inicio
    $fechaHoraInicioReserva = $fechaReserva . ' ' . $horaInicioReserva;

    // Concatenar la fecha y la hora final
    $fechaHoraFinalReserva = $fechaReserva . ' ' . $horaFinalReserva;

    // Verificar si la fecha y hora actuales están dentro del rango de la reserva
    if ($fechaHoraActual >= $fechaHoraInicioReserva && $fechaHoraActual <= $fechaHoraFinalReserva) {
        // La fecha y hora actuales están dentro del rango, cambiar el estado de la mesa a "Reservada"
        $estadoMesa = "Reservada";
    } else {
        // La fecha y hora actuales no están dentro del rango, no cambiar el estado de la mesa
        $estadoMesa = null;
    }

    // Crear la consulta de inserción
    $sql = "INSERT INTO tbl_reservas (hora_inicio_reserva, hora_final_reserva, id_camarero_reserva, id_mesa_reserva) VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincular los parámetros y ejecutar la consulta
        $stmt->bind_param("ssii", $fechaHoraInicioReserva, $fechaHoraFinalReserva, $idCamarero, $mesaId);
        
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            if ($estadoMesa !== null) {
                // Actualizar el estado de la mesa solo si $estadoMesa no es null
                $sqlActualizarMesa = "UPDATE tbl_mesas SET estado_mesa = ? WHERE id_mesa = ?";
                $stmtActualizarMesa = $conn->prepare($sqlActualizarMesa);

                if ($stmtActualizarMesa) {
                    $stmtActualizarMesa->bind_param("si", $estadoMesa, $mesaId);
                    $stmtActualizarMesa->execute();

                    if ($stmtActualizarMesa->affected_rows > 0) {
                        // Redirigir a index.php con información en la URL
                        header("Location: ../index.php?reserva=exito&mesa=$mesaId&fecha=$fechaReserva&hora=$horaReserva");
                        exit();
                    } else {
                        echo "Error al actualizar el estado de la mesa.";
                    }

                    $stmtActualizarMesa->close();
                } else {
                    echo "Error en la preparación de la consulta para actualizar la mesa.";
                }
            } else {
                // Redirigir a index.php con información en la URL
                header("Location: ../index.php?reserva=exito&mesa=$mesaId&fecha=$fechaReserva&hora=$horaReserva");
                exit();
            }
        } else {
            echo "Error al realizar la reserva.";
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta";
    }
} else {
    echo "Error: El ID del camarero es NULL.";
}

$conn->close();
?>
