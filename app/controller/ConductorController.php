<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Conductor.php';

class ConductorController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function listar() {
        $query = "SELECT c.*, b.placa AS bus_placa
                  FROM conductores c
                  LEFT JOIN asignaciones a ON a.conductor_id = c.id
                  LEFT JOIN buses b ON a.bus_id = b.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $query = "SELECT * FROM conductores WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear(Conductor $conductor) {
        $query = "INSERT INTO conductores (nombre, apellido, dni, telefono, bus_asignado) 
                  VALUES (:nombre, :apellido, :dni, :telefono, :bus_asignado)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $conductor->nombre);
        //$stmt->bindParam(':apellido', $conductor->apellido);
        $stmt->bindParam(':dni', $conductor->dni);
        $stmt->bindParam(':telefono', $conductor->telefono);
        //$stmt->bindParam(':bus_asignado', $conductor->bus_asignado);
        return $stmt->execute();
    }

    public function actualizar(Conductor $conductor) {
        $query = "UPDATE conductores 
                  SET nombre = :nombre, apellido = :apellido, dni = :dni, telefono = :telefono, bus_asignado = :bus_asignado 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $conductor->id);
        $stmt->bindParam(':nombre', $conductor->nombre);
        //$stmt->bindParam(':apellido', $conductor->apellido);
        $stmt->bindParam(':dni', $conductor->dni);
        $stmt->bindParam(':telefono', $conductor->telefono);
        //$stmt->bindParam(':bus_asignado', $conductor->bus_asignado);
        return $stmt->execute();
    }

    public function eliminar($id) {
        $query = "DELETE FROM conductores WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
