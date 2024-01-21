<?php
include_once("proc/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $newUsername = $_POST['username'];
    $newNombre = $_POST['nombre'];
    $newApellidos = $_POST['apellidos'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $newTipoUsuario = $_POST['tipoUsuario'];
    $newImagen = $_POST['imagen'];

    // Verificar si el nuevo nombre de usuario ya existe en tbl_usuarios
    $verificarUsuario = $conn->query("SELECT id_usuario FROM tbl_usuarios WHERE username_usuario = '$newUsername' AND id_usuario <> '$id'");

    if ($verificarUsuario->num_rows > 0) {
        // Usuario con el mismo username ya existe
        $mensajeError = "Ya existe un usuario con el nombre de usuario $newUsername.";
        header("Location: adminindex.php?error=true&mensajeError=" . urlencode($mensajeError));
        exit();
    }

    // Actualizar tbl_usuarios
    $sqlActualizarUsuario = "UPDATE tbl_usuarios SET
                            username_usuario = '$newUsername',
                            nombre_usuario = '$newNombre',
                            apellidos_usuario = '$newApellidos',
                            pwd_usuario = '$newPassword',
                            tipo_usuario = '$newTipoUsuario',
                            imagen_usuario = '$newImagen'
                            WHERE id_usuario = '$id'";

    if ($conn->query($sqlActualizarUsuario) === TRUE) {
        // Si el tipo de usuario es "Camarero", también actualizar tbl_camareros
        if ($newTipoUsuario === "Camarero") {
            $sqlActualizarCamarero = "UPDATE tbl_camareros SET
                                    username_camarero = '$newUsername',
                                    nombre_camarero = '$newNombre',
                                    apellidos_camarero = '$newApellidos',
                                    imagen_camarero = '$newImagen'
                                    WHERE id_camarero = '$id'";

            if ($conn->query($sqlActualizarCamarero) !== TRUE) {
                // Manejar errores en la actualización en tbl_camareros
                echo "Error al actualizar camarero: " . $conn->error;
            }
        }

        // Redirigir de nuevo a adminindex.php con éxito
        $mensaje = "Usuario actualizado con éxito.";
        header("Location: adminindex.php?success=true&mensaje=" . urlencode($mensaje));
        exit();
    } else {
        // Manejar errores en la actualización en tbl_usuarios
        echo "Error al actualizar usuario: " . $conn->error;
    }
} else {
    // Manejar el caso en que el formulario no fue enviado correctamente
    echo "Error en la solicitud";
}

$conn->close();
?>
