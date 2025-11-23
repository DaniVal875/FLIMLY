<?php
include '../conexion.php'; //

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // AQUI ESTÁ EL CAMBIO: Agregamos 'foto_perfil' a la lista
    $stmt = $conexion->prepare("SELECT nombre_usuario, descripcion, seguidores, seguidos, foto_perfil FROM usuario WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    
    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        if ($fila = $resultado->fetch_assoc()) {
            echo json_encode($fila);
        } else {
            echo json_encode(['error' => 'Usuario no encontrado']);
        }
    } else {
        echo json_encode(['error' => 'Error en la consulta']);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'No se proporcionó ID']);
}
$conexion->close();
?>