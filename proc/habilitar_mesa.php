<?php
include_once("./conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["mesa_id"])) {
    // Procesar la habilitación de la mesa
    $mesaId = $_GET["mesa_id"];

    // Realizar la actualización en la base de datos
    $sql = "UPDATE tbl_mesas SET estado_mesa = 'Libre' WHERE id_mesa = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mesaId);
    $stmt->execute();
    $stmt->close();

    // Redireccionar a la página de mantenimiento después de la habilitación
    header("Location: ../manteindex.php");
    exit();
}

$conn->close();
?>
