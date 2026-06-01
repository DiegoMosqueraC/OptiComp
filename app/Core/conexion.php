<?php

namespace App\Core;

use PDO;
use PDOException;

<<<<<<< HEAD
/**
 * Clase Conexion - Patrón Singleton (Guía 5)
 *
 * Responsabilidad única: gestionar la instancia de conexión PDO.
 * REFACTORING APLICADO:
 *   - Rename: $db -> $dbName (claridad semántica, PSR-12)
 *   - Move Method: configuración extraída a config/database.php
 *   - Extract Method: buildDsn() separado del constructor
 */
class Conexion
{
    private static ?Conexion $instancia = null;
    private PDO $conexion;
=======
    private $host = "localhost";
<<<<<<< HEAD
<<<<<<<< HEAD:public/app/Core/conexion.php
    private $db = "db_opticomp";
========
    private $db = "mysql";
>>>>>>>> 70c6add (Avance semana 6 y 7 de arquitectura y diseño):app/Core/conexion.php
=======
    private $db = "mysql";
>>>>>>> 9a10ca8 (CODIGO UPDATE)
    private $usuario = "root";
    private $password = "";
>>>>>>> 66a3ee8d557d9db8d2529e7749591bbdeb522868

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';

        try {
            $dsn = $this->buildDsn($config['host'], $config['dbname'], $config['charset']);
            $this->conexion = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            Logger::logEvent('ERROR_SISTEMA', 'Fallo de conexión BD: ' . $e->getMessage());
            die('Error de conexión a la base de datos. Revise la configuración.');
        }
    }

    /**
     * EXTRACT METHOD: construcción del DSN separada del constructor.
     */
    private function buildDsn(string $host, string $dbName, string $charset): string
    {
        return "mysql:host={$host};dbname={$dbName};charset={$charset}";
    }

    public static function getInstancia(): self
    {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }

        return self::$instancia;
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }
}
