<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once 'conexion.php';

try {
    $query = "SELECT * FROM bodega ORDER BY nombre ASC";
    $resultado = $conexion->query($query);

    $bodegas = [];

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $id = isset($fila['id_bodega']) ? $fila['id_bodega'] : (isset($fila['id']) ? $fila['id'] : 0);
            $bodegas[] = [
                'id' => $id,
                'nombre' => $fila['nombre']
            ];
        }
        echo json_encode($bodegas, JSON_UNESCAPED_UNICODE);
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
