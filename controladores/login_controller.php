<?php
// ¡HEMOS ELIMINADO LA LÍNEA var_dump($_POST); DE AQUÍ!

session_start(); // Es importante iniciar la sesión al principio

// 1. Incluimos la conexión
include '../conexion.php';

// 2. Recibimos los datos del formulario de login
$email = $_POST['email'];
$password = $_POST['password'];

// 3. Preparamos la consulta para buscar al usuario por correo
$stmt = $conexion->prepare("SELECT id_usuario, nombre_usuario, contrasenia FROM usuario WHERE correo = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

// 4. Verificamos si el usuario existe
if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    
    // 5. Verificamos que la contraseña sea correcta
    if (password_verify($password, $usuario['contrasenia'])) {
        // Si es correcta, guardamos sus datos en una sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
        
        // Ahora la redirección funcionará correctamente
        header('Location: ../vistas/home/Home.html');
        exit();
        
    } else {
        // Si la contraseña es incorrecta
        echo "Contraseña incorrecta. <a href='../vistas/login/Login.html'>Volver a intentar</a>";
    }
} else {
    // Si el correo no existe
    echo "El correo electrónico no está registrado. <a href='../vistas/login/Login.html'>Volver a intentar</a>";
}

// 6. Cerramos la conexión
$stmt->close();
$conexion->close();
?>