<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
include './proc/conexion.php';
$nombresalaurl = $_GET['nombre_sala'] ?? '';
$idCamarero = isset($_SESSION['user']) ? $_SESSION['user'] : ''; 


$queryMesas = "SELECT id_mesa, estado_mesa FROM tbl_mesas 
               WHERE id_sala_mesa IN (SELECT id_sala FROM tbl_salas WHERE nombre_sala = '$nombresalaurl')";
$resultMesas = mysqli_query($conn, $queryMesas);

if ($resultMesas && mysqli_num_rows($resultMesas) > 0) {
    while ($rowMesa = mysqli_fetch_assoc($resultMesas)) {
        $idMesa = $rowMesa['id_mesa'];
        $estadoMesa = $rowMesa['estado_mesa'];


        if ($estadoMesa == 'Libre') {
            $nuevoEstado = 'Ocupada';
        } else {
            $nuevoEstado = 'Libre';
        }


        $queryUpdateMesa = "UPDATE tbl_mesas SET estado_mesa = '$nuevoEstado' WHERE id_mesa = $idMesa";
        mysqli_query($conn, $queryUpdateMesa);
    }

    header('Location: index.php');
} else {
    echo "<p>No hay mesas disponibles en la sala $nombresalaurl.</p>";
}

mysqli_close($conn);
?>