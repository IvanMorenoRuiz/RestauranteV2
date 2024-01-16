
<?php
include("./proc/conexion.php");
$where = "";
if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
    $nombre = mysqli_real_escape_string($conn, $_GET['nombre']);
    $where .= " AND nombre_camarero LIKE '%$nombre%'";
}
if (isset($_GET['apellido']) && !empty($_GET['apellido'])) {
    $apellido = mysqli_real_escape_string($conn, $_GET['apellido']);
    $where .= " AND apellidos_camarero LIKE '%$apellido%'";
}


$query = "SELECT * FROM tbl_camareros WHERE 1 $where ";

$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    echo "<h2>Lista de Camareros</h2>";
    echo "<a href='addcamarero.php' class='btn btn-primary btn-block btn-large'>Añadir Camarero</a>";
    echo "<form method='get'>";
    echo "<label>Nombre: <input type='text' name='nombre' value='" . (isset($_GET['nombre']) ? $_GET['nombre'] : "") . "'></label>";
    echo "<label>Apellido: <input type='text' name='apellido' value='" . (isset($_GET['apellido']) ? $_GET['apellido'] : "") . "'></label>";
    echo "<input type='submit' value='Filtrar'>";
    echo "</form>";
    echo "<table>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Usuario</th>";
    echo "<th>>Nombre</a></th>";
    echo "<th>>Apellidos</a></th>";
    echo "<th>Contraseña</th>";
    echo "</tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id_camarero'] . "</td>";
        echo "<td>" . $row['username_camarero'] . "</td>";
        echo "<td>" . $row['nombre_camarero'] . "</td>";
        echo "<td>" . $row['apellidos_camarero'] . "</td>";
        echo "<td>" . $row['pwd_camarero'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay camareros registrados.</p>";
}
mysqli_close($conn);
?>