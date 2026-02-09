<?php

require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../repositories/ProductoRepository.php';
require_once __DIR__ . '/../Entities/Producto.php';

start_session();
header('Content-Type: application/json; charset=utf-8');

function json_input(): array
{
    $raw = file_get_contents('php://input');
    if (!$raw) return [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function require_login(): array
{
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'No autenticado'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    return $_SESSION['user'];
}

try {
    $user = require_login();
    $usuarioId = (int)($user['id'] ?? 0);

    if ($usuarioId <= 0) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'Sesión inválida'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $repo   = new ProductoRepository();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $input  = json_input();

    match ($method) {

           // -----------------GET: LISTAR TODOS -------------------
        'GET' => (function () use ($repo) {
            $items = $repo->findAllConSocio();
            echo json_encode(['ok' => true, 'items' => $items], JSON_UNESCAPED_UNICODE);
            exit;
        })(),

           // --------------- POST: CREAR ---------------
        'POST' => (function () use ($repo, $input, $usuarioId) {

            $nombre = trim((string)($input['nombre'] ?? ''));
            if ($nombre === '') {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Nombre obligatorio'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $p = new Producto(
                null,
                $nombre,
                (string)($input['categoria'] ?? ''),
                (string)($input['descripcion'] ?? ''),
                (float)($input['precio'] ?? 0),
                (int)($input['stock'] ?? 0),
                (string)($input['unidad_medida'] ?? ''),
                (string)($input['imagen_url'] ?? ''),
                (string)($input['procedencia'] ?? ''),
                $usuarioId // siempre x sesión
            );

            $id = $repo->create($p);

            echo json_encode(['ok' => true, 'id' => $id], JSON_UNESCAPED_UNICODE);
            exit;
        })(),

        // ----------------- PUT: EDITAR (solo si es del socio propietario) ------------------
        'PUT' => (function () use ($repo, $input, $usuarioId) {

            $id = (int)($input['id'] ?? 0);
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Falta id'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $existe = $repo->findById($id, $usuarioId);
            if (!$existe) {
                http_response_code(403);
                echo json_encode(['ok' => false, 'error' => 'No puedes editar este producto. Solo el propietario del producto, puede realizar la función de editar o borrar'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $p = new Producto(
                $id,
                trim((string)($input['nombre'] ?? '')),
                (string)($input['categoria'] ?? ''),
                (string)($input['descripcion'] ?? ''),
                (float)($input['precio'] ?? 0),
                (int)($input['stock'] ?? 0),
                (string)($input['unidad_medida'] ?? ''),
                (string)($input['imagen_url'] ?? ''),
                (string)($input['procedencia'] ?? ''),
                $usuarioId
            );

            $ok = $repo->update($p);

            if (!$ok) {
                http_response_code(403);
                echo json_encode(['ok' => false, 'error' => 'No se pudo actualizar'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
            exit;
        })(),

        // ---------------------- DELETE: BORRAR (solo si es tuyo) -----------------------
        'DELETE' => (function () use ($repo, $input, $usuarioId) {

            $id = isset($_GET['id']) ? (int)$_GET['id'] : (int)($input['id'] ?? 0);
            if ($id <= 0) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Falta id'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $ok = $repo->delete($id, $usuarioId);

            if (!$ok) {
                http_response_code(403);
                echo json_encode(['ok' => false, 'error' => 'No puedes borrar este producto. Solo el propietario del producto, puede realizar la función de editar o borrar'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
            exit;
        })(),

        //---------------------- DEFAULT ----------------------
        default => (function () {
            http_response_code(405);
            echo json_encode(['ok' => false, 'error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
            exit;
        })(),
    };

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
