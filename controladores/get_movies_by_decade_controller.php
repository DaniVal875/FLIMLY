<?php
include '../conexion.php'; //

header('Content-Type: application/json');

// 1. Definir las décadas posibles
$decadas = [1980, 1990, 2000, 2010, 2020];

// 2. Elegir una al azar
$decadaInicio = $decadas[array_rand($decadas)];
$decadaFin = $decadaInicio + 9; // Ej: 2000 a 2009

// 3. Buscar películas en ese rango de años
$sql = "SELECT id_pelicula, nombre, imagen FROM pelicula 
        WHERE YEAR(fecha_estreno) BETWEEN $decadaInicio AND $decadaFin
        ORDER BY RAND() LIMIT 10";

$result = $conexion->query($sql);

$peliculas = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $peliculas[] = $row;
    }
}

echo json_encode([
    'titulo' => "Cine de los $decadaInicio", // Ej: "Cine de los 2000"
    'movies' => $peliculas
]);

$conexion->close();
?>