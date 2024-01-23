<?php
// editar_mesa.php

include_once("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mesa_id"]) && isset($_POST["nuevas_sillas"])) {
    $mesaId = $_POST["mesa_id"];
    $nuevasSillas = $_POST["nuevas_sillas"];

    $sql = "UPDATE tbl_mesas SET sillas_mesa = ? WHERE id_mesa = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        redirigirConSweetAlert("Error al preparar la consulta: " . $conn->error, false);
    }

    $stmt->bind_param("ii", $nuevasSillas, $mesaId);
    $result = $stmt->execute();

    $stmt->close();
    $conn->close();

    if ($result === false) {
        redirigirConSweetAlert("Error al editar la mesa.", false);
    } else {
        redirigirConSweetAlert("Mesa editada correctamente.", true);
    }
} else {
    redirigirConSweetAlert("Parámetros no válidos.", false);
}

function redirigirConSweetAlert($mensaje, $exito) {
    // Almacenar el mensaje y el éxito en variables de sesión
    session_start();
    $_SESSION['sweetAlertMensaje'] = $mensaje;
    $_SESSION['sweetAlertExito'] = $exito;

    // Esperar 2 segundos (puedes ajustar esto según sea necesario)
    sleep(2);

    // Redirigir a la página de destino
    header("Location: ../manteindex.php");
    exit();
}

?>
