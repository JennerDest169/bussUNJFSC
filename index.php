<?php
// Define una constante BASE_URL para rutas absolutas
define('BASE_URL', '/20252Buses/bussUNJFSC/');

// Ejemplo típico de index.php con enrutamiento
$controller = $_GET['controller'] ?? 'Auth';
$action = $_GET['action'] ?? 'login';

$controllerFile = __DIR__ . '/app/controller/' . $controller . 'Controller.php';

if(file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerClass = $controller . 'Controller';
    $controllerObj = new $controllerClass();
    
    if(method_exists($controllerObj, $action)) {
        $controllerObj->$action();
    } else {
        echo "Acción no encontrada";
    }
} else {
    echo "Controlador no encontrado";
}
?>