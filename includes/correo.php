<?php

require_once __DIR__ . '/../lib/PHPMailer/Exception.php';
require_once __DIR__ . '/../lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

// envia un correo por smtp y devuelve true si termina bien
function enviar_correo($para, $nombre, $asunto, $html)
{
    if (SMTP_USUARIO === '' || SMTP_CLAVE === '') {
        return false;
    }

    try {
        $correo = new PHPMailer(true);
        $correo->isSMTP();
        $correo->Host = SMTP_HOST;
        $correo->SMTPAuth = true;
        $correo->Username = SMTP_USUARIO;
        $correo->Password = SMTP_CLAVE;
        $correo->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $correo->Port = SMTP_PUERTO;
        $correo->CharSet = 'UTF-8';
        $correo->setFrom(SMTP_USUARIO, CORREO_NOMBRE);
        $correo->addAddress($para, $nombre);
        $correo->isHTML(true);
        $correo->Subject = $asunto;
        $correo->Body = $html;
        $correo->AltBody = trim(strip_tags(str_replace(['</p>', '<br>', '</li>', '</h2>', '</h3>'], "\n", $html)));
        $correo->send();
        return true;
    } catch (Throwable $error) {
        return false;
    }
}

// agrega un marco sencillo al contenido del correo
function correo_marco($contenido)
{
    return '<div style="background:#f4f8f6;padding:24px 12px;font-family:Arial,Helvetica,sans-serif;color:#263b35">'
        . '<div style="max-width:600px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;border:1px solid #d3e4dc">'
        . '<div style="background:#24594f;color:#fff;padding:18px 24px;font-size:20px"><strong>apac</strong> fresnillo</div>'
        . '<div style="padding:24px;line-height:1.6;font-size:16px">' . $contenido . '</div>'
        . '<div style="background:#f4f8f6;padding:16px 24px;font-size:13px;color:#63756f">'
        . e(APAC_HORARIO) . '<br>tel. ' . e(APAC_TEL_1) . ' y ' . e(APAC_TEL_2) . '<br>' . e(APAC_DIRECCION)
        . '<br><br>estas guias son ideas de apoyo y no sustituyen la valoracion de profesionales de salud.</div>'
        . '</div></div>';
}

// envia un mensaje de bienvenida al registrar una cuenta
function correo_bienvenida($nombre)
{
    $html = '<h2 style="color:#24594f;margin-top:0">hola, ' . e($nombre) . '</h2>'
        . '<p>tu cuenta esta lista. puedes consultar las guias y registrar tus actividades.</p>'
        . '<p>elige una guia que sea adecuada para tu situacion. si algo te preocupa, pide orientacion profesional.</p>';

    return correo_marco($html);
}

// convierte las actividades fijas del catalogo a contenido de correo
function correo_actividad_html($actividad)
{
    $titulo = 'style="color:#24594f"';
    $html = '<h3 ' . $titulo . '>' . e($actividad['nombre']) . '</h3>'
        . '<p><em>' . e($actividad['duracion']) . ' | ' . e($actividad['objetivo']) . '</em></p>'
        . '<h4>materiales</h4><ul><li>' . implode('</li><li>', array_map('e', $actividad['materiales'])) . '</li></ul>'
        . '<h4>pasos</h4><ol><li>' . implode('</li><li>', array_map('e', $actividad['pasos'])) . '</li></ol>'
        . '<p><strong>mas sencillo:</strong> ' . e($actividad['mas_facil']) . '</p>';

    if ($actividad['cuidado'] !== '') {
        $html .= '<p><strong>cuidado:</strong> ' . e($actividad['cuidado']) . '</p>';
    }

    return $html;
}

// prepara el programa completo para enviarlo por correo
function correo_programa($nombre, $programa)
{
    $titulo = 'style="color:#24594f"';
    $html = '<h2 style="color:#24594f;margin-top:0">hola, ' . e($nombre) . '</h2>'
        . '<p>esta es la guia: <strong>' . e($programa['nombre']) . '</strong>.</p>'
        . '<p>' . e($programa['resumen']) . '</p>'
        . '<h3 ' . $titulo . '>materiales</h3><ul><li>' . implode('</li><li>', array_map('e', $programa['materiales'])) . '</li></ul>'
        . '<h3 ' . $titulo . '>antes de empezar</h3><ul><li>' . implode('</li><li>', array_map('e', $programa['antes_de_empezar'])) . '</li></ul>'
        . '<p><strong>detente y busca orientacion si:</strong></p><ul><li>' . implode('</li><li>', array_map('e', $programa['detente_si'])) . '</li></ul>'
        . '<p><strong>frecuencia sugerida:</strong> ' . e($programa['frecuencia']) . '</p>';

    $actividades = array_map('correo_actividad_html', array_values($programa['actividades']));
    $html .= implode('', $actividades);
    $html .= '<p>si tienes dudas, contacta a apac o al equipo profesional que acompana a tu familia.</p>';

    return correo_marco($html);
}
