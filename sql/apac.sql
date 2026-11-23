-- Importa este archivo en phpMyAdmin (pestaña "Importar") o ejecútalo en MySQL.
CREATE DATABASE IF NOT EXISTS apac_fresnillo
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE apac_fresnillo;

-- personas que crean una cuenta para guardar su avance
CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(120) NOT NULL,
  correo VARCHAR(190) NOT NULL,
  clave_hash VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuarios_correo (correo)
) ENGINE=InnoDB;

-- cada vez que la persona hace una actividad en casa y la registra
-- (solo se guarda que actividad hizo, como le fue y una nota opcional; ningun dato medico)
CREATE TABLE IF NOT EXISTS practicas (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  usuario_id INT UNSIGNED NOT NULL,
  programa VARCHAR(40) NOT NULL,
  actividad VARCHAR(60) NOT NULL,
  como_fue ENUM('bien','regular','molestia') NOT NULL,
  nota VARCHAR(200) NULL,
  creado_en DATETIME NOT NULL,
  PRIMARY KEY (id),
  KEY idx_practicas_usuario (usuario_id, creado_en),
  CONSTRAINT fk_practicas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;
