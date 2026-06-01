-- ============================================================
-- OptiComp - Script SQL Completo v1.0
-- Base de datos: db_opticomp
-- Entorno: MariaDB 10.4 / MySQL 8.x
-- Autores: Diego Alejandro Mosquera Caicedo
--          Julián Daniel Erazo Garzón
-- Asignatura: Arquitectura y Diseño de Software — FESC
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- Creación / selección de la base de datos
-- --------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `db_opticomp`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE `db_opticomp`;

-- --------------------------------------------------------
-- Tabla: categoria
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categoria` (
    `cod_categoria` INT(11)      NOT NULL AUTO_INCREMENT,
    `detalle`       VARCHAR(255) NOT NULL,
    PRIMARY KEY (`cod_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla: cliente
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cliente` (
    `id_cliente` INT(11)      NOT NULL AUTO_INCREMENT,
    `tp_doc`     VARCHAR(50)  NOT NULL COMMENT 'CC, NIT, CE, TI',
    `nombre`     VARCHAR(100) NOT NULL,
    `telefono`   VARCHAR(20)  NOT NULL,
    `email`      VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla: producto
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `producto` (
    `id`           BIGINT(20)   NOT NULL AUTO_INCREMENT,
    `descripcion`  VARCHAR(255) NOT NULL,
    `categoria_id` INT(11)      DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_producto_categoria` (`categoria_id`),
    CONSTRAINT `fk_producto_categoria`
        FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`cod_categoria`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla: venta
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `venta` (
    `id`         BIGINT(20) NOT NULL AUTO_INCREMENT,
    `fecha`      DATETIME   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `cliente_id` INT(11)    DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_venta_cliente` (`cliente_id`),
    CONSTRAINT `fk_venta_cliente`
        FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id_cliente`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla: producto_vendido
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `producto_vendido` (
    `id`          BIGINT(20)     NOT NULL AUTO_INCREMENT,
    `cantidad`    DECIMAL(10,2)  DEFAULT NULL,
    `producto_id` BIGINT(20)     DEFAULT NULL,
    `venta_id`    BIGINT(20)     DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_pv_producto` (`producto_id`),
    KEY `fk_pv_venta`    (`venta_id`),
    CONSTRAINT `fk_pv_producto`
        FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_pv_venta`
        FOREIGN KEY (`venta_id`) REFERENCES `venta` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla: ticket
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ticket` (
    `id`            INT(11)      NOT NULL AUTO_INCREMENT,
    `cliente_id`    INT(11)      DEFAULT NULL,
    `equipo`        VARCHAR(100) DEFAULT NULL,
    `descripcion`   TEXT         DEFAULT NULL,
    `estado`        VARCHAR(50)  DEFAULT 'Abierto'
                        COMMENT 'Abierto | En proceso | Cerrado',
    `fecha_ingreso` DATE         DEFAULT NULL,
    `fecha_salida`  DATE         DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_ticket_cliente` (`cliente_id`),
    CONSTRAINT `fk_ticket_cliente`
        FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id_cliente`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- DATOS DE PRUEBA (Seeders para sustentación)
-- ============================================================

-- Categorías
INSERT IGNORE INTO `categoria` (`cod_categoria`, `detalle`) VALUES
(1, 'Procesadores'),
(2, 'Memorias RAM'),
(3, 'Tarjetas de Video'),
(4, 'Almacenamiento SSD/HDD'),
(5, 'Fuentes de Poder'),
(6, 'Refrigeración');

-- Clientes
INSERT IGNORE INTO `cliente` (`id_cliente`, `tp_doc`, `nombre`, `telefono`, `email`) VALUES
(1, 'CC',  'Juan Carlos Pérez',        '3001234567', 'juan.perez@correo.com'),
(2, 'NIT', 'Tecnología SAS Colombia',  '3159876543', 'contacto@tecnsas.co');

-- Productos de muestra
INSERT IGNORE INTO `producto` (`descripcion`, `categoria_id`) VALUES
('Intel Core i5-12400F',         1),
('AMD Ryzen 5 5600X',            1),
('Kingston 16GB DDR4 3200MHz',   2),
('Corsair Vengeance 32GB DDR4',  2),
('NVIDIA RTX 3060 12GB',         3),
('AMD RX 6700 XT',               3),
('Samsung SSD 870 EVO 1TB',      4),
('Seagate Barracuda HDD 2TB',    4),
('Corsair CV550 550W 80+ Bronze',5),
('Cooler Master Hyper 212',      6);

-- Tickets de prueba
INSERT IGNORE INTO `ticket` (`cliente_id`, `equipo`, `descripcion`, `estado`, `fecha_ingreso`) VALUES
(1, 'Portátil Lenovo ThinkPad E14', 'Pantalla no enciende tras actualización de BIOS.', 'Abierto',     '2026-05-01'),
(2, 'PC Gamer ASUS ROG Strix',      'Tarjeta de video presenta artefactos visuales.',   'En proceso',  '2026-05-10'),
(1, 'MacBook Pro M1',               'Teclado con varias teclas sin respuesta.',          'Cerrado',     '2026-04-20');

-- Actualizar ticket cerrado con fecha de salida
UPDATE `ticket` SET `fecha_salida` = '2026-04-25' WHERE `id` = 3 AND `estado` = 'Cerrado';
