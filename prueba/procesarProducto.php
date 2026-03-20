<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
    require_once 'conexion.php';

    // Obtener datos del cuerpo de la petición (JSON)
    $jsonRecibido = file_get_contents('php://input');
    $datos = json_decode($jsonRecibido, true);

    if (!$datos) {
        throw new Exception("No se recibieron datos válidos");
    }

    // Validar que materiales sea un array
    if (!isset($datos['materiales']) || !is_array($datos['materiales'])) {
        throw new Exception("Los materiales no se recibieron correctamente.");
    }

    // Iniciar transacción
    $conexion->begin_transaction();

    // 1. Insertar en la tabla 'producto'
    $sql = "INSERT INTO producto (codigo, nombre, id_bodega, id_sucursal, moneda, precio, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error al preparar consulta de producto: " . $conexion->error);
    }

    // Aseguramos los tipos de datos
    $codigo = (string)$datos['codigo'];
    $nombre = (string)$datos['nombre'];
    $bodega = (int)$datos['bodega'];
    $sucursal = (int)$datos['sucursal'];
    $moneda = (string)$datos['moneda'];
    $precio = (float)$datos['precio'];
    $descripcion = (string)$datos['descripcion'];

    $stmt->bind_param("ssiisds", $codigo, $nombre, $bodega, $sucursal, $moneda, $precio, $descripcion);

    if (!$stmt->execute()) {
        throw new Exception("Error al insertar producto (posible código duplicado): " . $stmt->error);
    }

    // 2. Insertar en la tabla intermedia de materiales
    $sqlMat = "INSERT INTO producto_material (codigo, id_material) VALUES (?, ?)";
    $stmtMat = $conexion->prepare($sqlMat);
    
    if (!$stmtMat) {
        throw new Exception("Error al preparar consulta de materiales: " . $conexion->error);
    }

    foreach ($datos['materiales'] as $idMaterial) {
        $idMat = (int)$idMaterial;
        $stmtMat->bind_param("si", $codigo, $idMat);
        if (!$stmtMat->execute()) {
            throw new Exception("Error al insertar material ID $idMat: " . $stmtMat->error);
        }
    }

    // Confirmar cambios
    $conexion->commit();

    echo json_encode([
        "estado" => "exito",
        "mensaje" => "Producto guardado correctamente con código: $codigo"
    ]);

} catch (Exception $e) {
    if (isset($conexion) && $conexion->connect_errno == 0) {
        $conexion->rollback();
    }
    // No cambiamos el http_response_code para que el AJAX pueda leer el JSON de error
    echo json_encode([
        "estado" => "error",
        "mensaje" => $e->getMessage()
    ]);
}

if (isset($conexion)) {
    $conexion->close();
}
?>
