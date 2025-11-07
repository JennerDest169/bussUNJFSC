<?php
require_once __DIR__ . '/../config/Database.php';

class Incidencia {
    private $conn;
    private $table = 'incidencias';

    public $id;
    public $usuario_id;
    public $tipo;
    public $descripcion;
    public $fecha_reporte;
    public $estado;
    public $respuesta;
    public $fecha_respuesta;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nueva incidencia
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (usuario_id, tipo, descripcion, estado) 
                  VALUES (:usuario_id, :tipo, :descripcion, 'Pendiente')";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        $stmt->bindParam(':tipo', $this->tipo);
        $stmt->bindParam(':descripcion', $this->descripcion);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Listar todas las incidencias (para administrador)
    public function listarTodas() {
        $query = "SELECT i.*, u.nombre as nombre_usuario, u.correo 
                  FROM " . $this->table . " i
                  LEFT JOIN usuarios u ON i.usuario_id = u.id
                  ORDER BY i.fecha_reporte DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Listar incidencias por usuario
    public function listarPorUsuario($usuario_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE usuario_id = :usuario_id 
                  ORDER BY fecha_reporte DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt;
    }

    // Obtener una incidencia por ID
    public function obtenerPorId($id) {
        $query = "SELECT i.*, u.nombre as nombre_usuario, u.correo 
                  FROM " . $this->table . " i
                  LEFT JOIN usuarios u ON i.usuario_id = u.id
                  WHERE i.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar estado y respuesta (para administrador)
    public function responder($id, $respuesta, $estado) {
        $query = "UPDATE " . $this->table . " 
                  SET respuesta = :respuesta, 
                      estado = :estado,
                      fecha_respuesta = CURRENT_TIMESTAMP
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':respuesta', $respuesta);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Contar incidencias por estado
    public function contarPorEstado() {
        $query = "SELECT estado, COUNT(*) as total 
                  FROM " . $this->table . " 
                  GROUP BY estado";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Estadísticas de incidencias por tipo
    public function estadisticasPorTipo() {
        $query = "SELECT tipo, COUNT(*) as total 
                  FROM " . $this->table . " 
                  GROUP BY tipo 
                  ORDER BY total DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>