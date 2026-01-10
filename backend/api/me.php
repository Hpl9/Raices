<?php

// Devuelve en JSON si hay sesión iniciada y datos básicos.


require_once __DIR__ . '/../utils/session.php';

start_session();

header('Content-Type: application/json; charset=utf-8');

// Respuesta estándar para el frontend
echo json_encode([
    'ok' => true,
    'logged' => isset($_SESSION['user']),
    'user' => $_SESSION['user'] ?? null
], JSON_UNESCAPED_UNICODE);
