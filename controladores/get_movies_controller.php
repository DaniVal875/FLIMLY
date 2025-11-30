<?php
// 1. Incluir la conexión
include '../conexion.php'; //

// 2. Establecer cabecera JSON
header('Content-Type: application/json');

// 3. Preparar la consulta SQL
// Seleccionamos el ID, el nombre y la imagen de la tabla 'pelicula'
$sql = "SELECT id_pelicula, nombre, imagen FROM pelicula";
$result = $conexion->query($sql);

$peliculas = [];

// 4. Recorrer los resultados y guardarlos en un array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $peliculas[] = $row;
    }
}

// 5. Cerrar conexión
$conexion->close();

// 6. Devolver los datos en formato JSON
echo json_encode($peliculas);
?>