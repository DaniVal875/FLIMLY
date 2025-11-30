<?php
// Configuración de la conexión a la base de datos
$servidor = "localhost"; // Generalmente es "localhost"
$usuario = "root";       // Usuario por defecto de XAMPP/WAMP
$contrasena = "";        // Contraseña por defecto de XAMPP/WAMP es vacía
$base_de_datos = "filmly"; // El nombre que creaste en query.sql

// Crear la conexión
$conexion = new mysqli($servidor, $usuario, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conexion->connect_error) {
  // Si hay un error, se termina el script y se muestra el error
  die("Error de conexión: " . $conexion->connect_error);
}

// Opcional: Establecer el juego de caracteres a UTF-8 para evitar problemas con tildes y caracteres especiales
$conexion->set_charset("utf8");
?>