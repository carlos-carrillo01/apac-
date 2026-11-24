<?php

// config.php ya lo carga el bootstrap; este require_once es por si Database
// se usa fuera del contexto de la API (doble carga es segura con require_once)
if (!defined('DB_HOST')) {
    require_once __DIR__ . '/../../config.php';
}

class Database {
    private static $conexion = null;

    /**
     * Retorna una instancia singleton de conexión MySQLi
     */
    public static function conectar() {
        if (self::$conexion !== null) {
            return self::$conexion;
        }

        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            $con->set_charset('utf8mb4');
            self::$conexion = $con;
            return self::$conexion;
        } catch (mysqli_sql_exception $e) {
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok' => false,
                'error' => 'No se pudo conectar con la base de datos MySQL. Revisa tu configuración y credenciales.'
            ]);
            exit;
        }
    }
}
