<?php

// copia este archivo como config.local.php y agrega tus datos privados
return [
    'db_host' => '127.0.0.1',
    'db_user' => '',
    'db_pass' => '',
    'db_name' => 'apac_fresnillo',
    'smtp_host' => 'smtp-relay.brevo.com',
    'smtp_puerto' => 587,
    'smtp_usuario' => '', // Usuario SMTP de Brevo (o APAC_SMTP_USUARIO en el entorno)
    'smtp_clave' => '', // Clave SMTP de Brevo (o APAC_SMTP_CLAVE en el entorno)
    'correo_nombre' => 'apac fresnillo',

    // Conservado para instalaciones que también usen la API HTTP de Brevo:
    'brevo_api_key' => '', // Tu clave API de Brevo (xkeysib-...)
    'brevo_remitente' => '', // El correo remitente verificado en Brevo
];
