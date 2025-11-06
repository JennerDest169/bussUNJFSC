<?php

class Conductor {
    public $id;
    public $nombre;
    public $apellido;
    public $dni;
    public $telefono;
    public $bus_asignado;

    public function __construct($id = null, $nombre = "", $apellido = "", $dni = "", $telefono = "", $bus_asignado = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->telefono = $telefono;
        $this->bus_asignado = $bus_asignado;
    }
}
