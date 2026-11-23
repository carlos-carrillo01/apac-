<?php
require_once __DIR__ . '/includes/funciones.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

validar_csrf();
$slug_programa = campo_post('programa');
$slug_actividad = campo_post('actividad');
$volver = 'actividad.php?p=' . $slug_programa . '&a=' . $slug_actividad;
exigir_acceso($volver);
$usuario = usuario_actual();

// confirma que el programa y la actividad esten en el catalogo
if (!buscar_actividad($slug_programa, $slug_actividad)) {
    guardar_mensaje('error', 'no encontramos esa actividad.');
    header('Location: index.php#programas');
    exit;
}

$como_fue = campo_post('como_fue');

if (!in_array($como_fue, ['bien', 'regular', 'molestia'], true)) {
    guardar_mensaje('error', 'elige una opcion para guardar la practica.');
    header('Location: ' . $volver . '#registrar');
    exit;
}

// limita la nota a 200 caracteres
preg_match('/^.{0,200}/us', campo_post('nota'), $parte);
$nota = ($parte[0] ?? '') === '' ? null : $parte[0];
$ahora = date('Y-m-d H:i:s');

// guarda la practica de la cuenta activa
$con = conexion();
$consulta = $con->prepare('insert into practicas (usuario_id, programa, actividad, como_fue, nota, creado_en) values (?, ?, ?, ?, ?, ?)');
$consulta->bind_param('isssss', $usuario['id'], $slug_programa, $slug_actividad, $como_fue, $nota, $ahora);
$consulta->execute();

if ($como_fue === 'molestia') {
    guardar_mensaje('aviso', 'guardamos tu practica. descansa y consulta con tu profesional antes de repetir la actividad.');
} else {
    guardar_mensaje('ok', 'guardamos tu practica.');
}

header('Location: mi_avance.php');
exit;
