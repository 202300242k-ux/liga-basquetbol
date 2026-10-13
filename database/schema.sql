CREATE DATABASE IF NOT EXISTS liga_basquetbol CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE liga_basquetbol;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS equipos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL UNIQUE,
  ciudad VARCHAR(100) NULL,
  entrenador VARCHAR(100) NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS jugadores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  equipo_id INT UNSIGNED NOT NULL,
  nombres VARCHAR(100) NOT NULL,
  apellidos VARCHAR(100) NOT NULL,
  dorsal TINYINT UNSIGNED NULL,
  posicion VARCHAR(30) NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_jugador_equipo FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  UNIQUE KEY uq_equipo_dorsal (equipo_id,dorsal)
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS partidos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  equipo_local_id INT UNSIGNED NOT NULL,
  equipo_visitante_id INT UNSIGNED NOT NULL,
  fecha_hora DATETIME NOT NULL,
  puntos_local SMALLINT UNSIGNED NULL,
  puntos_visitante SMALLINT UNSIGNED NULL,
  estado ENUM('programado','finalizado','suspendido') NOT NULL DEFAULT 'programado',
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_partido_local FOREIGN KEY (equipo_local_id) REFERENCES equipos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_partido_visitante FOREIGN KEY (equipo_visitante_id) REFERENCES equipos(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_partido_fecha (fecha_hora)
) ENGINE=InnoDB;

-- La cuenta administradora se crea mediante setup_admin.php una sola vez.
