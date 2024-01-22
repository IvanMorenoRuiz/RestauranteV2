<?php
// habilitar_mesa.php

include_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mesa_id"])) {
    $mesaId = $_POST["mesa_id"];

    $sql = "UPDATE tbl_mesas SET motivo_deshabilitacion = NULL, estado_mesa = 'Libre' WHERE id_mesa = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        redirigirConSweetAlert("Error al preparar la consulta: " . $conn->error, false);
    }

    $stmt->bind_param("i", $mesaId);
    $result = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($result === false) {
        redirigirConSweetAlert("Error al habilitar la mesa.", false);
    } else {
        redirigirConSweetAlert("Mesa habilitada correctamente.", true);
    }
} else {
    redirigirConSweetAlert("Parámetros no válidos.", false);
}

function redirigirConSweetAlert($mensaje, $exito) {
    $url = "../manteindex.php?mensaje=" . urlencode($mensaje) . "&exito=" . ($exito ? 'true' : 'false');
    header("Location: $url");
    exit();
}
?>
