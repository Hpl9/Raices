<?php

// POST action=login  -> inicia sesión
// POST action=logout -> cierra sesión

require_once __DIR__ . '/../database/conexion.php';
require_once __DIR__ . '/../utils/session.php';

start_session();
header('Content-Type: application/json; charset=utf-8');

//  Solo permitimos POST (evita que alguien haga login por URL con GET)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_POST['action'] ?? '';


// ---------------LOGOUT--------------------------------------------

if ($action === 'logout') {
    //  Vaciar variables de sesión
    $_SESSION = [];

    //  Borrar cookie de sesión 
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();

    echo json_encode(['ok' => true, 'message' => 'Sesión cerrada'], JSON_UNESCAPED_UNICODE);
    exit;
}


// ---------------LOGIN-----------------------------------------------

if ($action !== 'login') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Acción inválida'], JSON_UNESCAPED_UNICODE);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Email y contraseña son obligatorios'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = db();

    // Buscar usuario por email (prepared statement)
    $stmt = $pdo->prepare("SELECT id, nombre, email, password_hash, rol FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    // Error genérico (no decimos si el email existe o no)
    if (!$user || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'Credenciales incorrectas'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    //  Punto decisivo: regenerar ID de sesión tras login
    // Evita fijación/secuestro de sesión
    session_regenerate_id(true);

    //  Guardamos solo lo necesario (NUNCA password_hash)
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'name' => $user['nombre'],
        'email' => $user['email'],
        'rol' => $user['rol']
    ];

    echo json_encode(['ok' => true, 'user' => $_SESSION['user']], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Error de servidor'], JSON_UNESCAPED_UNICODE);
}
