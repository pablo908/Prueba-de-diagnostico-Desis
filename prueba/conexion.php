<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "pablo2014";
$baseDatos = "formularioproducto";

// Crear conexión usando MySQLi
$conexion = new mysqli($servidor, $usuario, $contrasena, $baseDatos);

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode([
        "estado" => "error",
        "mensaje" => "Error de conexión: " . $conexion->connect_error
    ]));
}

// Asegurar que la comunicación sea en UTF-8
$conexion->set_charset("utf8");
?>
