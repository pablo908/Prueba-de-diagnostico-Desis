<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once 'conexion.php';

try {
    // Seleccionamos solo la columna codigo de la tabla producto
    $query = "SELECT codigo FROM producto";
    $resultado = $conexion->query($query);

    $codigos = [];

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $codigos[] = $fila['codigo'];
        }
        echo json_encode($codigos, JSON_UNESCAPED_UNICODE);
    } else {
        throw new Exception("Error SQL: " . $conexion->error);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

if (isset($conexion)) {
    $conexion->close();
}
?>
