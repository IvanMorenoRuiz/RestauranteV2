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
</head>
<body>

<div class="container">

<form >
    <a href="index.php">
        <img src="./img/botonvolver.png" alt="Volver" class="volver-button">
    </a>
</form>

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

        // La imagen de la mesa es un enlace al formulario de reserva
        echo "<a href='formreserva.php?mesa_id=$mesaId&estado_mesa=$estadoMesa&tipo_sala=$tipoSalaSeleccionado'>";
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
</div>

</body>
</html>
