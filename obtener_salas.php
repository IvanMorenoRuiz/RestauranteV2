<?php
include_once("proc/conexion.php");

// Obtener lista de salas
$resultSalas = $conn->query("SELECT id_sala, nombre_sala FROM tbl_salas");

// Convertir el resultado a un array asociativo
$salas = [];
while ($rowSala = $resultSalas->fetch_assoc()) {
    $salas[] = $rowSala;
}

// Devolver el array como respuesta JSON
header('Content-Type: application/json');
echo json_encode($salas);
?>
