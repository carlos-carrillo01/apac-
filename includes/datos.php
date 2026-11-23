<?php

// carga el catalogo escrito directamente en php
function todos_los_programas()
{
    static $programas = null;

    if ($programas === null) {
        $programas = require __DIR__ . '/catalogo.php';
    }

    return $programas;
}

// encuentra un programa por su nombre corto
function buscar_programa($slug)
{
    $programas = todos_los_programas();

    if (!is_string($slug) || !isset($programas[$slug])) {
        return null;
    }

    return $programas[$slug];
}

// encuentra una actividad dentro de un programa
function buscar_actividad($slug_programa, $slug_actividad)
{
    $programa = buscar_programa($slug_programa);

    if (!$programa || !is_string($slug_actividad)) {
        return null;
    }

    if (!isset($programa['actividades'][$slug_actividad])) {
        return null;
    }

    return $programa['actividades'][$slug_actividad];
}

// devuelve una ilustracion fija para cada actividad
function imagen_actividad($slug_programa, $slug_actividad)
{
    $actividad = buscar_actividad($slug_programa, $slug_actividad);

    if ($actividad && isset($actividad['imagen'])) {
        return 'img/actividades/' . $actividad['imagen'];
    }

    $imagenes = [
        'boca-abajo' => 'boca-abajo.svg',
        'seguir-juguete' => 'seguir-juguete.svg',
        'alcanzar-agarrar' => 'alcanzar-agarrar.svg',
        'brazos-al-frente' => 'brazos-al-frente.svg',
        'extension-rodilla' => 'extension-rodilla.svg',
        'pararse-sentarse' => 'pararse-sentarse.svg',
        'nombrar-objetos' => 'nombrar-objetos.svg',
        'cuento-preguntas' => 'cuento-preguntas.svg',
        'soplar-burbujas' => 'soplar-burbujas.svg',
        'caja-texturas' => 'caja-texturas.svg',
        'linterna' => 'linterna.svg',
        'musica-ritmo' => 'musica-ritmo.svg',
    ];

    if (isset($imagenes[$slug_actividad])) {
        return 'img/actividades/' . $imagenes[$slug_actividad];
    }

    return 'img/actividades/cuento-preguntas.svg';
}

// convierte una lista fija del catalogo en etiquetas html
function lista_html($elementos)
{
    $elementos = array_map('e', $elementos);
    return '<li>' . implode('</li><li>', $elementos) . '</li>';
}
