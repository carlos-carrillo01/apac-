<?php

class Session {
    /**
     * Inicia la sesión PHP con parámetros de cookies seguros
     */
    public static function iniciar() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
            session_set_cookie_params([
                'httponly' => true,
                'secure' => $https,
                'samesite' => 'Lax',
                'path' => '/'
            ]);
            session_start();
        }
    }

    /**
     * Obtiene el usuario autenticado actualmente
     */
    public static function usuario() {
        self::iniciar();
        return $_SESSION['usuario'] ?? null;
    }

    /**
     * Obtiene el ID del usuario en sesión
     */
    public static function usuarioId() {
        $usuario = self::usuario();
        return $usuario ? (int) $usuario['id'] : null;
    }

    /**
     * Guarda el usuario en sesión
     */
    public static function setUsuario($usuario) {
        self::iniciar();
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id' => (int) $usuario['id'],
            'nombre' => $usuario['nombre'],
            'correo' => $usuario['correo']
        ];
    }

    /**
     * Destruye la sesión actual (logout)
     */
    public static function cerrar() {
        self::iniciar();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    /**
     * Detiene la ejecución y responde 401 si no hay usuario en sesión
     */
    public static function exigirAcceso() {
        $usuario = self::usuario();
        if (!$usuario) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => false, 'error' => 'No autorizado. Debes iniciar sesión.']);
            exit;
        }
        return $usuario;
    }

    /**
     * Genera o retorna el token CSRF para peticiones seguras
     */
    public static function tokenCsrf() {
        self::iniciar();
        if (empty($_SESSION['token_csrf'])) {
            $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['token_csrf'];
    }

    /**
     * Valida el token CSRF enviado por header o por body
     */
    public static function validarCsrf($token = null) {
        self::iniciar();
        if ($token === null) {
            if (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
                $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
            } else {
                $headers = function_exists('getallheaders') ? getallheaders() : [];
                $headersLower = [];
                foreach ($headers as $k => $v) {
                    $headersLower[strtolower($k)] = $v;
                }
                $token = $headersLower['x-csrf-token'] ?? ($_POST['token_csrf'] ?? '');
            }
        }

        if (!is_string($token) || !hash_equals(self::tokenCsrf(), $token)) {
            http_response_code(403);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => false, 'error' => 'Token de seguridad inválido o expirado.']);
            exit;
        }
    }
}
