<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirigir a la página de inicio si no ha iniciado sesión
    exit();
}

include './proc/conexion.php';

// Inicializar las variables para evitar errores de "undefined index"
$fecha_inicio = $fecha_fin = $hora_inicio = $hora_fin = $camarero = $tipo_sala = "";

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_fin = $_POST["fecha_fin"];
    $hora_inicio = $_POST["hora_inicio"];
    $hora_fin = $_POST["hora_fin"];
    $camarero = $_POST["camarero"];
    $tipo_sala = $_POST["tipo_sala"];
}

// Construir la condición WHERE basada en los filtros proporcionados
$where_condition = "1";  // Esto es verdadero para todas las filas por defecto

if (!empty($fecha_inicio) && !empty($fecha_fin)) {
    $where_condition .= " AND DATE(r.hora_inicio_reserva) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

if (!empty($hora_inicio) && !empty($hora_fin)) {
    $where_condition .= " AND TIME(r.hora_inicio_reserva) BETWEEN '$hora_inicio' AND '$hora_fin'";
}

if (!empty($camarero)) {
    $where_condition .= " AND c.id_camarero = '$camarero'";
}

if (!empty($tipo_sala)) {
    $where_condition .= " AND s.tipo_sala = '$tipo_sala'";
}

// Consulta SQL con filtros y ordenamiento
$sql = "SELECT
            r.id_reserva,
            r.hora_inicio_reserva,
            r.hora_final_reserva,
            SUBSTRING(SEC_TO_TIME(TIME_TO_SEC(TIMESTAMPDIFF(MINUTE, r.hora_inicio_reserva, r.hora_final_reserva))*60), 1, 5) AS tiempo_transcurrido_formato,
            c.nombre_camarero,
            m.nombre_mesa,
            s.tipo_sala
        FROM
            tbl_reservas r
            INNER JOIN tbl_camareros c ON r.id_camarero_reserva = c.id_camarero
            INNER JOIN tbl_mesas m ON r.id_mesa_reserva = m.id_mesa
            INNER JOIN tbl_salas s ON m.id_sala_mesa = s.id_sala
        WHERE
            $where_condition
        ORDER BY r.id_reserva"; // Ordenar por id_reserva ascendente

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial</title>
    <link rel="stylesheet" type="text/css" href="./css/historial.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function resetFilters() {
            document.getElementById('fecha_inicio').value = '';
            document.getElementById('fecha_fin').value = '';
            document.getElementById('hora_inicio').value = '';
            document.getElementById('hora_fin').value = '';
            document.getElementById('camarero').value = '';
            document.getElementById('tipo_sala').value = '';
        }
    </script>
</head>
<body>
<?php
include("header.php");
?>

<div class="div-table">

<h1>HISTORIAL OASIS23 🌵</h1>

<div class="tabla-filtro">
    <!-- Formulario de filtros -->
    <form method="post" action="">
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio; ?>">

        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo $fecha_fin; ?>">

        <label for="hora_inicio">Hora de inicio:</label>
        <input type="time" name="hora_inicio" id="hora_inicio" value="<?php echo $hora_inicio; ?>">

        <label for="hora_fin">Hora de fin:</label>
        <input type="time" name="hora_fin" id="hora_fin" value="<?php echo $hora_fin; ?>">

        <label for="camarero">Camarero:</label>
        <select name="camarero" id="camarero">
            <option value="" <?php echo ($camarero == "") ? "selected" : ""; ?>>Todos</option>
            <?php
            // Consulta para obtener la lista de camareros
            $sql_camareros = "SELECT id_camarero, nombre_camarero FROM tbl_camareros";
            $result_camareros = mysqli_query($conn, $sql_camareros);

            while ($row_camarero = mysqli_fetch_assoc($result_camareros)) {
                echo "<option value='" . $row_camarero["id_camarero"] . "' " . ($camarero == $row_camarero["id_camarero"] ? "selected" : "") . ">" . $row_camarero["nombre_camarero"] . "</option>";
            }
            ?>
        </select>

        <label for="tipo_sala">Tipo de sala:</label>
        <select name="tipo_sala" id="tipo_sala">
            <option value="" <?php echo ($tipo_sala == "") ? "selected" : ""; ?>>Todos</option>
            <?php
            // Consulta para obtener la lista de tipos de sala
            $sql_tipos_sala = "SELECT DISTINCT tipo_sala FROM tbl_salas";
            $result_tipos_sala = mysqli_query($conn, $sql_tipos_sala);

            while ($row_tipo_sala = mysqli_fetch_assoc($result_tipos_sala)) {
                echo "<option value='" . $row_tipo_sala["tipo_sala"] . "' " . ($tipo_sala == $row_tipo_sala["tipo_sala"] ? "selected" : "") . ">" . $row_tipo_sala["tipo_sala"] . "</option>";
            }
            ?>
        </select>

        <input type="submit" value="Filtrar">
        <input type="button" value="Limpiar Filtros" onclick="resetFilters()">
    </form>

    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>ID Reserva</th><th>Hora Inicio</th><th>Hora Final</th><th>Tiempo Transcurrido (min)</th><th>Nombre Camarero</th><th>Num Mesa</th><th>Tipo Sala</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["id_reserva"] . "</td><td>" . $row["hora_inicio_reserva"] . "</td><td>" . $row["hora_final_reserva"] . "</td><td>" . $row["tiempo_transcurrido_formato"] . "</td><td>" . $row["nombre_camarero"] . "</td><td>" . $row["nombre_mesa"] . "</td><td>" . $row["tipo_sala"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 resultados";
    }
    ?>
    </div>
 </div>

    <!-- Cerrar la conexión -->
    <?php mysqli_close($conn); ?>

</body>
</html>
