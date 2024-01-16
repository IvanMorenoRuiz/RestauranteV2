<!-- registro.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/registro.less">
    <title>Registro</title>
</head>
<body>
    <div class="registro">
        <h1>Registro en OASIS 23</h1>
        <form action="./proc/procregistro.php" method="post">
            <!-- Agrega aquí los campos necesarios para el registro -->
            <label for="username">Usuario:</label>
            <input type="text" name="username" id="username" required>
            <br>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" required>
            <br>
            <label for="apellidos">Apellidos:</label>
            <input type="text" name="apellidos" id="apellidos" required>
            <br>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <!-- Agrega más campos según tu esquema de base de datos -->
            <button type="submit" class="btn btn-primary btn-block btn-large" name="registro" value="Registro">Registrarse</button>
        </form>
    </div>
</body>
</html>
