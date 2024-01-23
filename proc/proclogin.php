<?php
session_destroy();
session_start();
include 'conexion.php';


if (!isset($_POST['login'])) {
    header('Location: ../login.php');
} else {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    // Consulta SQL para seleccionar el nombre de usuario, la contraseña hash y el tipo de usuario de la base de datos
    $sql = "SELECT id_usuario, username_usuario, pwd_usuario, tipo_usuario
            FROM tbl_usuarios
            WHERE username_usuario = ?";
    
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $pwd_encript = $row['pwd_usuario'];

        // Verificar la contraseña utilizando password_verify
        if (password_verify($pwd, $pwd_encript)) {
            $_SESSION['user'] = $row['id_usuario'];
            $_SESSION['username'] = $row['username_usuario'];

            // Redirigir según el tipo de usuario
            switch ($row['tipo_usuario']) {
                case 'Administracion':
                    header('Location: ../adminindex.php');
                    break;
                case 'Mantenimiento':
                    header('Location: ../manteindex.php');
                    break;
                case 'Camarero':
                    header('Location: ../index.php');
                    break;
                default:
                    header('Location: ../login.php?fallo=0');
                    break;
            }
        } else {
            header('Location: ../login.php?fallo=0');
        }
    } else {
        header('Location: ../login.php?fallo=0');
    }
}
?>
