<?php
// 1. Incluir conexión
include '../conexion.php'; //

// 2. Respuesta JSON
header('Content-Type: application/json');

$response = [
    'movies' => [],
    'users' => []
];

// 4. Verificar si hay término de búsqueda
if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $busqueda = trim($_GET['query']);
    $param = "%" . $busqueda . "%";

    // --- A. BUSCAR PELÍCULAS ---
    $sqlMovies = "SELECT id_pelicula, nombre, imagen FROM pelicula WHERE nombre LIKE ? LIMIT 10";
    $stmtM = $conexion->prepare($sqlMovies);
    $stmtM->bind_param("s", $param);
    $stmtM->execute();
    $resultM = $stmtM->get_result();
    
    while ($row = $resultM->fetch_assoc()) {
        $response['movies'][] = $row;
    }
    $stmtM->close();

    // --- B. BUSCAR USUARIOS ---
    // AQUI ESTÁ EL CAMBIO: Agregamos 'foto_perfil' a la selección
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