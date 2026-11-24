<?php

require_once __DIR__ . '/../config/Database.php';

class Practica {
    /**
     * Guarda una práctica realizada por el usuario
     */
    public static function registrar($usuarioId, $programa, $actividad, $comoFue, $nota) {
        $con = Database::conectar();
        $ahora = date('Y-m-d H:i:s');
        
        $stmt = $con->prepare('INSERT INTO practicas (usuario_id, programa, actividad, como_fue, nota, creado_en) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('isssss', $usuarioId, $programa, $actividad, $comoFue, $nota, $ahora);
        return $stmt->execute();
    }

    /**
     * Obtiene el total de veces y última fecha por actividad en un programa
     */
    public static function porActividad($usuarioId, $programa) {
        $con = Database::conectar();
        $stmt = $con->prepare('SELECT actividad, count(*) as veces, max(creado_en) as ultima FROM practicas WHERE usuario_id = ? AND programa = ? GROUP BY actividad');
        $stmt->bind_param('is', $usuarioId, $programa);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $lista = [];

        while ($fila = $resultado->fetch_assoc()) {
            $lista[$fila['actividad']] = [
                'veces' => (int) $fila['veces'],
                'ultima' => $fila['ultima']
            ];
        }

        return $lista;
    }

    /**
     * Resumen agrupado por programas practicados
     */
    public static function resumenProgramas($usuarioId) {
        $con = Database::conectar();
        $stmt = $con->prepare('SELECT programa, count(*) as sesiones, count(distinct actividad) as distintas, max(creado_en) as ultima FROM practicas WHERE usuario_id = ? GROUP BY programa ORDER BY ultima DESC');
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Calcula estadísticas generales: total sesiones, días únicos, racha actual y los últimos 7 días
     */
    public static function resumenGeneral($usuarioId) {
        $con = Database::conectar();
        $stmt = $con->prepare('SELECT date(creado_en) as dia, count(*) as veces FROM practicas WHERE usuario_id = ? GROUP BY date(creado_en) ORDER BY dia DESC');
        $stmt->bind_param('i', $usuarioId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        $dias = [];
        $sesiones = 0;

        while ($fila = $resultado->fetch_assoc()) {
            $dias[$fila['dia']] = (int) $fila['veces'];
            $sesiones += (int) $fila['veces'];
        }

        // Cálculo de racha consecutiva de días
        $racha = 0;
        $dia = date('Y-m-d');

        if (!isset($dias[$dia])) {
            $dia = date('Y-m-d', strtotime('-1 day'));
        }

        while (isset($dias[$dia])) {
            $racha++;
            $dia = date('Y-m-d', strtotime($dia . ' -1 day'));
        }

        // Historial de la última semana
        $semana = [];
        $nombres = ['d', 'l', 'm', 'm', 'j', 'v', 's'];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = date('Y-m-d', strtotime('-' . $i . ' day'));
            $semana[] = [
                'fecha' => $fecha,
                'letra' => $nombres[(int) date('w', strtotime($fecha))],
                'hecho' => isset($dias[$fecha]),
                'hoy' => $i === 0
            ];
        }

        return [
            'sesiones' => $sesiones,
            'dias' => count($dias),
            'racha' => $racha,
            'semana' => $semana
        ];
    }

    /**
     * Retorna el historial cronológico de prácticas recientes
     */
    public static function historial($usuarioId, $limite = 15) {
        $con = Database::conectar();
        $stmt = $con->prepare('SELECT id, programa, actividad, como_fue, nota, creado_en FROM practicas WHERE usuario_id = ? ORDER BY creado_en DESC, id DESC LIMIT ?');
        $stmt->bind_param('ii', $usuarioId, $limite);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Elimina un registro de práctica perteneciente al usuario
     */
    public static function eliminar($id, $usuarioId) {
        $con = Database::conectar();
        $stmt = $con->prepare('DELETE FROM practicas WHERE id = ? AND usuario_id = ?');
        $stmt->bind_param('ii', $id, $usuarioId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    /**
     * Formatea el estado de la práctica en texto amigable
     */
    public static function comoFueTexto($valor) {
        $opciones = [
            'bien' => 'bien',
            'regular' => 'más o menos',
            'molestia' => 'con molestia'
        ];
        return $opciones[$valor] ?? $valor;
    }
}
