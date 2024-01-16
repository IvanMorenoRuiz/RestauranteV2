<?php 

session_start();

if (!isset($_SESSION["user"]) || !isset($_GET["mesa"]) || !isset($_GET["estado"])) {
    header("Location: ../index.php");
}

$id_camarero = $_SESSION["user"];
$id_mesa = $_GET["mesa"];
$estado_mesa = $_GET["estado"];


include_once("conexion.php");

try {
    $stmt= mysqli_stmt_init($conn);
    mysqli_autocommit($conn,false);
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

    $sql = 'UPDATE tbl_mesas SET estado_mesa = ? WHERE id_mesa = ?;';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $estado_mesa, $id_mesa);
    mysqli_stmt_execute($stmt);

    if($estado_mesa == "Ocupada") {
        $horaInicio = date('Y-m-d\TH:i:s');

        $sql2 = 'INSERT INTO tbl_reservas (hora_inicio_reserva, hora_final_reserva, id_camarero_reserva, id_mesa_reserva)
        VALUES (?, NULL, ?, ?);';
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "sii", $horaInicio, $id_camarero, $id_mesa);
        mysqli_stmt_execute($stmt2);

    } else {
        $horaFinal = date('Y-m-d\TH:i:s');

        $sql2 = 'UPDATE tbl_reservas INNER JOIN tbl_mesas ON id_mesa = id_mesa_reserva 
        SET hora_final_reserva = ? WHERE id_mesa = ? AND estado_mesa = "Libre";';
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "si", $horaFinal, $id_mesa);
        mysqli_stmt_execute($stmt2);
    }
    

    mysqli_commit($conn);
    
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);

    header("Location: ../index.php");

} catch (mysqli_sql_exception $e) {
    mysqli_rollback($conn);
    echo $e->getMessage();
    die();
}


?>