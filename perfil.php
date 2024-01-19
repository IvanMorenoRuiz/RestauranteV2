<?php
session_start();

// Verificar si el usuario ha iniciado sesión como camarero
if (!isset($_SESSION["user"])) {
    header("location: login.php");
}

// Obtener información del camarero desde la sesión
$camarero_id = isset($_SESSION['user']) ? $_SESSION['user'] : ''; // Modificado

// Consultar información del camarero desde la base de datos
include_once("proc/conexion.php");
$sql_camarero_info = "SELECT * FROM tbl_camareros WHERE id_camarero = ?";
$stmt_camarero_info = mysqli_prepare($conn, $sql_camarero_info);
mysqli_stmt_bind_param($stmt_camarero_info, "i", $camarero_id);
mysqli_stmt_execute($stmt_camarero_info);
$resultado_camarero_info = mysqli_stmt_get_result($stmt_camarero_info);

// Obtener información del camarero
if ($fila_camarero = mysqli_fetch_assoc($resultado_camarero_info)) {
    $camarero_nombre = $fila_camarero["nombre_camarero"];
    $camarero_apellidos = $fila_camarero["apellidos_camarero"];
    $camarero_username = $fila_camarero["username_camarero"];
    $camarero_imagen = $fila_camarero["imagen_camarero"];
} else {
    // Manejar el caso en el que no se pueda obtener la información del camarero
    // Puedes redirigir a una página de error o realizar otra acción apropiada.
    die("Error al obtener la información del camarero.");
}

// Consultar las mesas atendidas por el camarero
$sql_mesas_atendidas = "SELECT DISTINCT id_mesa_reserva FROM tbl_reservas WHERE id_camarero_reserva = ?";
$stmt_mesas_atendidas = mysqli_prepare($conn, $sql_mesas_atendidas);
mysqli_stmt_bind_param($stmt_mesas_atendidas, "i", $camarero_id);
mysqli_stmt_execute($stmt_mesas_atendidas);
$resultado_mesas_atendidas = mysqli_stmt_get_result($stmt_mesas_atendidas);

// Obtener el total de mesas atendidas
$total_mesas_atendidas = mysqli_num_rows($resultado_mesas_atendidas);

// Consultar el porcentaje de mesas atendidas en cada sala
$sql_porcentaje_salas = "
    SELECT tipo_sala, COUNT(*) AS mesas_atendidas
    FROM tbl_mesas
    INNER JOIN tbl_reservas ON tbl_mesas.id_mesa = tbl_reservas.id_mesa_reserva
    INNER JOIN tbl_salas ON tbl_mesas.id_sala_mesa = tbl_salas.id_sala
    WHERE tbl_reservas.id_camarero_reserva = ?
    GROUP BY tipo_sala
";
$stmt_porcentaje_salas = mysqli_prepare($conn, $sql_porcentaje_salas);
mysqli_stmt_bind_param($stmt_porcentaje_salas, "i", $camarero_id);
mysqli_stmt_execute($stmt_porcentaje_salas);
$resultado_porcentaje_salas = mysqli_stmt_get_result($stmt_porcentaje_salas);

// Crear un array asociativo con el porcentaje de mesas atendidas en cada sala
// Crear un array asociativo con el porcentaje de mesas atendidas en cada sala
$porcentaje_salas = array();
while ($fila_porcentaje_salas = mysqli_fetch_assoc($resultado_porcentaje_salas)) {
    $tipo_sala = $fila_porcentaje_salas["tipo_sala"];
    $mesas_atendidas = $fila_porcentaje_salas["mesas_atendidas"];
    $porcentaje = number_format(($mesas_atendidas / $total_mesas_atendidas) * 100, 1);
    $porcentaje_salas[$tipo_sala] = $porcentaje;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Oasis23</title>
    <link rel="stylesheet" type="text/css" href="./css/perfil.css">
    <script src="js/script.js"></script>
</head>
<body>

<div class="estadisticas-box">
    <!-- Información del camarero -->
    <div class="perfil-camarero">
        <img src="<?php echo $camarero_imagen; ?>" alt="Imagen de perfil">
        <h2><?php echo $camarero_nombre . " " . $camarero_apellidos; ?></h2>
        <p><?php echo "Nombre de usuario: " . $camarero_username; ?></p>
    </div>

    <!-- Estadísticas del camarero -->
    <div class="estadisticas-camarero">
        <h3>Mesas Atendidas:         <?php echo $total_mesas_atendidas; ?></h3>


        <h3>Porcentajes por Sala:</h3>
        <ul>
            <?php
            foreach ($porcentaje_salas as $tipo_sala => $porcentaje) {
                echo "<li>$tipo_sala: $porcentaje%</li>";
            }
            ?>
        </ul>
    </div>
    <button onclick="window.location.href='index.php'">Volver al Inicio</button>
</div>

</body>
</html>