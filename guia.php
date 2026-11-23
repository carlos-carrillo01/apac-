<?php
require_once __DIR__ . '/includes/funciones.php';
mostrar_encabezado('elige una guia', 'guia');
?>
<section class="seccion">
    <div class="contenedor">
        <div class="titulo-seccion izquierda">
            <span class="sobrelinea">elige una opcion</span>
            <h1>que tipo de apoyo buscas?</h1>
            <p>selecciona una opcion para ver ideas generales. una situacion no es un diagnostico y cada actividad puede omitirse o adaptarse.</p>
        </div>
        <div class="rejilla-situaciones">
            <a class="situacion" href="programa.php?p=bebes"><strong>mi bebe o nino pequeno</strong><small>juegos de movimiento, mirada y comunicacion.</small></a>
            <a class="situacion" href="programa.php?p=lenguaje"><strong>le cuesta hablar o comunicarse</strong><small>conversacion, cuentos y objetos conocidos.</small></a>
            <a class="situacion" href="programa.php?p=movilidad"><strong>le cuesta mover brazos o piernas</strong><small>guia general; consulta antes si hubo lesion o cirugia.</small></a>
            <a class="situacion" href="programa.php?p=movilidad"><strong>adulto mayor que quiere moverse</strong><small>actividades con apoyo y cuidado ante el riesgo de caidas.</small></a>
            <a class="situacion" href="programa.php?p=sentidos"><strong>busco actividades sensoriales</strong><small>texturas y sonidos suaves con objetos seguros.</small></a>
            <a class="situacion" href="programa.php?p=autismo"><strong>autismo</strong><small>rutinas opcionales y respeto a las preferencias de cada persona.</small></a>
            <a class="situacion" href="programa.php?p=bienestar-emocional"><strong>depresion o tristeza persistente</strong><small>ideas de apoyo cotidiano; no son tratamiento ni sustituyen una consulta.</small></a>
            <a class="situacion" href="programa.php?p=bienestar-emocional"><strong>ansiedad o estres</strong><small>pasos flexibles y pausas que se pueden detener en cualquier momento.</small></a>
            <a class="situacion" href="programa.php?p=bienestar-emocional"><strong>agotamiento o burnout</strong><small>opciones sencillas para descansar y pedir apoyo.</small></a>
            <a class="situacion" href="programa.php?p=atencion-rutinas"><strong>TDAH o dificultad para organizar tareas</strong><small>listas breves y pausas opcionales.</small></a>
            <a class="situacion" href="programa.php?p=memoria-rutinas"><strong>Parkinson o Alzheimer</strong><small>conversacion y rutinas; continua la atencion profesional indicada.</small></a>
            <a class="situacion" href="programa.php?p=movilidad"><strong>recuperacion despues de un evento vascular</strong><small>consulta al equipo tratante antes de hacer movimientos o ejercicios.</small></a>
            <a class="situacion" href="programa.php?p=bienestar-corporal"><strong>migrana o dolor de cabeza</strong><small>ideas para preparar un espacio comodo. no hagas ejercicio durante una crisis.</small></a>
            <a class="situacion" href="programa.php?p=bienestar-corporal"><strong>fibromialgia o dolor persistente</strong><small>pausas y ajustes del entorno; cualquier movimiento requiere autorizacion profesional.</small></a>
            <a class="situacion" href="programa.php?p=apoyo-profesional"><strong>anorexia o bulimia</strong><small>busca apoyo profesional; esta guia no ofrece dietas ni ejercicios para tratarlo.</small></a>
            <a class="situacion" href="programa.php?p=apoyo-profesional"><strong>adicciones o consumo problematico</strong><small>la abstinencia puede necesitar atencion medica. no suspendas sustancias de golpe sin ayuda clinica.</small></a>
            <a class="situacion" href="programa.php?p=movilidad"><strong>paralisis cerebral u otra condicion de movimiento</strong><small>consulta al equipo de rehabilitacion para adaptar cualquier actividad.</small></a>
            <a class="situacion" href="index.php#programas"><strong>quiero ver todas las guias</strong><small>elige entre los programas disponibles en casa.</small></a>
        </div>
        <div class="aviso espacio-arriba">
            <strong>importante:</strong> estas actividades son orientativas y no diagnostican ni tratan condiciones. sigue el plan de tus profesionales y detente si aparece dolor, angustia, mareo o falta de aire.
        </div>
    </div>
</section>
<?php mostrar_pie();
