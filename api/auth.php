<?php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../backend/bootstrap.php';
require_once __DIR__ . '/../backend/utils/Response.php';
require_once __DIR__ . '/../backend/controllers/AuthController.php';

$metodo = $_SERVER['REQUEST_METHOD'];
$accion = $_GET['accion'] ?? '';

// Si la petición es POST y contiene JSON en el cuerpo, leerlo
$input = [];
if ($metodo === 'POST') {
    $cuerpo = file_get_contents('php://input');
    if (!empty($cuerpo)) {
        $json = json_decode($cuerpo, true);
        if (is_array($json)) {
            $input = $json;
        }
    }
    // Si no vino por JSON o viene combinado con $_POST
    $input = array_merge($_POST, $input);
    if (empty($accion) && !empty($input['accion'])) {
        $accion = $input['accion'];
    }
}

switch ($accion) {
    case 'login':
        if ($metodo !== 'POST') {
            Response::error('Método no permitido', 405);
        }
        AuthController::login($input);
        break;

    case 'registro':
        if ($metodo !== 'POST') {
            Response::error('Método no permitido', 405);
        }
        AuthController::registro($input);
        break;

    case 'logout':
        if ($metodo !== 'POST') {
            Response::error('Método no permitido', 405);
        }
        AuthController::logout();
        break;

    case 'estado':
    default:
        AuthController::estado();
        break;
}
