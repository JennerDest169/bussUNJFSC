<?php
// Incluir el controlador
require_once __DIR__ . '/../app/controller/RutaController.php';

// Crear instancia del controlador
$controller = new RutaController();

// Obtener los datos
$rutas = $controller->listar();

// Incluir la vista y pasarle los datos
include __DIR__ . '/../app/view/ruta/index.php';
?>
