<?php

require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../utils/Session.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../../includes/correo.php';

class AuthController {
    /**
     * Inicia sesión con correo y contraseña
     */
    public static function login($datos) {
        $correo = trim((string) ($datos['correo'] ?? ''));
        $clave = (string) ($datos['clave'] ?? '');

        if ($correo === '' || $clave === '') {
            Response::error('Por favor escribe tu correo y contraseña.');
        }

        $usuario = Usuario::buscarPorCorreo($correo);

        if (!$usuario || !Usuario::verificarClave($clave, $usuario['clave_hash'])) {
            Response::error('Correo o contraseña incorrectos.', 401);
        }

        Session::setUsuario($usuario);

        Response::exito([
            'usuario' => [
                'id' => $usuario['id'],
                'nombre' => $usuario['nombre'],
                'correo' => $usuario['correo']
            ],
            'token_csrf' => Session::tokenCsrf()
        ], 'Sesión iniciada correctamente.');
    }

    /**
     * Registra un nuevo usuario
     */
    public static function registro($datos) {
        $nombre = trim((string) ($datos['nombre'] ?? ''));
        $correo = strtolower(trim((string) ($datos['correo'] ?? '')));
        $clave = (string) ($datos['clave'] ?? '');
        $confirmacion = (string) ($datos['confirmacion'] ?? '');

        if ($nombre === '' || mb_strlen($nombre) > 120) {
            Response::error('Escribe tu nombre (máximo 120 caracteres).');
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || mb_strlen($correo) > 190) {
            Response::error('Escribe un correo electrónico válido.');
        }

        if (mb_strlen($clave) < 8) {
            Response::error('La contraseña debe tener al menos 8 caracteres.');
        }

        if ($clave !== $confirmacion) {
            Response::error('Las contraseñas no coinciden.');
        }

        // Validar si el correo ya existe
        if (Usuario::buscarPorCorreo($correo)) {
            Response::error('Ese correo ya tiene una cuenta registrada. Por favor inicia sesión.', 409);
        }

        try {
            $nuevoUsuario = Usuario::crear($nombre, $correo, $clave);
            Session::setUsuario($nuevoUsuario);

            // Intentar enviar correo de bienvenida si SMTP está activo
            if (defined('SMTP_USUARIO') && SMTP_USUARIO !== '') {
                @enviar_correo($correo, $nombre, 'Bienvenido a las guías de APAC', correo_bienvenida($nombre));
            }

            Response::exito([
                'usuario' => $nuevoUsuario,
                'token_csrf' => Session::tokenCsrf()
            ], 'Cuenta creada exitosamente.', 201);
        } catch (Exception $e) {
            Response::error('No se pudo crear la cuenta en este momento. Inténtalo más tarde.', 500);
        }
    }

    /**
     * Retorna el estado actual de la sesión del usuario
     */
    public static function estado() {
        $usuario = Session::usuario();
        $tokenCsrf = Session::tokenCsrf();

        if ($usuario) {
            Response::exito([
                'autenticado' => true,
                'usuario' => $usuario,
                'token_csrf' => $tokenCsrf
            ]);
        } else {
            Response::exito([
                'autenticado' => false,
                'usuario' => null,
                'token_csrf' => $tokenCsrf
            ]);
        }
    }

    /**
     * Cierra la sesión
     */
    public static function logout() {
        Session::cerrar();
        Response::exito([], 'Sesión finalizada.');
    }
}
