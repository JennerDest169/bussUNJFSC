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

    // GETTERS
    public function getId() {
        return $this->id;
    }

    public function getBusId() {
        return $this->bus_id;
    }

    public function getConductorId() {
        return $this->conductor_id;
    }

    public function getRutaId() {
        return $this->ruta_id;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function getFechaAsignacion() {
        return $this->fecha_asignacion;
    }

    // SETTERS
    public function setId($id) {
        $this->id = $id;
    }

    public function setBugId($bug_id) {
        $this->bug_id = $bug_id;
    }

    public function setConductorId($conductor_id) {
        $this->conductor_id = $conductor_id;
    }

    public function setRutaId($ruta_id) {
        $this->ruta_id = $ruta_id;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
    }

    public function setFechaAsignacion($fecha_asignacion) {
        $this->fecha_asignacion = $fecha_asignacion;
    }
}
?>