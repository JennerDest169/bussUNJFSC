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

    public function crear(Bus $bus) {
        $query = "INSERT INTO buses (placa, modelo, capacidad, estado) VALUES (:placa, :modelo, :capacidad, :estado)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':placa', $bus->placa);
        $stmt->bindParam(':modelo', $bus->modelo);
        $stmt->bindParam(':capacidad', $bus->capacidad);
        $stmt->bindParam(':estado', $bus->estado);
        return $stmt->execute();
    }

    public function actualizar(Bus $bus) {
        $query = "UPDATE buses SET placa = :placa, modelo = :modelo, capacidad = :capacidad, estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $bus->id);
        $stmt->bindParam(':placa', $bus->placa);
        $stmt->bindParam(':modelo', $bus->modelo);
        $stmt->bindParam(':capacidad', $bus->capacidad);
        $stmt->bindParam(':estado', $bus->estado);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM buses WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
