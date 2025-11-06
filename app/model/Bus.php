<?php

class Bus {
    public $id;
    public $placa;
    public $modelo;
    public $capacidad;
    public $estado;

    public function __construct($id = null, $placa = "", $modelo = "", $capacidad = 0, $estado = "Activo") {
        $this->id = $id;
        $this->placa = $placa;
        $this->modelo = $modelo;
        $this->capacidad = $capacidad;
        $this->estado = $estado;
    }
}
