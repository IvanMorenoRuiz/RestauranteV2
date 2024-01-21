<?php
include_once("proc/conexion.php");

// Manejar la inserción de usuarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = $_POST['username'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash de la contraseña
    $tipoUsuario = $_POST['tipoUsuario'];

    // Verificar si ya existe un usuario con el mismo username
    $verificarUsuario = $conn->query("SELECT id_usuario FROM tbl_usuarios WHERE username_usuario = '$username'");

    if ($verificarUsuario->num_rows > 0) {
        // Usuario con el mismo username ya existe
        $mensajeError = "Ya existe un usuario con el nombre de usuario $username.";
        header("Location: adminindex.php?error=true&mensajeError=" . urlencode($mensajeError));
        exit();
    }

    // Insertar el usuario en tbl_usuarios
    $sqlUsuario = "INSERT INTO tbl_usuarios (username_usuario, nombre_usuario, apellidos_usuario, pwd_usuario, tipo_usuario)
            VALUES ('$username', '$nombre', '$apellidos', '$password', '$tipoUsuario')";

    if ($conn->query($sqlUsuario) === TRUE) {
        $idUsuarioInsertado = $conn->insert_id;

        // Si el tipo de usuario es "Camarero", también insertar en tbl_camareros
        if ($tipoUsuario === "Camarero") {
            $sqlCamarero = "INSERT INTO tbl_camareros (id_camarero, username_camarero, nombre_camarero, apellidos_camarero, pwd_camarero)
                VALUES ('$idUsuarioInsertado', '$username', '$nombre', '$apellidos', '$password')";

            if ($conn->query($sqlCamarero) !== TRUE) {
                // Manejar errores en la inserción en tbl_camareros
                echo "Error al agregar camarero: " . $conn->error;
            }
        }

        // Usuario agregado con éxito
        $mensaje = "El usuario $username ha sido contratado como $tipoUsuario.";
        header("Location: adminindex.php?success=true&mensaje=" . urlencode($mensaje));
        exit();
    } else {
        echo "Error al agregar usuario: " . $conn->error;
    }
}

// Mostrar la lista de usuarios
$result = $conn->query("SELECT * FROM tbl_usuarios");

if ($result->num_rows > 0) {
    echo "<table border='1'>
             <tr>
                 <th>Username</th>
                 <th>Tipo Usuario</th>
                 <th>Acciones</th>
             </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                 <td>{$row['username_usuario']}</td>
                 <td>{$row['tipo_usuario']}</td>
                 <td>
                     <a href='modificar_usuario.php?id={$row['id_usuario']}'>Modificar</a>
                     <form action='eliminar_usuario.php' method='post'>
                         <input type='hidden' name='id' value='{$row['id_usuario']}'>
                         <button type='submit'>Eliminar</button>
                     </form>
                 </td>
             </tr>";
    }

    echo "</table>";
} else {
    echo "No hay usuarios.";
}


// Cerrar la conexión a la base de datos
$conn->close();
?>
