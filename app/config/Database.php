<?php
class Database {
    private $host = "localhost";
    private $db_name = "transporte_unjfsc";
    private $username = "root";
    private $password = "";
    private $port = "3306"; // Agregar puerto
    private $conn;
    
    public function connect()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . 
                   ";port=" . $this->port . 
                   ";dbname=" . $this->db_name . 
                   ";charset=utf8mb4";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch(PDOException $e) {
            echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
            echo "Código de error: " . $e->getCode() . "<br>";
            
            // Información adicional para debugging
            echo "Host: " . $this->host . "<br>";
            echo "Base de datos: " . $this->db_name . "<br>";
            echo "Usuario: " . $this->username . "<br>";
        }

        return $this->conn;
    }
}
?>