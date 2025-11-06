<?php
require_once 'app/model/Bus.php';
require_once 'app/model/Conductor.php';
require_once 'app/model/Ruta.php';

class TransporteController {
    private $busModel;
    private $conductorModel;
    private $rutaModel;

    public function __construct() {
        $this->busModel = new Bus();
        $this->conductorModel = new Conductor();
        $this->rutaModel = new Ruta();
    }

    /**
     * Muestra un panel general con la información de transporte:
     * buses, conductores y rutas disponibles.
     */
    public function index() {
        $buses = $this->busModel->getAll();
        $conductores = $this->conductorModel->getAll();
        $rutas = $this->rutaModel->getAll();

        include 'app/views/transporte/index.php';
    }

    /**
     * Muestra una vista general tipo dashboard con totales
     * y enlaces hacia los submódulos (buses, conductores, rutas).
     */
    public function dashboard() {
        $totalBuses = count($this->busModel->getAll());
        $totalConductores = count($this->conductorModel->getAll());
        $totalRutas = count($this->rutaModel->getAll());

        include 'app/views/transporte/dashboard.php';
    }

    /**
     * Permite ver el detalle de una ruta, incluyendo el bus y conductor asignado.
     */
    public function detalleRuta($idRuta) {
        $ruta = $this->rutaModel->findById($idRuta);
        $bus = $this->busModel->findById($ruta['id_bus']);
        $conductor = $this->conductorModel->findById($ruta['id_conductor']);
        include 'app/views/transporte/detalleRuta.php';
    }

    /**
     * Muestra la planificación de transporte (relación rutas - buses - conductores).
     */
    public function planificacion() {
        $rutas = $this->rutaModel->getAll();
        $buses = $this->busModel->getAll();
        $conductores = $this->conductorModel->getAll();
        include 'app/views/transporte/planificacion.php';
    }
}
