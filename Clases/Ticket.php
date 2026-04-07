<?php
//Actividad 6 arquitectura diseño
class Ticket {
    
    private $id;
    private $cliente_id;
    private $equipo;
    private $descripcion;
    private $estado;
    private $fecha_ingreso;

    public function __construct($cliente_id, $equipo, $descripcion, $estado, $fecha_ingreso) {
        $this->cliente_id = $cliente_id;
        $this->equipo = $equipo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
        $this->fecha_ingreso = $fecha_ingreso;
    }

    public function getClienteId(){ return $this->cliente_id; }
    public function getEquipo(){ return $this->equipo; }
    public function getDescripcion(){ return $this->descripcion; }
    public function getEstado(){ return $this->estado; }
    public function getFechaIngreso(){ return $this->fecha_ingreso; }
}
?>
