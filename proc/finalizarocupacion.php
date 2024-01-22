<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['mesa']) || !isset($_GET['tipo'])) {
    header('Location: ../index.php');
    exit();
}

$id_camarero = $_SESSION['user'];
$id_mesa = $_GET['mesa'];
$tipo_sala = $_GET['tipo'];

include_once('conexion.php');

try {
    $stmt = mysqli_stmt_init($conn);
    mysqli_autocommit($conn, false);
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

    // Actualizar el estado de la mesa a "Libre"
    $estado_nuevo = 'Libre';
    $sql = 'UPDATE tbl_mesas SET estado_mesa = ? WHERE id_mesa = ?;';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $estado_nuevo, $id_mesa);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error al actualizar el estado de la mesa: ' . mysqli_error($conn));
    }

    // Actualizar la hora final de la reserva
    $horaFinal = date('Y-m-d H:i:s');
    $sql2 = 'UPDATE tbl_reservas SET hora_final_reserva = ? 
             WHERE id_mesa_reserva = ? AND hora_final_reserva IS NULL;';
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'si', $horaFinal, $id_mesa);

    if (!mysqli_stmt_execute($stmt2)) {
        throw new Exception('Error al actualizar la hora final de la reserva: ' . mysqli_error($conn));
    }

    mysqli_commit($conn);

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);

    header('Location: ../seleccionarsitio.php');
    exit();
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo 'Error: ' . $e->getMessage();
    die();
}
?>
