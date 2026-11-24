<?php

class Response {
    /**
     * Envía una respuesta JSON y finaliza la ejecución
     */
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Respuesta estandarizada de éxito
     */
    public static function exito($data = [], $mensaje = '', $statusCode = 200) {
        $respuesta = ['ok' => true];
        if ($mensaje !== '') {
            $respuesta['mensaje'] = $mensaje;
        }
        if (is_array($data)) {
            $respuesta = array_merge($respuesta, $data);
        } elseif ($data !== null) {
            $respuesta['datos'] = $data;
        }
        self::json($respuesta, $statusCode);
    }

    /**
     * Respuesta estandarizada de error
     */
    public static function error($mensaje, $statusCode = 400, $detalles = null) {
        $respuesta = ['ok' => false, 'error' => $mensaje];
        if ($detalles !== null) {
            $respuesta['detalles'] = $detalles;
        }
        self::json($respuesta, $statusCode);
    }
}
