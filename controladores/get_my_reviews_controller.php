<?php
// Activar reporte de errores para depuración (Quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../conexion.php'; // Asegúrate de que la ruta a conexion.php es correcta

header('Content-Type: application/json');

// Si no hay sesión, devolvemos array vacío
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode([]);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta SQL: Unimos reseñas con películas
$sql = "SELECT r.texto_resenia, r.calificacion, r.fecha_pub, p.nombre as nombre_pelicula, p.imagen, p.id_pelicula
        FROM resenia r
        JOIN pelicula p ON r.pelicula = p.id_pelicula
        WHERE r.usuario = ?
        ORDER BY r.fecha_pub DESC";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    // Si falla la preparación SQL, devolvemos el error
    echo json_encode(['error' => 'Error SQL: ' . $conexion->error]);
    exit();
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

$resenias = [];
while ($fila = $resultado->fetch_assoc()) {
    $resenias[] = $fila;
}

echo json_encode($resenias);

$stmt->close();
$conexion->close();
?>