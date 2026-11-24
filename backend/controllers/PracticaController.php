<?php

require_once __DIR__ . '/../models/Practica.php';
require_once __DIR__ . '/../models/Catalogo.php';
require_once __DIR__ . '/../utils/Session.php';
require_once __DIR__ . '/../utils/Response.php';

class PracticaController {
    /**
     * Guarda un nuevo registro de práctica
     */
    public static function guardar($datos) {
        $usuario = Session::exigirAcceso();
        Session::validarCsrf();

        $slugPrograma = trim((string) ($datos['programa'] ?? ''));
        $slugActividad = trim((string) ($datos['actividad'] ?? ''));
        $comoFue = trim((string) ($datos['como_fue'] ?? ''));
        $nota = trim((string) ($datos['nota'] ?? ''));

        // Validar que la actividad exista en el catálogo
        if (!Catalogo::buscarActividad($slugPrograma, $slugActividad)) {
            Response::error('La actividad especificada no existe en el catálogo.', 404);
        }

        if (!in_array($comoFue, ['bien', 'regular', 'molestia'], true)) {
            Response::error('Selecciona una opción válida de cómo te fue (bien, regular o molestia).');
        }

        // Limitar la nota a 200 caracteres
        $notaLimpia = mb_substr($nota, 0, 200);
        $notaFinal = $notaLimpia === '' ? null : $notaLimpia;

        $guardado = Practica::registrar($usuario['id'], $slugPrograma, $slugActividad, $comoFue, $notaFinal);

        if (!$guardado) {
            Response::error('No se pudo guardar la práctica.', 500);
        }

        $mensaje = ($comoFue === 'molestia')
            ? 'Guardamos tu práctica. Descansa y consulta con tu profesional antes de repetir la actividad.'
            : '¡Práctica guardada con éxito!';

        Response::exito([], $mensaje, 201);
    }

    /**
     * Retorna el resumen completo del avance del usuario activo
     */
    public static function resumen() {
        $usuario = Session::exigirAcceso();
        $usuarioId = (int) $usuario['id'];

        $general = Practica::resumenGeneral($usuarioId);
        $programas = Practica::resumenProgramas($usuarioId);
        $historial = Practica::historial($usuarioId, 15);

        // Enriquecer programas en curso
        foreach ($programas as &$p) {
            $progCat = Catalogo::buscarPrograma($p['programa']);
            $p['nombre'] = $progCat ? $progCat['nombre'] : $p['programa'];
            $p['total_actividades'] = $progCat ? count($progCat['actividades']) : 0;
            $distintas = (int) $p['distintas'];
            $p['porcentaje'] = $p['total_actividades'] > 0 ? (int) round(($distintas * 100) / $p['total_actividades']) : 0;
            $p['fecha_formateada'] = date('d/m/Y', strtotime($p['ultima']));
        }
        unset($p);

        // Enriquecer el historial con nombres del catálogo y formato
        foreach ($historial as &$registro) {
            $actividad = Catalogo::buscarActividad($registro['programa'], $registro['actividad']);
            $programa = Catalogo::buscarPrograma($registro['programa']);
            $registro['nombre_actividad'] = $actividad ? $actividad['nombre'] : $registro['actividad'];
            $registro['nombre_programa'] = $programa ? $programa['nombre'] : $registro['programa'];
            $registro['imagen'] = Catalogo::imagenActividad($registro['programa'], $registro['actividad']);
            $registro['como_fue_texto'] = Practica::comoFueTexto($registro['como_fue']);
            $registro['fecha_formateada'] = date('d/m/Y H:i', strtotime($registro['creado_en']));
        }
        unset($registro);

        Response::exito([
            'usuario' => $usuario,
            'general' => $general,
            'programas' => $programas,
            'historial' => $historial
        ]);
    }

    /**
     * Retorna cuántas veces se han realizado las actividades de un programa
     */
    public static function porPrograma($slugPrograma) {
        $usuario = Session::usuario();
        if (!$usuario) {
            Response::exito(['hechas' => []]);
        }

        $hechas = Practica::porActividad($usuario['id'], $slugPrograma);
        Response::exito(['hechas' => $hechas]);
    }

    /**
     * Elimina un registro de práctica
     */
    public static function eliminar($id) {
        $usuario = Session::exigirAcceso();
        Session::validarCsrf();

        $idInt = filter_var($id, FILTER_VALIDATE_INT);
        if (!$idInt) {
            Response::error('ID de práctica inválido.');
        }

        $eliminado = Practica::eliminar($idInt, $usuario['id']);
        if (!$eliminado) {
            Response::error('No se pudo encontrar o eliminar la práctica seleccionada.', 404);
        }

        Response::exito([], 'Práctica eliminada correctamente.');
    }
}
