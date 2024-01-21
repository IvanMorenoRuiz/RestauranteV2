<?php
include_once("proc/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idUsuario = $_POST['id'];

    // Realizar la consulta DELETE en tbl_usuarios
    $sqlEliminarUsuario = "DELETE FROM tbl_usuarios WHERE id_usuario = $idUsuario";

    if ($conn->query($sqlEliminarUsuario) === TRUE) {
        // Redirigir a adminindex.php con mensaje de éxito
        header("Location: adminindex.php?success=true&mensaje=Usuario eliminado correctamente");
        exit();
    } else {
        // Manejar errores en la eliminación
        echo "Error al eliminar usuario: " . $conn->error;
    }
} else {
    // Redirigir a adminindex.php si no se proporcionó el ID
    header("Location: adminindex.php");
    exit();
}
?>
