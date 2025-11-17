<?php
session_start();

// Controlador y acción por defecto
$controller = $_GET['controller'] ?? 'Auth';
$action = $_GET['action'] ?? 'login';

// ⚠️ IMPORTANTE: Ajusta esta ruta según tu estructura
// Tu carpeta es "controller" (singular), no "controllers"
$controllerFile = __DIR__ . '/app/controller/' . $controller . 'Controller.php';

// Debug: Descomentar para ver la ruta que está buscando
// echo "Buscando: " . $controllerFile . "<br>";
// echo "Existe: " . (file_exists($controllerFile) ? 'SI' : 'NO') . "<br>";
// exit;

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    $controllerClass = $controller . 'Controller';
    $obj = new $controllerClass();
    
    if (method_exists($obj, $action)) {
        $obj->$action();
    } else {
        http_response_code(404);
        echo "Acción no encontrada: " . htmlspecialchars($action);
    }
} else {
    http_response_code(404);
    echo "Controlador no encontrado: " . htmlspecialchars($controller);
    echo "<br>Ruta buscada: " . $controllerFile;
}
?>