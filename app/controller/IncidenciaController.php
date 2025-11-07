<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Incidencia.php';

class IncidenciaController
{
    private $db;
    private $incidencia;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->incidencia = new Incidencia($this->db);
    }

    // Listar incidencias
    public function index()
    {
        session_start();

        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        // ⭐ AGREGAR ESTA LÍNEA - Pasar $usuario a la vista
        $usuario = $_SESSION['usuario'];
        $rol = $usuario['rol'];

        // Si es administrador, mostrar todas las incidencias
        if ($rol === 'Administrador') {
            $stmt = $this->incidencia->listarTodas();
        } else {
            // Si es estudiante/conductor, mostrar solo sus incidencias
            $stmt = $this->incidencia->listarPorUsuario($usuario['id']);
        }

        $incidencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Estadísticas para administrador
        $estadisticas = [];
        $tipos_stats = [];
        if ($rol === 'Administrador') {
            $stmt_stats = $this->incidencia->contarPorEstado();
            while ($row = $stmt_stats->fetch(PDO::FETCH_ASSOC)) {
                $estadisticas[$row['estado']] = $row['total'];
            }

            $stmt_tipos = $this->incidencia->estadisticasPorTipo();
            $tipos_stats = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);
        }

        // ⭐ La variable $usuario ya está disponible para la vista
        include __DIR__ . '/../view/incidencia/index.php';
    }




    // Crear nueva incidencia
    /*public function crear() {
        session_start();

        if(!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_SESSION['usuario'];
            
            $this->incidencia->usuario_id = $usuario['id'];
            $this->incidencia->tipo = $_POST['tipo'] ?? 'Otro';
            $this->incidencia->descripcion = trim($_POST['descripcion']);

            // Validaciones
            $errores = [];
            
            if(empty($this->incidencia->descripcion)) {
                $errores[] = "La descripción es obligatoria";
            }

            if(strlen($this->incidencia->descripcion) < 10) {
                $errores[] = "La descripción debe tener al menos 10 caracteres";
            }

            if(empty($errores)) {
                if($this->incidencia->crear()) {
                    $_SESSION['exito'] = "Incidencia reportada exitosamente";
                } else {
                    $_SESSION['error'] = "Error al reportar la incidencia";
                }
            } else {
                $_SESSION['error'] = implode(", ", $errores);
            }

            header('Location: index.php?controller=Incidencia&action=index');
            exit();
        }
    }*/



    // Crear nueva incidencia
    public function crear()
    {
        session_start();

        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];

        // Si el usuario entra a la página sin enviar el formulario (GET)
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include __DIR__ . '/../view/incidencia/crear.php';
            return;
        }

        // Si se envía el formulario (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->incidencia->usuario_id = $usuario['id'];
            $this->incidencia->tipo = $_POST['tipo'] ?? 'Otro';
            $this->incidencia->descripcion = trim($_POST['descripcion']);

            $errores = [];
            if (empty($this->incidencia->descripcion)) {
                $errores[] = "La descripción es obligatoria";
            }
            if (strlen($this->incidencia->descripcion) < 10) {
                $errores[] = "La descripción debe tener al menos 10 caracteres";
            }

            if (empty($errores)) {
                if ($this->incidencia->crear()) {
                    $_SESSION['exito'] = "Incidencia reportada exitosamente";
                    header('Location: index.php?controller=Incidencia&action=index');
                    exit();
                } else {
                    $_SESSION['error'] = "Error al reportar la incidencia";
                }
            } else {
                $_SESSION['error'] = implode(", ", $errores);
            }

            header('Location: index.php?controller=Incidencia&action=crear');
            exit();
        }
    }


    // Ver detalle de incidencia
    public function ver()
    {
        session_start();

        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=Incidencia&action=index');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        $incidencia = $this->incidencia->obtenerPorId($id);

        // Verificar permisos: admin puede ver todas, usuarios solo las suyas
        if ($usuario['rol'] !== 'Administrador' && $incidencia['usuario_id'] != $usuario['id']) {
            $_SESSION['error'] = "No tienes permiso para ver esta incidencia";
            header('Location: index.php?controller=Incidencia&action=index');
            exit();
        }

        include __DIR__ . '/../view/incidencia/detalle.php';
    }





    // Responder incidencia (solo administrador)
    public function responder()
    {
        session_start();

        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        if ($usuario['rol'] !== 'Administrador') {
            $_SESSION['error'] = "No tienes permiso para realizar esta acción";
            header('Location: index.php?controller=Incidencia&action=index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $respuesta = trim($_POST['respuesta']);
            $estado = $_POST['estado'];

            if ($this->incidencia->responder($id, $respuesta, $estado)) {
                $_SESSION['exito'] = "Respuesta enviada correctamente";
            } else {
                $_SESSION['error'] = "Error al enviar la respuesta";
            }

            header('Location: index.php?controller=Incidencia&action=ver&id=' . $id);
            exit();
        }
    }
}
