<?php
require_once __DIR__ . '/../model/Usuario.php';

class AuthController {
    
    // Mostrar formulario de login
    public function login() {
        include __DIR__ . '/../view/auth/login.php';
    }
    
    // Procesar login
    public function ingresar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $usuario = new Usuario();
            $resultado = $usuario->login($correo, $password);
            
            if ($resultado !== false) {
                // Login exitoso
                session_start();
                $_SESSION['usuario'] = $resultado;
                $_SESSION['logueado'] = true;
                
<<<<<<< HEAD
                header("Location: index.php?controller=Dashboard&action=index");
=======
                header("Location: index.php?controller=Incidencia&action=index"); //redireccion
>>>>>>> 669d71246efa76798f9d9bbe9ac59b7d79333bca
                exit;
            } else {
                // Login fallido
                session_start();
                $_SESSION['error'] = "Credenciales incorrectas";
                header("Location: index.php?controller=Auth&action=login");
                exit;
            }
        }
    }
    
    // Mostrar formulario de registro
    public function registro() {
        include __DIR__ . '/../view/auth/registro.php';
    }
    
    // Procesar registro
    public function registrar() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $correo = $_POST['correo'] ?? '';
            $password = $_POST['password'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $rol = $_POST['rol'] ?? '';

            $usuario = new Usuario();
            $resultado = $usuario->create($correo, $password, $nombre, $rol);
            
            if ($resultado) {
                session_start();
                $_SESSION['exito'] = "Usuario registrado exitosamente";
                header("Location: index.php?controller=Auth&action=login");
                exit;
            } else {
                session_start();
                $_SESSION['error'] = "El correo ya está registrado o hubo un error";
                header("Location: index.php?controller=Auth&action=registro");
                exit;
            }
        }
    }
    
    // Cerrar sesión
    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }
}
?>