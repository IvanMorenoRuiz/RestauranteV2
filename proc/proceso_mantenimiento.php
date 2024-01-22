<?php
include_once("conexion.php");

// proceso_mantenimiento.php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["mesa"]) && isset($_GET["motivo"])) {

    // Obtener datos de la solicitud GET
    $mesaId = $_GET["mesa"];
    $motivo = $_GET["motivo"];

    // Preparar y ejecutar la consulta de actualización
    $sql = "UPDATE tbl_mesas SET motivo_deshabilitacion = ?, estado_mesa = 'Deshabilitada' WHERE id_mesa = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    $stmt->bind_param("si", $motivo, $mesaId);
    $result = $stmt->execute();

    // Verificar si la consulta se ejecutó correctamente
    if ($result === false) {
        die("Error al ejecutar la consulta: " . $stmt->error);
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();

    // Redirigir a la página principal
    header("Location: ../index.php"); // Cambia "index.php" con la página a la que quieras redirigir
    exit();
} else {
    // Si no se proporcionan los parámetros esperados, redirige a la página principal
    header("Location: ../index.php"); // Cambia "index.php" con la página a la que quieras redirigir en caso de error
    exit();
}
?>
