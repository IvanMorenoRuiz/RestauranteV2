<?php
session_start();

if (!isset($_SESSION["user"]) || !isset($_SESSION["username"])) {
    header("location: ../login.php");
}

include_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mesaId = $_POST["mesa_id"];
    $fechaReserva = $_POST["fecha_reserva"];
    $horaReserva = $_POST["hora_reserva"];

    // Validar la fecha para asegurarse de que no sea del día anterior
    $fechaActual = date('Y-m-d');
    if ($fechaReserva < $fechaActual) {
        echo "Error: La fecha no puede ser del día anterior.";
        exit();
    }

    // Puedes hacer más validaciones aquí, por ejemplo, para las horas permitidas.

    $fechaHoraReserva = $fechaReserva . ' ' . $horaReserva . ':00';

    // Calcular la hora de finalización (1.5 horas después de la hora de inicio)
    $horaFinReserva = date('Y-m-d H:i:s', strtotime($fechaHoraReserva . ' + 1 hour 30 minutes'));

    // Validar si ya hay una reserva para la misma mesa, día y hora
    $sqlValidarReserva = "SELECT id_reserva FROM tbl_reservas WHERE id_mesa_reserva = ? AND hora_inicio_reserva = ?";
    $stmtValidarReserva = mysqli_prepare($conn, $sqlValidarReserva);
    mysqli_stmt_bind_param($stmtValidarReserva, "is", $mesaId, $fechaHoraReserva);
    mysqli_stmt_execute($stmtValidarReserva);
    mysqli_stmt_store_result($stmtValidarReserva);

    if (mysqli_stmt_num_rows($stmtValidarReserva) > 0) {
        echo "<script>alert('Ya hay una reserva para esta mesa, día y hora. Por favor, selecciona otra hora.');</script>";
    } else {
        // Insertar la nueva reserva
        $sql = "INSERT INTO tbl_reservas (hora_inicio_reserva, hora_final_reserva, id_camarero_reserva, id_mesa_reserva)
                VALUES (?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $fechaHoraReserva, $horaFinReserva, $_SESSION["user"], $mesaId);

        if (mysqli_stmt_execute($stmt)) {
            // Obtener la fecha y hora actuales
            $fechaHoraActual = date('Y-m-d H:i:s');

            // Si la fecha y hora de la reserva son iguales o menores a la fecha y hora actuales, actualizar el estado de la mesa
            if ($fechaHoraReserva <= $fechaHoraActual) {
                $sqlUpdate = "UPDATE tbl_mesas SET estado_mesa = 'Reservada' WHERE id_mesa = ?";
                $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
                mysqli_stmt_bind_param($stmtUpdate, "i", $mesaId);
                mysqli_stmt_execute($stmtUpdate);
            }

            header("location: ../index.php"); // Redirigir a la página principal
        } else {
            echo "Error al programar la reserva.";
        }

        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmtUpdate);
    }

    mysqli_stmt_close($stmtValidarReserva);
    mysqli_close($conn);
} else {
    header("location: ../index.php"); // Redirigir si no es una solicitud POST
}
?>
