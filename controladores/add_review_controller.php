<?php
session_start();
include '../conexion.php'; //

header('Content-Type: application/json');

// Verificar login
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión.']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_pelicula = $_POST['id_pelicula']; // ID oculto en el formulario
$texto = $_POST['review_text'];
$calificacion = $_POST['rating'];
$fecha = date('Y-m-d');

// Validación básica
if (empty($texto) || empty($calificacion)) {
    echo json_encode(['success' => false, 'message' => 'Falta texto o calificación.']);
    exit();
}

// Insertar en la base de datos
$stmt = $conexion->prepare("INSERT INTO resenia (texto_resenia, calificacion, fecha_pub, usuario, pelicula) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sisii", $texto, $calificacion, $fecha, $id_usuario, $id_pelicula);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al guardar: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>