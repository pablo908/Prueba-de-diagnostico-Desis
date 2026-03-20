<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$contrasena = "pablo2014";
$baseDatos = "formularioproducto";

// Crear conexión usando MySQLi
try {
    // Desactivar reporte de errores de mysqli para manejarlo manualmente
    mysqli_report(MYSQLI_REPORT_OFF);
    $conexion = new mysqli($servidor, $usuario, $contrasena, $baseDatos);

    // Verificar conexión
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }

    // Asegurar que la comunicación sea en UTF-8
    $conexion->set_charset("utf8");

} catch (Exception $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "estado" => "error",
        "mensaje" => $e->getMessage()
    ]);
    exit;
}
?>
