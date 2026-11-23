<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/correo.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

validar_csrf();
$slug = campo_post('programa');
$programa = buscar_programa($slug);

if (!$programa) {
    guardar_mensaje('error', 'no encontramos ese programa.');
    header('Location: index.php#programas');
    exit;
}

$destino = 'programa.php?p=' . $slug;
exigir_acceso($destino);
$usuario = usuario_actual();
$asunto = 'tu guia para practicar en casa: ' . $programa['nombre'];
$contenido = correo_programa($usuario['nombre'], $programa);
$enviado = enviar_correo($usuario['correo'], $usuario['nombre'], $asunto, $contenido);

if ($enviado) {
    guardar_mensaje('ok', 'enviamos el programa a ' . $usuario['correo'] . '.');
} else {
    guardar_mensaje('error', 'no se pudo enviar el correo. revisa la configuracion smtp e intenta de nuevo.');
}

header('Location: ' . $destino);
exit;
