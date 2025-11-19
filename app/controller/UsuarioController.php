<?php
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController {
    
    // Listar todos los usuarios
    public function index() {
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
        
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $rol = $_POST['rol'] ?? '';

            $usuario = new Usuario();
            $resultado = $usuario->create($correo, $password, $nombre, $rol);
>>>>>>> 669d71246efa76798f9d9bbe9ac59b7d79333bca
            
            if ($resultado) {
                $_SESSION['exito'] = "Usuario registrado exitosamente";
                header("Location: index.php?controller=Usuario&action=index");
                exit;
            } else {
                $_SESSION['error'] = "El correo ya está registrado o hubo un error";
                header("Location: index.php?controller=Usuario&action=index");
                exit;
            }
        }
    }

    public function actualizar(){
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $rol = $_POST['rol'] ?? '';
            $estado = $_POST['estado'] ?? '';
            

            $usuario = new Usuario();
            $resultado = $usuario->update($estado, $nombre, $rol, $id);
            
            if ($resultado) {
                $_SESSION['exito'] = "Usuario editado exitosamente";
                header("Location: index.php?controller=Usuario&action=index");
                exit;
            } else {
                $_SESSION['error'] = "El correo ya está registrado o hubo un error";
                header("Location: index.php?controller=Usuario&action=index");
                exit;
            }
        }
    }

    public function eliminar(){
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = $_POST['id'] ?? '';
            
            $usuario = new Usuario();
            $resultado = $usuario->delete($id);
            
            if ($resultado) {
                $_SESSION['exito'] = "Usuario eliminado exitosamente";
                header("Location: index.php?controller=Usuario&action=index");
                exit;
            } else {
                $_SESSION['error'] = "El correo ya está registrado o hubo un error";
                header("Location: index.php?controller=Usuario&action=index");
                exit;
            }
        }
    }
}
?>