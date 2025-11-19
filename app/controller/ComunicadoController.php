<!--ComunicadoController.php - controller-->
<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Comunicado.php';

class ComunicadoController {
    private $db;
    private $comunicado;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->comunicado = new Comunicado($this->db);
    }

    // Listar comunicados
    public function index() {

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        $rol = $usuario['rol'];

        // Administradores ven todos, estudiantes solo activos
        if($rol === 'Administrador') {
            $stmt = $this->comunicado->listarTodos();
        } else {
            $stmt = $this->comunicado->listarActivos();
        }

        $comunicados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../view/comunicado/index.php';
    }

    // Mostrar formulario de creación
    public function nuevo() {

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        if($usuario['rol'] !== 'Administrador') {
            $_SESSION['error'] = "No tienes permiso para realizar esta acción";
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        include __DIR__ . '/../view/comunicado/crear.php';
    }

    // Crear comunicado
    public function guardar() {

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        if($usuario['rol'] !== 'Administrador') {
            $_SESSION['error'] = "No tienes permiso para realizar esta acción";
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->comunicado->titulo = trim($_POST['titulo']);
            $this->comunicado->contenido = trim($_POST['contenido']);
            $this->comunicado->tipo = $_POST['tipo'];
            $this->comunicado->prioridad = $_POST['prioridad'];
            $this->comunicado->usuario_id = $usuario['id'];
            $this->comunicado->fecha_vigencia = !empty($_POST['fecha_vigencia']) ? $_POST['fecha_vigencia'] : null;

            // Validaciones
            $errores = [];

            if(empty($this->comunicado->titulo)) {
                $errores[] = "El título es obligatorio";
            }

            if(empty($this->comunicado->contenido)) {
                $errores[] = "El contenido es obligatorio";
            }

            if(strlen($this->comunicado->titulo) < 5) {
                $errores[] = "El título debe tener al menos 5 caracteres";
            }

            if(empty($errores)) {
                if($this->comunicado->crear()) {
                    $_SESSION['exito'] = "Comunicado publicado exitosamente";
                } else {
                    $_SESSION['error'] = "Error al publicar el comunicado";
                }
            } else {
                $_SESSION['error'] = implode(", ", $errores);
            }

            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }
    }

    // Ver detalle del comunicado
    public function ver() {

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if(!$id) {
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        $comunicado = $this->comunicado->obtenerPorId($id);

        if(!$comunicado) {
            $_SESSION['error'] = "Comunicado no encontrado";
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        include __DIR__ . '/../view/comunicado/detalle.php';
    }

    // Editar comunicado
    public function editar() {

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        if($usuario['rol'] !== 'Administrador') {
            $_SESSION['error'] = "No tienes permiso para realizar esta acción";
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if(!$id) {
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        $comunicado = $this->comunicado->obtenerPorId($id);

        if(!$comunicado) {
            $_SESSION['error'] = "Comunicado no encontrado";
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        include __DIR__ . '/../view/comunicado/editar.php';
    }

    // Actualizar comunicado
    public function actualizar() {

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        if($usuario['rol'] !== 'Administrador') {
            $_SESSION['error'] = "No tienes permiso para realizar esta acción";
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->comunicado->id = $_POST['id'];
            $this->comunicado->titulo = trim($_POST['titulo']);
            $this->comunicado->contenido = trim($_POST['contenido']);
            $this->comunicado->tipo = $_POST['tipo'];
            $this->comunicado->prioridad = $_POST['prioridad'];
            $this->comunicado->fecha_vigencia = !empty($_POST['fecha_vigencia']) ? $_POST['fecha_vigencia'] : null;
            $this->comunicado->estado = $_POST['estado'];

            if($this->comunicado->actualizar()) {
                $_SESSION['exito'] = "Comunicado actualizado exitosamente";
            } else {
                $_SESSION['error'] = "Error al actualizar el comunicado";
            }

            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }
    }

    // Eliminar comunicado
    public function eliminar() {

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        if($usuario['rol'] !== 'Administrador') {
            $_SESSION['error'] = "No tienes permiso para realizar esta acción";
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if(!$id) {
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        if($this->comunicado->eliminar($id)) {
            $_SESSION['exito'] = "Comunicado eliminado exitosamente";
        } else {
            $_SESSION['error'] = "Error al eliminar el comunicado";
        }

        header('Location: index.php?controller=Comunicado&action=index');
        exit();
    }

    // Cambiar estado (activar/desactivar)
    public function cambiarEstado() {

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        if($usuario['rol'] !== 'Administrador') {
            $_SESSION['error'] = "No tienes permiso para realizar esta acción";
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        $id = $_GET['id'] ?? null;
        $estado = $_GET['estado'] ?? null;

        if(!$id || !$estado) {
            header('Location: index.php?controller=Comunicado&action=index');
            exit();
        }

        if($this->comunicado->cambiarEstado($id, $estado)) {
            $_SESSION['exito'] = "Estado actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al cambiar el estado";
        }

        header('Location: index.php?controller=Comunicado&action=index');
        exit();
    }
}
?>