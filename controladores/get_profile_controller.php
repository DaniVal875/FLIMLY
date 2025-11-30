<?php
session_start();
include '../conexion.php'; //

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autenticado']);
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Agregamos 'foto_perfil' a la selección
$stmt = $conexion->prepare("SELECT nombre_usuario, descripcion, seguidores, seguidos, foto_perfil FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

echo json_encode($usuario);
$stmt->close();
$conexion->close();
?>