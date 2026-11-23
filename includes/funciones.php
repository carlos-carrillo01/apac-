<?php
// funciones compartidas por las paginas de apac
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/datos.php';

date_default_timezone_set('America/Mexico_City');

// inicia la sesion para reconocer a la persona que ingreso
if (session_status() !== PHP_SESSION_ACTIVE) {
    $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['httponly' => true, 'secure' => $https, 'samesite' => 'Lax', 'path' => '/']);
    session_start();
}

// abre la conexion con mysql cuando una pagina la necesita
function conexion()
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } catch (mysqli_sql_exception $error) {
        http_response_code(500);
        exit('no se pudo conectar con mysql. revisa la configuracion y la base de datos.');
    }

    $con->set_charset('utf8mb4');
    return $con;
}

// escapa texto antes de mostrarlo como html
function e($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

// lee un campo enviado por un formulario
function campo_post($nombre, $recortar = true)
{
    $valor = $_POST[$nombre] ?? '';

    if (!is_string($valor)) {
        return '';
    }

    return $recortar ? trim($valor) : $valor;
}

// devuelve la cuenta que esta activa
function usuario_actual()
{
    return $_SESSION['usuario'] ?? null;
}

// solo permite regresar a una pagina interna
function ruta_segura($ruta, $por_defecto = 'mi_avance.php')
{
    if (is_string($ruta) && preg_match('/^[a-z_]+\.php(\?[a-z0-9_=&-]*)?$/', $ruta)) {
        return $ruta;
    }

    return $por_defecto;
}

// envia a la pagina de entrada cuando hace falta iniciar sesion
function exigir_acceso($volver = 'mi_avance.php')
{
    if (!usuario_actual()) {
        header('Location: login.php?volver=' . urlencode(ruta_segura($volver)));
        exit;
    }
}

// crea un codigo para proteger los formularios
function token_csrf()
{
    if (empty($_SESSION['token_csrf'])) {
        $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['token_csrf'];
}

// revisa el codigo enviado por un formulario
function validar_csrf()
{
    $token = $_POST['token_csrf'] ?? '';

    if (!is_string($token) || !hash_equals(token_csrf(), $token)) {
        http_response_code(400);
        exit('el formulario vencio. regresa e intenta de nuevo.');
    }
}

// guarda un aviso para mostrarlo despues de cambiar de pagina
function guardar_mensaje($tipo, $texto)
{
    $_SESSION['mensaje_flash'] = ['tipo' => $tipo, 'texto' => $texto];
}

// lee y borra el aviso guardado
function leer_mensaje()
{
    $mensaje = $_SESSION['mensaje_flash'] ?? null;
    unset($_SESSION['mensaje_flash']);
    return $mensaje;
}

// muestra el encabezado de todas las paginas
function mostrar_encabezado($titulo, $actual = '')
{
    $usuario = usuario_actual();
    ?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?> | apac fresnillo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<a class="saltar" href="#contenido">saltar al contenido</a>
<div class="barra-superior"><?= e(APAC_HORARIO) ?> | tel. <?= e(APAC_TEL_1) ?> y <?= e(APAC_TEL_2) ?></div>
<header class="encabezado">
    <div class="contenedor encabezado-interno">
        <a class="logo" href="index.php">
            <img src="img/logo.png" alt="">
            <span><b>apac</b> fresnillo<small>terapia guiada en casa</small></span>
        </a>
        <nav aria-label="menu principal">
            <a href="index.php" <?= $actual === 'inicio' ? 'class="activo"' : '' ?>>inicio</a>
            <a href="guia.php" <?= $actual === 'guia' ? 'class="activo"' : '' ?>>elige una guia</a>
            <a href="index.php#programas" <?= $actual === 'programas' ? 'class="activo"' : '' ?>>programas</a>
            <?php if ($usuario): ?>
                <a href="mi_avance.php" <?= $actual === 'mi_avance' ? 'class="activo"' : '' ?>>mi avance</a>
                <form method="post" action="salir.php" class="form-en-linea">
                    <input type="hidden" name="token_csrf" value="<?= e(token_csrf()) ?>">
                    <button type="submit" class="boton-enlace">salir</button>
                </form>
            <?php else: ?>
                <a href="login.php" <?= $actual === 'login' ? 'class="activo"' : '' ?>>entrar</a>
                <a class="boton boton-chico" href="registro.php">crear cuenta</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main id="contenido">
<?php
    $mensaje = leer_mensaje();

    if ($mensaje) {
        echo '<div class="contenedor"><div class="mensaje ' . e($mensaje['tipo']) . '" role="status">' . e($mensaje['texto']) . '</div></div>';
    }
}

// muestra el pie de todas las paginas
function mostrar_pie()
{
    ?>
</main>
<footer class="pie">
    <div class="contenedor pie-interno">
        <div>
            <strong>apac fresnillo</strong>
            <p>asociacion pro personas con paralisis cerebral<br>reg. cofepris <?= e(APAC_COFEPRIS) ?></p>
        </div>
        <div>
            <strong>contacto</strong>
            <p><?= e(APAC_HORARIO) ?><br>tel. <?= e(APAC_TEL_1) ?> y <?= e(APAC_TEL_2) ?><br>facebook: apac fresnillo | instagram y x: @apacfresnillo</p>
        </div>
        <div>
            <strong>importante</strong>
            <p>estas guias son un apoyo para practicar en casa. no son un diagnostico ni sustituyen la valoracion o el tratamiento de profesionales de salud.</p>
        </div>
    </div>
</footer>
</body>
</html>
<?php
}

// muestra los pasos fijos de una actividad
function mostrar_pasos($pasos)
{
    echo '<ol class="linea-tiempo"><li><div><p>' . implode('</p></div></li><li><div><p>', array_map('e', $pasos)) . '</p></div></li></ol>';
}

// muestra una tarjeta de actividad conocida en el catalogo
function mostrar_tarjeta_actividad($slug_programa, $slug_actividad, $numero, $hechas = [])
{
    $actividad = buscar_actividad($slug_programa, $slug_actividad);

    if (!$actividad) {
        return;
    }
    ?>
    <article class="tarjeta">
        <a class="tarjeta-foto" href="actividad.php?p=<?= e($slug_programa) ?>&amp;a=<?= e($slug_actividad) ?>">
            <img src="<?= e(imagen_actividad($slug_programa, $slug_actividad)) ?>" alt="ilustracion de <?= e($actividad['nombre']) ?>" loading="lazy">
        </a>
        <div class="tarjeta-texto">
            <span class="etiqueta">actividad <?= (int) $numero ?> | <?= e($actividad['duracion']) ?></span>
            <h3><?= e($actividad['nombre']) ?></h3>
            <p><?= e($actividad['objetivo']) ?></p>
            <?php if (isset($hechas[$slug_actividad])): ?>
                <p class="hecha">la has hecho <?= (int) $hechas[$slug_actividad]['veces'] ?> veces</p>
            <?php endif; ?>
            <a class="enlace-flecha" href="actividad.php?p=<?= e($slug_programa) ?>&amp;a=<?= e($slug_actividad) ?>">ver la guia</a>
        </div>
    </article>
    <?php
}
