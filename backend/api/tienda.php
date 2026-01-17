<?php

// - Endpoint público de catálogo para CLIENTE (solo lectura)
// - Devuelve solo los campos necesarios para pintar cards



require_once __DIR__ . '/../database/conexion.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db();

    //  Consulta de catálogo público
   
    $stmt = $pdo->query("
        SELECT
          id,
          nombre,
          categoria,
          descripcion,
          precio,
          stock,
          unidad_medida,
          imagen_url,
          procedencia
        FROM productos
        ORDER BY categoria, nombre
    ");

    $items = $stmt->fetchAll();

    echo json_encode([
        'ok' => true,
        'items' => $items
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
