<?php
require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../database/conexion.php';

header('Content-Type: application/json; charset=utf-8');
start_session();

if (empty($_SESSION['user']) || ($_SESSION['user']['rol'] ?? '') !== 'admin') {
  http_response_code(403);
  echo json_encode(['ok'=>false,'error'=>'No autorizado'], JSON_UNESCAPED_UNICODE);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  http_response_code(405);
  echo json_encode(['ok'=>false,'error'=>'Método no permitido'], JSON_UNESCAPED_UNICODE);
  exit;
}

$pedidoId = (int)($_GET['id'] ?? 0);
if ($pedidoId <= 0) {
  http_response_code(400);
  echo json_encode(['ok'=>false,'error'=>'ID inválido'], JSON_UNESCAPED_UNICODE);
  exit;
}

$pdo = db();

try {
  // (Opcional) validar que existe el pedido
  $st = $pdo->prepare("SELECT id, total FROM pedidos WHERE id = ? LIMIT 1");
  $st->execute([$pedidoId]);
  $pedido = $st->fetch();
  if (!$pedido) {
    http_response_code(404);
    echo json_encode(['ok'=>false,'error'=>'Pedido no encontrado'], JSON_UNESCAPED_UNICODE);
    exit;
  }

  // Detalles
  $sql = "
    SELECT
      pd.producto_id,
      p.nombre AS producto,
      pd.cantidad,
      pd.precio_unitario,
      (pd.cantidad * pd.precio_unitario) AS subtotal
    FROM pedido_detalles pd
    JOIN productos p ON p.id = pd.producto_id
    WHERE pd.pedido_id = ?
    ORDER BY p.nombre ASC
  ";
  $st = $pdo->prepare($sql);
  $st->execute([$pedidoId]);
  $items = $st->fetchAll();

  echo json_encode([
    'ok' => true,
    'pedido_id' => $pedidoId,
    'items' => $items,
    'total' => (float)$pedido['total'],
  ], JSON_UNESCAPED_UNICODE);
  exit;

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['ok'=>false,'error'=>'Error interno'], JSON_UNESCAPED_UNICODE);
  exit;
}