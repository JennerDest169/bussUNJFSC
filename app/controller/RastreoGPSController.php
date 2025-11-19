<?php
require_once __DIR__ . '/../config/Database.php';

class RastreoGPSController {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function index() {
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        
        include __DIR__ . '/../view/rastreogps/index.php';
    }

    public function actualizarUbicacion() {
    // ⭐⭐ INICIAR SESIÓN si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['logueado'])) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'No autorizado']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $latitud = $data['latitud'] ?? null;
        $longitud = $data['longitud'] ?? null;
        $conductor_id = $data['conductor_id'] ?? null;

        if ($latitud && $longitud && $conductor_id) {
            try {
                $query = "INSERT INTO ubicaciones_buses (conductor_id, latitud, longitud, fecha_actualizacion) 
                          VALUES (:conductor_id, :latitud, :longitud, NOW()) 
                          ON DUPLICATE KEY UPDATE 
                          latitud = VALUES(latitud), 
                          longitud = VALUES(longitud), 
                          fecha_actualizacion = NOW()";
                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':conductor_id', $conductor_id);
                $stmt->bindParam(':latitud', $latitud);
                $stmt->bindParam(':longitud', $longitud);
                $stmt->execute();

                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'timestamp' => time()]);
                
            } catch (Exception $e) {
                // ⭐⭐ AGREGAR manejo de errores
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Error en base de datos: ' . $e->getMessage()]);
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }
    
    // ⭐⭐ AGREGAR exit al final
    exit;
}

    // PARA USUARIOS: Obtener todas las ubicaciones activas
    public function obtenerUbicaciones() {
        if (!isset($_SESSION['logueado'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        // Obtener ubicaciones de los últimos 5 minutos
        $query = "SELECT 
                    ub.conductor_id,
                    ub.latitud,
                    ub.longitud,
                    ub.fecha_actualizacion,
                    c.nombre as conductor_nombre,
                    b.placa,
                    b.modelo,
                    UNIX_TIMESTAMP(ub.fecha_actualizacion) as timestamp
                  FROM ubicaciones_buses ub
                  LEFT JOIN conductores c ON ub.conductor_id = c.id
                  LEFT JOIN asignaciones a ON c.id = a.conductor_id AND a.estado = 'En ruta'
                  LEFT JOIN buses b ON a.bus_id = b.id
                  WHERE ub.fecha_actualizacion >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
                  ORDER BY ub.fecha_actualizacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $ubicaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Encontrar la última actualización
        $timestampMasReciente = 0;
        foreach ($ubicaciones as $ubicacion) {
            $timestampMasReciente = max($timestampMasReciente, $ubicacion['timestamp']);
        }

        header('Content-Type: application/json');
        echo json_encode([
            'ubicaciones' => $ubicaciones,
            'timestamp' => $timestampMasReciente,
            'actualizado' => time()
        ]);
    }
}