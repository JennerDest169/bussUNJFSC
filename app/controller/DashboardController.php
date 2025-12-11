<?php
require_once __DIR__ . '/../config/Database.php';

class DashboardController {
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
        
        // Obtener todas las estadísticas para el dashboard
        $stats = $this->obtenerEstadisticas();
        extract($stats);

        include __DIR__ . '/../view/dashboard/dashboard.php';
    }

    public function obtenerDatosGrafica() {
        header('Content-Type: application/json');
        
        $mes = isset($_POST['mes']) ? (int)$_POST['mes'] : date('n');
        $ano = isset($_POST['ano']) ? (int)$_POST['ano'] : date('Y');
        
        $datos = $this->obtenerNumeroDeViajesPorRutaEnMes($mes, $ano);
        
        echo json_encode($datos);
        exit;
    }

    private function obtenerEstadisticas() {
        $stats = [];
        
        // Total de buses activos
        $stats['totalBuses'] = $this->contarBusesActivos();
        
        // Total de conductores activos
        $stats['totalConductores'] = $this->contarConductoresActivos();
        
        // Total de rutas activas
        $stats['totalRutas'] = $this->contarRutasActivas();
        
        // Viajes de hoy
        $stats['viajesHoy'] = $this->contarViajesHoy();
        
        // Viajes completados hoy
        $stats['viajesCompletados'] = $this->contarViajesCompletadosHoy();
        
        // Buses en ruta
        $stats['busesEnRuta'] = $this->contarBusesEnRuta();
        
        // Distribución de rutas
        $stats['distribucionRutas'] = $this->obtenerDistribucionRutas();
        
        // Conductores activos
        $stats['conductoresActivos'] = $this->obtenerConductoresActivos();
        
        // Asignaciones recientes
        $stats['asignacionesRecientes'] = $this->obtenerAsignacionesRecientes();

        // obtener la grafica de los viajes completados en los ultimos 5 dias
        $stats['ultimo5Dias'] = $this->contarTodosLosViajesDeLos5UltimosDias();

        //como paso aki las variables
        $stats['graficaPrincipal'] = $this->obtenerNumeroDeViajesPorRutaEnMes(date('n'), date('Y'));

        return $stats;
    }

    private function contarBusesActivos() {
        $query = "SELECT COUNT(*) as total FROM buses WHERE estado = 'Activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function contarConductoresActivos() {
        $query = "SELECT COUNT(*) as total FROM conductores WHERE estado = 'Disponible'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function contarRutasActivas() {
        $query = "SELECT COUNT(*) as total FROM rutas WHERE estado = 'Activa'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function contarViajesHoy() {
        $hoy = date('Y-m-d');
        $query = "SELECT COUNT(*) as total FROM asignaciones WHERE DATE(fecha_asignacion) = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$hoy]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function contarViajesCompletadosHoy() {
        $hoy = date('Y-m-d');
        $query = "SELECT COUNT(*) as total FROM asignaciones WHERE DATE(fecha_asignacion) = ? AND estado = 'Completado'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$hoy]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function contarBusesEnRuta() {
        $hoy = date('Y-m-d');
        $query = "SELECT COUNT(DISTINCT bus_id) as total FROM asignaciones WHERE estado = 'En ruta' AND DATE(fecha_asignacion) = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$hoy]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    private function contarTodosLosViajesDeLos5UltimosDias(){
        $query = "SELECT 
                DATE(fecha_asignacion) as fecha, 
                COUNT(*) as total 
              FROM asignaciones 
              WHERE DATE(fecha_asignacion) >= DATE_SUB(CURDATE(), INTERVAL 4 DAY)
                AND estado = 'Completado'
              GROUP BY DATE(fecha_asignacion)
              ORDER BY fecha ASC";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerDistribucionRutas() {
        $query = "
            SELECT r.nombre, COUNT(a.id) as cantidad_buses
            FROM rutas r 
            LEFT JOIN asignaciones a ON r.id = a.ruta_id AND a.estado = 'En ruta'
            WHERE r.estado = 'Activa'
            GROUP BY r.id, r.nombre
            ORDER BY cantidad_buses DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerConductoresActivos() {
        $query = "
            SELECT c.id, u.nombre, r.nombre as ruta
            FROM conductores c
            LEFT JOIN asignaciones a ON c.id = a.conductor_id AND a.estado = 'En ruta'
            LEFT JOIN usuarios u ON u.id = c.id_usuarios
            LEFT JOIN rutas r ON a.ruta_id = r.id
            WHERE c.estado = 'Disponible'
            ORDER BY u.nombre
            LIMIT 4
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerAsignacionesRecientes() {
        $query = "
            SELECT 
                a.id,
                r.nombre as ruta,
                a.fecha_asignacion,
                b.placa as bus,
                a.estado
            FROM asignaciones a
            LEFT JOIN rutas r ON a.ruta_id = r.id
            LEFT JOIN buses b ON a.bus_id = b.id
            ORDER BY a.fecha_asignacion DESC
            LIMIT 4
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function obtenerNumeroDeViajesPorRutaEnMes($mes, $ano){
        $query = "
            SELECT 
                r.id,
                r.nombre,
                COUNT(v.id) as total_viajes,
                DATE_FORMAT(v.fecha_asignacion, '%Y-%m') as periodo
            FROM asignaciones v
            INNER JOIN rutas r ON v.ruta_id = r.id
            WHERE MONTH(v.fecha_asignacion) = ? 
            AND YEAR(v.fecha_asignacion) = ?
            GROUP BY r.id, r.nombre, DATE_FORMAT(v.fecha_asignacion, '%Y-%m')
            ORDER BY total_viajes DESC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$mes, $ano]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener datos de gráficos (si necesitas endpoints separados para AJAX)
    public function obtenerDatosGrafico() {
        if (!isset($_SESSION['logueado'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $query = "
            SELECT 
                DATE(fecha_asignacion) as fecha,
                COUNT(*) as total_viajes,
                SUM(CASE WHEN estado = 'Completado' THEN 1 ELSE 0 END) as viajes_completados
            FROM asignaciones 
            WHERE fecha_asignacion >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            GROUP BY DATE(fecha_asignacion)
            ORDER BY fecha
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($datos);
    }
}