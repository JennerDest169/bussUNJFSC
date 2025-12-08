<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

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
            //para conductor si es su rol
            $dni = $_POST['dni'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $licencia = $_POST['licencia'] ?? '';
            $estado = 'Disponible';

            $usuario = new Usuario();
            $resultado = $usuario->create($correo, $password, $nombre, $rol);

            if ($resultado) {
                $id = $usuario->LastUserId();
                if($rol == 'Conductor' && !empty($dni) && !empty($telefono) && !empty($licencia)){
                    $query = "INSERT INTO conductores (dni, telefono, licencia, estado, id_usuarios)
                      VALUES (:dni, :telefono, :licencia, :estado, :usuario )";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':dni', $dni);
                    $stmt->bindParam(':telefono', $telefono);
                    $stmt->bindParam(':licencia', $licencia);
                    $stmt->bindParam(':estado', $estado);
                    $stmt->bindParam(':usuario', $id);
                    $stmt->execute();
                }
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
            $estado = $_POST['estado'] ?? '';
            

            $usuario = new Usuario();
            $resultado = $usuario->update($estado, $nombre, $id);
            
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