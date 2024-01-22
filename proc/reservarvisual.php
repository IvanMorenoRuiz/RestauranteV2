<?php
session_start();

if (!isset($_SESSION['user']) || !isset($_GET['mesa']) || !isset($_GET['estado'])) {
    header('Location: ../index.php');
    exit();
}

$id_camarero = $_SESSION['user'];
$id_mesa = $_GET['mesa'];
$estado_mesa = $_GET['estado'];

include_once('conexion.php');

try {
    $stmt = mysqli_stmt_init($conn);
    mysqli_autocommit($conn, false);
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

    // Actualizar el estado de la mesa a "Ocupada"
    $estado_nuevo = 'Ocupada';
    $sql = 'UPDATE tbl_mesas SET estado_mesa = ? WHERE id_mesa = ?;';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $estado_nuevo, $id_mesa);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Error al actualizar el estado de la mesa: ' . mysqli_error($conn));
    }

    // Insertar nueva reserva
    $horaInicio = date('Y-m-d H:i:s');
    $sql2 = 'INSERT INTO tbl_reservas (hora_inicio_reserva, id_camarero_reserva, id_mesa_reserva) VALUES (?, ?, ?);';
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, 'sii', $horaInicio, $id_camarero, $id_mesa);

    if (!mysqli_stmt_execute($stmt2)) {
        throw new Exception('Error al insertar la reserva: ' . mysqli_error($conn));
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
