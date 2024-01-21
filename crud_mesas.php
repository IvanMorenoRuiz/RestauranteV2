<?php
include_once("proc/conexion.php");

// Obtener lista de mesas para mostrar en el CRUD
$resultMesas = $conn->query("SELECT * FROM tbl_mesas");

// Función para obtener el nombre de la sala
function obtenerNombreSala($idSala, $conn)
{
    $sql = "SELECT nombre_sala FROM tbl_salas WHERE id_sala = '$idSala' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_sala'];
    } else {
        return 'Desconocida';
    }
}

// Agregar Mesa
if (isset($_POST['agregarMesa'])) {
    $nombreMesa = $_POST['nombreMesa'];
    $sillasMesa = $_POST['sillasMesa'];

    // Obtener id de sala desde el formulario
    $idTipoSala = $_POST['idTipoSala'];

    // Consultar la base de datos para obtener el id de la sala correspondiente al tipo seleccionado
    $sqlIdSala = "SELECT id_sala FROM tbl_salas WHERE tipo_sala = '$idTipoSala' LIMIT 1";
    $resultIdSala = $conn->query($sqlIdSala);

    if ($resultIdSala->num_rows > 0) {
        $rowIdSala = $resultIdSala->fetch_assoc();
        $idSalaMesa = $rowIdSala['id_sala'];

        // Insertar mesa en la base de datos
        $sqlInsercionMesa = "INSERT INTO tbl_mesas (nombre_mesa, estado_mesa, sillas_mesa, id_sala_mesa) 
                             VALUES ('$nombreMesa', 'Libre', '$sillasMesa', '$idSalaMesa')";

        if ($conn->query($sqlInsercionMesa) === TRUE) {
            // Redirigir con mensajes de éxito
            header("Location: adminindex.php?success=true&mensaje=Mesa agregada con éxito");
            exit();
        } else {
            // Redirigir con mensajes de error
            header("Location: adminindex.php?error=true&mensajeError=Error al agregar la mesa");
            exit();
        }
    } else {
        // Redirigir con mensajes de error si no se encuentra la sala
        header("Location: adminindex.php?error=true&mensajeError=Tipo de sala no válido");
        exit();
    }
}

// Obtener lista de salas para mostrar en el formulario de agregar mesa
$resultSalas = $conn->query("SELECT id_sala, nombre_sala FROM tbl_salas");

// CRUD de Mesas
echo "<h2>Añadir Mesas</h2>";
echo "<form action='crud_mesas.php' method='post'>";
echo "<label>Nombre de Mesa:</label>";
echo "<input type='text' name='nombreMesa' required>";
echo "<br>";
echo "<label>Tipo de Sala:</label>";
echo "<select name='idTipoSala' required>";

// Construir opciones del menú desplegable con resultados de la consulta
while ($rowSala = $resultSalas->fetch_assoc()) {
    echo "<option value='{$rowSala['id_sala']}'>{$rowSala['nombre_sala']}</option>";
}

echo "</select>";
echo "<br>";
echo "<label>Sillas de Mesa:</label>";
echo "<select name='sillasMesa' required>
        <option value='1'>1</option>
        <option value='2'>2</option>
        <option value='3'>3</option>
        <option value='4'>4</option>
        <option value='5'>5</option>
        <option value='6'>6</option>
        <option value='7'>7</option>
        <option value='8'>8</option>
        <option value='9'>9</option>
        <option value='10'>10</option>
      </select>";
echo "<br>";
echo "<button type='submit' name='agregarMesa'>Agregar Mesa</button>";
echo "</form>";
echo "<hr>";

if ($resultMesas->num_rows > 0) {
    echo "<h2>Crud de Mesas</h2>";
    echo "<table border='1'>
    
             <tr>
                 <th>ID Mesa</th>
                 <th>Nombre Mesa</th>
                 <th>Estado Mesa</th>
                 <th>Sillas Mesa</th>
                 <th>Nombre Sala</th>
                 <th>Acciones</th>
             </tr>";

    while ($rowMesa = $resultMesas->fetch_assoc()) {
        $nombreSala = obtenerNombreSala($rowMesa['id_sala_mesa'], $conn);
        echo "<tr>
                         <td>{$rowMesa['id_mesa']}</td>
                         <td>{$rowMesa['nombre_mesa']}</td>
                         <td>{$rowMesa['estado_mesa']}</td>
                         <td>{$rowMesa['sillas_mesa']}</td>
                         <td>{$nombreSala}</td>
                         <td>
                             <a href='#' data-accion='editarMesa'
                                data-idMesa='{$rowMesa['id_mesa']}'
                                data-nombreMesa='{$rowMesa['nombre_mesa']}'
                                data-sillasMesa='{$rowMesa['sillas_mesa']}'
                                data-idTipoSala='{$rowMesa['id_sala_mesa']}'
                                data-nombreSala='{$nombreSala}'
                                onclick='mostrarFormularioEdicionMesa(\"{$rowMesa['id_mesa']}\", \"{$rowMesa['nombre_mesa']}\", \"{$rowMesa['sillas_mesa']}\", \"{$rowMesa['id_sala_mesa']}\", \"{$nombreSala}\")'
                             >Editar</a>
                         
                             <form action='eliminar_mesa.php' method='post'>
                                 <input type='hidden' name='eliminarMesa' value='true'>
                                 <input type='hidden' name='idMesa' value='{$rowMesa['id_mesa']}'>
                                 <button type='submit'>Eliminar</button>
                             </form>
                         </td>
                     </tr>";
    }

    echo "</table>";
} else {
    echo "No hay mesas.";
}

// No cerrar la conexión aquí
?>

<!-- Script para mostrar SweetAlert -->
<script>
    function mostrarFormularioEdicionMesa(idMesa, nombreMesa, sillasMesa, idTipoSala) {
        // Realizar una petición AJAX para obtener la lista de salas
        fetch('obtener_salas.php')
            .then(response => response.json())
            .then(data => {
                // Crear opciones del menú desplegable
                const opcionesSala = data.map(sala => `<option value="${sala.id_sala}" ${(idTipoSala === sala.id_sala) ? 'selected' : ''}>${sala.nombre_sala}</option>`).join('');

                // Mostrar el formulario de edición con las opciones de sala
                Swal.fire({
                    title: 'Editar Mesa',
                    html: `
                        <form id="formulario-edicion" action="actualizar_mesa.php" method="post">
                            <input type="hidden" name="idMesa" value="${idMesa}">
                            <label>Nombre de Mesa:</label>
                            <input type="text" name="nombreMesa" value="${nombreMesa}" required>
                            <br>
                            <label>Sillas de Mesa:</label>
                            <input type="text" name="sillasMesa" value="${sillasMesa}" required>
                            <br>
                            <label>Tipo de Sala:</label>
                            <select name="idTipoSala" required>
                                ${opcionesSala}
                            </select>
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
            });
    }
</script>
