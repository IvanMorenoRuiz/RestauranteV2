<?php
include_once("proc/conexion.php");

// Obtener estadísticas desde la base de datos
$sqlEstadisticas = "SELECT
                        COUNT(*) as total_reservas,
                        s.tipo_sala,
                        m.nombre_mesa,
                        MAX(m.sillas_mesa) as sillas_ocupadas
                    FROM tbl_reservas r
                    JOIN tbl_mesas m ON r.id_mesa_reserva = m.id_mesa
                    JOIN tbl_salas s ON m.id_sala_mesa = s.id_sala
                    GROUP BY s.tipo_sala, m.nombre_mesa
                    ORDER BY total_reservas DESC
                    LIMIT 1";

$resultEstadisticas = $conn->query($sqlEstadisticas);

if ($resultEstadisticas->num_rows > 0) {
    $rowEstadisticas = $resultEstadisticas->fetch_assoc();
    
    // Construir el contenido del Sweet Alert
    $contenido = "<p>Total de reservas: " . $rowEstadisticas['total_reservas'] . "</p>";
    $contenido .= "<p>Tipo de sala más frecuente: " . $rowEstadisticas['tipo_sala'] . "</p>";
    $contenido .= "<p>Mesa más ocupada: " . $rowEstadisticas['nombre_mesa'] . " con " . $rowEstadisticas['sillas_ocupadas'] . " sillas ocupadas</p>";

    echo $contenido;
} else {
    echo "<p>No hay estadísticas disponibles.</p>";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
