<?php
require_once __DIR__ . '/../database/conexion.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $pdo = db();

    // ✅ SOLO GET por ahora
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
        exit;
    }

$stmt = $pdo->query("
    SELECT
        p.id,
        p.nombre,
        p.categoria,
        p.descripcion,
        p.precio,
        p.stock,
        p.unidad_medida,
        p.imagen_url,
        p.procedencia,
        p.usuario_id,
        CONCAT(u.nombre, ' ', u.apellido) AS socio
    FROM productos p
    INNER JOIN usuarios u ON u.id = p.usuario_id
    ORDER BY p.categoria, p.nombre
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
