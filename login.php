<?php
require_once __DIR__ . '/includes/funciones.php';

$volver = ruta_segura($_GET['volver'] ?? ($_POST['volver'] ?? ''));

if (usuario_actual()) {
    header('Location: ' . $volver);
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $correo = strtolower(campo_post('correo'));
    $clave = campo_post('clave', false);

    // busca la cuenta y compara la clave con su hash
    $con = conexion();
    $consulta = $con->prepare('select id, nombre, correo, clave_hash from usuarios where correo = ? limit 1');
    $consulta->bind_param('s', $correo);
    $consulta->execute();
    $usuario = $consulta->get_result()->fetch_assoc();

    if ($usuario && password_verify($clave, $usuario['clave_hash'])) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = ['id' => (int) $usuario['id'], 'nombre' => $usuario['nombre'], 'correo' => $usuario['correo']];
        header('Location: ' . $volver);
        exit;
    }

    $error = 'correo o clave incorrectos.';
}

mostrar_encabezado('iniciar sesion', 'login');
?>
<section class="seccion">
    <div class="contenedor acceso">
        <div class="acceso-info">
            <span class="sobrelinea">tu cuenta</span>
            <h1>inicia sesion</h1>
            <p>entra para registrar una practica y consultar tu avance.</p>
        </div>
        <div class="caja">
            <?php if ($error !== ''): ?><div class="mensaje error" role="alert"><?= e($error) ?></div><?php endif; ?>
            <form class="formulario" method="post" action="login.php">
                <input type="hidden" name="token_csrf" value="<?= e(token_csrf()) ?>">
                <input type="hidden" name="volver" value="<?= e($volver) ?>">
                <label for="correo">correo electronico</label>
                <input id="correo" name="correo" type="email" autocomplete="email" required>
                <label for="clave">clave</label>
                <input id="clave" name="clave" type="password" autocomplete="current-password" required>
                <button class="boton boton-grande" type="submit">entrar</button>
            </form>
            <p class="centrado">no tienes cuenta? <a href="registro.php?volver=<?= urlencode($volver) ?>">crear una cuenta</a></p>
        </div>
    </div>
</section>
<?php mostrar_pie();
