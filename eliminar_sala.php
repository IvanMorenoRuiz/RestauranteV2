<?php
include_once("proc/conexion.php");

// Verificar si se ha enviado el formulario de eliminación
if (isset($_POST['eliminarSala']) && $_POST['eliminarSala'] == 'true') {
    // Obtener el ID de la sala a eliminar desde el formulario
    $idSala = $_POST['idSala'];

    // Consultar la base de datos para obtener las mesas asociadas a la sala
    $sqlObtenerMesas = "SELECT id_mesa FROM tbl_mesas WHERE id_sala_mesa = '$idSala'";
    $resultMesas = $conn->query($sqlObtenerMesas);

    // Eliminar las mesas asociadas
    while ($rowMesa = $resultMesas->fetch_assoc()) {
        $idMesa = $rowMesa['id_mesa'];
        $sqlEliminarMesa = "DELETE FROM tbl_mesas WHERE id_mesa = '$idMesa'";
        $conn->query($sqlEliminarMesa);
    }

    // Ahora, eliminar la sala
    $sqlEliminarSala = "DELETE FROM tbl_salas WHERE id_sala = '$idSala'";

    if ($conn->query($sqlEliminarSala) === TRUE) {
        // Redirigir con mensaje de éxito
        header("Location: adminindex.php?success=true&mensaje=Sala y mesas eliminadas con éxito");
        exit();
    } else {
        // Redirigir con mensaje de error
        header("Location: adminindex.php?error=true&mensajeError=Error al eliminar la sala y las mesas");
        exit();
    }
} else {
    // Si no se ha enviado el formulario correctamente, redirigir con mensaje de error
    header("Location: adminindex.php?error=true&mensajeError=Formulario de eliminación no válido");
    exit();
}
?>
