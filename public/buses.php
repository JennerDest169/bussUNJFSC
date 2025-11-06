<?php
// Incluir el controlador
require_once __DIR__ . '/../app/controller/BusController.php';

// Crear instancia del controlador
$controller = new BusController();

// Obtener los datos
$buses = $controller->listar();

// Incluir la vista y pasarle los datos
include __DIR__ . '/../app/view/bus/index.php';
?>
