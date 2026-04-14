<?php
class TicketDTO {
    // Actividad 5 cliente servidor
    public $accion;
    public $cliente_id;
    public $equipo;
    public $descripcion;
    public $fecha_ingreso;

    public function __construct($accion, $cliente_id, $equipo, $descripcion_problema, $fecha_ingreso) {
        $this->accion = $accion;
        $this->cliente_id = $cliente_id;
        $this->equipo = $equipo;
        $this->descripcion = $descripcion;
        $this->fecha_ingreso = $fecha_ingreso;
    }
}
?>
