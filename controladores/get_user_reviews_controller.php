<?php
include '../conexion.php'; //

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Traemos la reseña Y los datos de la película asociada
    $sql = "SELECT r.texto_resenia, r.calificacion, p.nombre as nombre_pelicula, p.imagen 
            FROM resenia r
            JOIN pelicula p ON r.pelicula = p.id_pelicula
            WHERE r.usuario = ?
            ORDER BY r.fecha_pub DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $resenias = [];
    while ($fila = $resultado->fetch_assoc()) {
        $resenias[] = $fila;
    }

    echo json_encode($resenias);
    $stmt->close();
} else {
    echo json_encode([]);
}
$conexion->close();
?>