<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administración</title>
    <!-- Incluir SweetAlert2 mediante CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Incluir jQuery mediante CDN -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffe799;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 1em;
        }

        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .box {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 10px;
            flex-grow: 1;
            box-sizing: border-box;
        }
        h1 {
    text-align: center;
}

        h2 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        label {
            width: 45%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #ffe799;
        }
    </style>
</head>
<body>
<div class="container">

 <div class="box">
    <!-- Formulario de inserción de usuarios -->
    <h1>ADMINISTRADOR DE OASIS23</h1>
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
    </div>
    
    <div class="box">
    <?php include("crud_salas.php"); ?>
    </div>


    <div class="box">
        <!-- CRUD de Usuarios -->
    <?php include("crud_mesas.php"); ?>
    </div>




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
<script>
function mostrarFormularioEdicion(id, username, nombre, apellidos, tipoUsuario, pwdUsuario, imagenUsuario) {
    Swal.fire({
        title: 'Editar Usuario',
        html: `
            <form id="formulario-edicion" action="actualizar_usuario.php" method="post">
                <input type="hidden" name="id" value="${id}">
                <label>Username:</label>
                <input type="text" name="username" value="${username}" required>
                <br>
                <label>Nombre:</label>
                <input type="text" name="nombre" value="${nombre}" required>
                <br>
                <label>Apellidos:</label>
                <input type="text" name="apellidos" value="${apellidos}" required>
                <br>
                <label>Contraseña:</label>
                <input type="password" name="password" value="${pwdUsuario}" required>
                <br>
                <label>Tipo de Usuario:</label>
                <select name="tipoUsuario" required>
                    <option value="Administracion" ${(tipoUsuario === 'Administracion') ? 'selected' : ''}>Administración</option>
                    <option value="Mantenimiento" ${(tipoUsuario === 'Mantenimiento') ? 'selected' : ''}>Mantenimiento</option>
                    <option value="Camarero" ${(tipoUsuario === 'Camarero') ? 'selected' : ''}>Camarero</option>
                </select>
                <br>
                <label>Dirección de Imagen:</label>
                <input type="text" name="imagen" value="${imagenUsuario}">
                <br>
            </form>
        `,
        showCancelButton: true,
        showCloseButton: true, 
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar Cambios',
        preConfirm: () => {
            document.getElementById('formulario-edicion').submit();
        }
    });
}



$(document).off('click', 'a[data-accion="editar"]').on('click', 'a[data-accion="editar"]', function() {
    var idUsuario = $(this).data('id');
    var username = $(this).data('username');
    var nombre = $(this).data('nombre');
    var apellidos = $(this).data('apellidos');
    var tipoUsuario = $(this).data('tipo-usuario');
    var password = $(this).data('password');
    var imagen = $(this).data('imagen');

    // Imprime el valor de imagen en la consola
    console.log(imagen);

    // Llama a la función para mostrar el formulario de edición
    mostrarFormularioEdicion(idUsuario, username, nombre, apellidos, tipoUsuario, password, imagen);
});

</script>




</body>
</html>
