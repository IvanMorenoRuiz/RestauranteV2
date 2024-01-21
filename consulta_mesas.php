<?php
// Aquí incluir tus configuraciones de conexión a la base de datos

// Consulta de Mesas
$resultMesas = $conn->query("SELECT m.*, s.tipo_sala
                             FROM tbl_mesas m
                             INNER JOIN tbl_salas s ON m.id_sala_mesa = s.id_sala");

if ($resultMesas->num_rows > 0) {
    echo "<table border='1'>
             <tr>
                 <th>ID Mesa</th>
                 <th>Nombre Mesa</th>
                 <th>Sillas Mesa</th>
                 <th>Tipo Sala</th>
             </tr>";

    while ($rowMesa = $resultMesas->fetch_assoc()) {
        echo "<tr>
                 <td>{$rowMesa['id_mesa']}</td>
                 <td>{$rowMesa['nombre_mesa']}</td>
                 <td>{$rowMesa['sillas_mesa']}</td>
                 <td>{$rowMesa['tipo_sala']}</td>
             </tr>";
    }

    echo "</table>";
} else {
    echo "No hay mesas.";
}

$conn->close();
?>
