<?php
class ImagenIncidencia {
    private $conn;
    private $table = 'imagenes_incidencias';

    public $id;
    public $incidencia_id;
    public $nombre_archivo;
    public $imagen_blob;
    public $tipo_mime;
    public $tamano;
    public $fecha_subida;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nueva imagen
    public function crear() {
        $query = "INSERT INTO " . $this->table . " 
                  (incidencia_id, nombre_archivo, imagen_blob, tipo_mime, tamano) 
                  VALUES (:incidencia_id, :nombre_archivo, :imagen_blob, :tipo_mime, :tamano)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':incidencia_id', $this->incidencia_id);
        $stmt->bindParam(':nombre_archivo', $this->nombre_archivo);
        $stmt->bindParam(':imagen_blob', $this->imagen_blob, PDO::PARAM_LOB);
        $stmt->bindParam(':tipo_mime', $this->tipo_mime);
        $stmt->bindParam(':tamano', $this->tamano);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener imágenes por incidencia
    public function obtenerPorIncidencia($incidencia_id) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE incidencia_id = :incidencia_id 
                  ORDER BY fecha_subida ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':incidencia_id', $incidencia_id);
        $stmt->execute();
        return $stmt;
    }

    // Obtener una imagen por ID
    public function obtenerPorId($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Eliminar imagen
    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar todas las imágenes de una incidencia
    public function eliminarPorIncidencia($incidencia_id) {
        $query = "DELETE FROM " . $this->table . " WHERE incidencia_id = :incidencia_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':incidencia_id', $incidencia_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>