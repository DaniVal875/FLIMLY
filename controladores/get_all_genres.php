<?php
include '../conexion.php';

header('Content-Type: application/json');

// Seleccionamos todos los géneros ordenados alfabéticamente
$sql = "SELECT nombre_genero FROM genero ORDER BY nombre_genero ASC";
$result = $conexion->query($sql);

$generos = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $generos[] = $row['nombre_genero'];
    }
}

echo json_encode($generos);
$conexion->close();
?>