<?php

// Centraliza start_session() 


function start_session(): void
{
    // Si ya está activa, no hacemos nada
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    //  Cookies de sesión: configuración básica segura
    // En local (HTTP) 'secure' debe ser false.
    session_set_cookie_params([
        'lifetime' => 0,       // hasta cerrar navegador
        'path' => '/',
        'httponly' => true,    // JS no puede leer cookie
        'secure' => false,     // en producción con HTTPS: true
        'samesite' => 'Lax',   // ayuda contra CSRF básico
    ]);

    session_start();
}
