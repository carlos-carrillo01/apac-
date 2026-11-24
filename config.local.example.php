<?php

// copia este archivo como config.local.php y agrega tus datos privados
return [
    'db_host' => '127.0.0.1',
    'db_user' => '',
    'db_pass' => '',
    'db_name' => 'apac_fresnillo',
    'smtp_host' => 'smtp.gmail.com',
    'smtp_puerto' => 587,
    'smtp_usuario' => '',
    'smtp_clave' => '',
    'correo_nombre' => 'apac fresnillo',

    // Para hosting como InfinityFree (usa API HTTP sin puertos SMTP bloqueados):
    'brevo_api_key' => '', // Tu clave API de Brevo (xkeysib-...)
    'brevo_remitente' => '', // El correo que verificaste como remitente en Brevo
];
