<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once 'conexion.php';

$idBodega = isset($_GET['bodega_id']) ? (int)$_GET['bodega_id'] : 0;

try {
    if ($idBodega > 0) {
        $query = "SELECT * FROM sucursal WHERE id_bodega = $idBodega ORDER BY nombre ASC";
        $resultado = $conexion->query($query);

        $sucursales = [];

        if ($resultado) {
            while ($fila = $resultado->fetch_assoc()) {
                $id = isset($fila['id_sucursal']) ? $fila['id_sucursal'] : (isset($fila['id']) ? $fila['id'] : 0);
                $sucursales[] = [
                    'id' => $id,
                    'nombre' => $fila['nombre']
                ];
            }
            echo json_encode($sucursales, JSON_UNESCAPED_UNICODE);
        } else {
            throw new Exception("Error SQL: " . $conexion->error);
        }
    } else {
        echo json_encode([]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

if (isset($conexion)) {
    $conexion->close();
}
?>
