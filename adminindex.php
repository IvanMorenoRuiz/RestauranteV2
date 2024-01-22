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
<link rel="stylesheet" type="text/css" href="./css/adminindex.css">
<script src="validaciones.js" defer></script>
</head>
<body>
<div class="container">

 <div class="box">
    <!-- Formulario de inserción de usuarios -->
    <h1>ADMINISTRADOR DE OASIS23</h1>
    <button id="btnEstadisticas">Estadísticas</button>

    <h2>Añadir Usuario</h2>
    <form name="myForm" action="backend.php" method="post" onsubmit="return validarFormulario()">
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



    <script>
    $(document).ready(function() {
        $("#btnEstadisticas").click(function() {
            // Realizar la petición AJAX para obtener las estadísticas
            $.ajax({
                url: 'obtener_estadisticas.php', // Ruta del archivo PHP que procesará la solicitud
                type: 'GET',
                success: function(data) {
                    // Mostrar las estadísticas en un Sweet Alert
                    Swal.fire({
                        title: 'Estadísticas de Reservas',
                        html: data,
                        icon: 'info',
                        confirmButtonText: 'Cerrar'
                    });
                },
                error: function() {
                    // Manejar errores si la petición AJAX falla
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al obtener estadísticas',
                        icon: 'error',
                        confirmButtonText: 'Cerrar'
                    });
                }
            });
        });
    });
</script>

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
function validarFormularioEdicion() {
    // Validar nombre
    var nombre = document.forms["formulario-edicion"]["nombre"].value;
    if (!/^[a-zA-Z]+$/.test(nombre)) {
        Swal.showValidationMessage("El nombre solo debe contener letras.");
        return false;
    }

    // Validar apellidos
    var apellidos = document.forms["formulario-edicion"]["apellidos"].value;
    if (!/^[a-zA-Z ]+$/.test(apellidos)) {
        Swal.showValidationMessage("Los apellidos solo deben contener letras y espacios.");
        return false;
    }

    // Validar contraseña
    var contraseña = document.forms["formulario-edicion"]["password"].value;
    if (contraseña.length < 5) {
        Swal.showValidationMessage("La contraseña debe tener al menos 5 caracteres.");
        return false;
    }

    return true;
}

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
            // Llama a la función para validar el formulario antes de enviar
            if (validarFormularioEdicion()) {
                document.getElementById('formulario-edicion').submit();
            }
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
