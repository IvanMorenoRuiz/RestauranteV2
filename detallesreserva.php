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
    $sql = "SELECT r.hora_inicio_reserva, r.hora_final_reserva, m.nombre_mesa, c.nombre_camarero, c.apellidos_camarero
            FROM tbl_reservas r
            INNER JOIN tbl_mesas m ON r.id_mesa_reserva = m.id_mesa
            INNER JOIN tbl_camareros c ON r.id_camarero_reserva = c.id_camarero
            WHERE r.id_reserva = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idReserva);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($resultado)) {
        $nombreMesa = $fila['nombre_mesa'];
        $horaInicio = $fila['hora_inicio_reserva'];
        $horaFinal = $fila['hora_final_reserva'];
        $nombreCamarero = $fila['nombre_camarero'];
        $apellidosCamarero = $fila['apellidos_camarero'];
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
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .detalles-reserva {
            max-width: 600px;
            margin: 20px auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffe799;
            text-align: center;
        }

        .detalles-reserva h2 {
            font-size: 35px;
            text-align: center;
        }

        .detalles-reserva p {
            margin: 8px 0;
        }

        .detalles-reserva a {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: grey;
            font-weight: bold;
        }

        .detalles-reserva a:hover {
            color: black;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <div class="detalles-reserva">
        <h2>Detalles de la Reserva</h2>
        <p><strong>Mesa:</strong> <?php echo $nombreMesa; ?></p>
        <p><strong>Hora de Inicio:</strong> <?php echo $horaInicio; ?></p>
        <p><strong>Hora Final:</strong> <?php echo ($horaFinal) ? $horaFinal : 'No finalizada'; ?></p>
        <p><strong>Camarero:</strong> <?php echo $nombreCamarero . ' ' . $apellidosCamarero; ?></p>
        <!-- Agrega más detalles según sea necesario -->

        <!-- Puedes agregar enlaces o botones para volver al calendario o realizar otras acciones -->
        <a href="calendarioreservas.php">Volver al Calendario de Reservas</a>
    </div>
</body>
</html>
