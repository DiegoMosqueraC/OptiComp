<?php
class TicketDTO {
<<<<<<< HEAD
    // Actividad 5 cliente servidor
=======
>>>>>>> 9a10ca8 (CODIGO UPDATE)
    public $accion;
    public $cliente_id;
    public $equipo;
    public $descripcion;
    public $fecha_ingreso;
<<<<<<< HEAD

    public function __construct($accion, $cliente_id, $equipo, $descripcion_problema, $fecha_ingreso) {
=======
    
    // Nuevos campos para el Callback
    public $callback_ip;
    public $callback_port;

    public function __construct($accion, $cliente_id, $equipo, $descripcion, $fecha_ingreso, $cb_ip = null, $cb_port = null) {
>>>>>>> 9a10ca8 (CODIGO UPDATE)
        $this->accion = $accion;
        $this->cliente_id = $cliente_id;
        $this->equipo = $equipo;
        $this->descripcion = $descripcion;
        $this->fecha_ingreso = $fecha_ingreso;
<<<<<<< HEAD
    }
}
?>
=======
        $this->callback_ip = $cb_ip;
        $this->callback_port = $cb_port;
    }
}
?>
>>>>>>> 9a10ca8 (CODIGO UPDATE)
