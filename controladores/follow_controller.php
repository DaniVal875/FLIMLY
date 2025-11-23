<?php
session_start();
include '../conexion.php'; //

header('Content-Type: application/json');

// 1. Verificar sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'error' => 'No has iniciado sesión']);
    exit();
}

$mi_id = $_SESSION['id_usuario'];       // Yo (Seguidor)
$su_id = $_POST['id_usuario_a_seguir']; // Él (Seguido)

if ($mi_id == $su_id) {
    echo json_encode(['success' => false, 'error' => 'No te puedes seguir a ti mismo']);
    exit();
}

// 2. Verificar si ya lo sigo
$check = $conexion->prepare("SELECT id_seguimiento FROM seguimiento WHERE id_seguidor = ? AND id_seguido = ?");
$check->bind_param("ii", $mi_id, $su_id);
$check->execute();
$resultado = $check->get_result();
$ya_lo_sigo = $resultado->num_rows > 0;
$check->close();

$accion = ''; // Para decirle al JS qué pasó

if ($ya_lo_sigo) {
    // --- DEJAR DE SEGUIR (UNFOLLOW) ---
    // 1. Borrar relación
    $stmt = $conexion->prepare("DELETE FROM seguimiento WHERE id_seguidor = ? AND id_seguido = ?");
    $stmt->bind_param("ii", $mi_id, $su_id);
    $stmt->execute();
    
    // 2. Actualizar contadores (Resta)
    $conexion->query("UPDATE usuario SET seguidos = seguidos - 1 WHERE id_usuario = $mi_id");
    $conexion->query("UPDATE usuario SET seguidores = seguidores - 1 WHERE id_usuario = $su_id");
    
    $accion = 'unfollowed';
} else {
    // --- SEGUIR (FOLLOW) ---
    // 1. Crear relación
    $stmt = $conexion->prepare("INSERT INTO seguimiento (id_seguidor, id_seguido) VALUES (?, ?)");
    $stmt->bind_param("ii", $mi_id, $su_id);
    $stmt->execute();
    
    // 2. Actualizar contadores (Suma)
    $conexion->query("UPDATE usuario SET seguidos = seguidos + 1 WHERE id_usuario = $mi_id");
    $conexion->query("UPDATE usuario SET seguidores = seguidores + 1 WHERE id_usuario = $su_id");
    
    $accion = 'followed';
}

// 3. Devolver los nuevos datos para actualizar la pantalla sin recargar
// Obtenemos los seguidores actualizados del usuario perfil
$q = $conexion->query("SELECT seguidores FROM usuario WHERE id_usuario = $su_id");
$data = $q->fetch_assoc();

echo json_encode(['success' => true, 'action' => $accion, 'nuevos_seguidores' => $data['seguidores']]);

$conexion->close();
?>