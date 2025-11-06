<?php
// Incluir el controlador
require_once __DIR__ . '/../app/controller/ConductorController.php';

// Crear instancia del controlador
$controller = new ConductorController();

// Obtener los datos
$buses = $controller->listar();

// Incluir la vista y pasarle los datos
include __DIR__ . '/../app/view/conductores/index.php';
?>
