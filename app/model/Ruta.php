<?php
class Ruta {
    public $id;
    public $nombre;
    public $origen;
    public $destino;
    public $descripcion;
    public $hora_salida;
    public $hora_llegada;
    public $estado;
    public $fecha_registro;
    
    // Nuevas propiedades para coordenadas
    public $lat_origen;
    public $lng_origen;
    public $lat_destino;
    public $lng_destino;

    public function __construct($id = null, $nombre = '', $origen = '', $destino = '', $descripcion = '', 
                                $hora_salida = null, $hora_llegada = null, $estado = 'Activa', $fecha_registro = null,
                                $lat_origen = null, $lng_origen = null, $lat_destino = null, $lng_destino = null) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->origen = $origen;
        $this->destino = $destino;
        $this->descripcion = $descripcion;
        $this->hora_salida = $hora_salida;
        $this->hora_llegada = $hora_llegada;
        $this->estado = $estado;
        $this->fecha_registro = $fecha_registro;
        $this->lat_origen = $lat_origen;
        $this->lng_origen = $lng_origen;
        $this->lat_destino = $lat_destino;
        $this->lng_destino = $lng_destino;
    }
}
?>