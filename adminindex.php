<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Usuarios</title>
    <link rel="stylesheet" type="text/css" href="./css/admsin.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="crud.js"></script>
</head>
<body>
    <div id="usuarios-container">
        <h2>Usuarios</h2>
        <button id="btnNuevo">Nuevo Usuario</button>
        <div id="tablaUsuarios"></div>
    </div>
    <div id="modalUsuario" style="display: none;">
        <!-- Contenido del modal para crear/editar usuarios -->
    </div>

    <!-- Nuevo formulario para añadir usuarios -->
    <div id="formNuevoUsuario" style="display: none;">
        <h3>Añadir Nuevo Usuario</h3>
        <form id="formUsuario" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
            
            <label for="username_usuario">Nombre de Usuario:</label>
            <input type="text" name="username_usuario" required>
            
            <label for="nombre_usuario">Nombre:</label>
            <input type="text" name="nombre_usuario" required>
            
            <label for="apellidos_usuario">Apellido:</label>
            <input type="text" name="apellidos_usuario" required>
            
            <label for="pwd_usuario">Contraseña:</label>
            <input type="password" name="pwd_usuario" required>
            
            <label for="imagen_usuario">Imagen (URL):</label>
            <input type="text" name="imagen_usuario" required>
            
            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select name="tipo_usuario" required>
                <option value="Administracion">Administración</option>
                <option value="Mantenimiento">Mantenimiento</option>
                <option value="Camarero">Camarero</option>
            </select>
            <script>
            // Con esto:
$("#formNuevoUsuario").on("click", "button[type='button'][onclick='guardarUsuario()']", function() {
    guardarUsuario();
});
</script>
            <button type="button" onclick="cerrarFormNuevoUsuario()">Cancelar</button>
        </form>
    </div>
</div>
</body>
</html>
