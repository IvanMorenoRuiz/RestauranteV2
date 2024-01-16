<?php
session_start();

if (!isset($_SESSION["user"]) || !isset($_SESSION["username"])) {
    header("location: ./login.php");
}

$user = $_SESSION['user'];
$username = $_SESSION['username'];

include_once("proc/conexion.php");

// Obtener el ID de la reserva desde la URL
if (isset($_GET['id'])) {
    $idReserva = $_GET['id'];

    // Obtener detalles de la reserva desde la base de datos
    $sql = "SELECT * FROM tbl_reservas WHERE id_reserva = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idReserva);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $idMesa = $fila['id_mesa_reserva'];
        $horaInicio = $fila['hora_inicio_reserva'];
        $horaFinal = $fila['hora_final_reserva'];

        // Puedes agregar más detalles según tus necesidades
    } else {
        // Manejar el caso en que la reserva no existe
        echo "Reserva no encontrada";
        exit();
    }

    mysqli_stmt_close($stmt);
} else {
    // Manejar el caso en que no se proporciona un ID de reserva
    echo "ID de reserva no proporcionado";
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Reserva</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<body>
    <?php include("header.php"); ?>

    <div class="detalles-reserva">
        <h2>Detalles de la Reserva</h2>
        <p><strong>ID de Reserva:</strong> <?php echo $idReserva; ?></p>
        <p><strong>ID de Mesa:</strong> <?php echo $idMesa; ?></p>
        <p><strong>Hora de Inicio:</strong> <?php echo $horaInicio; ?></p>
        <p><strong>Hora Final:</strong> <?php echo ($horaFinal) ? $horaFinal : 'No finalizada'; ?></p>
        <!-- Agrega más detalles según sea necesario -->

        <!-- Puedes agregar enlaces o botones para volver al calendario o realizar otras acciones -->
        <a href="calendarioreservas.php">Volver al Calendario de Reservas</a>
    </div>
</body>
</html>
