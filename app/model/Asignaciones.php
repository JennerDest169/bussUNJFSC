<?php

class Asignaciones {
    private $id;
    private $bus_id;
    private $conductor_id;
    private $ruta_id;
    private $estado;
    private $observacion;
    private $fecha_asignacion;

    public function __construct($id = null, $bus_id = null, $conductor_id = null, $ruta_id = null, 
                                $estado = null, $observacion = null, $fecha_asignacion = null) {
        $this->id = $id;
        $this->bus_id = $bus_id;
        $this->conductor_id = $conductor_id;
        $this->ruta_id = $ruta_id;
        $this->estado = $estado;
        $this->observacion = $observacion;
        $this->fecha_asignacion = $fecha_asignacion;
    }
}
?>