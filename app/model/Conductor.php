<?php

class Conductor {
    public $id;
    public $nombre;
    public $dni;
    public $correo;
    public $telefono;
    public $licencia;
    public $estado;
    public $fecha_registro;

    public function __construct($id = null, $nombre = "", $dni = "", $correo = "", $telefono = "", $licencia = "", $estado = "Activo", $fecha_registro = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->dni = $dni;
        $this->correo = $correo;
        $this->telefono = $telefono;
        $this->licencia = $licencia;
        $this->estado = $estado;
        $this->fecha_registro = $fecha_registro;
    }
}