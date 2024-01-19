<?php
session_start();
include 'conexion.php';

if (!isset($_POST['login'])) {
    header('Location: ../login.php');
} else {
    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

    // Consulta SQL para seleccionar el nombre de usuario, la contraseña hash y el tipo de usuario de la base de datos
    $sql = "SELECT id_camarero, username_camarero, pwd_camarero FROM tbl_camareros WHERE username_camarero = ?";
    
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_bind_param($stmt, "s", $user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $pwd2_encript = $row['pwd_camarero'];
        // Verificar la contraseña utilizando password_verify
        if (password_verify($pwd, $pwd2_encript)) {
            $_SESSION['user'] = $row['id_camarero'];
            $_SESSION['username'] = $row['username_camarero'];

            // Verificar si el nombre de usuario es "administrador" y redirigir a adminindex.php
            if ($user === 'administrador') {
                header('Location: ../adminindex.php');
            } else {
                header('Location: ../index.php');
            }
        } else {
            header('Location: ../login.php?fallo=0');
        }
    } else {
        header('Location: ../login.php?fallo=0');
    }
}
?>
