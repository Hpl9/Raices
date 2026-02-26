<?php
require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../database/conexion.php';

header('Content-Type: application/json; charset=utf-8');

start_session();

// Solo admin
if (empty($_SESSION['user']) || ($_SESSION['user']['rol'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'No autorizado'], JSON_UNESCAPED_UNICODE);
    exit;
}

$pdo = db();
$method = $_SERVER['REQUEST_METHOD'];

try {

    // LISTAR CLIENTES
    if ($method === 'GET') {

        $stmt = $pdo->query("
            SELECT 
                id,
                nombre,
                apellido,
                email,
                telefono,
                direccion,
                codigo_postal,
                poblacion,
                municipio
            FROM usuarios
            WHERE rol = 'cliente'
            ORDER BY id DESC
        ");

        echo json_encode([
            'ok' => true,
            'clientes' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // ELIMINAR CLIENTE (POST)
    if ($method === 'POST') {

        $id = (int)($_POST['id'] ?? 0);

        if ($id > 0) {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
        }

        echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
        exit;
    }

    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error servidor'], JSON_UNESCAPED_UNICODE);
}