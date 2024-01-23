<?php
include_once("proc/conexion.php");

// Obtener lista de reservas para mostrar en el CRUD
$resultReservas = $conn->query("SELECT * FROM tbl_reservas");

// Función para obtener el nombre del camarero
function obtenerNombreCamarero($idCamarero, $conn)
{
    $sql = "SELECT nombre_camarero FROM tbl_camareros WHERE id_camarero = '$idCamarero' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_camarero'];
    } else {
        return 'Desconocido';
    }
}

// Función para obtener el nombre de la mesa
function obtenerNombreMesa($idMesa, $conn)
{
    $sql = "SELECT nombre_mesa FROM tbl_mesas WHERE id_mesa = '$idMesa' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['nombre_mesa'];
    } else {
        return 'Desconocida';
    }
}

// CRUD de Reservas
if ($resultReservas->num_rows > 0) {
    echo "<h2>Crud de Reservas</h2>";
    echo "<table border='1'>
             <tr>
                 <th>ID Reserva</th>
                 <th>Hora Inicio</th>
                 <th>Hora Final</th>
                 <th>Camarero</th>
                 <th>Mesa</th>
                 <th>Acciones</th>
             </tr>";

    while ($rowReserva = $resultReservas->fetch_assoc()) {
        $nombreCamarero = obtenerNombreCamarero($rowReserva['id_camarero_reserva'], $conn);
        $nombreMesa = obtenerNombreMesa($rowReserva['id_mesa_reserva'], $conn);

        echo "<tr>
                 <td>{$rowReserva['id_reserva']}</td>
                 <td>{$rowReserva['hora_inicio_reserva']}</td>
                 <td>{$rowReserva['hora_final_reserva']}</td>
                 <td>{$nombreCamarero}</td>
                 <td>{$nombreMesa}</td>
                 <td>
                     <a href='#' data-accion='editarReserva'
                        data-idReserva='{$rowReserva['id_reserva']}'
                        data-horaInicio='{$rowReserva['hora_inicio_reserva']}'
                        data-horaFinal='{$rowReserva['hora_final_reserva']}'
                        data-idCamarero='{$rowReserva['id_camarero_reserva']}'
                        data-idMesa='{$rowReserva['id_mesa_reserva']}'
                        onclick='mostrarFormularioEdicionReserva(\"{$rowReserva['id_reserva']}\", \"{$rowReserva['hora_inicio_reserva']}\", \"{$rowReserva['hora_final_reserva']}\", \"{$rowReserva['id_camarero_reserva']}\", \"{$rowReserva['id_mesa_reserva']}\")'
                     >Editar</a>
                     
                     <form id='eliminarReservaForm_{$rowReserva['id_reserva']}' action='eliminar_reserva.php' method='post'>
                         <input type='hidden' name='eliminarReserva' value='true'>
                         <input type='hidden' name='idReserva' value='{$rowReserva['id_reserva']}'>
                         <button type='button' onclick='confirmarEliminarReserva(\"{$rowReserva['id_reserva']}\")'>Eliminar</button>
                     </form>
                 </td>
             </tr>";
    }

    echo "</table>";
} else {
    echo "No hay reservas.";
}

// No cerrar la conexión aquí
?>

<!-- Script para mostrar SweetAlert -->
<script>

    function confirmarEliminarReserva(idReserva) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará la reserva. ¿Quieres continuar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si se confirma, enviar el formulario de eliminación
            document.getElementById(`eliminarReservaForm_${idReserva}`).submit();
        }
    });
}

function mostrarFormularioEdicionReserva(idReserva, horaInicio, horaFinal, idCamarero, idMesa) {
    // Obtener la fecha actual en formato YYYY-MM-DD
    var today = new Date().toISOString().split('T')[0];

    // Obtener lista de camareros y mesas
    var resultCamareros = <?php echo json_encode($conn->query("SELECT id_camarero, nombre_camarero FROM tbl_camareros")->fetch_all(MYSQLI_ASSOC)); ?>;
    var resultMesas = <?php echo json_encode($conn->query("SELECT id_mesa, nombre_mesa FROM tbl_mesas")->fetch_all(MYSQLI_ASSOC)); ?>;
    
    // Construir opciones para el select de camareros
    var optionsCamareros = '';
    resultCamareros.forEach(function(rowCamarero) {
        var selectedCamarero = (rowCamarero.id_camarero == idCamarero) ? 'selected' : '';
        optionsCamareros += `<option value='${rowCamarero.id_camarero}' ${selectedCamarero}>${rowCamarero.nombre_camarero}</option>`;
    });

    // Construir opciones para el select de mesas
    var optionsMesas = '';
    resultMesas.forEach(function(rowMesa) {
        var selectedMesa = (rowMesa.id_mesa == idMesa) ? 'selected' : '';
        optionsMesas += `<option value='${rowMesa.id_mesa}' ${selectedMesa}>${rowMesa.nombre_mesa}</option>`;
    });

    // Mostrar el formulario de edición con los selects de camareros y mesas
    Swal.fire({
        title: 'Editar Reserva',
        html: `
            <form id="formulario-edicion-reserva" action="actualizar_reserva.php" method="post">
                <input type="hidden" name="idReserva" value="${idReserva}">
                <label>Hora Inicio:</label>
                <input type="datetime-local" name="horaInicio" value="${horaInicio}" min="${today}" required>
                <br>
                <label>Hora Final:</label>
                <input type="datetime-local" name="horaFinal" value="${horaFinal}" min="${today}" required>
                <br>
                <label>Camarero:</label>
                <select name="idCamarero" required>
                    ${optionsCamareros}
                </select>
                <br>
                <label>Mesa:</label>
                <select name="idMesa" required>
                    ${optionsMesas}
                </select>
                <br>
            </form>
        `,
        showCancelButton: true,
        showCloseButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Guardar Cambios',
        preConfirm: () => {
            // Validar las fechas
            const horaInicioInput = document.querySelector('input[name="horaInicio"]');
            const horaFinalInput = document.querySelector('input[name="horaFinal"]');
            const horaInicioValue = new Date(horaInicioInput.value).getTime();
            const horaFinalValue = new Date(horaFinalInput.value).getTime();

            // Calcular la diferencia en milisegundos
            const diferenciaEnMillis = horaFinalValue - horaInicioValue;

            // Calcular la diferencia en horas
            const diferenciaEnHoras = diferenciaEnMillis / (1000 * 60 * 60);

            if (isNaN(horaInicioValue) || isNaN(horaFinalValue) || horaInicioValue >= horaFinalValue || diferenciaEnHoras > 5) {
                Swal.showValidationMessage('Las fechas de inicio y final deben ser válidas, la hora de inicio debe ser anterior a la hora final, y la diferencia no debe ser superior a 5 horas');
                return false;
            }

            // Enviar el formulario si la validación es exitosa
            document.getElementById('formulario-edicion-reserva').submit();
        }
    });
}


</script>
