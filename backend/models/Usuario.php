<?php

require_once __DIR__ . '/../config/Database.php';

class Usuario {
    /**
     * Busca un usuario por su correo electrónico
     */
    public static function buscarPorCorreo($correo) {
        $con = Database::conectar();
        $stmt = $con->prepare('SELECT id, nombre, correo, clave_hash FROM usuarios WHERE correo = ? LIMIT 1');
        $correoLimpio = strtolower(trim((string) $correo));
        $stmt->bind_param('s', $correoLimpio);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado ?: null;
    }

    /**
     * Busca un usuario por su ID
     */
    public static function buscarPorId($id) {
        $con = Database::conectar();
        $stmt = $con->prepare('SELECT id, nombre, correo, creado_en FROM usuarios WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        return $resultado ?: null;
    }

    /**
     * Registra un nuevo usuario en la base de datos
     */
    public static function crear($nombre, $correo, $clave) {
        $con = Database::conectar();
        $nombreLimpio = trim((string) $nombre);
        $correoLimpio = strtolower(trim((string) $correo));
        $hash = password_hash($clave, PASSWORD_DEFAULT);

        $stmt = $con->prepare('INSERT INTO usuarios (nombre, correo, clave_hash) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $nombreLimpio, $correoLimpio, $hash);
        $stmt->execute();

        return [
            'id' => (int) $con->insert_id,
            'nombre' => $nombreLimpio,
            'correo' => $correoLimpio
        ];
    }

    /**
     * Verifica si una contraseña coincide con su hash
     */
    public static function verificarClave($clavePlana, $hash) {
        return password_verify($clavePlana, $hash);
    }
}
