<?php
session_start();

// Redirigir a la página de inicio de sesión si el usuario no está autenticado
if (!isset($_SESSION["user"]) || !isset($_SESSION["username"])) {
    header("location: ./login.php");
}

$user = $_SESSION['user'];
$username = $_SESSION['username'];

include_once("proc/conexion.php");

// Obtener las reservas desde la base de datos
$sql = "SELECT id_reserva, hora_inicio_reserva, id_mesa_reserva FROM tbl_reservas";
$result = mysqli_query($conn, $sql);

$reservas = array();
while ($row = mysqli_fetch_assoc($result)) {
    $reservas[] = array(
        'id' => $row['id_reserva'],
        'title' => 'Reserva - Mesa ' . $row['id_mesa_reserva'],
        'start' => $row['hora_inicio_reserva'],
    );
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Reservas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>
</head>
<body>
    <?php include("header.php"); ?>

    <div id="calendario"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#calendario').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                events: <?php echo json_encode($reservas); ?>,
                eventClick: function(event) {
                    // Redirigir a la página de detalles de reserva al hacer clic en un evento
                    window.location.href = 'detallesreserva.php?id=' + event.id;
                }
            });
        });
    </script>
</body>
</html>
