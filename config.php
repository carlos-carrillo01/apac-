<?php

date_default_timezone_set('America/Mexico_City');

// carga valores privados desde el entorno o desde config.local.php
$config_local = [];
$config_privado = __DIR__ . '/config.local.php';

if (is_file($config_privado)) {
    $valores_privados = require $config_privado;

    if (is_array($valores_privados)) {
        $config_local = $valores_privados;
    }
}

// busca primero la variable del servidor y luego el archivo local
function apac_config($nombre, $predeterminado = '')
{
    global $config_local;
    $variable = getenv('APAC_' . strtoupper($nombre));

    if ($variable !== false && $variable !== '') {
        return $variable;
    }

    return $config_local[$nombre] ?? $predeterminado;
}

define('DB_HOST', apac_config('db_host', 'localhost'));
define('DB_USER', apac_config('db_user', 'root'));
define('DB_PASS', apac_config('db_pass', '1234'));
define('DB_NAME', apac_config('db_name', 'apac_fresnillo'));

define('SMTP_HOST', apac_config('smtp_host', 'smtp.gmail.com'));
define('SMTP_PUERTO', (int) apac_config('smtp_puerto', 587));
define('SMTP_USUARIO', apac_config('smtp_usuario', 'carloscarrillogonzalez07@gmail.com'));
define('SMTP_CLAVE', apac_config('smtp_clave', 'bliswtmttvozgylu'));
define('CORREO_NOMBRE', apac_config('correo_nombre', 'apac fresnillo'));

// Configuración de API HTTP para Brevo (ideal para InfinityFree y hostings que bloquean SMTP)
define('BREVO_API_KEY', apac_config('brevo_api_key', ''));
define('BREVO_REMITENTE', apac_config('brevo_remitente', apac_config('smtp_usuario', 'carloscarrillogonzalez07@gmail.com')));

define('APAC_TEL_1', '4939357179');
define('APAC_TEL_2', '4939357809');
define('APAC_HORARIO', 'lunes a viernes, de 8:00 a.m. a 4:00 p.m.');
define('APAC_DIRECCION', 'avenida fresnos no. 54, col. luis donaldo colosio, fresnillo, zacatecas');
define('APAC_COFEPRIS', '2432035036X00130');
