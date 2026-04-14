<?php
//Actividad 6 arquitectura diseño
class Ticket {
    private $id;
    private $cliente_id;
    private $equipo;
    private $descripcion;
    private $estado;
    private $fecha_ingreso;
    private $fecha_salida;

    public function __construct($cliente_id, $equipo, $descripcion, $estado, $fecha_ingreso) {
        $this->cliente_id = $cliente_id;
        $this->equipo = $equipo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->fecha_ingreso = $fecha_ingreso;
    }


    public function getId() { return $this->id; }
    public function getClienteId() { return $this->cliente_id; }
    public function getEquipo() { return $this->equipo; }
    public function getDescripcion() { return $this->descripcion; }
    public function getEstado() { return $this->estado; }
    public function getFechaIngreso() { return $this->fecha_ingreso; }
    public function getFechaSalida() { return $this->fecha_salida; }


    public function setId($id) { $this->id = $id; }
    public function setClienteId($cliente_id) { $this->cliente_id = $cliente_id; }
    public function setEquipo($equipo) { $this->equipo = $equipo; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setEstado($estado) { $this->estado = $estado; }
    public function setFechaIngreso($fecha_ingreso) { $this->fecha_ingreso = $fecha_ingreso; }
    public function setFechaSalida($fecha_salida) { $this->fecha_salida = $fecha_salida; }
}
?>
