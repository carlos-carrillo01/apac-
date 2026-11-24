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
        $programa = Catalogo::buscarPrograma($slug);

        if (!$programa) {
            Response::error('No encontramos ese programa.', 404);
        }

        $asunto = 'Tu guía para practicar en casa: ' . $programa['nombre'];
        $contenido = correo_programa($usuario['nombre'], $programa);
        $enviado = enviar_correo($usuario['correo'], $usuario['nombre'], $asunto, $contenido);

        if ($enviado) {
            Response::exito([], 'Enviamos el programa a ' . $usuario['correo'] . '.');
        } else {
            Response::error('No se pudo enviar el correo. Revisa la configuración SMTP del servidor.', 500);
        }
    }
}
