<?php

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../backend/bootstrap.php';
require_once __DIR__ . '/../backend/utils/Response.php';
require_once __DIR__ . '/../backend/controllers/CatalogoController.php';

$slugPrograma = $_GET['p'] ?? '';
$slugActividad = $_GET['a'] ?? '';

if ($slugPrograma !== '' && $slugActividad !== '') {
    CatalogoController::detalleActividad($slugPrograma, $slugActividad);
} elseif ($slugPrograma !== '') {
    CatalogoController::detallePrograma($slugPrograma);
} else {
    CatalogoController::listar();
}
