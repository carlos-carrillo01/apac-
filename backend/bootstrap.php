<?php
/**
 * Bootstrap centralizado para todos los endpoints de la API.
 * Carga la configuración, inicia sesión y registra el manejador de errores.
 */

// Configuración base y constantes (DB, SMTP, datos de APAC, zona horaria)
require_once __DIR__ . '/../config.php';

// Iniciar sesión de forma segura antes de cualquier controlador
if (session_status() !== PHP_SESSION_ACTIVE) {
    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure'   => $https,
        'samesite' => 'Lax',
        'path'     => '/'
    ]);
    session_start();
}

// En producción activa esto para no filtrar errores PHP en la respuesta JSON
// ini_set('display_errors', '0');
// error_reporting(0);

// Capturar errores fatales no atrapados y devolverlos como JSON limpio
set_exception_handler(function (Throwable $e) {
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode([
        'ok'    => false,
        'error' => 'Error interno del servidor.',
        'debug' => $e->getMessage() // Quita esta línea en producción
    ], JSON_UNESCAPED_UNICODE);
    exit;
});
