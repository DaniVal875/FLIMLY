<?php
// 1. Incluir conexión
include '../conexion.php'; //

// 2. Respuesta JSON
header('Content-Type: application/json');

$respuesta = [
    'populares' => [],
    'genero_titulo' => '',
    'genero_peliculas' => []
];

// --- PARTE 1: PELÍCULAS POPULARES ---
// CAMBIO AQUÍ: Cambiamos LIMIT 12 por LIMIT 16
$sqlPop = "SELECT id_pelicula, nombre, imagen FROM pelicula ORDER BY RAND() LIMIT 16";
$resultPop = $conexion->query($sqlPop);

if ($resultPop->num_rows > 0) {
    while($row = $resultPop->fetch_assoc()) {
        $respuesta['populares'][] = $row;
    }
}

// --- PARTE 2: ELEGIR UN GÉNERO AL AZAR ---
$sqlGen = "SELECT id_genero, nombre_genero FROM genero ORDER BY RAND() LIMIT 1";
$resultGen = $conexion->query($sqlGen);

if ($rowGen = $resultGen->fetch_assoc()) {
    $idGenero = $rowGen['id_genero'];
    $nombreGenero = $rowGen['nombre_genero'];
    
    $respuesta['genero_titulo'] = $nombreGenero;

    // --- PARTE 3: PELÍCULAS DE ESE GÉNERO ---
    // Mantenemos 6 o puedes subirlo también si quieres una fila más larga
    $sqlMoviesByGen = "SELECT DISTINCT p.id_pelicula, p.nombre, p.imagen 
                       FROM pelicula p
                       JOIN pelicula_genero pg ON p.id_pelicula = pg.pelicula
                       WHERE pg.genero = $idGenero
                       LIMIT 6"; 

    $resultMoviesGen = $conexion->query($sqlMoviesByGen);
    
    if ($resultMoviesGen->num_rows > 0) {
        while($row = $resultMoviesGen->fetch_assoc()) {
            $respuesta['genero_peliculas'][] = $row;
        }
    }
}

// 4. Devolver datos
echo json_encode($respuesta);

$conexion->close();
?>