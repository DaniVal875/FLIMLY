<?php
include '../conexion.php'; //

header('Content-Type: application/json');

$response = [
    'movies' => [],
    'users' => []
];

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $busqueda = trim($_GET['query']);
    $param = "%" . $busqueda . "%";

    // --- A. BUSCAR PELÍCULAS (POR TÍTULO O GÉNERO) ---
    // Usamos DISTINCT para evitar duplicados si coincide nombre y género
    // Hacemos LEFT JOIN con pelicula_genero y genero para buscar ahí también
    $sqlMovies = "SELECT DISTINCT p.id_pelicula, p.nombre, p.imagen 
                  FROM pelicula p
                  LEFT JOIN pelicula_genero pg ON p.id_pelicula = pg.pelicula
                  LEFT JOIN genero g ON pg.genero = g.id_genero
                  WHERE p.nombre LIKE ? OR g.nombre_genero LIKE ? 
                  LIMIT 20";
                  
    $stmtM = $conexion->prepare($sqlMovies);
    // Vinculamos el parámetro dos veces: una para el título, otra para el género
    $stmtM->bind_param("ss", $param, $param); 
    $stmtM->execute();
    $resultM = $stmtM->get_result();
    
    while ($row = $resultM->fetch_assoc()) {
        $response['movies'][] = $row;
    }
    $stmtM->close();

    // --- B. BUSCAR USUARIOS ---
    $sqlUsers = "SELECT id_usuario, nombre_usuario, seguidores, foto_perfil FROM usuario WHERE nombre_usuario LIKE ? LIMIT 10";
    $stmtU = $conexion->prepare($sqlUsers);
    $stmtU->bind_param("s", $param);
    $stmtU->execute();
    $resultU = $stmtU->get_result();
    
    while ($row = $resultU->fetch_assoc()) {
        $response['users'][] = $row;
    }
    $stmtU->close();
}

echo json_encode($response);
$conexion->close();
?>