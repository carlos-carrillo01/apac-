<?php
require_once __DIR__ . '/includes/funciones.php';
mostrar_encabezado('guias para practicar en casa', 'inicio');
?>
<section class="hero">
    <div class="contenedor hero-interno">
        <div class="hero-texto">
            <span class="sobrelinea">apoyo para las familias</span>
            <h1>practica en casa, paso a paso</h1>
            <p>encuentra actividades sencillas para hacer en casa, a tu ritmo. elige una guia, revisa sus cuidados y registra tu avance si quieres.</p>
            <div class="botones">
                <a class="boton boton-grande" href="guia.php">elegir una guia</a>
                <a class="boton boton-claro boton-grande" href="#programas">ver programas</a>
            </div>
        </div>
        <div class="hero-foto">
            <img src="img/hero.jpg" alt="una terapeuta acompana a un nino durante una actividad">
        </div>
    </div>
</section>

<section class="seccion">
    <div class="contenedor">
        <div class="titulo-seccion">
            <span class="sobrelinea">asi de facil</span>
            <h2>tres pasos para empezar</h2>
        </div>
        <div class="pasos">
            <article class="paso"><span class="numero">1</span><h3>elige un tema</h3><p>selecciona la situacion que mas se parece a lo que buscas.</p></article>
            <article class="paso"><span class="numero">2</span><h3>revisa la guia</h3><p>cada actividad explica que necesitas, como empezar y cuando detenerte.</p></article>
            <article class="paso"><span class="numero">3</span><h3>avanza a tu ritmo</h3><p>puedes hacer una actividad, descansar o pedir orientacion profesional.</p></article>
        </div>
    </div>
</section>

<section class="seccion fondo-suave" id="programas">
    <div class="contenedor">
        <div class="titulo-seccion">
            <span class="sobrelinea">programas en casa</span>
            <h2>guias y actividades cotidianas</h2>
            <p>son ideas generales para acompanamiento. no diagnostican ni sustituyen la atencion profesional.</p>
        </div>
        <div class="rejilla-tarjetas">
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=bebes"><img class="foto-fija" src="img/estimulacion-temprana.jpg" alt="actividad de estimulacion para un nino" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">bebes y ninos pequenos</span><h3>estimulacion temprana</h3><p>juegos breves para explorar el movimiento, la mirada y la comunicacion con objetos seguros.</p><a class="enlace-flecha" href="programa.php?p=bebes">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=movilidad"><img class="foto-fija" src="img/terapia-fisica.jpg" alt="ejercicio de movilidad" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">movimiento</span><h3>movilidad y fuerza</h3><p>ideas de movimiento sentado y con apoyo; consulta antes si hay lesion, cirugia o dolor.</p><a class="enlace-flecha" href="programa.php?p=movilidad">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=lenguaje"><img class="foto-fija" src="img/lenguaje.jpg" alt="actividad para conversar y comunicarse" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">comunicacion</span><h3>lenguaje y conversacion</h3><p>juegos de palabras, objetos y cuentos para compartir en familia.</p><a class="enlace-flecha" href="programa.php?p=lenguaje">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=sentidos"><img class="foto-fija" src="img/actividades/musica-ritmo.svg" alt="ilustracion de un instrumento musical" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">exploracion sensorial</span><h3>estimulos y sentidos</h3><p>actividades tranquilas con texturas, sonidos suaves y objetos conocidos.</p><a class="enlace-flecha" href="programa.php?p=sentidos">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=autismo"><img class="foto-fija" src="img/estimulacion-temprana.jpg" alt="actividad familiar con objetos conocidos" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">autismo</span><h3>comunicacion y preferencias sensoriales</h3><p>apoyos opcionales para anticipar rutinas y respetar las preferencias de cada persona.</p><a class="enlace-flecha" href="programa.php?p=autismo">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=bienestar-emocional"><img class="foto-fija" src="img/hero.jpg" alt="acompanamiento en una actividad" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">bienestar emocional</span><h3>un paso pequeno y pausas</h3><p>ideas flexibles para organizar una tarea, tomar una pausa y pedir apoyo.</p><a class="enlace-flecha" href="programa.php?p=bienestar-emocional">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=atencion-rutinas"><img class="foto-fija" src="img/lenguaje.jpg" alt="materiales para organizar actividades" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">atencion y organizacion</span><h3>rutinas en pasos cortos</h3><p>listas visibles y pausas flexibles para las actividades cotidianas.</p><a class="enlace-flecha" href="programa.php?p=atencion-rutinas">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=memoria-rutinas"><img class="foto-fija" src="img/nosotros.jpg" alt="personas compartiendo una actividad" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">memoria y rutinas</span><h3>conversacion y recordatorios</h3><p>actividades compartidas que no ponen a prueba ni corrigen los recuerdos.</p><a class="enlace-flecha" href="programa.php?p=memoria-rutinas">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=bienestar-corporal"><img class="foto-fija" src="img/terapia-fisica.jpg" alt="silla estable para una pausa" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">dolor o fatiga</span><h3>pausas y bienestar corporal</h3><p>ideas para adaptar el entorno y descansar; los movimientos requieren autorizacion profesional.</p><a class="enlace-flecha" href="programa.php?p=bienestar-corporal">ver la guia</a></div></article>
            <article class="tarjeta"><a class="tarjeta-foto" href="programa.php?p=apoyo-profesional"><img class="foto-fija" src="img/nosotros.jpg" alt="familia conversando" loading="lazy"></a><div class="tarjeta-texto"><span class="etiqueta">orientacion profesional</span><h3>acompanamiento y apoyo</h3><p>pasos para conversar y buscar orientacion en situaciones que necesitan atencion profesional.</p><a class="enlace-flecha" href="programa.php?p=apoyo-profesional">ver la guia</a></div></article>
        </div>
    </div>
