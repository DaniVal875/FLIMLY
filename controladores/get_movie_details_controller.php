<?php
include '../conexion.php'; //

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // AGREGAMOS UNA NUEVA LÍNEA EN EL SELECT:
    // (SELECT AVG(calificacion) FROM resenia WHERE pelicula = p.id_pelicula) as promedio
    
    $sql = "SELECT 
                p.*, 
                GROUP_CONCAT(DISTINCT g.nombre_genero SEPARATOR ', ') as generos,
                GROUP_CONCAT(DISTINCT i.idioma SEPARATOR ', ') as idiomas,
                GROUP_CONCAT(DISTINCT pers.nombre_personal SEPARATOR ', ') as directores,
                (SELECT AVG(calificacion) FROM resenia WHERE pelicula = p.id_pelicula) as promedio
            FROM pelicula p
            LEFT JOIN pelicula_genero pg ON p.id_pelicula = pg.pelicula
            LEFT JOIN genero g ON pg.genero = g.id_genero
            LEFT JOIN pelicula_idioma pi ON p.id_pelicula = pi.pelicula
            LEFT JOIN idioma i ON pi.idioma = i.id_idioma
            LEFT JOIN pelicula_personal pp ON p.id_pelicula = pp.pelicula
            LEFT JOIN personal pers ON pp.personal = pers.id_personal
            
            WHERE p.id_pelicula = ?
            GROUP BY p.id_pelicula";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        
        if ($fila = $resultado->fetch_assoc()) {
            // Si el promedio es null (sin reseñas), lo enviamos como null
            echo json_encode($fila);
        } else {
            echo json_encode(['error' => 'Película no encontrada']);
        }
    } else {
        echo json_encode(['error' => 'Error en la consulta: ' . $conexion->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No se proporcionó un ID']);
}

$conexion->close();
?>