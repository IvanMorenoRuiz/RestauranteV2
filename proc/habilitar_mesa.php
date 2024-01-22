<?php
include_once("./conexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["editar_sillas"])) {
        // Procesar la edición de sillas
        $mesaId = $_POST["mesa_id"];
        $nuevasSillas = $_POST["nuevas_sillas"];

        // Realizar la actualización en la base de datos
        $sql = "UPDATE tbl_mesas SET sillas_mesa = ? WHERE id_mesa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $nuevasSillas, $mesaId);
        $stmt->execute();
        $stmt->close();

        // Redireccionar a la página de mantenimiento después de la edición
        header("Location: ../manteindex.php");
        exit();
    } elseif (isset($_POST["habilitar_mesa"])) {
        // Procesar la habilitación de la mesa
        $mesaId = $_POST["mesa_id"];

        // Realizar la actualización en la base de datos
        $sql = "UPDATE tbl_mesas SET estado_mesa = 'Libre' WHERE id_mesa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $mesaId);
        $stmt->execute();
        $stmt->close();

        // Redireccionar a la página de mantenimiento después de la habilitación
        header("Location: ../manteindex.php");
        exit();
    } elseif (isset($_POST["borrar_mesa"])) {
        // Procesar la eliminación de la mesa
        $mesaId = $_POST["mesa_id"];

        // Realizar la eliminación en la base de datos
        $sql = "DELETE FROM tbl_mesas WHERE id_mesa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $mesaId);
        $stmt->execute();
        $stmt->close();

        // Redireccionar a la página de mantenimiento después de la eliminación
        header("Location: ../manteindex.php");
        exit();
    }
}

$conn->close();
?>
