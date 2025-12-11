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
            $user_id = $_SESSION['usuario_id'] ?? null;
            
            if ($latitud && $longitud && $user_id) {
                try {
                    // ⭐⭐ 1. OBTENER EL conductor_id BASADO EN EL user_id
                    $getConductorQuery = "SELECT id FROM conductores WHERE id_usuarios = :user_id";
                    $getConductorStmt = $this->conn->prepare($getConductorQuery);
                    $getConductorStmt->bindParam(':user_id', $user_id);
                    $getConductorStmt->execute();
                    
                    if ($getConductorStmt->rowCount() === 0) {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'error' => 'No se encontró un conductor asociado a este usuario',
                            'user_id' => $user_id
                        ]);
                        exit;
                    }
                    
                    $conductor = $getConductorStmt->fetch(PDO::FETCH_ASSOC);
                    $conductor_id = $conductor['id'];
                    
                    // ⭐⭐ 2. Ahora proceder con el conductor_id correcto
                    $checkUbicacionQuery = "SELECT id FROM ubicaciones_buses WHERE conductor_id = :conductor_id";
                    $checkUbicacionStmt = $this->conn->prepare($checkUbicacionQuery);
                    $checkUbicacionStmt->bindParam(':conductor_id', $conductor_id);
                    $checkUbicacionStmt->execute();
                    
                    if ($checkUbicacionStmt->rowCount() > 0) {
                        $query = "UPDATE ubicaciones_buses 
                                SET latitud = :latitud, 
                                    longitud = :longitud, 
                                    fecha_actualizacion = NOW() 
                                WHERE conductor_id = :conductor_id";
                    } else {
                        $query = "INSERT INTO ubicaciones_buses (conductor_id, latitud, longitud, fecha_actualizacion) 
                                VALUES (:conductor_id, :latitud, :longitud, NOW())";
                    }
                    
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':conductor_id', $conductor_id);
                    $stmt->bindParam(':latitud', $latitud);
                    $stmt->bindParam(':longitud', $longitud);
                    $stmt->execute();

                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true, 
                        'timestamp' => time(),
                        'conductor_id' => $conductor_id,
                        'action' => $checkUbicacionStmt->rowCount() > 0 ? 'updated' : 'inserted'
                    ]);
                    
                } catch (Exception $e) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'error' => 'Error en base de datos: ' . $e->getMessage()
                    ]);
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'error' => 'Datos incompletos o sesión inválida',
                    'user_id_session' => $_SESSION['user_id'] ?? 'no_set'
                ]);
            }
        }
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
                        u.nombre as conductor_nombre,
                        b.placa,
                        b.modelo,
                        UNIX_TIMESTAMP(ub.fecha_actualizacion) as timestamp
                    FROM ubicaciones_buses ub
                    INNER JOIN conductores c ON ub.conductor_id = c.id
                    INNER JOIN asignaciones a ON c.id = a.conductor_id
                    INNER JOIN buses b ON a.bus_id = b.id
                    LEFT JOIN usuarios u ON u.id = c.id_usuarios
                    WHERE ub.fecha_actualizacion >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
                        AND c.estado != 'Inactivo'
                        AND a.estado = 'En ruta'
                        AND b.estado = 'Activo'
                    GROUP BY ub.conductor_id
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