<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirigir a la página de inicio si no ha iniciado sesión
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modo Visualización</title>
    <link rel="stylesheet" href="css/modovisual.css">
    <!-- Incluir SweetAlert2 mediante CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<header>
        <!-- Contenido del encabezado aquí -->
        <?php include("header.php"); ?>
    </header>
<body>

<div class="container">

    <?php
    include './proc/conexion.php';

    // Obtener el tipo de sala seleccionado
    $tipoSalaSeleccionado = $_GET['tipo_sala'] ?? '';

    // Consulta para obtener las mesas de la sala seleccionada
    $query = "SELECT m.id_mesa, m.nombre_mesa, m.estado_mesa, m.sillas_mesa, s.nombre_sala
              FROM tbl_mesas m
              INNER JOIN tbl_salas s ON m.id_sala_mesa = s.id_sala
              WHERE s.tipo_sala = '$tipoSalaSeleccionado'
              ORDER BY m.id_sala_mesa, m.id_mesa";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $currentSalaId = null;

        echo "<h1>MESAS DE: $tipoSalaSeleccionado </h1>";

        while ($row = mysqli_fetch_assoc($result)) {
            $mesaId = $row['id_mesa'];
            $nombreMesa = $row['nombre_mesa'];
            $estadoMesa = $row['estado_mesa'];
            $sillasMesa = $row['sillas_mesa'];
            $nombreSala = $row['nombre_sala'];

            // Mostrar el título de la sala solo cuando cambia
            if ($currentSalaId !== $nombreSala) {
                if ($currentSalaId !== null) {
                    echo "</div>"; // Cerrar el grupo anterior de imágenes
                }
                echo "<h2>$nombreSala</h2>";
                echo "<div class='mesa-group'>"; // Iniciar un nuevo grupo de imágenes
                $currentSalaId = $nombreSala;
            }

            // Construir el nombre de la imagen según el número de sillas
            $imagenMesa = "./img/mesa$sillasMesa.png";

            // Determinar la clase CSS según el estado de la mesa
            $claseEstado = ($estadoMesa == 'Libre') ? 'libre' : 'ocupada';

            // Modificar el enlace de la imagen para ejecutar la función programarReserva
            echo "<a href='javascript:void(0)' onclick='elegirTipoReserva($mesaId, \"$estadoMesa\", \"$tipoSalaSeleccionado\")'>";
            echo "<img class='$claseEstado' src='$imagenMesa' alt='Mesa $nombreMesa - $estadoMesa'>";
            echo "</a>";
        }

        echo "</div>"; // Cerrar el último grupo de imágenes
    } else {
        echo "No hay mesas en la sala $tipoSalaSeleccionado.";
    }

    // Cerrar la conexión
    mysqli_close($conn);
    ?>

    <!-- Script para manejar la elección del tipo de reserva -->
    <script>
function elegirTipoReserva(mesaId, estadoMesa, tipoSala) {
    if (estadoMesa === 'Ocupada') {
        Swal.fire({
            title: '¿Seguro que quieres finalizar la ocupación?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                finalizarOcupacion(mesaId, tipoSala);
            }
        });
    } else {
        Swal.fire({
            title: '¿Cómo quieres reservar la mesa?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Reservar ahora',
            cancelButtonText: 'Reservar para otro día'
        }).then((result) => {
            if (result.isConfirmed) {
                programarReserva(mesaId, estadoMesa, tipoSala, true);
            } else {
                programarReserva(mesaId, estadoMesa, tipoSala, false);
            }
        });
    }
}
function finalizarOcupacion(mesaId, tipoSala) {
    // Lógica para finalizar la ocupación
    window.location.href = `./proc/finalizarocupacion.php?mesa=${mesaId}&tipo=${tipoSala}`;
}

function programarReserva(mesaId, estadoMesa, tipoSala, reservarAhora) {
    if (reservarAhora) {
        // Lógica para reservar ahora
        window.location.href = `./proc/reservarvisual.php?mesa=${mesaId}&estado=${estadoMesa}`;
    } else {
        // SweetAlert para reservar para otro día
        Swal.fire({
            title: '¿Quieres reservar la mesa para otro día?',
            text: 'Selecciona la fecha y hora de la reserva',
            icon: 'warning',
            html: `
                <form id="programarReservaForm">
                    <label for="fechaReserva">Fecha:</label>
                    <input type="date" id="fechaReserva" min="${getFechaActual()}" required>

                    <label for="horaReserva">Hora:</label>
                    <select id="horaReserva" required>
                        <option value="13:00 - 14:30">13:00 - 14:30</option>
                        <option value="14:30 - 15:00">14:30 - 15:00</option>
                        <option value="15:00 - 16:30">15:00 - 16:30</option>
                        <option value="20:00 - 21:30">20:00 - 21:30</option>
                        <option value="21:30 - 23:00">21:30 - 23:00</option>
                    </select>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Reservar',
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const fechaReserva = document.getElementById('fechaReserva').value;
                const horaReserva = document.getElementById('horaReserva').value;

                // Validar que la fecha seleccionada no sea anterior a la fecha actual
                const fechaActual = new Date();

                // Puedes agregar aquí la lógica para enviar los datos a tu backend
                if (fechaReserva !== '' && horaReserva !== '') {
                    window.location.href = `./proc/programarreserva.php?mesa=${mesaId}&fecha=${encodeURIComponent(fechaReserva)}&hora=${encodeURIComponent(horaReserva)}&tipo=${tipoSala}`;
                } else {
                    Swal.showValidationMessage('Fecha y hora son obligatorias');
                    return false;
                }
            }
        });
    }
}

function getFechaActual() {
    const fechaActual = new Date();
    const mes = fechaActual.getMonth() + 1; // Los meses en JavaScript van de 0 a 11
    const dia = fechaActual.getDate();
    const fechaFormatted = `${fechaActual.getFullYear()}-${mes < 10 ? '0' + mes : mes}-${dia < 10 ? '0' + dia : dia}`;
    return fechaFormatted;
}

    </script>
</div>

</body>
</html>
