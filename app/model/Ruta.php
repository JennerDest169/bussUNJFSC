<?php

class Ruta {
    public $id;
    public $nombre;
    public $origen;
    public $destino;
    public $horario;
    public $bus_id;

    public function __construct($id = null, $nombre = "", $origen = "", $destino = "", $horario = "", $bus_id = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->origen = $origen;
        $this->destino = $destino;
        $this->horario = $horario;
        $this->bus_id = $bus_id;
    }
}
