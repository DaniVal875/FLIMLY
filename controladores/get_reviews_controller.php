<?php
// Incluir conexión
include '../conexion.php'; //

// Importante: Indicar que la respuesta es JSON
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id_pelicula = $_GET['id'];

    // Consulta: Traemos el texto, calificación, fecha y el nombre del usuario
    // UNIMOS la tabla 'resenia' con 'usuario' para saber quién escribió
    $sql = "SELECT r.texto_resenia, r.calificacion, r.fecha_pub, u.nombre_usuario 
            FROM resenia r
            JOIN usuario u ON r.usuario = u.id_usuario
            WHERE r.pelicula = ?
            ORDER BY r.fecha_pub DESC"; // Ordenamos por fecha (más recientes primero)

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_pelicula);
    
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        $resenias = [];
        
        while ($fila = $resultado->fetch_assoc()) {
            $resenias[] = $fila;
        }
        
        // Devolvemos la lista de reseñas (puede estar vacía, es normal)
        echo json_encode($resenias);
    } else {
        // Error en SQL
        echo json_encode(['error' => 'Error en la consulta']);
    }
    
    $stmt->close();
} else {
    // Si no mandaron ID
    echo json_encode([]);
}

$conexion->close();
?>