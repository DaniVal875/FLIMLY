<?php
// Muestra todos los errores para facilitar la depuración
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. Incluimos el archivo de conexión a la base de datos
include '../conexion.php';

// 2. Recibimos los datos del formulario
$email = $_POST['email'];
$new_password = $_POST['password'];

// --- Validación básica de campos vacíos ---
if (empty($email) || empty($new_password)) {
    echo "Error: Por favor, completa todos los campos. <a href='../vistas/recover/Recover.html'>Volver a intentar</a>";
    exit();
}

// --- Verificamos primero que el correo exista ---
$stmt_check = $conexion->prepare("SELECT id_usuario FROM usuario WHERE correo = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result(); // Necesario para contar las filas

if ($stmt_check->num_rows === 0) {
    // Si no se encuentra el correo, mostramos un error
    echo "Error: El correo electrónico proporcionado no se encuentra registrado. <a href='../vistas/recover/Recover.html'>Volver a intentar</a>";
    $stmt_check->close();
    $conexion->close();
    exit();
}
$stmt_check->close();

// 3. Si el correo existe, procedemos a cifrar la nueva contraseña
$password_cifrada = password_hash($new_password, PASSWORD_DEFAULT);

// 4. Preparamos la consulta SQL para ACTUALIZAR (UPDATE) la contraseña
$stmt_update = $conexion->prepare("UPDATE usuario SET contrasenia = ? WHERE correo = ?");

// 5. Vinculamos las variables a la consulta
$stmt_update->bind_param("ss", $password_cifrada, $email);

// 6. Ejecutamos la consulta y verificamos el resultado
if ($stmt_update->execute()) {
    // Si la actualización fue exitosa, mostramos un mensaje y enlazamos al login
    echo "¡Contraseña actualizada con éxito! Ahora puedes <a href='../vistas/login/Login.html'>iniciar sesión</a> con tu nueva contraseña.";
} else {
    // Si hubo un error en la actualización, lo mostramos
    echo "Error al actualizar la contraseña: " . $stmt_update->error;
}

// 7. Cerramos la sentencia y la conexión
$stmt_update->close();
$conexion->close();
?>