<?php
require_once __DIR__ . '/includes/funciones.php';
$programas = todos_los_programas();
mostrar_encabezado('elige una guia', 'guia');
?>
<section class="seccion">
    <div class="contenedor">
        <div class="titulo-seccion izquierda">
            <span class="sobrelinea">apoyos para practicar en casa</span>
            <h1>¿Qué tipo de actividad buscas?</h1>
            <p>Elige por el tipo de apoyo que quieren explorar, no por un diagnóstico. Cada tarjeta abre una guía diferente con sus propias actividades, materiales y cuidados.</p>
        </div>
        <div class="rejilla-situaciones">
            <?php foreach ($programas as $slug => $programa): ?>
                <a class="situacion" href="programa.php?p=<?= e($slug) ?>">
                    <small class="situacion-para"><?= e($programa['para']) ?></small>
                    <strong><?= e($programa['nombre']) ?></strong>
                    <small><?= e($programa['resumen']) ?></small>
                    <span class="enlace-flecha">Ver esta guía</span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="aviso espacio-arriba">
            <strong>Importante:</strong> son ideas orientativas para casa; no diagnostican ni sustituyen una valoración o tratamiento. Sigue las indicaciones de tus profesionales, participa solo si la persona quiere y detente ante dolor, mareo, falta de aire o angustia.
        </div>
    </div>
</section>
<?php mostrar_pie();
