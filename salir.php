<?php
require_once __DIR__ . '/backend/controllers/AuthController.php';
AuthController::logout();
header('Location: index.html');
exit;
