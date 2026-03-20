<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 0);
error_reporting(E_ALL);

require_once 'conexion.php';

// Obtener datos del cuerpo de la petición (JSON)
$jsonRecibido = file_get_contents('php://input');
$datos = json_decode($jsonRecibido, true);

if (!$datos) {
    http_response_code(400);
    echo json_encode(["estado" => "error", "mensaje" => "No se recibieron datos válidos"]);
    exit;
}

try {
    // Iniciar transacción para asegurar integridad
    $conexion->begin_transaction();

    // 1. Insertar en la tabla 'producto'
    // Asumimos los nombres de columnas estándar
    $stmt = $conexion->prepare("INSERT INTO producto (codigo, nombre, id_bodega, id_sucursal, moneda, precio, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param(
        "ssiisds", 
        $datos['codigo'], 
        $datos['nombre'], 
        $datos['bodega'], 
        $datos['sucursal'], 
        $datos['moneda'], 
        $datos['precio'], 
        $datos['descripcion']
    );

    if (!$stmt->execute()) {
        throw new Exception("Error al insertar producto: " . $stmt->error);
    }

    // Obtener el ID del producto recién insertado
    $idProducto = $conexion->insert_id;

    // 2. Insertar en la tabla intermedia de materiales
    // Según el error, la clave foránea apunta a 'codigo' en la tabla 'producto'
    $stmtMat = $conexion->prepare("INSERT INTO producto_material (codigo, id_material) VALUES (?, ?)");
    
    foreach ($datos['materiales'] as $idMaterial) {
        // Usamos 's' para el código (string) e 'i' para el id_material (integer)
        $stmtMat->bind_param("si", $datos['codigo'], $idMaterial);
        if (!$stmtMat->execute()) {
            throw new Exception("Error al insertar material ID $idMaterial: " . $stmtMat->error);
        }
    }

    // Confirmar cambios
    $conexion->commit();

    echo json_encode([
        "estado" => "exito",
        "mensaje" => "Producto guardado correctamente con ID: $idProducto"
    ]);

} catch (Exception $e) {
    // Si algo falla, revertimos los cambios
    $conexion->rollback();
    http_response_code(500);
    echo json_encode([
        "estado" => "error",
        "mensaje" => $e->getMessage()
    ]);
}

if (isset($conexion)) {
    $conexion->close();
}
?>
