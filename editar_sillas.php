<?php

if (isset($_GET['mesaId']) && isset($_GET['nuevaCantidad'])) {
    $mesaId = $_GET['mesaId'];
    $nuevaCantidad = $_GET['nuevaCantidad'];

    include_once("proc/conexion.php");


    $sqlUpdate = 'UPDATE tbl_mesas SET sillas_mesa = ? WHERE id_mesa = ?';
    $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "si", $nuevaCantidad, $mesaId);
    
    if (mysqli_stmt_execute($stmtUpdate)) {
        mysqli_stmt_close($stmtUpdate);
        mysqli_close($conn);


        header("Location: index.php");
        exit();
    } else {

        echo "Error al actualizar la cantidad de sillas.";
    }
} else {
    header("Location: index.php");
    exit();
}
?>
