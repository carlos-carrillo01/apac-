<?php
require_once __DIR__ . '/backend/controllers/PracticaController.php';

$input = array_merge($_GET, $_POST);
PracticaController::guardar($input);
