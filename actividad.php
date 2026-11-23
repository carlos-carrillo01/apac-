<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/avance.php';

$slug_programa = $_GET['p'] ?? '';
$slug_actividad = $_GET['a'] ?? '';
$programa = buscar_programa($slug_programa);
$actividad = buscar_actividad($slug_programa, $slug_actividad);

if (!$programa || !$actividad) {
    header('Location: index.php#programas');
    exit;
}

$usuario = usuario_actual();
$volver = 'actividad.php?p=' . $slug_programa . '&a=' . $slug_actividad;
$hechas = $usuario ? practicas_por_actividad($usuario['id'], $slug_programa) : [];
$mias = $hechas[$slug_actividad] ?? null;
$claves = array_keys($programa['actividades']);
$posicion = array_search($slug_actividad, $claves, true);
$anterior = $posicion > 0 ? $claves[$posicion - 1] : null;
$siguiente = $posicion < count($claves) - 1 ? $claves[$posicion + 1] : null;
mostrar_encabezado($actividad['nombre'], 'programas');
?>
<section class="seccion">
    <div class="contenedor contenedor-angosto">
        <a class="regresar" href="programa.php?p=<?= e($slug_programa) ?>">volver al programa: <?= e($programa['nombre']) ?></a>
        <span class="etiqueta etiqueta-verde">actividad <?= (int) $posicion + 1 ?> de <?= count($claves) ?> | <?= e($actividad['duracion']) ?></span>
        <h1><?= e($actividad['nombre']) ?></h1>
        <p class="objetivo"><strong>objetivo:</strong> <?= e($actividad['objetivo']) ?></p>

        <img class="foto-actividad" src="<?= e(imagen_actividad($slug_programa, $slug_actividad)) ?>" alt="ilustracion de <?= e($actividad['nombre']) ?>">

        <?php if ($actividad['cuidado'] !== ''): ?>
            <div class="aviso aviso-importante" role="note"><strong>cuidado:</strong> <?= e($actividad['cuidado']) ?></div>
        <?php endif; ?>

        <section class="caja">
            <h2>que necesitas</h2>
            <ul class="lista-check"><?= lista_html($actividad['materiales']) ?></ul>
        </section>

        <section class="caja">
            <h2>paso a paso</h2>
            <?php mostrar_pasos($actividad['pasos']); ?>
        </section>

        <div class="dos-columnas">
            <section class="caja">
                <h2>consejos</h2>
                <ul class="lista-check"><?= lista_html($actividad['consejos']) ?></ul>
            </section>
            <section class="caja">
                <h2>hazlo mas sencillo</h2>
                <p><?= e($actividad['mas_facil']) ?></p>
            </section>
        </div>

        <div class="aviso">si aparece dolor, mareo, falta de aire o molestia, detente. consulta el programa completo y pide orientacion profesional.</div>

        <section class="caja caja-registro" id="registrar">
            <h2>registra una practica</h2>
            <?php if ($usuario): ?>
                <?php if ($mias): ?>
                    <p class="hecha">esta actividad tiene <?= (int) $mias['veces'] ?> registros. el ultimo fue el <?= e(date('d/m/Y', strtotime($mias['ultima']))) ?>.</p>
                <?php endif; ?>
                <form class="formulario" method="post" action="registrar_practica.php">
                    <input type="hidden" name="token_csrf" value="<?= e(token_csrf()) ?>">
                    <input type="hidden" name="programa" value="<?= e($slug_programa) ?>">
                    <input type="hidden" name="actividad" value="<?= e($slug_actividad) ?>">
                    <fieldset class="opciones">
                        <legend>como te fue?</legend>
                        <label class="opcion"><input type="radio" name="como_fue" value="bien" required><span>bien</span></label>
                        <label class="opcion"><input type="radio" name="como_fue" value="regular"><span>mas o menos</span></label>
                        <label class="opcion"><input type="radio" name="como_fue" value="molestia"><span>con molestia</span></label>
                    </fieldset>
                    <label for="nota">nota opcional</label>
                    <input id="nota" name="nota" maxlength="200" placeholder="no escribas diagnosticos ni datos privados de salud">
                    <button class="boton boton-grande" type="submit">guardar practica</button>
                </form>
            <?php else: ?>
                <p>crea una cuenta si quieres guardar una practica y consultar tu avance.</p>
                <div class="botones">
                    <a class="boton" href="registro.php?volver=<?= urlencode($volver) ?>">crear cuenta</a>
                    <a class="boton boton-claro" href="login.php?volver=<?= urlencode($volver) ?>">ya tengo cuenta</a>
                </div>
            <?php endif; ?>
        </section>

        <div class="navegar">
            <?php if ($anterior): ?>
                <a class="boton boton-claro" href="actividad.php?p=<?= e($slug_programa) ?>&amp;a=<?= e($anterior) ?>">actividad anterior</a>
            <?php else: ?><span></span><?php endif; ?>
            <?php if ($siguiente): ?>
                <a class="boton" href="actividad.php?p=<?= e($slug_programa) ?>&amp;a=<?= e($siguiente) ?>">siguiente actividad</a>
            <?php else: ?>
                <a class="boton" href="programa.php?p=<?= e($slug_programa) ?>">volver al programa</a>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php mostrar_pie();
