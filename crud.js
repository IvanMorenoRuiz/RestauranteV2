// crud.js

$(document).ready(function() {
    cargarUsuarios();

    // Botón para mostrar formulario de nuevo usuario
    $("#btnNuevo").click(function() {
        mostrarFormNuevoUsuario();
    });

    // Función para cargar usuarios
    function cargarUsuarios() {
        $.ajax({
            url: "crud_ajax.php",
            type: "GET",
            data: { action: "read" },
            success: function(response) {
                $("#tablaUsuarios").html(response);
            }
        });
    }

    // Función para mostrar el formulario de nuevo usuario
    function mostrarFormNuevoUsuario() {
        $("#formNuevoUsuario").show();
    }

    // Función para cerrar el formulario de nuevo usuario
    function cerrarFormNuevoUsuario() {
        $("#formNuevoUsuario").hide();
    }
    
    // Evento click para el botón "Guardar" dentro del formulario
    $("#formNuevoUsuario").on("click", "button[type='button'][onclick='guardarUsuario()']", function() {
        guardarUsuario();
    });

    function guardarUsuario() {
        console.log("Botón Nuevo clickeado");
        // Obtener datos del formulario
        var datosUsuario = $("#formUsuario").serialize();
    
        $.ajax({
            url: "crud_ajax.php",
            type: "POST",
            data: datosUsuario,
            success: function(response) {
                if (response === "success") {
                    cargarUsuarios();
                    cerrarFormNuevoUsuario();  // Corregido aquí
                } else {
                    alert("Error al guardar usuario");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
            }
        });
    }

    // Función para eliminar usuario
    function eliminarUsuario(idUsuario) {
        $.ajax({
            url: "crud_ajax.php",
            type: "POST",
            data: { action: "delete", id: idUsuario },
            success: function(response) {
                if (response == "success") {
                    cargarUsuarios();
                } else {
                    alert("Error al eliminar usuario");
                }
            }
        });
    }

    // Evento para cerrar el formulario al hacer clic fuera de él
    $(document).mouseup(function(e) {
        var container = $("#formNuevoUsuario");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            cerrarFormNuevoUsuario();
        }
    });
    
    // Asegúrate de que la función cerrarModalUsuario() esté definida correctamente
    function cerrarModalUsuario() {
        $("#modalUsuario").hide();
    }
});
