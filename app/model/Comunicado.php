<!--Comunicado.php - model-->
<?php
require_once __DIR__ . '/../config/Database.php';

class Comunicado {
    private $conn;
    private $table = 'comunicados';

    public $id;
    public $titulo;
    public $contenido;
    public $tipo;
    public $prioridad;
    public $usuario_id;
    public $fecha_publicacion;
    public $fecha_vigencia;
    public $estado;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nuevo comunicado
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (titulo, contenido, tipo, prioridad, usuario_id, fecha_vigencia, estado) 
                  VALUES (:titulo, :contenido, :tipo, :prioridad, :usuario_id, :fecha_vigencia, 'Activo')";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':contenido', $this->contenido);
        $stmt->bindParam(':tipo', $this->tipo);
        $stmt->bindParam(':prioridad', $this->prioridad);
        $stmt->bindParam(':usuario_id', $this->usuario_id);
        $stmt->bindParam(':fecha_vigencia', $this->fecha_vigencia);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Listar comunicados activos (para estudiantes)
    public function listarActivos() {
        $query = "SELECT c.*, u.nombre as autor 
                  FROM " . $this->table . " c
                  LEFT JOIN usuarios u ON c.usuario_id = u.id
                  WHERE c.estado = 'Activo' 
                  AND (c.fecha_vigencia IS NULL OR c.fecha_vigencia >= CURDATE())
                  ORDER BY c.prioridad DESC, c.fecha_publicacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Listar todos los comunicados (para administrador)
    public function listarTodos() {
        $query = "SELECT c.*, u.nombre as autor 
                  FROM " . $this->table . " c
                  LEFT JOIN usuarios u ON c.usuario_id = u.id
                  ORDER BY c.fecha_publicacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener comunicado por ID
    public function obtenerPorId($id) {
        $query = "SELECT c.*, u.nombre as autor 
                  FROM " . $this->table . " c
                  LEFT JOIN usuarios u ON c.usuario_id = u.id
                  WHERE c.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar comunicado
    public function actualizar() {
        $query = "UPDATE " . $this->table . " 
                  SET titulo = :titulo,
                      contenido = :contenido,
                      tipo = :tipo,
                      prioridad = :prioridad,
                      fecha_vigencia = :fecha_vigencia,
                      estado = :estado
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':contenido', $this->contenido);
        $stmt->bindParam(':tipo', $this->tipo);
        $stmt->bindParam(':prioridad', $this->prioridad);
        $stmt->bindParam(':fecha_vigencia', $this->fecha_vigencia);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar comunicado
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cambiar estado (activar/desactivar)
    public function cambiarEstado($id, $estado) {
        $query = "UPDATE " . $this->table . " SET estado = :estado WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>