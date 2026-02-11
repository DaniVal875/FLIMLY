<?php
session_start();
include '../conexion.php'; //

// 1. Establecemos que la respuesta siempre será JSON
header('Content-Type: application/json');

// 2. Preparamos un array para la respuesta
$response = [
    'success' => false,
    'message' => ''
];

// 3. Recibimos los datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// 4. Preparamos la consulta
$stmt = $conexion->prepare("SELECT id_usuario, nombre_usuario, contrasenia FROM usuario WHERE correo = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

// 5. Verificamos al usuario
if ($resultado->num_rows === 1) {
    $usuario = $resultado->fetch_assoc();
    
    // 6. Verificamos la contraseña
    if (password_verify($password, $usuario['contrasenia'])) {
        
        // ¡ÉXITO! Guardamos sesión y preparamos respuesta
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
        
        $response['success'] = true;
        
    } else {
        // ¡ERROR 1! Contraseña incorrecta
        $response['message'] = 'Contraseña incorrecta. Por favor, vuelve a intentar.';
    }
} else {
    // ¡ERROR 2! Correo no existe
    $response['message'] = 'El correo electrónico no está registrado.';
}

// 7. Cerramos la conexión
$stmt->close();
$conexion->close();

// 8. Enviamos la respuesta JSON al JavaScript
echo json_encode($response);
exit();
?>