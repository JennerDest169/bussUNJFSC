<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Conductor.php';

class ConductorController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function index() {

        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $query = "SELECT c.*, b.placa AS bus_placa
                  FROM conductores c
                  LEFT JOIN asignaciones a ON a.conductor_id = c.id
                  LEFT JOIN buses b ON a.bus_id = b.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $conductores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../view/conductor/index.php';
    }

    public function crear() {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conductor = new Conductor();
            $conductor->nombre = $_POST['nombre'];
            $conductor->dni = $_POST['dni'];
            $conductor->correo = $_POST['correo'];
            $conductor->telefono = $_POST['telefono'];
            $conductor->licencia = $_POST['licencia'];
            $conductor->estado = $_POST['estado'] ?? 'Activo';
            $conductor->fecha_registro = date('Y-m-d H:i:s');

            $query = "INSERT INTO conductores (nombre, dni, correo, telefono, licencia, estado, fecha_registro)
                      VALUES (:nombre, :dni, :correo, :telefono, :licencia, :estado, :fecha_registro)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $conductor->nombre);
            $stmt->bindParam(':dni', $conductor->dni);
            $stmt->bindParam(':correo', $conductor->correo);
            $stmt->bindParam(':telefono', $conductor->telefono);
            $stmt->bindParam(':licencia', $conductor->licencia);
            $stmt->bindParam(':estado', $conductor->estado);
            $stmt->bindParam(':fecha_registro', $conductor->fecha_registro);
            $stmt->execute();

            header("Location: index.php?controller=Conductor&action=index");
            exit;
        } else {
            header("Location: index.php?controller=Conductor&action=index");
            exit;
        }
    }


    public function actualizar() {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $conductor = new Conductor();
            $conductor->id = $_POST['id'];
            $conductor->nombre = $_POST['nombre'];
            $conductor->dni = $_POST['dni'];
            $conductor->correo = $_POST['correo'];
            $conductor->telefono = $_POST['telefono'];
            $conductor->licencia = $_POST['licencia'];
            $conductor->estado = $_POST['estado'];

            $query = "UPDATE conductores 
                      SET nombre = :nombre, dni = :dni, correo = :correo, 
                          telefono = :telefono, licencia = :licencia, estado = :estado
                      WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $conductor->id);
            $stmt->bindParam(':nombre', $conductor->nombre);
            $stmt->bindParam(':dni', $conductor->dni);
            $stmt->bindParam(':correo', $conductor->correo);
            $stmt->bindParam(':telefono', $conductor->telefono);
            $stmt->bindParam(':licencia', $conductor->licencia);
            $stmt->bindParam(':estado', $conductor->estado);
            $stmt->execute();

            header("Location: index.php?controller=Conductor&action=index");
            exit;
        } else {
            header("Location: index.php?controller=Conductor&action=index");
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

            $query = "UPDATE conductores 
                    SET estado = 'Inactivo'
                    WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            header("Location: index.php?controller=Conductor&action=index");
            exit;
        } else {
            header("Location: index.php?controller=Conductor&action=index");
            exit;
        }
    }
}