</section>

<section class="seccion">
    <div class="contenedor">
        <div class="titulo-seccion">
            <span class="sobrelinea">atencion presencial</span>
            <h2>algunos servicios solo se realizan en apac</h2>
            <p>requieren equipo y personal capacitado. no intentes reproducirlos en casa.</p>
        </div>
        <div class="rejilla-tarjetas">
            <article class="tarjeta"><img class="foto-fija" src="img/camara-hiperbarica.jpg" alt="equipo de camara hiperbarica" loading="lazy"><div class="tarjeta-texto"><h3>camara hiperbarica</h3><p>servicio presencial con valoracion y supervision profesional.</p></div></article>
            <article class="tarjeta"><img class="foto-fija" src="img/magnetica.jpg" alt="equipo para atencion especializada" loading="lazy"><div class="tarjeta-texto"><h3>terapia magnetica transcraneal</h3><p>servicio especializado que requiere valoracion medica.</p></div></article>
            <article class="tarjeta"><img class="foto-fija" src="img/terapia-acuatica.jpg" alt="terapia en alberca" loading="lazy"><div class="tarjeta-texto"><h3>terapia acuatica y disfagia</h3><p>requieren instalaciones y atencion de personal capacitado.</p></div></article>
        </div>
    </div>
</section>

<section class="seccion fondo-verde" id="contacto">
    <div class="contenedor contacto">
        <div>
            <h2>contacto y orientacion</h2>
            <p><strong>horario:</strong> <?= e(APAC_HORARIO) ?></p>
            <p><strong>telefonos:</strong> <a href="tel:<?= e(APAC_TEL_1) ?>"><?= e(APAC_TEL_1) ?></a> y <a href="tel:<?= e(APAC_TEL_2) ?>"><?= e(APAC_TEL_2) ?></a></p>
            <p><strong>direccion:</strong> <?= e(APAC_DIRECCION) ?></p>
        </div>
        <div>
            <p><strong>redes:</strong> apac fresnillo en facebook; @apacfresnillo en x e instagram.</p>
            <p>si hay riesgo inmediato para una persona, contacta los servicios de emergencia de tu localidad. estas guias no sustituyen una valoracion profesional.</p>
            <p class="chico">reg. cofepris <?= e(APAC_COFEPRIS) ?></p>
        </div>
    </div>
</section>
<?php mostrar_pie();
