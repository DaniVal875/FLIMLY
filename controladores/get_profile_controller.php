<?php
// 1. Iniciar la sesión
session_start();

// 2. Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay sesión, devolvemos un error
    http_response_code(401); // No autorizado
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit();
}

// 3. Incluir la conexión
include '../conexion.php'; //

// 4. Obtener el ID del usuario
$id_usuario = $_SESSION['id_usuario'];

// 5. Consultar los datos del perfil
// (Usamos los campos de tu tabla 'usuario')
$stmt = $conexion->prepare("SELECT nombre_usuario, descripcion, seguidores, seguidos FROM usuario WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// 6. Cerrar la conexión
$stmt->close();
$conexion->close();

// 7. Devolver los datos del usuario como JSON
// Esto es lo que leerá el JavaScript
header('Content-Type: application/json');
echo json_encode($usuario);

?>