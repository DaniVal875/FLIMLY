<?php
// 1. Iniciar la sesión
session_start();

// 2. Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    // Si no hay sesión, no debe estar aquí
    header('Location: ../vistas/login/Login.html');
    exit();
}

// 3. Incluir la conexión
include '../conexion.php'; //

// 4. Recibir los datos del formulario (enviados por POST)
$id_usuario = $_SESSION['id_usuario'];
$nombre = $_POST['name'];
$descripcion = $_POST['bio'];
// (El campo 'photo' se omite ya que no está en tu tabla 'usuario')

// 5. Preparar la consulta SQL para ACTUALIZAR
$stmt = $conexion->prepare("UPDATE usuario SET nombre_usuario = ?, descripcion = ? WHERE id_usuario = ?");
$stmt->bind_param("ssi", $nombre, $descripcion, $id_usuario);

// 6. Ejecutar la consulta y redirigir
if ($stmt->execute()) {
    // Si se guardó con éxito, lo regresamos a su perfil
    header('Location: ../vistas/profile/Profile.html');
    exit();
} else {
    // Si hubo un error
    echo "Error al actualizar el perfil: " . $stmt->error;
}

// 7. Cerrar todo
$stmt->close();
$conexion->close();
?>