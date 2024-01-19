<?php
include './proc/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'read') {
        // Obtener usuarios y generar la tabla
        $usuarios = obtenerUsuarios();
        generarTablaUsuarios($usuarios);
    } elseif ($_GET['action'] === 'getUsuario') {
        // Obtener datos del usuario para el modal
        $idUsuario = $_GET['id'];
        obtenerUsuarioParaModal($idUsuario);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'save') {
        // Guardar o actualizar usuario
        guardarActualizarUsuario($_POST);
    } elseif ($_POST['action'] === 'delete') {
        // Eliminar usuario
        eliminarUsuario($_POST['id']);
    }
}

function obtenerUsuarios() {
    global $conn;
    $sql = "SELECT * FROM tbl_usuarios";
    $result = mysqli_query($conn, $sql);
    $usuarios = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row;
    }
    return $usuarios;
}

function generarTablaUsuarios($usuarios) {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Acciones</th>
            </tr>";
    foreach ($usuarios as $usuario) {
        echo "<tr>
                <td>{$usuario['id_usuario']}</td>
                <td>{$usuario['username_usuario']}</td>
                <td>{$usuario['nombre_usuario']}</td>
                <td>{$usuario['apellidos_usuario']}</td>
                <td>
                    <button onclick='mostrarUsuario({$usuario['id_usuario']})'>Editar</button>
                    <button onclick='eliminarUsuario({$usuario['id_usuario']})'>Eliminar</button>
                </td>
            </tr>";
    }
    echo "</table>";
}

function obtenerUsuarioParaModal($idUsuario) {
    global $conn;
    $sql = "SELECT * FROM tbl_usuarios WHERE id_usuario = ?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($result);

    // HTML del formulario para el modal
    echo "<form id='formUsuario'>
            <input type='hidden' name='action' value='save'>
            <input type='hidden' name='id_usuario' value='{$usuario['id_usuario']}'>
            <label for='username_usuario'>Nombre de Usuario:</label>
            <input type='text' name='username_usuario' value='{$usuario['username_usuario']}' required>
            <br>
            <label for='nombre_usuario'>Nombre:</label>
            <input type='text' name='nombre_usuario' value='{$usuario['nombre_usuario']}' required>
            <br>
            <label for='apellidos_usuario'>Apellido:</label>
            <input type='text' name='apellidos_usuario' value='{$usuario['apellidos_usuario']}' required>
            <br>
            <button type='button' onclick='guardarUsuario()'>Guardar</button>
            <button type='button' onclick='cerrarModalUsuario()'>Cancelar</button>
        </form>";
}

function guardarActualizarUsuario($datosUsuario) {
    global $conn;
    $idUsuario = $datosUsuario['id_usuario'];
    $username = $datosUsuario['username_usuario'];
    $nombre = $datosUsuario['nombre_usuario'];
    $apellidos = $datosUsuario['apellidos_usuario'];

    if ($idUsuario == 0) {
        // Nuevo usuario
        $sql = "INSERT INTO tbl_usuarios (username_usuario, nombre_usuario, apellidos_usuario) VALUES (?, ?, ?)";
    } else {
        // Actualizar usuario existente
        $sql = "UPDATE tbl_usuarios SET username_usuario=?, nombre_usuario=?, apellidos_usuario=? WHERE id_usuario=?";
    }

    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    if ($idUsuario != 0) {
        mysqli_stmt_bind_param($stmt, "sssi", $username, $nombre, $apellidos, $idUsuario);
    } else {
        mysqli_stmt_bind_param($stmt, "sss", $username, $nombre, $apellidos);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
}

function eliminarUsuario($idUsuario) {
    global $conn;
    $sql = "DELETE FROM tbl_usuarios WHERE id_usuario = ?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
