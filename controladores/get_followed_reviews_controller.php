<?php
session_start();
include '../conexion.php'; 

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([]);
    exit();
}

$mi_id = $_SESSION['id_usuario'];


$sqlFollowed = "SELECT u.id_usuario, u.nombre_usuario, u.foto_perfil 
                FROM seguimiento s
                JOIN usuario u ON s.id_seguido = u.id_usuario
                WHERE s.id_seguidor = ?
                ORDER BY RAND() 
                LIMIT 3"; 

$stmt = $conexion->prepare($sqlFollowed);
$stmt->bind_param("i", $mi_id);
$stmt->execute();
$resultFollowed = $stmt->get_result();

$amigosData = [];

while ($amigo = $resultFollowed->fetch_assoc()) {
    
    $sqlReviews = "SELECT p.id_pelicula, p.nombre, p.imagen, r.calificacion
                   FROM resenia r
                   JOIN pelicula p ON r.pelicula = p.id_pelicula
                   WHERE r.usuario = ?
                   ORDER BY r.fecha_pub DESC
                   LIMIT 4";

    $stmt2 = $conexion->prepare($sqlReviews);
    $stmt2->bind_param("i", $amigo['id_usuario']);
    $stmt2->execute();
    $resultReviews = $stmt2->get_result();

    $peliculas = [];
    while ($row = $resultReviews->fetch_assoc()) {
        $peliculas[] = $row;
    }
    $stmt2->close();

    // Solo agregamos al amigo si tiene reseñas
    if (!empty($peliculas)) {
        $amigosData[] = [
            'user' => $amigo,
            'movies' => $peliculas
        ];
    }
}

$stmt->close();
$conexion->close();

// Devolvemos la lista de amigos y sus películas
echo json_encode($amigosData);
?>