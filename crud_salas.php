<?php
include_once("proc/conexion.php");

// Agregar Sala
if (isset($_POST['agregarSala'])) {
    $nombreSala = $_POST['nombreSala'];
    $tipoSala = $_POST['tipoSala'];

    // Insertar sala en la base de datos
    $sqlInsercionSala = "INSERT INTO tbl_salas (nombre_sala, tipo_sala) 
                         VALUES ('$nombreSala', '$tipoSala')";

    if ($conn->query($sqlInsercionSala) === TRUE) {
        // Redirigir con mensajes de éxito
        header("Location: adminindex.php?success=true&mensaje=Sala agregada con éxito");
        exit();
    } else {
        // Redirigir con mensajes de error
        header("Location: adminindex.php?error=true&mensajeError=Error al agregar la sala");
        exit();
    }
}

// Obtener lista de salas para mostrar en el CRUD
$resultSalas = $conn->query("SELECT * FROM tbl_salas");

// CRUD de Salas
echo "<h2>Añadir Salas</h2>";
echo "<form action='crud_salas.php' method='post'>";
echo "<label>Nombre de Sala:</label>";
echo "<input type='text' name='nombreSala' required>";
echo "<br>";
echo "<label>Tipo de Sala:</label>";
echo "<select name='tipoSala' required>";
echo "<option value='Comedor'>Comedor</option>";
echo "<option value='Terraza'>Terraza</option>";
echo "<option value='Sala Privada'>Sala Privada</option>";
echo "</select>";
echo "<br>";
echo "<button type='submit' name='agregarSala'>Agregar Sala</button>";
echo "</form>";
echo "<hr>";

if ($resultSalas->num_rows > 0) {
    echo "<h2>Crud de Salas</h2>";
    echo "<table border='1'>
    
             <tr>
                 <th>ID Sala</th>
                 <th>Nombre Sala</th>
                 <th>Tipo Sala</th>
                 <th>Acciones</th>
             </tr>";

    while ($rowSala = $resultSalas->fetch_assoc()) {
        echo "<tr>
                 <td>{$rowSala['id_sala']}</td>
                 <td>{$rowSala['nombre_sala']}</td>
                 <td>{$rowSala['tipo_sala']}</td>
                 <td>
                     <a href='#' data-accion='editarSala'
                        data-idSala='{$rowSala['id_sala']}'
                        data-nombreSala='{$rowSala['nombre_sala']}'
                        data-tipoSala='{$rowSala['tipo_sala']}'
                        onclick='mostrarFormularioEdicionSala(\"{$rowSala['id_sala']}\", \"{$rowSala['nombre_sala']}\", \"{$rowSala['tipo_sala']}\")'
                     >Editar</a>
                     
                     <form action='eliminar_sala.php' method='post'>
                         <input type='hidden' name='eliminarSala' value='true'>
                         <input type='hidden' name='idSala' value='{$rowSala['id_sala']}'>
                         <button type='submit'>Eliminar</button>
                     </form>
                 </td>
             </tr>";
    }

    echo "</table>";
} else {
    echo "No hay salas.";
}

// No cerrar la conexión aquí
?>

<!-- Script para mostrar SweetAlert -->
<script>
    function mostrarFormularioEdicionSala(idSala, nombreSala, tipoSala) {
        Swal.fire({
            title: 'Editar Sala',
            html: `
                <form id="formulario-edicion-sala" action="actualizar_sala.php" method="post">
                    <input type="hidden" name="idSala" value="${idSala}">
                    <label>Nombre de Sala:</label>
                    <input type="text" name="nombreSala" value="${nombreSala}" required>
                    <br>
                    <label>Tipo de Sala:</label>
                    <select name="tipoSala" required>
                        <option value="Comedor" ${(tipoSala === 'Comedor') ? 'selected' : ''}>Comedor</option>
                        <option value="Terraza" ${(tipoSala === 'Terraza') ? 'selected' : ''}>Terraza</option>
                        <option value="Sala Privada" ${(tipoSala === 'Sala Privada') ? 'selected' : ''}>Sala Privada</option>
                    </select>
                    <br>
                </form>
            `,
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Guardar Cambios',
            preConfirm: () => {
                document.getElementById('formulario-edicion-sala').submit();
            }
        });
    }
</script>
