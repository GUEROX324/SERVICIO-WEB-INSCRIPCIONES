CREATE DATABASE IF NOT EXISTS gamehub_inscripciones;

USE gamehub_inscripciones;

CREATE TABLE IF NOT EXISTS inscripciones (
    idInscripcion INT AUTO_INCREMENT PRIMARY KEY,
    idTorneo INT NOT NULL,
    idEquipo INT NOT NULL,
    fechaInscripcion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'aceptada', 'rechazada', 'cancelada') DEFAULT 'pendiente',
    UNIQUE KEY unico_equipo_torneo (idTorneo, idEquipo)
);