<?php
// Muestra todos los errores para facilitar la depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Incluimos el archivo de conexión a la base de datos
include '../conexion.php'; //

// 2. Recibimos los datos que envía el formulario HTML
$nombre = $_POST['name']; //
$email = $_POST['email']; //
$password = $_POST['password']; //

// --- Validación básica de campos vacíos ---
if (empty($nombre) || empty($email) || empty($password)) { //
    echo "Error: Por favor, completa todos los campos del formulario. <a href='../vistas/registro/Registro.html'>Volver</a>";
    exit();
}

// --- ¡NUEVO! Verificación de correo electrónico existente ---
// Preparamos una consulta para buscar si el correo ya existe.
$stmt_check = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result(); // Necesario para poder usar num_rows

// Si el número de filas encontradas es mayor a 0, el correo ya existe.
if ($stmt_check->num_rows > 0) {
    echo "Error: El correo electrónico que ingresaste ya está registrado. Por favor, <a href='../vistas/login/Login.html'>inicia sesión</a> o <a href='../vistas/registro/Registro.html'>utiliza otro correo</a>.";
    $stmt_check->close();
    $conexion->close();
    exit(); // Detenemos la ejecución del script.
}
// Cerramos la consulta de verificación para liberar recursos.
$stmt_check->close();
// --- Fin de la nueva sección ---

// 3. Ciframos la contraseña antes de guardarla
$password_cifrada = password_hash($password, PASSWORD_DEFAULT); //

// 4. Obtenemos la fecha actual para el campo 'fecha_reg'
$fecha_registro = date('Y-m-d'); //

// 5. Preparamos la consulta SQL para la inserción
$stmt = $conexion->prepare("INSERT INTO usuario (nombre_usuario, correo, contrasenia, fecha_reg) VALUES (?, ?, ?, ?)"); //

// 6. Vinculamos las variables de PHP con la consulta
$stmt->bind_param("ssss", $nombre, $email, $password_cifrada, $fecha_registro); //

// 7. Ejecutamos la consulta y verificamos el resultado
if ($stmt->execute()) { //
    // Si la ejecución fue exitosa, redirigimos al login
    header('Location: ../vistas/login/Login.html'); //
    exit();
} else {
    // Si hubo un error, lo mostramos
    echo "Error al registrar el usuario: " . $stmt->error; //
}

// 8. Cerramos la sentencia y la conexión
$stmt->close(); //
$conexion->close(); //

?>