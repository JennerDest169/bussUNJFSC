<?php
require_once __DIR__ . '/../config/Database.php';

class NotificacionController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Obtener el conteo de notificaciones nuevas para el usuario actual
     * Retorna JSON con el conteo de incidencias y comunicados nuevos
     */
    public function obtenerConteo() {
        header('Content-Type: application/json');
        
        if(!isset($_SESSION['logueado'])) {
            echo json_encode(['error' => 'No autenticado']);
            exit();
        }

        $usuario = $_SESSION['usuario'];
        $usuario_id = $usuario['id'];
        $rol = $usuario['rol'];

        $resultado = [
            'incidencias_nuevas' => 0,
            'comunicados_nuevos' => 0
        ];

        try {
            // Para ADMINISTRADORES: contar incidencias nuevas
            if ($rol === 'Administrador') {
                $ultima_vista = $this->obtenerUltimaVista($usuario_id, 'incidencia');
                
                if ($ultima_vista) {
                    $query = "SELECT COUNT(*) as total FROM incidencias 
                              WHERE fecha_reporte > :ultima_vista";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':ultima_vista', $ultima_vista);
                } else {
                    // Si nunca ha visto, contar todas las incidencias
                    $query = "SELECT COUNT(*) as total FROM incidencias";
                    $stmt = $this->db->prepare($query);
                }
                
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $resultado['incidencias_nuevas'] = (int)$row['total'];
            }

            // Para USUARIOS NO ADMINISTRATIVOS: contar comunicados nuevos
            if ($rol !== 'Administrador') {
                $ultima_vista = $this->obtenerUltimaVista($usuario_id, 'comunicado');
                
                if ($ultima_vista) {
                    $query = "SELECT COUNT(*) as total FROM comunicados 
                              WHERE fecha_publicacion > :ultima_vista 
                              AND estado = 'Activo'";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':ultima_vista', $ultima_vista);
                } else {
                    // Si nunca ha visto, contar todos los comunicados activos
                    $query = "SELECT COUNT(*) as total FROM comunicados 
                              WHERE estado = 'Activo'";
                    $stmt = $this->db->prepare($query);
                }
                
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $resultado['comunicados_nuevos'] = (int)$row['total'];
            }

            // Para ADMINISTRADORES: también contar comunicados nuevos de otros admins
            if ($rol === 'Administrador') {
                $ultima_vista = $this->obtenerUltimaVista($usuario_id, 'comunicado');
                
                if ($ultima_vista) {
                    $query = "SELECT COUNT(*) as total FROM comunicados 
                              WHERE fecha_publicacion > :ultima_vista 
                              AND usuario_id != :usuario_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':ultima_vista', $ultima_vista);
                    $stmt->bindParam(':usuario_id', $usuario_id);
                } else {
                    // Contar comunicados de otros admins
                    $query = "SELECT COUNT(*) as total FROM comunicados 
                              WHERE usuario_id != :usuario_id";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':usuario_id', $usuario_id);
                }
                
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $resultado['comunicados_nuevos'] = (int)$row['total'];
            }

            echo json_encode($resultado);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }

    /**
     * Registrar que el usuario visitó una sección
     */
    public function marcarComoVisto() {
        header('Content-Type: application/json');
        
        if(!isset($_SESSION['logueado'])) {
            echo json_encode(['error' => 'No autenticado']);
            exit();
        }

        $tipo = $_POST['tipo'] ?? null;
        
        if (!$tipo || !in_array($tipo, ['incidencia', 'comunicado'])) {
            echo json_encode(['error' => 'Tipo inválido']);
            exit();
        }

        $usuario_id = $_SESSION['usuario']['id'];
        
        try {
            $query = "INSERT INTO vistas_usuario (usuario_id, tipo_contenido, ultima_vista) 
                      VALUES (:usuario_id, :tipo, NOW()) 
                      ON DUPLICATE KEY UPDATE ultima_vista = NOW()";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->execute();
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit();
    }

    /**
     * Obtener la última vez que el usuario vio un tipo de contenido
     */
    private function obtenerUltimaVista($usuario_id, $tipo) {
        $query = "SELECT ultima_vista FROM vistas_usuario 
                  WHERE usuario_id = :usuario_id AND tipo_contenido = :tipo";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['ultima_vista'] : null;
    }
}