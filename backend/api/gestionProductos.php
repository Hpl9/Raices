<?php


require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../repositories/ProductoRepository.php';

start_session();

header('Content-Type: application/json; charset=utf-8');

try {

    // ------------------ GET------------ -------
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(['ok' => false, 'error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
        exit;
    }

   /*  // -------------------- DELETE ---------------
    if ($method === 'DELETE') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Falta id'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $ok = $repo->delete($id);
        echo json_encode(['ok' => $ok], JSON_UNESCAPED_UNICODE);
        exit;
    }*/

    //  Proteger: solo admin
    if (!isset($_SESSION['user']) || ($_SESSION['user']['rol'] ?? '') !== 'admin') {
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'No autorizado'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $repo = new ProductoRepository();
    $items = $repo->findAllConSocio();  // Lista (viene del repo)

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

