<?php
require_once __DIR__ . '/backend/controllers/CorreoController.php';

$slug = $_POST['programa'] ?? ($_GET['p'] ?? '');
CorreoController::enviarPrograma($slug);
