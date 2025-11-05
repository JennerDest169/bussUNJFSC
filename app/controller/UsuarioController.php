<?php
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController {
    
    // Listar todos los usuarios
    public function index() {
        session_start();
        
        // Verificar que esté logueado
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        
        $usuario = new Usuario();
        $usuarios = $usuario->getAll();
        
        include __DIR__ . '/../view/usuario/index.php';
    }
    
    // Mostrar formulario para crear usuario
    public function crear() {
        session_start();
        
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        
        include __DIR__ . '/../view/usuario/crear.php';
    }
    
    // Guardar nuevo usuario
    public function store() {
        session_start();
        
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            
            $usuario = new Usuario();
            $resultado = $usuario->create($correo, $password, $nombre);
            
            if ($resultado) {
                $_SESSION['exito'] = "Usuario creado exitosamente";
            } else {
                $_SESSION['error'] = "Error al crear usuario";
            }
            
            header("Location: index.php?controller=Usuario&action=index");
            exit;
        }
    }
    
    // Eliminar usuario (opcional)
    public function eliminar() {
        session_start();
        
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $usuario = new Usuario();
            $usuario->delete($id);
        }
        
        header("Location: index.php?controller=Usuario&action=index");
        exit;
    }
}
?>