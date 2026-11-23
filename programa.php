<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/avance.php';

$slug = $_GET['p'] ?? '';
$programa = buscar_programa($slug);

if (!$programa) {
    header('Location: index.php#programas');
    exit;
}

$usuario = usuario_actual();
$hechas = $usuario ? practicas_por_actividad($usuario['id'], $slug) : [];
$total = count($programa['actividades']);
$practicadas = count($hechas);
$porcentaje = $total > 0 ? (int) round($practicadas * 100 / $total) : 0;
mostrar_encabezado($programa['nombre'], 'programas');
?>
<section class="seccion">
    <div class="contenedor">
        <a class="regresar" href="guia.php">volver a elegir una guia</a>
        <article class="principal">
            <img src="<?= e($programa['imagen']) ?>" alt="">
            <div>
                <span class="etiqueta etiqueta-verde"><?= e($programa['para']) ?></span>
                <h1><?= e($programa['nombre']) ?></h1>
                <p><?= e($programa['resumen']) ?></p>
                <?php if ($usuario): ?>
                    <div class="progreso-bloque">
                        <strong>tu avance: <?= $practicadas ?> de <?= $total ?> actividades registradas</strong>
                        <div class="barra"><span style="width: <?= $porcentaje ?>%"></span></div>
                    </div>
                <?php else: ?>
                    <p class="chico"><a href="registro.php?volver=<?= urlencode('programa.php?p=' . $slug) ?>">crea una cuenta</a> si quieres guardar tus practicas.</p>
                <?php endif; ?>
            </div>
        </article>

        <div class="aviso aviso-importante" role="note">
            <strong>detente y consulta a un profesional si:</strong>
            <ul class="lista-simple"><?= lista_html($programa['detente_si']) ?></ul>
        </div>

        <div class="dos-columnas">
            <section class="caja">
                <h2>que necesitas</h2>
                <ul class="lista-check"><?= lista_html($programa['materiales']) ?></ul>
                <h2 class="espacio-arriba">cada cuanto</h2>
                <p><?= e($programa['frecuencia']) ?></p>
            </section>
            <section class="caja">
                <h2>antes de empezar</h2>
                <ul class="lista-check"><?= lista_html($programa['antes_de_empezar']) ?></ul>
            </section>
        </div>

        <div class="titulo-seccion izquierda espacio-arriba">
            <h2>actividades del programa</h2>
            <p>elige una guia. puedes descansar o terminar cuando quieras.</p>
        </div>
        <div class="rejilla-tarjetas">
            <?php if ($slug === 'bebes'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'boca-abajo', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'seguir-juguete', 2, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'alcanzar-agarrar', 3, $hechas); ?>
            <?php elseif ($slug === 'movilidad'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'brazos-al-frente', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'extension-rodilla', 2, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'pararse-sentarse', 3, $hechas); ?>
            <?php elseif ($slug === 'lenguaje'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'nombrar-objetos', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'cuento-preguntas', 2, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'soplar-burbujas', 3, $hechas); ?>
            <?php elseif ($slug === 'sentidos'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'caja-texturas', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'linterna', 2, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'musica-ritmo', 3, $hechas); ?>
            <?php elseif ($slug === 'bienestar-emocional'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'paso-pequeno', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'pausa-presente', 2, $hechas); ?>
            <?php elseif ($slug === 'autismo'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'rutina-visual', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'eleccion-sensorial', 2, $hechas); ?>
            <?php elseif ($slug === 'atencion-rutinas'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'lista-tres-pasos', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'pausa-temporizador', 2, $hechas); ?>
            <?php elseif ($slug === 'memoria-rutinas'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'conversacion-familiar', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'calendario-visible', 2, $hechas); ?>
            <?php elseif ($slug === 'bienestar-corporal'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'preparar-descanso', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'movimiento-acordado', 2, $hechas); ?>
            <?php elseif ($slug === 'apoyo-profesional'): ?>
                <?php mostrar_tarjeta_actividad($slug, 'persona-de-confianza', 1, $hechas); ?>
                <?php mostrar_tarjeta_actividad($slug, 'preparar-consulta', 2, $hechas); ?>
            <?php endif; ?>
        </div>

        <?php if ($usuario): ?>
            <div class="caja caja-correo">
                <div>
                    <h2>recibe esta guia por correo</h2>
                    <p>enviaremos el programa completo a <strong><?= e($usuario['correo']) ?></strong>.</p>
                </div>
                <form method="post" action="enviar_programa.php">
                    <input type="hidden" name="token_csrf" value="<?= e(token_csrf()) ?>">
                    <input type="hidden" name="programa" value="<?= e($slug) ?>">
                    <button class="boton boton-grande" type="submit">enviarme la guia por correo</button>
                </form>
            </div>
        <?php else: ?>
            <div class="caja caja-correo">
                <div>
                    <h2>recibe esta guia por correo</h2>
                    <p>crea una cuenta para enviar el programa a tu correo y guardar tus practicas.</p>
                </div>
                <a class="boton" href="registro.php?volver=<?= urlencode('programa.php?p=' . $slug) ?>">crear cuenta</a>
            </div>
        <?php endif; ?>

        <div class="aviso aviso-siguiente">
            <strong>necesitas orientacion?</strong> llama a apac al <a href="tel:<?= e(APAC_TEL_1) ?>"><?= e(APAC_TEL_1) ?></a> o al <a href="tel:<?= e(APAC_TEL_2) ?>"><?= e(APAC_TEL_2) ?></a>. estas ideas no sustituyen la valoracion ni el tratamiento.
        </div>
    </div>
</section>
<?php mostrar_pie();
