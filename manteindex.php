<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de OASIS23</title>
    <link rel="stylesheet" type="text/css" href="./css/mantenimiento.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

<!-- Contenido de la página de mantenimiento -->

<div class="container">
    <h2>Mantenimiento de OASIS23</h2>
    <form action="login.php" method="post">
        <button type="submit" name="cerrar_sesion" class="cerrar-sesion">Cerrar Sesión</button>
    </form>
    <?php
    include_once("./proc/conexion.php");

    // Obtener todas las mesas (habilitadas y deshabilitadas)
    $sql = "SELECT id_mesa, nombre_mesa, motivo_deshabilitacion, sillas_mesa, estado_mesa FROM tbl_mesas";
    $resultado = $conn->query($sql);

    $mesasDeshabilitadas = false;

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $mesaId = $fila["id_mesa"];
            $nombreMesa = $fila["nombre_mesa"];
            $motivoDeshabilitacion = $fila["motivo_deshabilitacion"];
            $numSillas = $fila["sillas_mesa"];
            $estadoMesa = $fila["estado_mesa"];

            if ($estadoMesa == 'Deshabilitada') {
                $mesasDeshabilitadas = true;
    ?>
                <div class="mesa-deshabilitada">
                    <p><strong>Mesa:</strong> <?php echo $nombreMesa; ?></p>
                    <p><strong>Motivo de Deshabilitación:</strong> <?php echo $motivoDeshabilitacion; ?></p>
                    <p><strong>Número de Sillas:</strong> <?php echo $numSillas; ?></p>
                    <form action="./proc/editar_mesa.php" method="post">
                        <input type="hidden" name="mesa_id" value="<?php echo $mesaId; ?>">
                        <label for="nuevas_sillas">Editar Número de Sillas:</label>
                        <input type="number" name="nuevas_sillas" id="nuevas_sillas" value="<?php echo $numSillas; ?>">
                        <button type="submit" onclick="mostrarAlerta('Mesa editada correctamente.');">Guardar</button>
                    </form>
                    <form action="./proc/habilitar_mesas.php" method="post" id="form-habilitar-mesa-<?php echo $mesaId; ?>">
                        <input type="hidden" name="mesa_id" value="<?php echo $mesaId; ?>">
                        <button type="button" name="habilitar_mesa" onclick="habilitarMesa(<?php echo $mesaId; ?>);">Habilitar Mesa</button>
                    </form>
                    <form action="./proc/borrar_mesa.php" method="post" id="form-borrar-mesa">
                        <input type="hidden" name="mesa_id" value="<?php echo $mesaId; ?>">
                        <button type="button" name="borrar_mesa" onclick="confirmarBorrar();">Borrar Mesa</button>
                    </form>
                </div>
    <?php
            } else {
                // Lógica para mostrar mesas habilitadas si es necesario
            }
        }
    } else {
        echo "<p>No hay mesas disponibles.</p>";
    }

    if (!$mesasDeshabilitadas) {
        echo "<p>Todas las mesas están en orden.</p>";
    }

    $conn->close();
    ?>

<script>
    function mostrarAlerta(mensaje) {
        Swal.fire({
            title: 'Éxito',
            text: mensaje,
            icon: 'success',
        });
    }

    function habilitarMesa(mesaId) {
        Swal.fire({
            title: '¿Quieres habilitar la mesa?',
            text: '¿Has solucionado el problema de la mesa?',
            icon: 'warning',
            showConfirmButton: true,
            confirmButtonText: 'Sí',
            showCancelButton: true,
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar el formulario manualmente
                document.getElementById('form-habilitar-mesa-' + mesaId).submit();
            }
        });
    }

    function confirmarBorrar() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: '¿Quieres eliminar la mesa?.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si se confirma, enviar el formulario
                document.getElementById('form-borrar-mesa').submit();
            }
        });
    }

    // Refrescar la página cada 5 segundos
    setTimeout(function() {
        location.reload();
    }, 5000);
</script>
</div>

</body>
</html>
