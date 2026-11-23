<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/avance.php';
exigir_acceso('mi_avance.php');
$usuario = usuario_actual();

// permite borrar una practica de la cuenta activa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $id = filter_var(campo_post('id'), FILTER_VALIDATE_INT);

    if ($id) {
        $con = conexion();
        $borrar = $con->prepare('delete from practicas where id = ? and usuario_id = ?');
        $borrar->bind_param('ii', $id, $usuario['id']);
        $borrar->execute();
        guardar_mensaje('ok', 'practica eliminada.');
    }

    header('Location: mi_avance.php');
    exit;
}

$general = resumen_general($usuario['id']);
$programas_en_curso = resumen_programas($usuario['id']);
$registros = historial($usuario['id'], 15);
mostrar_encabezado('mi avance', 'mi_avance');
?>
<section class="seccion">
    <div class="contenedor">
        <div class="titulo-seccion izquierda">
            <span class="sobrelinea">tu progreso</span>
            <h1>hola, <?= e($usuario['nombre']) ?></h1>
            <p>aqui puedes consultar lo que has practicado. cada actividad cuenta, aunque sea corta.</p>
        </div>

        <?php if ($general['sesiones'] === 0): ?>
            <div class="caja vacio">
                <h2>aun no hay practicas registradas</h2>
                <p>elige un programa, consulta una actividad y guarda como te fue.</p>
                <a class="boton" href="guia.php">elegir una guia</a>
            </div>
        <?php else: ?>
            <div class="estadisticas">
                <div class="dato"><strong><?= $general['sesiones'] ?></strong><span>practicas registradas</span></div>
                <div class="dato"><strong><?= $general['dias'] ?></strong><span>dias practicados</span></div>
                <div class="dato"><strong><?= $general['racha'] ?></strong><span>dias seguidos</span></div>
            </div>

            <section class="caja">
                <h2>tus ultimos siete dias</h2>
                <div class="semana">
                    <?php
                    $dia_indice = 0;
                    while ($dia_indice < count($general['semana'])):
                        $dia = $general['semana'][$dia_indice];
                    ?>
                        <div class="dia <?= $dia['hecho'] ? 'hecho' : '' ?> <?= $dia['hoy'] ? 'hoy' : '' ?>">
                            <span><?= e($dia['letra']) ?></span>
                            <b><?= $dia['hecho'] ? 'listo' : 'pendiente' ?></b>
                        </div>
                    <?php
                        $dia_indice++;
                    endwhile;
                    ?>
                </div>
            </section>

            <div class="titulo-seccion izquierda espacio-arriba"><h2>tus programas</h2></div>
            <div class="dos-columnas">
                <?php
                $programa_indice = 0;
                while ($programa_indice < count($programas_en_curso)):
                    $fila = $programas_en_curso[$programa_indice];
                    $prog = buscar_programa($fila['programa']);
                    $programa_indice++;

                    if (!$prog) {
                        continue;
                    }

                    $total = count($prog['actividades']);
                    $distintas = (int) $fila['distintas'];
                    $pct = $total > 0 ? (int) round($distintas * 100 / $total) : 0;
                ?>
                    <article class="caja">
                        <h3><?= e($prog['nombre']) ?></h3>
                        <div class="barra"><span style="width: <?= $pct ?>%"></span></div>
                        <p><strong><?= $distintas ?> de <?= $total ?></strong> actividades registradas | <?= (int) $fila['sesiones'] ?> practicas<br>
                        <small>ultima practica: <?= e(date('d/m/Y', strtotime($fila['ultima']))) ?></small></p>
                        <a class="boton boton-chico" href="programa.php?p=<?= e($fila['programa']) ?>">continuar</a>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="titulo-seccion izquierda espacio-arriba"><h2>ultimas practicas</h2></div>
            <?php
            $registro_indice = 0;
            while ($registro_indice < count($registros)):
                $reg = $registros[$registro_indice];
                $act = buscar_actividad($reg['programa'], $reg['actividad']);
                $registro_indice++;

                if (!$act) {
                    continue;
                }
            ?>
                <article class="registro">
                    <img src="<?= e(imagen_actividad($reg['programa'], $reg['actividad'])) ?>" alt="">
                    <div class="registro-texto">
                        <strong><a href="actividad.php?p=<?= e($reg['programa']) ?>&amp;a=<?= e($reg['actividad']) ?>"><?= e($act['nombre']) ?></a></strong>
                        <span class="chico"><?= e(date('d/m/Y H:i', strtotime($reg['creado_en']))) ?> | <?= e(como_fue_texto($reg['como_fue'])) ?></span>
                        <?php if ($reg['nota']): ?><span class="nota"><?= e($reg['nota']) ?></span><?php endif; ?>
                    </div>
                    <form method="post" action="mi_avance.php" class="form-en-linea">
                        <input type="hidden" name="token_csrf" value="<?= e(token_csrf()) ?>">
                        <input type="hidden" name="id" value="<?= e($reg['id']) ?>">
                        <button class="boton boton-chico boton-peligro" type="submit">quitar</button>
                    </form>
                </article>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>
<?php mostrar_pie();
