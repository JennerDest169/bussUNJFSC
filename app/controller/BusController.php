<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Bus.php';

class BusController {
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
        
        $buses = $this->listar();
        
        include __DIR__ . '/../view/bus/index.php';
    }

    public function listar() {
        $query = "SELECT * FROM buses";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM buses WHERE id = :id";
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
        // Crear el objeto Bus a partir del formulario
        $bus = new Bus();
        $bus->placa = $_POST['placa'];
        $bus->modelo = $_POST['modelo'];
        $bus->capacidad = $_POST['capacidad'];
        $bus->estado = $_POST['estado'];

        // Insertar en la base de datos
        $query = "INSERT INTO buses (placa, modelo, capacidad, estado)
                  VALUES (:placa, :modelo, :capacidad, :estado)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':placa', $bus->placa);
        $stmt->bindParam(':modelo', $bus->modelo);
        $stmt->bindParam(':capacidad', $bus->capacidad);
        $stmt->bindParam(':estado', $bus->estado);
        $stmt->execute();

        // Mostrar la lista actualizada
        $buses = $this->listar();
        include __DIR__ . '/../view/bus/index.php';
        } else {
        // Si no viene por POST, redirigir
        header("Location: index.php?controller=Bus&action=index");
        exit;
        }
    }


    public function actualizar() {
    if (!isset($_SESSION['logueado'])) {
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Crear el objeto Bus desde los datos del formulario
        $bus = new Bus();
        $bus->id = $_POST['id'];
        $bus->placa = $_POST['placa'];
        $bus->modelo = $_POST['modelo'];
        $bus->capacidad = $_POST['capacidad'];
        $bus->estado = $_POST['estado'];

        // Actualizar en la base de datos
        $query = "UPDATE buses 
                  SET placa = :placa, modelo = :modelo, capacidad = :capacidad, estado = :estado 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $bus->id);
        $stmt->bindParam(':placa', $bus->placa);
        $stmt->bindParam(':modelo', $bus->modelo);
        $stmt->bindParam(':capacidad', $bus->capacidad);
        $stmt->bindParam(':estado', $bus->estado);
        $stmt->execute();

        // Volver al listado actualizado
        $buses = $this->listar();
        include __DIR__ . '/../view/bus/index.php';
        } else {
        // Si se intenta acceder sin POST, redirige al listado
        header("Location: index.php?controller=Bus&action=index");
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

        $query = "UPDATE buses
                SET estado = 'Inactivo'    
                WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Volver al listado actualizado
        $buses = $this->listar();
        include __DIR__ . '/../view/bus/index.php';
        } else {
        // Si no hay ID, redirigir al listado
        header("Location: index.php?controller=Bus&action=index");
        exit;
        }
    }
}
