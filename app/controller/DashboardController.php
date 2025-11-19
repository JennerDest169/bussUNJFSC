<?php
require_once __DIR__ . '/../config/Database.php';

class DashboardController {
    private $db;
    
    public function __construct() {
        $db = new Database();
        $this->db = $db->connect();
    }
    
    public function index() {
        
        // Verificar que esté logueado
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        // Obtener estadísticas reales de la base de datos
        $stats = $this->getDashboardStats();
        
        // Incluir la vista del dashboard
        include __DIR__ . '/../view/dasboard/dashboard.php';
    }
    
    private function getDashboardStats() {
        
        // Verificar que esté logueado
        if (!isset($_SESSION['logueado'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
        
        $stats = [];
        
        // Total de buses activos
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM buses WHERE estado = 'Activo'");
        $stmt->execute();
        $stats['totalBuses'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total de conductores
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM conductores WHERE estado = 'Activo'");
        $stmt->execute();
        $stats['totalConductores'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Total de rutas activas
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM rutas WHERE estado = 'Activa'");
        $stmt->execute();
        $stats['totalRutas'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Viajes de hoy
        $hoy = date('Y-m-d');
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM asignaciones WHERE DATE(fecha_asignacion) = ?");
        $stmt->execute([$hoy]);
        $stats['viajesHoy'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Buses en ruta
        /*$stmt = $this->db->prepare("SELECT COUNT(DISTINCT bus_id) as total FROM asignaciones WHERE estado = 'En ruta'");
        $stmt->execute();
        $stats['busesEnRuta'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];*/
        
        // Conductores activos
        /*$stmt = $this->db->prepare("SELECT COUNT(DISTINCT conductor_id) as total FROM asignaciones WHERE estado = 'En ruta'");
        $stmt->execute();
        $stats['conductoresActivos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];*/
        
        return $stats;
    }
}