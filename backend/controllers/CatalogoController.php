<?php

require_once __DIR__ . '/../models/Catalogo.php';
require_once __DIR__ . '/../utils/Response.php';

class CatalogoController {
    /**
     * Lista todos los programas disponibles con información resumida
     */
    public static function listar() {
        $todos = Catalogo::todos();
        $lista = [];

        foreach ($todos as $slug => $programa) {
            $lista[] = [
                'slug' => $slug,
                'nombre' => $programa['nombre'],
                'para' => $programa['para'] ?? '',
                'resumen' => $programa['resumen'],
                'color' => $programa['color'] ?? 'verde',
                'frecuencia' => $programa['frecuencia'],
                'total_actividades' => count($programa['actividades'])
            ];
        }

        Response::exito(['programas' => $lista]);
    }

    /**
     * Obtiene el detalle completo de un programa y la lista de sus actividades
     */
    public static function detallePrograma($slug) {
        $programa = Catalogo::buscarPrograma($slug);

        if (!$programa) {
            Response::error('Programa no encontrado.', 404);
        }

        // Formatear actividades con su slug e ilustración
        $actividades = [];
        $indice = 1;
        foreach ($programa['actividades'] as $slugActividad => $act) {
            $actividades[] = [
                'slug' => $slugActividad,
                'numero' => $indice++,
                'nombre' => $act['nombre'],
                'duracion' => $act['duracion'],
                'objetivo' => $act['objetivo'],
                'imagen' => Catalogo::imagenActividad($slug, $slugActividad)
            ];
        }

        $datos = [
            'slug' => $slug,
            'nombre' => $programa['nombre'],
            'para' => $programa['para'] ?? '',
            'imagen' => $programa['imagen'] ?? 'img/estimulacion-temprana.jpg',
            'resumen' => $programa['resumen'],
            'frecuencia' => $programa['frecuencia'],
            'materiales' => $programa['materiales'],
            'antes_de_empezar' => $programa['antes_de_empezar'],
            'detente_si' => $programa['detente_si'],
            'actividades' => $actividades
        ];

        Response::exito(['programa' => $datos]);
    }

    /**
     * Obtiene el detalle completo de una actividad individual
     */
    public static function detalleActividad($slugPrograma, $slugActividad) {
        $programa = Catalogo::buscarPrograma($slugPrograma);
        $actividad = Catalogo::buscarActividad($slugPrograma, $slugActividad);

        if (!$programa || !$actividad) {
            Response::error('Actividad o programa no encontrado.', 404);
        }

        $claves = array_keys($programa['actividades']);
        $posicion = array_search($slugActividad, $claves, true);
        $anterior = ($posicion > 0) ? $claves[$posicion - 1] : null;
        $siguiente = ($posicion < count($claves) - 1) ? $claves[$posicion + 1] : null;

        $datos = [
            'programa' => [
                'slug' => $slugPrograma,
                'nombre' => $programa['nombre']
            ],
            'actividad' => [
                'slug' => $slugActividad,
                'posicion' => (int) $posicion + 1,
                'total' => count($claves),
                'nombre' => $actividad['nombre'],
                'duracion' => $actividad['duracion'],
                'objetivo' => $actividad['objetivo'],
                'cuidado' => $actividad['cuidado'] ?? '',
                'materiales' => $actividad['materiales'] ?? [],
                'pasos' => $actividad['pasos'] ?? [],
                'consejos' => $actividad['consejos'] ?? [],
                'mas_facil' => $actividad['mas_facil'] ?? '',
                'imagen' => Catalogo::imagenActividad($slugPrograma, $slugActividad),
                'anterior' => $anterior,
                'siguiente' => $siguiente
            ]
        ];

        Response::exito($datos);
    }
}
