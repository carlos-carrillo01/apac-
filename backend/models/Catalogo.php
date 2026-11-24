<?php

class Catalogo {
    private static $programas = null;

    /**
     * Carga todos los programas y actividades desde el archivo de catálogo
     */
    public static function todos() {
        if (self::$programas === null) {
            self::$programas = require __DIR__ . '/../../includes/catalogo.php';
        }
        return self::$programas;
    }

    /**
     * Busca un programa por su clave o slug
     */
    public static function buscarPrograma($slug) {
        $todos = self::todos();
        if (!is_string($slug) || !isset($todos[$slug])) {
            return null;
        }
        return $todos[$slug];
    }

    /**
     * Busca una actividad específica dentro de un programa
     */
    public static function buscarActividad($slugPrograma, $slugActividad) {
        $programa = self::buscarPrograma($slugPrograma);
        if (!$programa || !is_string($slugActividad) || !isset($programa['actividades'][$slugActividad])) {
            return null;
        }
        return $programa['actividades'][$slugActividad];
    }

    /**
     * Retorna la ruta relativa de la ilustración para una actividad
     */
    public static function imagenActividad($slugPrograma, $slugActividad) {
        $actividad = self::buscarActividad($slugPrograma, $slugActividad);
        if ($actividad && isset($actividad['imagen']) && $actividad['imagen'] !== '') {
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

        if (isset($imagenes[$slugActividad])) {
            return 'img/actividades/' . $imagenes[$slugActividad];
        }

        return 'img/actividades/cuento-preguntas.svg';
    }
}
