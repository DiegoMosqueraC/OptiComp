<?php

// Conexion a la base de datos - Avance semana 5 Diseño y Arquitectura de Software
class Conexion {

    private static $instancia = null;
    private $conexion;

    private $host = "localhost";
<<<<<<<< HEAD:public/app/Core/conexion.php
    private $db = "db_opticomp";
========
    private $db = "mysql";
>>>>>>>> 70c6add (Avance semana 6 y 7 de arquitectura y diseño):app/Core/conexion.php
    private $usuario = "root";
    private $password = "";

    private function __construct() {

        try {

            $this->conexion = new PDO(
                "mysql:host=".$this->host.";dbname=".$this->db,
                $this->usuario,
                $this->password
            );

            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            die("Error de conexión: " . $e->getMessage());

        }
    }

    public static function getInstancia() {

        if(self::$instancia == null){
            self::$instancia = new Conexion();
        }

        return self::$instancia;

    }

    public function getConexion(){
        return $this->conexion;
    }

}
?>
