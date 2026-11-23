<?php
require_once __DIR__ . '/includes/funciones.php';

// el formulario de salida envia una solicitud post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'] ?? '', $p['secure'], $p['httponly']);
    }

    session_destroy();
}

header('Location: index.php');
exit;
