<?php 
    session_start();

    if (!isset($_SESSION["user"]) || !isset($_SESSION["username"])) {
        header("location: ./login.php");
    }

    $user = $_SESSION['user'];
    $username = $_SESSION['username'];

    if (isset($_GET['mesa'])) {
        $mesaId = $_GET['mesa'];
    } else {
        header("location: ./index.php");
    }

    include_once("./proc/conexion.php");

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fechaReserva = $_POST["fecha_reserva"];
        $horaReserva = $_POST["hora_reserva"];
        $fechaHoraReserva = date('Y-m-d H:i:s', strtotime($fechaReserva . ' ' . $horaReserva . ':00'));

        // Consultar reservas existentes en la mesa para la fecha y hora seleccionadas
        $sqlVerificarReserva = "SELECT * FROM tbl_reservas WHERE id_mesa_reserva = ? AND hora_inicio_reserva = ?";
        $stmtVerificarReserva = mysqli_prepare($conn, $sqlVerificarReserva);
        mysqli_stmt_bind_param($stmtVerificarReserva, "is", $mesaId, $fechaHoraReserva);

        mysqli_stmt_execute($stmtVerificarReserva);
        $resultVerificarReserva = mysqli_stmt_get_result($stmtVerificarReserva);
        $reservaExistente = mysqli_fetch_assoc($resultVerificarReserva);

        mysqli_stmt_close($stmtVerificarReserva);

        // Si hay una reserva existente, mostrar un mensaje de error
        if ($reservaExistente) {
            echo "<p style='color: red;'>Ya hay una reserva para esta mesa y hora. Por favor, selecciona otra hora.</p>";
        } else {
            // Si no hay reserva existente, procesar el formulario normalmente
            // (puedes colocar aquí tu código para insertar la reserva en la base de datos)
            echo "<p>Formulario procesado correctamente.</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programar Reserva</title>
    <link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<body>
    <?php include("header.php"); ?>

    <div class="reserva-form">
        <h2>Programar Reserva</h2>
        <form action="./proc/insertar_reserva.php" method="POST">
            <input type="hidden" name="mesa_id" value="<?php echo $mesaId; ?>">
            
            <label for="fecha_reserva">Fecha:</label>
            <input type="date" name="fecha_reserva" min="<?php echo date('Y-m-d'); ?>" required>
            
            <label for="hora_reserva">Hora:</label>
            <select name="hora_reserva" required>
                <option value="13:00">13:00 - 14:30</option>
                <option value="14:30">14:30 - 15:00</option>
                <option value="15:00">15:00 - 16:30</option>
                <option value="20:00">20:00 - 21:30</option>
                <option value="21:30">21:30 - 23:00</option>
            </select>

            <input type="submit" value="Programar Reserva">
        </form>
    </div>
</body>
</html>
