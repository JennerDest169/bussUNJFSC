<?php
require_once __DIR__ . '/../config/Database.php';

class Usuario {
    private $conn;
    private $table = "usuarios";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function login($correo, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE correo = :correo LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verificar si existe el usuario y la contraseña coincide
        if ($usuario && password_verify($password, $usuario['password'])) {
            // No retornar la contraseña por seguridad
            unset($usuario['password']);
            return $usuario; // Retorna los datos del usuario
        }
        
        return false; // Credenciales incorrectas
    }
    
    // Obtener todos los usuarios
    public function getAll() {
        $query = "SELECT id, correo, nombre, rol, estado, fecha_registro FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Crear un nuevo usuario
    public function create($correo, $password, $nombre, $rol) {
        if ($this->emailExists($correo)) {
            return false; // El correo ya está registrado
        }

        // Cifrar la contraseña con password_hash (bcrypt por defecto)
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO " . $this->table . " (correo, password, nombre, rol) VALUES (:correo, :password, :nombre, :rol)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':rol', $rol);
        
        return $stmt->execute();
    }
    
    // Verificar si un correo ya existe
    public function emailExists($correo) {
        $query = "SELECT id FROM " . $this->table . " WHERE correo = :correo LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
<<<<<<< HEAD
}
?>
=======

    // Eliminar usuario
public function delete($id) {
    $query = "DELETE FROM " . $this->table . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

// Actualizar usuario (opcional)
public function update($id, $nombre, $rol, $estado) {
    $query = "UPDATE " . $this->table . " SET nombre=:nombre, rol=:rol, estado=:estado WHERE id=:id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':rol', $rol);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}


}
?>
>>>>>>> 669d71246efa76798f9d9bbe9ac59b7d79333bca
