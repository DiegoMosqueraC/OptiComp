//avance de arquitectura diseño semana 6
CREATE DATABASE DB_OptiComp;
USE DB_OptiComp;

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    equipo VARCHAR(100),
    descripcion TEXT,
    estado VARCHAR(50),
    fecha_ingreso DATE
);
