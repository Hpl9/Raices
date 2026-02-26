<?php
require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../database/conexion.php';

header('Content-Type: application/json; charset=utf-8');

start_session();

$pdo = db();



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Método no permitido'], JSON_UNESCAPED_UNICODE);
  exit;
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);
if (!is_array($data)) $data = [];

//  VALIDACIÓN RGPD 
if (empty($data['acepta_privacidad'])) {
  http_response_code(400);
  echo json_encode([
    'ok' => false,
    'error' => 'Debes aceptar la Política de Privacidad'
  ], JSON_UNESCAPED_UNICODE);
  exit;
}

function s($v) { return is_string($v) ? trim($v) : ''; }

$nombre   = s($data['nombre'] ?? '');
$apellido = s($data['apellido'] ?? '');
$email    = strtolower(s($data['email'] ?? ''));
$password = (string)($data['password'] ?? '');

$telefono      = s($data['telefono'] ?? '');
$direccion     = s($data['direccion'] ?? '');
$codigo_postal = s($data['codigo_postal'] ?? '');
$poblacion     = s($data['poblacion'] ?? '');
$municipio     = s($data['municipio'] ?? '');



// Validaciónes
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Email no válido'], JSON_UNESCAPED_UNICODE);
  exit;
}

if (strlen($password) < 6) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Contraseña demasiado corta'], JSON_UNESCAPED_UNICODE);
  exit;
}


if ($telefono !== '' && !preg_match('/^\+?\d{9,15}$/', $telefono)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Teléfono no válido'], JSON_UNESCAPED_UNICODE);
  exit;
}

if ($codigo_postal !== '' && !preg_match('/^\d{5}$/', $codigo_postal)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Código postal no válido'], JSON_UNESCAPED_UNICODE);
  exit;
}

// pasar hash pswd
$hash = password_hash($password, PASSWORD_BCRYPT);


if ($hash === false) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'No se pudo procesar la contraseña'], JSON_UNESCAPED_UNICODE);
  exit;
}

try {
  // Comprobación de email 
  $st = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
  $st->execute([$email]);
  if ($st->fetchColumn()) {
    http_response_code(409);
    echo json_encode(['ok' => false, 'error' => 'Ese email ya está registrado'], JSON_UNESCAPED_UNICODE);
    exit;
  }

  $sql = "INSERT INTO usuarios
    (nombre, apellido, email, contrasena_hash, telefono, rol, direccion, codigo_postal, poblacion, municipio)
    VALUES
    (:nombre, :apellido, :email, :hash, :telefono, 'cliente', :direccion, :cp, :poblacion, :municipio)";

  $ins = $pdo->prepare($sql);
  $ins->execute([
    ':nombre'   => $nombre,
    ':apellido' => $apellido,
    ':email'    => $email,
    ':hash'     => $hash,
    ':telefono' => ($telefono !== '' ? $telefono : null),
    ':direccion'=> ($direccion !== '' ? $direccion : null),
    ':cp'       => ($codigo_postal !== '' ? $codigo_postal : null),
    ':poblacion'=> ($poblacion !== '' ? $poblacion : null),
    ':municipio'=> ($municipio !== '' ? $municipio : null),
  ]);

  echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
  exit;

} catch (PDOException $e) {
  // Duplicate entry 
  if ((int)($e->errorInfo[1] ?? 0) === 1062) {
    http_response_code(409);
    echo json_encode(['ok' => false, 'error' => 'Ese email ya está registrado'], JSON_UNESCAPED_UNICODE);
    exit;
  }

  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'Error interno al registrar'], JSON_UNESCAPED_UNICODE);
  exit;
}