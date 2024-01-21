<?php
include_once("proc/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idMesa'])) {
    $idMesa = $_POST['idMesa'];

    // Eliminar la mesa de la base de datos
    $sqlEliminarMesa = "DELETE FROM tbl_mesas WHERE id_mesa = '$idMesa'";

    if ($conn->query($sqlEliminarMesa) === TRUE) {
        // Redirigir con mensaje de éxito
        header("Location: adminindex.php?success=true&mensaje=Mesa eliminada con éxito");
        exit();
    } else {
        // Redirigir con mensaje de error
        header("Location: adminindex.php?error=true&mensajeError=Error al eliminar la mesa");
        exit();
    }
} else {
    // Manejar el caso en el que no se envía el ID de la mesa
    echo "ID de mesa no proporcionado.";
    exit();
}
?>
