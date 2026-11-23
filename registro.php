<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/correo.php';

$volver = ruta_segura($_GET['volver'] ?? ($_POST['volver'] ?? ''));

// una persona con sesion activa puede continuar al destino solicitado
if (usuario_actual()) {
    header('Location: ' . $volver);
    exit;
}

$error = '';
$nombre = '';
$correo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $nombre = campo_post('nombre');
    $correo = strtolower(campo_post('correo'));
    $clave = campo_post('clave', false);
    $confirmacion = campo_post('confirmacion', false);

    // revisa los datos antes de crear la cuenta
    if ($nombre === '' || strlen($nombre) > 120) {
        $error = 'escribe tu nombre.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL) || strlen($correo) > 190) {
        $error = 'escribe un correo valido.';
    } elseif (strlen($clave) < 8) {
        $error = 'la clave debe tener al menos 8 caracteres.';
    } elseif ($clave !== $confirmacion) {
        $error = 'las claves no coinciden.';
    } else {
        $con = conexion();

        try {
            $hash = password_hash($clave, PASSWORD_DEFAULT);
            $consulta = $con->prepare('insert into usuarios (nombre, correo, clave_hash) values (?, ?, ?)');
            $consulta->bind_param('sss', $nombre, $correo, $hash);
            $consulta->execute();

            // inicia sesion sin guardar la clave en texto normal
            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id' => (int) $con->insert_id, 'nombre' => $nombre, 'correo' => $correo];
            enviar_correo($correo, $nombre, 'bienvenido a las guias de apac', correo_bienvenida($nombre));
            guardar_mensaje('ok', 'tu cuenta esta lista.');
            header('Location: ' . $volver);
            exit;
        } catch (mysqli_sql_exception $falla) {
            $error = $falla->getCode() === 1062
                ? 'ese correo ya tiene una cuenta. inicia sesion.'
                : 'no se pudo crear la cuenta. intenta mas tarde.';
        }
    }
}

mostrar_encabezado('crear cuenta', 'login');
?>
<section class="seccion">
    <div class="contenedor acceso">
        <div class="acceso-info">
            <span class="sobrelinea">cuenta gratuita</span>
            <h1>crea una cuenta</h1>
            <p>puedes registrar tus actividades, consultar tu avance y recibir las guias por correo.</p>
            <p class="chico">no escribas diagnosticos ni datos privados de salud en las notas.</p>
        </div>
        <div class="caja">
            <?php if ($error !== ''): ?><div class="mensaje error" role="alert"><?= e($error) ?></div><?php endif; ?>
            <form class="formulario" method="post" action="registro.php">
                <input type="hidden" name="token_csrf" value="<?= e(token_csrf()) ?>">
                <input type="hidden" name="volver" value="<?= e($volver) ?>">
                <label for="nombre">nombre</label>
                <input id="nombre" name="nombre" maxlength="120" autocomplete="name" value="<?= e($nombre) ?>" required>
                <label for="correo">correo electronico</label>
                <input id="correo" name="correo" type="email" maxlength="190" autocomplete="email" value="<?= e($correo) ?>" required>
                <label for="clave">clave (8 caracteres o mas)</label>
                <input id="clave" name="clave" type="password" minlength="8" autocomplete="new-password" required>
                <label for="confirmacion">repite la clave</label>
                <input id="confirmacion" name="confirmacion" type="password" minlength="8" autocomplete="new-password" required>
                <button class="boton boton-grande" type="submit">crear cuenta</button>
            </form>
            <p class="centrado">ya tienes cuenta? <a href="login.php?volver=<?= urlencode($volver) ?>">inicia sesion</a></p>
        </div>
    </div>
</section>
<?php mostrar_pie();
