<?php
require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../database/conexion.php';

header('Content-Type: application/json; charset=utf-8');

start_session();

// Comprobar login
if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['ok'=>false,'error'=>'No autenticado'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok'=>false,'error'=>'Método no permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Leer datos
$input = json_decode(file_get_contents('php://input'), true) ?? [];

$tipo  = $input['tipo_entrega'] ?? '';
$items = $input['items'] ?? [];

// Validar tipo_entrega
if ($tipo !== 'recogida' && $tipo !== 'envio') {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Tipo de entrega inválido'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$items || !is_array($items)) {
    http_response_code(400);
    echo json_encode(['ok'=>false,'error'=>'Carrito vacío'], JSON_UNESCAPED_UNICODE);
    exit;
}

$pdo = db();
$usuarioId = (int)($_SESSION['user']['id'] ?? 0);

try {
    // Empezar transacción (si algo falla, no se guarda)
    $pdo->beginTransaction();

    //  Preparar consultas 
    // Bloquea el producto para evitar compras simultáneas con stock incorrecto
    $qProd = $pdo->prepare("SELECT id, precio, stock FROM productos WHERE id = ? FOR UPDATE");

    // Resta stock solo si hay suficiente
    $qRestar = $pdo->prepare("UPDATE productos SET stock = stock - ? WHERE id = ? AND stock >= ?");

    // Insertar detalle
    $qDetalle = $pdo->prepare("
        INSERT INTO pedido_detalles (pedido_id, producto_id, cantidad, precio_unitario)
        VALUES (?, ?, ?, ?)
    ");

    //  Calcular total validando stock
    $total = 0;

    foreach ($items as $it) {
        $prodId = (int)($it['id'] ?? 0);
        $qty    = (int)($it['qty'] ?? 0);

        if ($prodId <= 0 || $qty <= 0) {
            throw new Exception("Item inválido en carrito");
        }

        $qProd->execute([$prodId]);
        $prod = $qProd->fetch();

        if (!$prod) {
            throw new Exception("Producto no existe (ID $prodId)");
        }

        // Comprobar stock suficiente
        if ((int)$prod['stock'] < $qty) {
            throw new Exception("Stock insuficiente para el producto ID $prodId");
        }

        $total += ((float)$prod['precio']) * $qty;
    }

    //  Crear pedido
    $stmt = $pdo->prepare("
        INSERT INTO pedidos (usuario_id, total, direccion_entrega, estado_id, tipo_entrega)
        VALUES (?, ?, ?, 1, ?)
    ");

    $stmt->execute([
        $usuarioId,
        $total,
        null, // dirección simple por ahora
        $tipo
    ]);

    // ID del pedido recién creado
    $pedidoId = (int)$pdo->lastInsertId();

    // Insertar detalles + restar stock
    foreach ($items as $it) {
        $prodId = (int)$it['id'];
        $qty    = (int)$it['qty'];

        // Volvemos a sacar precio (o podrías guardarlo del bucle anterior)
        $qProd->execute([$prodId]);
        $prod = $qProd->fetch();

        $precio = (float)$prod['precio'];

        // Insertar detalle
        $qDetalle->execute([$pedidoId, $prodId, $qty, $precio]);

        // Restar stock (seguro)
        $qRestar->execute([$qty, $prodId, $qty]);
        if ($qRestar->rowCount() === 0) {
            throw new Exception("No se pudo actualizar stock (ID $prodId)");
        }
    }

    //  Confirmar todo
    $pdo->commit();

    echo json_encode([
        'ok' => true,
        'pedido_id' => $pedidoId
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    //  Si algo falla, se deshace todo
    if ($pdo->inTransaction()) $pdo->rollBack();

    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}