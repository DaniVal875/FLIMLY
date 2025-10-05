<?php
// Configuración de la conexión
$servidor = "localhost";
$usuario = "root";
$contrasena = ""; // Por defecto en XAMPP la contraseña es vacía
$base_de_datos = "filmly";

// Crear la conexión con mysqli
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el juego de caracteres a UTF-8 para manejar tildes y ñ
$conexion->set_charset("utf8");
?>