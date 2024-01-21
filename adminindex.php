<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <!-- Incluir SweetAlert2 mediante CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Formulario de inserción de usuarios -->
    <h2>Añadir Usuario</h2>
    <form action="backend.php" method="post">
        <!-- Campos del formulario -->
        <label>Username:</label>
        <input type="text" name="username" required>
        <br>
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <br>
        <label>Apellidos:</label>
        <input type="text" name="apellidos" required>
        <br>
        <label>Contraseña:</label>
        <input type="password" name="password" required>
        <br>
        <label>Tipo de Usuario:</label>
        <select name="tipoUsuario" required>
            <option value="Administracion">Administración</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Camarero">Camarero</option>
        </select>
        <br>
        <button type="submit">Agregar Usuario</button>
    </form>

    <hr>

    <!-- CRUD de Usuarios -->
    <h2>CRUD de Usuarios</h2>
    <?php include("backend.php"); ?>

<!-- Script para mostrar SweetAlert -->
<script>
    <?php
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $mensaje = urldecode($_GET['mensaje']);
        echo "Swal.fire({
            icon: 'success',
            title: 'Usuario Agregado',
            text: '$mensaje'
        });";
    }

    if (isset($_GET['error']) && $_GET['error'] == 'true') {
        $mensajeError = urldecode($_GET['mensajeError']);
        echo "Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '$mensajeError'
        });";
    }
    
    // Script para mostrar SweetAlert después de eliminar un usuario
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $mensaje = urldecode($_GET['mensaje']);
    echo "Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: '$mensaje'
    });";
}

    ?>
</script>


</body>
</html>
