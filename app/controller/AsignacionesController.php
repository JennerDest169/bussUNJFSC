<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Asignaciones.php';

class AsignacionesController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function index() {
        // Verificar que esté logueado
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        
        $asignaciones = $this->listar();
        
        include __DIR__ . '/../view/asignaciones/index.php';
    }

    public function listar() {
        $query = "SELECT * FROM asignaciones";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM asignaciones WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear() {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Crear el objeto AsignacionModel a partir del formulario
            $asignacion = new Asignaciones(
                null, // id (auto-increment)
                $_POST['bus_id'],
                $_POST['conductor_id'],
                $_POST['ruta_id'],
                $_POST['estado'] ?? 'En ruta', // Valor por defecto
                $_POST['observacion'] ?? null,
                date('Y-m-d H:i:s') // fecha_asignacion actual
            );

            // Insertar en la base de datos
            $query = "INSERT INTO asignaciones 
                     (J23_bus_id, J23_conductor_id, J23_ruta_id, A2_estado, A2_observacion, A2_fecha_asignacion)
                     VALUES (:bus_id, :conductor_id, :ruta_id, :estado, :observacion, :fecha_asignacion)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':bus_id', $asignacion->getBugId());
            $stmt->bindParam(':conductor_id', $asignacion->getConductorId());
            $stmt->bindParam(':ruta_id', $asignacion->getRutaId());
            $stmt->bindParam(':estado', $asignacion->getEstado());
            $stmt->bindParam(':observacion', $asignacion->getObservacion());
            $stmt->bindParam(':fecha_asignacion', $asignacion->getFechaAsignacion());
            $stmt->execute();

            // Mostrar la lista actualizada
            $asignaciones = $this->listar();
            include __DIR__ . '/../view/asignaciones/index.php';
        } else {
            // Si no viene por POST, redirigir
            header("Location: index.php?controller=Asignaciones&action=index");
            exit;
        }
    }

    public function actualizar() {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Crear el objeto AsignacionModel desde los datos del formulario
            $asignacion = new Asignaciones(
                $_POST['id'],
                $_POST['bus_id'],
                $_POST['conductor_id'],
                $_POST['ruta_id'],
                $_POST['estado'],
                $_POST['observacion'],
                $_POST['fecha_asignacion']
            );

            // Actualizar en la base de datos
            $query = "UPDATE asignaciones 
                     SET J23_bus_id = :bus_id, 
                         J23_conductor_id = :conductor_id, 
                         J23_ruta_id = :ruta_id, 
                         A2_estado = :estado, 
                         A2_observacion = :observacion, 
                         A2_fecha_asignacion = :fecha_asignacion
                     WHERE J23_id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $asignacion->getId());
            $stmt->bindParam(':bus_id', $asignacion->getBugId());
            $stmt->bindParam(':conductor_id', $asignacion->getConductorId());
            $stmt->bindParam(':ruta_id', $asignacion->getRutaId());
            $stmt->bindParam(':estado', $asignacion->getEstado());
            $stmt->bindParam(':observacion', $asignacion->getObservacion());
            $stmt->bindParam(':fecha_asignacion', $asignacion->getFechaAsignacion());
            $stmt->execute();

            // Volver al listado actualizado
            $asignaciones = $this->listar();
            include __DIR__ . '/../view/asignaciones/index.php';
        } else {
            // Si se intenta acceder sin POST, redirige al listado
            header("Location: index.php?controller=Asignaciones&action=index");
            exit;
        }
    }

    public function eliminar() {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            // Cambiar estado a 'Completado' en lugar de eliminar
            $query = "UPDATE asignaciones
                     SET A2_estado = 'Completado'
                     WHERE J23_id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Volver al listado actualizado
            $asignaciones = $this->listar();
            include __DIR__ . '/../view/asignaciones/index.php';
        } else {
            // Si no hay ID, redirigir al listado
            header("Location: index.php?controller=Asignaciones&action=index");
            exit;
        }
    }

    public function ver($id) {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $asignacion = $this->obtenerPorId($id);
        include __DIR__ . '/../view/asignaciones/ver.php';
    }

    public function editar($id) {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $asignacion = $this->obtenerPorId($id);
        include __DIR__ . '/../view/asignaciones/editar.php';
    }

    public function nuevo() {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        include __DIR__ . '/../view/asignaciones/nuevo.php';
    }
}
?>