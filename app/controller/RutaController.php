<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Ruta.php';

class RutaController {
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
        
        $rutas = $this->listar();
        
        include __DIR__ . '/../view/ruta/index.php';
    }

    public function listar() {
        $query = "SELECT * FROM rutas ORDER BY fecha_registro DESC";
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

    public function crear() {
    if (!isset($_SESSION['logueado'])) {
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ruta = new Ruta();
        $ruta->nombre = $_POST['nombre'];
        $ruta->origen = $_POST['origen'];
        $ruta->destino = $_POST['destino'];
        $ruta->descripcion = $_POST['descripcion'];
        $ruta->hora_salida = $_POST['hora_salida'];
        $ruta->hora_llegada = $_POST['hora_llegada'];
        $ruta->estado = $_POST['estado'];
        
        // COORDENADAS DESDE EL FORMULARIO
        $ruta->lat_origen = $_POST['lat_origen'];
        $ruta->lng_origen = $_POST['lng_origen'];
        $ruta->lat_destino = $_POST['lat_destino'];
        $ruta->lng_destino = $_POST['lng_destino'];

        $query = "INSERT INTO rutas (nombre, origen, destino, descripcion, hora_salida, hora_llegada, estado, lat_origen, lng_origen, lat_destino, lng_destino)
                  VALUES (:nombre, :origen, :destino, :descripcion, :hora_salida, :hora_llegada, :estado, :lat_origen, :lng_origen, :lat_destino, :lng_destino)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $ruta->nombre);
        $stmt->bindParam(':origen', $ruta->origen);
        $stmt->bindParam(':destino', $ruta->destino);
        $stmt->bindParam(':descripcion', $ruta->descripcion);
        $stmt->bindParam(':hora_salida', $ruta->hora_salida);
        $stmt->bindParam(':hora_llegada', $ruta->hora_llegada);
        $stmt->bindParam(':estado', $ruta->estado);
        $stmt->bindParam(':lat_origen', $ruta->lat_origen);
        $stmt->bindParam(':lng_origen', $ruta->lng_origen);
        $stmt->bindParam(':lat_destino', $ruta->lat_destino);
        $stmt->bindParam(':lng_destino', $ruta->lng_destino);
        
        $stmt->execute();

        $rutas = $this->listar();
        include __DIR__ . '/../view/ruta/index.php';
    }
}

    public function actualizar() {
    if (!isset($_SESSION['logueado'])) {
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ruta = new Ruta();
        $ruta->id = $_POST['id'];
        $ruta->nombre = $_POST['nombre'];
        $ruta->origen = $_POST['origen'];
        $ruta->destino = $_POST['destino'];
        $ruta->descripcion = $_POST['descripcion'];
        $ruta->hora_salida = $_POST['hora_salida'];
        $ruta->hora_llegada = $_POST['hora_llegada'];
        $ruta->estado = $_POST['estado'];
        
        // COORDENADAS DESDE EL FORMULARIO
        $ruta->lat_origen = $_POST['lat_origen'];
        $ruta->lng_origen = $_POST['lng_origen'];
        $ruta->lat_destino = $_POST['lat_destino'];
        $ruta->lng_destino = $_POST['lng_destino'];

        $query = "UPDATE rutas 
                  SET nombre = :nombre, origen = :origen, destino = :destino, 
                      descripcion = :descripcion, hora_salida = :hora_salida, 
                      hora_llegada = :hora_llegada, estado = :estado,
                      lat_origen = :lat_origen, lng_origen = :lng_origen,
                      lat_destino = :lat_destino, lng_destino = :lng_destino
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $ruta->id);
        $stmt->bindParam(':nombre', $ruta->nombre);
        $stmt->bindParam(':origen', $ruta->origen);
        $stmt->bindParam(':destino', $ruta->destino);
        $stmt->bindParam(':descripcion', $ruta->descripcion);
        $stmt->bindParam(':hora_salida', $ruta->hora_salida);
        $stmt->bindParam(':hora_llegada', $ruta->hora_llegada);
        $stmt->bindParam(':estado', $ruta->estado);
        $stmt->bindParam(':lat_origen', $ruta->lat_origen);
        $stmt->bindParam(':lng_origen', $ruta->lng_origen);
        $stmt->bindParam(':lat_destino', $ruta->lat_destino);
        $stmt->bindParam(':lng_destino', $ruta->lng_destino);
        
        $stmt->execute();

        $rutas = $this->listar();
        include __DIR__ . '/../view/ruta/index.php';
    }
}

    public function eliminar() {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $query = "UPDATE rutas
                    SET estado = 'Suspendida'    
                    WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Volver al listado actualizado
            $rutas = $this->listar();
            include __DIR__ . '/../view/ruta/index.php';
        } else {
            // Si no hay ID, redirigir al listado
            header("Location: index.php?controller=Ruta&action=index");
            exit;
        }
    }
}
?>