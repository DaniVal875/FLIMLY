<?php
session_start();
include '../conexion.php'; //

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../vistas/login/Login.html');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$nombre = $_POST['name'];
$descripcion = $_POST['bio'];
$foto = $_POST['photo']; // <--- NUEVO: Recibimos la URL de la foto

// Actualizamos la consulta para incluir 'foto_perfil'
$stmt = $conexion->prepare("UPDATE usuario SET nombre_usuario = ?, descripcion = ?, foto_perfil = ? WHERE id_usuario = ?");
$stmt->bind_param("sssi", $nombre, $descripcion, $foto, $id_usuario);

if ($stmt->execute()) {
    // Actualizamos también la sesión por si acaso se usa en otro lado
    $_SESSION['nombre_usuario'] = $nombre;
    header('Location: ../vistas/profile/Profile.html');
    exit();
} else {
    echo "Error al actualizar el perfil: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>