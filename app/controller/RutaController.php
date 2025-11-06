<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Ruta.php';

class RutaController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function listar() {
        $query = "SELECT r.*, b.placa AS bus_placa 
                  FROM rutas r 
                  LEFT JOIN asignaciones a ON a.ruta_id = r.id
                  LEFT JOIN buses b ON a.bus_id = b.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM rutas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear(Ruta $ruta) {
        $query = "INSERT INTO rutas (nombre, origen, destino, horario, bus_id) 
                  VALUES (:nombre, :origen, :destino, :horario, :bus_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $ruta->nombre);
        $stmt->bindParam(':origen', $ruta->origen);
        $stmt->bindParam(':destino', $ruta->destino);
        $stmt->bindParam(':horario', $ruta->horario);
        $stmt->bindParam(':bus_id', $ruta->bus_id);
        return $stmt->execute();
    }

    public function actualizar(Ruta $ruta) {
        $query = "UPDATE rutas 
                  SET nombre = :nombre, origen = :origen, destino = :destino, horario = :horario, bus_id = :bus_id 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $ruta->id);
        $stmt->bindParam(':nombre', $ruta->nombre);
        $stmt->bindParam(':origen', $ruta->origen);
        $stmt->bindParam(':destino', $ruta->destino);
        $stmt->bindParam(':horario', $ruta->horario);
        $stmt->bindParam(':bus_id', $ruta->bus_id);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM rutas WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
