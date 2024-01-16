<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    include("conexion.php"); 

    $username = $_POST["username_camarero"];
    $nombre = $_POST["nombre_camarero"];
    $apellidos = $_POST["apellidos_camarero"];
    $password = password_hash($_POST["pwd_camarero"], PASSWORD_DEFAULT); 

    $check_query = "SELECT * FROM tbl_camareros WHERE username_camarero = '$username'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        mysqli_close($conn);
        header("Location: ../addcamarero.php?error=ElusernameYaExiste");
        exit();
    }
        $insert_query = "INSERT INTO tbl_camareros (username_camarero, nombre_camarero, apellidos_camarero, pwd_camarero) 
                        VALUES ('$username', '$nombre', '$apellidos', '$password')";

        if (mysqli_query($conn, $insert_query)) {
            mysqli_close($conn);
            header("Location: ../camareros.php");
            exit();
    } else {
        echo "Error al registrar: " . mysqli_error($conn);
    }
    
    mysqli_close($conn);
} else {
    header("Location: ../login.php");
    exit();
}
?>
