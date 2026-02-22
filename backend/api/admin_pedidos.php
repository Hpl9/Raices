<?php
require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../database/conexion.php';

// GET que retorna la lista completa de pedidos y estados
header('Content-Type: application/json; charset=utf-8');

start_session();

// Solo admin
if (empty($_SESSION['user']) || ($_SESSION['user']['rol'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode([
        'ok' => false,
        'error' => 'No autorizado'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Solo GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'error' => 'Método no permitido'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$pdo = db();

 // Obtener estados
$estados = $pdo->query("
    SELECT id, nombre
    FROM estados_pedidos
    ORDER BY id
")->fetchAll();


// Obtener pedidos
$sql = "
    SELECT
        p.id AS pedido_id,
        u.nombre AS cliente_nombre,
        u.apellido AS cliente_apellido,
        u.direccion AS direccion_entrega,
        p.tipo_entrega,
        p.total,
        p.estado_id
    FROM pedidos p
    INNER JOIN usuarios u ON u.id = p.usuario_id
    ORDER BY p.id DESC
";
$pedidos = $pdo->query($sql)->fetchAll();

  // Respuesta final
echo json_encode([
    'ok' => true,
    'estados' => $estados,
    'pedidos' => $pedidos
], JSON_UNESCAPED_UNICODE);

exit;