<?php
// public/bus.php

// Incluir el controlador
require_once __DIR__ . '/../app/controller/BusController.php';

// Crear instancia del controlador
$controller = new BusController();

// Si viene POST (crear o actualizar desde modales)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../app/model/Bus.php';
    $bus = new Bus();
    
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // ACTUALIZAR
        $bus->id = $_POST['id'];
        $bus->placa = $_POST['placa'];
        $bus->modelo = $_POST['modelo'];
        $bus->capacidad = $_POST['capacidad'];
        $bus->estado = $_POST['estado'];
        
        $controller->actualizar($bus); // CORREGIDO: era $busController
    } else {
        // CREAR
        $bus->placa = $_POST['placa'];
        $bus->modelo = $_POST['modelo'];
        $bus->capacidad = $_POST['capacidad'];
        $bus->estado = $_POST['estado'];
        
        $controller->crear($bus); // CORREGIDO: era $busController
    }
    
    header('Location: bus.php');
    exit();
}

// Si viene GET para eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $controller->eliminar($id); // CORREGIDO: era $busController
    header('Location: bus.php');
    exit();
}

// Obtener los datos
$buses = $controller->listar();

// Incluir la vista y pasarle los datos
include __DIR__ . '/../app/view/bus/index.php';
?>