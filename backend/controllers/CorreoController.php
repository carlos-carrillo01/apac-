<?php

require_once __DIR__ . '/../models/Catalogo.php';
require_once __DIR__ . '/../utils/Session.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../../includes/correo.php';

class CorreoController {
    /**
     * Envía la guía completa del programa por correo al usuario en sesión
     */
    public static function enviarPrograma($slugPrograma) {
        $usuario = Session::exigirAcceso();
        Session::validarCsrf();

        $slug = trim((string) $slugPrograma);

        if ($slug === '') {
            Response::error('No se especificó el programa a enviar.', 400);
        }

        $programa = Catalogo::buscarPrograma($slug);

        if (!$programa) {
            Response::error('No encontramos ese programa en el catálogo.', 404);
        }

        // Verificar que SMTP esté configurado antes de intentar enviar
        if (!defined('SMTP_USUARIO') || SMTP_USUARIO === '' || !defined('SMTP_CLAVE') || SMTP_CLAVE === '') {
            Response::error(
                'El correo no está configurado en el servidor. Contacta al administrador para habilitar el envío de guías por email.',
                503
            );
        }

        try {
            $asunto    = 'Tu guía para practicar en casa: ' . $programa['nombre'];
            $contenido = correo_programa($usuario['nombre'], $programa);
            $enviado   = enviar_correo($usuario['correo'], $usuario['nombre'], $asunto, $contenido);
        } catch (Throwable $e) {
            Response::error(
                'Ocurrió un error al preparar el correo: ' . $e->getMessage(),
                500
            );
        }

        if ($enviado) {
            Response::exito([], 'Enviamos el programa a ' . $usuario['correo'] . '. Revisa tu bandeja de entrada.');
        } else {
            Response::error(
                'El correo no pudo enviarse. Verifica que las credenciales SMTP estén bien configuradas en config.local.php.',
                500
            );
        }
    }
}
