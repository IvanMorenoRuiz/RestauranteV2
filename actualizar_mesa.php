<?php
include_once("proc/conexion.php");

// ...

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idMesa'])) {
    $idMesa = $_POST['idMesa'];
    $nombreMesa = $_POST['nombreMesa'];
    $sillasMesa = $_POST['sillasMesa'];
    $idTipoSala = $_POST['idTipoSala'];

    // Validar que el tipo de sala exista antes de actualizar
    $sqlValidarSala = "SELECT id_sala FROM tbl_salas WHERE id_sala = '$idTipoSala' LIMIT 1";
    $resultValidarSala = $conn->query($sqlValidarSala);

    if ($resultValidarSala->num_rows > 0) {
        // Actualizar la mesa
        $sqlActualizarMesa = "UPDATE tbl_mesas SET nombre_mesa = '$nombreMesa', sillas_mesa = '$sillasMesa', id_sala_mesa = '$idTipoSala' WHERE id_mesa = '$idMesa'";

        if ($conn->query($sqlActualizarMesa) === TRUE) {
            // Redirigir con mensajes de éxito
            header("Location: adminindex.php?success=true&mensaje=Mesa actualizada con éxito");
            exit();
        } else {
            // Redirigir con mensajes de error
            header("Location: adminindex.php?error=true&mensajeError=Error al actualizar la mesa");
            exit();
        }
    } else {
        // Redirigir con mensajes de error si el tipo de sala no existe
        header("Location: adminindex.php?error=true&mensajeError=Tipo de sala no válido");
        exit();
    }
}

// ...
?>
