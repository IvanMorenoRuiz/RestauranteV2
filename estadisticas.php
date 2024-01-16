<?php
// Inicia la sesión de PHP.
session_start();

// Verificar si el usuario está autenticado.
if (!isset($_SESSION['user'])) {
    // Si no ha iniciado sesión, redirige a la página de inicio de sesión.
    header('Location: login.php');
    exit();
}

// Incluye el archivo de conexión a la base de datos.
include_once("proc/conexion.php");

// Estadísticas de porcentaje de reservas en cada tipo de sala.
$sqlSalaCount = "SELECT tipo_sala, COUNT(*) as count FROM tbl_reservas
                JOIN tbl_mesas ON tbl_reservas.id_mesa_reserva = tbl_mesas.id_mesa
                JOIN tbl_salas ON tbl_mesas.id_sala_mesa = tbl_salas.id_sala
                GROUP BY tipo_sala";

$resultSalaCount = mysqli_query($conn, $sqlSalaCount);

$salaStats = array();
while ($row = mysqli_fetch_assoc($resultSalaCount)) {
    // Almacena las estadísticas en un array asociativo.
    $salaStats[$row['tipo_sala']] = $row['count'];
}

// Estadísticas del camarero con más reservas.
$sqlCamareroCount = "SELECT tbl_camareros.nombre_camarero, tbl_camareros.apellidos_camarero, COUNT(tbl_reservas.id_reserva) as count
                    FROM tbl_camareros
                    JOIN tbl_reservas ON tbl_camareros.id_camarero = tbl_reservas.id_camarero_reserva
                    GROUP BY tbl_camareros.id_camarero
                    ORDER BY count DESC
                    LIMIT 1";

$resultCamareroCount = mysqli_query($conn, $sqlCamareroCount);
$rowCamarero = mysqli_fetch_assoc($resultCamareroCount);
$nombreCamarero = $rowCamarero['nombre_camarero'] . " " . $rowCamarero['apellidos_camarero'];
$camareroReservas = $rowCamarero['count'];

// Contar mesas atendidas por cada camarero.
$sqlMesasCamarero = "SELECT tbl_camareros.nombre_camarero, tbl_camareros.apellidos_camarero, COUNT(DISTINCT tbl_reservas.id_mesa_reserva) as count
                    FROM tbl_camareros
                    JOIN tbl_reservas ON tbl_camareros.id_camarero = tbl_reservas.id_camarero_reserva
                    GROUP BY tbl_camareros.id_camarero";

$resultMesasCamarero = mysqli_query($conn, $sqlMesasCamarero);

// Contar total de mesas atendidas por todos los camareros.
$sqlTotalMesas = "SELECT COUNT(DISTINCT id_mesa_reserva) as total_mesas FROM tbl_reservas";
$resultTotalMesas = mysqli_query($conn, $sqlTotalMesas);
$rowTotalMesas = mysqli_fetch_assoc($resultTotalMesas);
$totalMesas = $rowTotalMesas['total_mesas'];

// Cerrar la conexión a la base de datos.
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas Oasis23</title>
    <link rel="stylesheet" type="text/css" href="./css/estadisticas.css">
</head>
<body>
    <!-- Contenedor principal de la página -->
    <div class="translucent-box">

        <!-- Título principal -->
        <h1>🌵ESTADISTICAS OASIS23🌵</h1>

        <!-- Sección de Porcentajes de Sala -->
        <h2>PORCENTAJES DE SALA</h2>
        <ul>
            <?php
            // Calcula el total de reservas.
            $totalReservas = array_sum($salaStats);

            // Itera sobre las estadísticas de sala y muestra el porcentaje.
            foreach ($salaStats as $tipoSala => $count) {
                $porcentaje = number_format(($count / $totalReservas) * 100, 1);
                echo "<li>$tipoSala: $porcentaje%</li>";
            }
            ?>
        </ul>

        <!-- Sección del Empleado del Mes -->
        <h2>EMPLEADO DEL MES</h2>
        <p>El camarero que más ha trabajado es <?php echo "$nombreCamarero con $camareroReservas mesas atendidas."; ?></p>

        <!-- Sección de Estadísticas de Cada Camarero -->
        <h2>ESTADISTICAS DE CADA CAMARERO</h2>
        <ul>
            <?php
            // Muestra las estadísticas de mesas atendidas por cada camarero.
            while ($row = mysqli_fetch_assoc($resultMesasCamarero)) {
                echo "<li>{$row['nombre_camarero']} {$row['apellidos_camarero']}: {$row['count']} mesas</li>";
            }
            ?>
        </ul>

        <!-- Sección del Total de Mesas Atendidas -->
        <h2>TOTAL DE MESAS ATENDIDAS</h2>
        <p>Se han atendido un total de <?php echo "$totalMesas mesas."; ?></p>

        <!-- Botón para volver al Inicio -->
        <button onclick="window.location.href='index.php'">Volver al Inicio</button>
    </div>
</body>
</html>
