<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Camarero</title>
    <script>
        function validarFormulario() {
            var username = document.getElementById('username_camarero').value;
            var nombre = document.getElementById('nombre_camarero').value;
            var apellidos = document.getElementById('apellidos_camarero').value;
            var password = document.getElementById('pwd_camarero').value;

            if (username.trim() === '') {
                alert('Por favor, ingrese un nombre de usuario.');
                return false;
            }

            
            if (nombre.trim() === '' || apellidos.trim() === '' || nombre.length < 3 || apellidos.length < 3) {
                alert('El nombre y apellidos son obligatorios y deben tener al menos 3 caracteres.');
                return false;
            }

            
            if (!/^[a-zA-Z]+$/.test(nombre) || !/^[a-zA-Z]+$/.test(apellidos)) {
                alert('El nombre y apellidos deben contener solo letras.');
                return false;
            }

            if (password.length < 6 || !/\d/.test(password)) {
                alert('La contraseña debe tener al menos 6 caracteres, incluyendo al menos un número.');
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <h2>Añadir Camarero</h2>
    <form action="./proc/procaddcamarero.php" method="post" onsubmit="return validarFormulario()">
        <label for="username_camarero">Usuario</label>
        <input type="text" name="username_camarero" id="username_camarero">
        <br>
        <label for="nombre_camarero">Nombre</label>
        <input type="text" name="nombre_camarero" id="nombre_camarero">
        <br>
        <label for="apellidos_camarero">Apellidos</label>
        <input type="text" name="apellidos_camarero" id="apellidos_camarero">
        <br>
        <label for="pwd_camarero">Contraseña</label>
        <input type="password" name="pwd_camarero" id="pwd_camarero">
        <br>
        <input type="submit" value="Añadir Camarero">
    </form>
</body>
</html>






























