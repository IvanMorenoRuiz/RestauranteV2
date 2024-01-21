<?php
include_once("proc/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $idUsuario = $_POST['id'];
    $username = $_POST['username'];

    $sql = "UPDATE tbl_usuarios SET username_usuario = '$username' WHERE id_usuario = $idUsuario";

    if ($conn->query($sql) === TRUE) {
        echo "Actualización exitosa";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

$conn->close();
?>
