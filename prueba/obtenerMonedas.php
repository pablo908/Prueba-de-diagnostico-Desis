<?php
header('Content-Type: application/json; charset=utf-8');
// Desactivar reporte de errores HTML para que no rompa el JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once 'conexion.php';

try {
    // Intentamos obtener id_moneda o id, según lo que exista
    // Si no estás seguro del nombre, podrías usar SELECT *
    $query = "SELECT * FROM monedas ORDER BY nombre ASC";
    $resultado = $conexion->query($query);

    $monedas = [];

    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            // Normalizamos para que el JS siempre vea 'id' y 'nombre'
            $id = isset($fila['id_moneda']) ? $fila['id_moneda'] : (isset($fila['id']) ? $fila['id'] : 0);
            $monedas[] = [
                'id' => $id,
                'nombre' => $fila['nombre']
            ];
        }
        echo json_encode($monedas, JSON_UNESCAPED_UNICODE);
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
