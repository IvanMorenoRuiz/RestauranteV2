<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos (asegúrate de tener la conexión configurada)
    include("conexion.php"); // Reemplaza "conexion.php" con el archivo que contiene tu conexión a la base de datos

    // Recoger datos del formulario
    $username = $_POST["username"];
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hashear la contraseña antes de almacenarla

    // Insertar datos en la tabla de camareros
    $query = "INSERT INTO tbl_camareros (username_camarero, nombre_camarero, apellidos_camarero, pwd_camarero) 
              VALUES ('$username', '$nombre', '$apellidos', '$password')";

    // Ejecutar la consulta
    if (mysqli_query($conn, $query)) {
        echo "Registro exitoso. ¡Ahora puedes iniciar sesión!";
    } else {
        echo "Error al registrar: " . mysqli_error($conn);
    }

    // Cerrar la conexión
    mysqli_close($conn);
} else {
    // Si alguien intenta acceder directamente a procregistro.php sin enviar el formulario, redirigirlos al formulario de registro
    header("Location: ../login.php");
    exit();
}
?>
