<?php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../backend/bootstrap.php';
require_once __DIR__ . '/../backend/utils/Response.php';
require_once __DIR__ . '/../backend/controllers/CorreoController.php';

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo !== 'POST') {
    Response::error('Método no permitido', 405);
}

$input = [];
$cuerpo = file_get_contents('php://input');
if (!empty($cuerpo)) {
    $json = json_decode($cuerpo, true);
    if (is_array($json)) {
        $input = $json;
    }
}
$input = array_merge($_POST, $input);

$slugPrograma = $input['programa'] ?? ($_GET['p'] ?? '');
CorreoController::enviarPrograma($slugPrograma);
