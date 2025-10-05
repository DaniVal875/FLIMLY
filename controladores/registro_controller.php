<?php
// Muestra todos los errores para facilitar la depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Incluimos el archivo de conexión a la base de datos
include '../conexion.php';

// 2. Recibimos los datos que envía el formulario HTML
$nombre = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// --- Validación básica ---
if (empty($nombre) || empty($email) || empty($password)) {
    echo "Error: Por favor, completa todos los campos del formulario.";
    exit();
}

// 3. Ciframos la contraseña antes de guardarla
$password_cifrada = password_hash($password, PASSWORD_DEFAULT);

// 4. Obtenemos la fecha actual para el campo 'fecha_reg'
$fecha_registro = date('Y-m-d');

// 5. Preparamos la consulta SQL (YA SIN 'tel_usuario')
// Ahora solo insertamos los 4 campos que necesitamos.
$stmt = $conexion->prepare("INSERT INTO usuario (nombre_usuario, correo, contrasenia, fecha_reg) VALUES (?, ?, ?, ?)");

// 6. Vinculamos las variables de PHP con la consulta
// El "ssss" ahora corresponde a los 4 campos que estamos insertando.
$stmt->bind_param("ssss", $nombre, $email, $password_cifrada, $fecha_registro);

// 7. Ejecutamos la consulta y verificamos el resultado
if ($stmt->execute()) {
    // Si la ejecución fue exitosa, redirigimos al login
    header('Location: ../vistas/login/Login.html');
    exit();
} else {
    // Si hubo un error, lo mostramos
    echo "Error al registrar el usuario: " . $stmt->error;
}

// 8. Cerramos la sentencia y la conexión
$stmt->close();
$conexion->close();

?>