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
        $buses = $this->obtenerBuses();
        $conductores = $this->obtenerConductores();
        $rutas = $this->obtenerRutas();
        
        include __DIR__ . '/../view/asignaciones/index.php';
    }

    public function obtenerBuses() {
        $query = "SELECT id, placa, modelo FROM buses WHERE estado = 'Activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerConductores() {
        $query = "SELECT c.id, u.nombre
                    FROM conductores c
                    inner join usuarios u on c.id_usuarios = u.id
                    WHERE c.estado != 'Inactivo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerRutas() {
        $query = "SELECT id, nombre, origen, destino FROM rutas WHERE estado = 'Activa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $bus_id = $_POST['bus_id'];
            $conductor_id = $_POST['conductor_id'];
            $ruta_id = $_POST['ruta_id'];
            $estado = $_POST['estado'] ?? 'En ruta';
            $observacion = $_POST['observacion'] ?? null;
            $fecha_asignacion = date('Y-m-d H:i:s');

            // Insertar en la base de datos
            $query = "INSERT INTO asignaciones 
                     (bus_id, conductor_id, ruta_id, estado, observacion, fecha_asignacion)
                     VALUES (:bus_id, :conductor_id, :ruta_id, :estado, :observacion, :fecha_asignacion)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':bus_id', $bus_id);
            $stmt->bindParam(':conductor_id', $conductor_id);
            $stmt->bindParam(':ruta_id', $ruta_id);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':observacion', $observacion);
            $stmt->bindParam(':fecha_asignacion', $fecha_asignacion);
            $stmt->execute();

            // Mostrar la lista actualizada
            $_SESSION['exito'] = "Asignación creada correctamente";
            header("Location: index.php?controller=Asignaciones&action=index");
            exit;
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
            $id = $_POST['id'];
            $bus_id = $_POST['bus_id'];
            $conductor_id = $_POST['conductor_id'];
            $ruta_id = $_POST['ruta_id'];
            $estado = $_POST['estado'];
            $observacion = $_POST['observacion'];
            $fecha_asignacion = $_POST['fecha_asignacion'];

            // Actualizar en la base de datos
            $query = "UPDATE asignaciones 
                     SET bus_id = :bus_id, 
                         conductor_id = :conductor_id, 
                         ruta_id = :ruta_id, 
                         estado = :estado, 
                         observacion = :observacion, 
                         fecha_asignacion = :fecha_asignacion
                     WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':bus_id', $bus_id);
            $stmt->bindParam(':conductor_id', $conductor_id);
            $stmt->bindParam(':ruta_id', $ruta_id);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':observacion', $observacion);
            $stmt->bindParam(':fecha_asignacion', $fecha_asignacion);
            $stmt->execute();

            // Volver al listado actualizado
            $_SESSION['exito'] = "Asignación actualizada correctamente";
            header("Location: index.php?controller=Asignaciones&action=index");
            exit;
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

        // Cambiar de GET a POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];

            $query = "UPDATE asignaciones
                    SET estado = 'Completado'
                    WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $_SESSION['exito'] = "Asignación finalizada correctamente";
            header("Location: index.php?controller=Asignaciones&action=index");
            exit;
        } else {
            header("Location: index.php?controller=Asignaciones&action=index");
            exit;
        }
    }
}
?>