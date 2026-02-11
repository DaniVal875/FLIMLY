<?php
// Configuración de la conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "";
$base_de_datos = "filmly";

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// === CAMBIO AQUÍ: Usar 'utf8mb4' en lugar de 'utf8' ===
// Esto permite caracteres especiales como emojis y signos como '¡' sin errores
$conexion->set_charset("utf8mb4");
?>