<?php
session_start();
include '../conexion.php'; //

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    echo json_encode(['following' => false]);
    exit();
}

$mi_id = $_SESSION['id_usuario'];
$su_id = $_GET['id'];

// Verificar si existe la relación
$stmt = $conexion->prepare("SELECT id_seguimiento FROM seguimiento WHERE id_seguidor = ? AND id_seguido = ?");
$stmt->bind_param("ii", $mi_id, $su_id);
$stmt->execute();
$stmt->store_result();

echo json_encode(['following' => $stmt->num_rows > 0]);

$stmt->close();
$conexion->close();
?>