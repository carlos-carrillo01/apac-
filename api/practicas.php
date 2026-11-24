<?php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../backend/controllers/PracticaController.php';

$metodo = $_SERVER['REQUEST_METHOD'];

// Leer cuerpo JSON si existe
$input = [];
$cuerpo = file_get_contents('php://input');
if (!empty($cuerpo)) {
    $json = json_decode($cuerpo, true);
    if (is_array($json)) {
        $input = $json;
    }
}
$input = array_merge($_POST, $input);

switch ($metodo) {
    case 'GET':
        if (isset($_GET['programa']) && $_GET['programa'] !== '') {
            PracticaController::porPrograma($_GET['programa']);
        } else {
            PracticaController::resumen();
        }
        break;

    case 'POST':
        // Si se pide eliminar vía POST
        if (isset($input['accion']) && $input['accion'] === 'eliminar') {
            PracticaController::eliminar($input['id'] ?? null);
        } else {
            PracticaController::guardar($input);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? ($input['id'] ?? null);
        PracticaController::eliminar($id);
        break;

    default:
        Response::error('Método no permitido', 405);
}
