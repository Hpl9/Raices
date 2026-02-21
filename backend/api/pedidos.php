<?php
require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../database/conexion.php';

header('Content-Type: application/json; charset=utf-8');

start_session();

// Comprobar login
if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['ok'=>false,'error'=>'No autenticado']);
    exit;
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// Leer datos
$input = json_decode(file_get_contents('php://input'), true) ?? [];

$tipo  = $input['tipo_entrega'] ?? '';
$items = $input['items'] ?? [];

// Validar tipo_entrega
if ($tipo !== 'recogida' && $tipo !== 'envio') {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Tipo de entrega inválido']);
    exit;
}

if (!$items || !is_array($items)) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Carrito vacío']);
    exit;
}

$pdo = db();
$usuarioId = $_SESSION['user']['id'];

// Calcular total
$total = 0;

foreach ($items as $it) {
    $stmt = $pdo->prepare("SELECT precio FROM productos WHERE id=?");
    $stmt->execute([$it['id']]);
    $producto = $stmt->fetch();

    if ($producto) {
        $total += $producto['precio'] * $it['qty'];
    }
}

// Crear pedido
$stmt = $pdo->prepare("
    INSERT INTO pedidos
    (usuario_id, total, direccion_entrega, estado_id, tipo_entrega)
    VALUES (?, ?, ?, 1, ?)
");

$stmt->execute([
    $usuarioId,
    $total,
    null,      // dirección simple por ahora
    $tipo     
]);

$pedidoId = $pdo->lastInsertId();

// Insertar detalles
$stmtDetalle = $pdo->prepare("
  INSERT INTO pedido_detalles (pedido_id, producto_id, cantidad, precio_unitario)
  SELECT ?, id, ?, precio
  FROM productos
  WHERE id = ?
");

foreach ($items as $it) {
  $stmtDetalle->execute([$pedidoId, $it['qty'], $it['id']]);
}

echo json_encode([
  'ok' => true,
  'pedido_id' => $pedidoId
], JSON_UNESCAPED_UNICODE);

exit;
