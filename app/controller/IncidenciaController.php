<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Incidencia.php';

class IncidenciaController
{
    private $db;
    private $incidencia;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->incidencia = new Incidencia($this->db);
    }

    // Listar incidencias
    public function index()
    {

        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        // ⭐ AGREGAR ESTA LÍNEA - Pasar $usuario a la vista
        $usuario = $_SESSION['usuario'];
        $rol = $usuario['rol'];

        // Si es administrador, mostrar todas las incidencias
        if ($rol === 'Administrador') {
            $stmt = $this->incidencia->listarTodas();
        } else {
            // Si es estudiante/conductor, mostrar solo sus incidencias
            $stmt = $this->incidencia->listarPorUsuario($usuario['id']);
        }

        $incidencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Estadísticas para administrador
        $estadisticas = [];
        $tipos_stats = [];
        if ($rol === 'Administrador') {
            $stmt_stats = $this->incidencia->contarPorEstado();
            while ($row = $stmt_stats->fetch(PDO::FETCH_ASSOC)) {
                $estadisticas[$row['estado']] = $row['total'];
            }

            $stmt_tipos = $this->incidencia->estadisticasPorTipo();
            $tipos_stats = $stmt_tipos->fetchAll(PDO::FETCH_ASSOC);
        }

        // ⭐ La variable $usuario ya está disponible para la vista
        include __DIR__ . '/../view/incidencia/index.php';
    }

    // Crear nueva incidencia
    // Mostrar formulario y procesar creación
    public function crear()
    {

        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Mostrar formulario
            include __DIR__ . '/../view/incidencia/crear.php';
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Procesar formulario
            $this->incidencia->usuario_id = $usuario['id'];
            $this->incidencia->tipo = $_POST['tipo'] ?? 'Otro';
            $this->incidencia->descripcion = trim($_POST['descripcion']);

            // Validaciones
            $errores = [];

            if (empty($this->incidencia->descripcion)) {
                $errores[] = "La descripción es obligatoria";
            }

            if (strlen($this->incidencia->descripcion) < 10) {
                $errores[] = "La descripción debe tener al menos 10 caracteres";
            }

            if (empty($errores)) {
                // Primero crear la incidencia
                if ($this->incidencia->crear()) {
                    // Obtener el ID de la incidencia recién creada
                    $incidencia_id = $this->db->lastInsertId();

                    // Procesar imágenes si existen
                    if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'][0])) {
                        require_once __DIR__ . '/../model/ImagenIncidencia.php';
                        $imagenModel = new ImagenIncidencia($this->db);

                        $imagenes_subidas = 0;
                        foreach ($_FILES['imagenes']['name'] as $key => $filename) {
                            if ($_FILES['imagenes']['error'][$key] === 0 && $imagenes_subidas < 3) {
                                // Validar tipo de archivo
                                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                                $file_type = $_FILES['imagenes']['type'][$key];

                                if (!in_array($file_type, $allowed_types)) {
                                    continue;
                                }

                                // Validar tamaño (5MB máximo)
                                if ($_FILES['imagenes']['size'][$key] > 5 * 1024 * 1024) {
                                    continue;
                                }

                                // ⭐ Leer el archivo como binario
                                $imagen_binaria = file_get_contents($_FILES['imagenes']['tmp_name'][$key]);

                                if ($imagen_binaria !== false) {
                                    // Guardar en la base de datos
                                    $imagenModel->incidencia_id = $incidencia_id;
                                    $imagenModel->nombre_archivo = $filename;
                                    $imagenModel->imagen_blob = $imagen_binaria; // ⭐ Guardamos el BLOB
                                    $imagenModel->tipo_mime = $file_type;
                                    $imagenModel->tamano = $_FILES['imagenes']['size'][$key];

                                    if ($imagenModel->crear()) {
                                        $imagenes_subidas++;
                                    }
                                }
                            }
                        }

                        if ($imagenes_subidas > 0) {
                            $_SESSION['exito'] = "Incidencia reportada exitosamente con {$imagenes_subidas} imagen(es) adjunta(s)";
                        } else {
                            $_SESSION['exito'] = "Incidencia reportada exitosamente";
                        }
                    } else {
                        $_SESSION['exito'] = "Incidencia reportada exitosamente";
                    }
                } else {
                    $_SESSION['error'] = "Error al reportar la incidencia";
                }
            } else {
                $_SESSION['error'] = implode(", ", $errores);
            }

            header('Location: index.php?controller=Incidencia&action=index');
            exit();
        }
    }


    // Ver detalle de incidencia
    public function ver()
    {

        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?controller=Incidencia&action=index');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        $incidencia = $this->incidencia->obtenerPorId($id);

        // Verificar permisos: admin puede ver todas, usuarios solo las suyas
        if ($usuario['rol'] !== 'Administrador' && $incidencia['usuario_id'] != $usuario['id']) {
            $_SESSION['error'] = "No tienes permiso para ver esta incidencia";
            header('Location: index.php?controller=Incidencia&action=index');
            exit();
        }

        // Obtener imágenes de la incidencia
        require_once __DIR__ . '/../model/ImagenIncidencia.php';
        $imagenModel = new ImagenIncidencia($this->db);
        $stmt_imagenes = $imagenModel->obtenerPorIncidencia($id);
        $imagenes = $stmt_imagenes->fetchAll(PDO::FETCH_ASSOC);

        include __DIR__ . '/../view/incidencia/detalle.php';
    }





    // Responder incidencia (solo administrador)
    public function responder()
    {

        if (!isset($_SESSION['logueado'])) {
            header('Location: index.php?controller=Auth&action=login');
            exit();
        }

        $usuario = $_SESSION['usuario'];
        if ($usuario['rol'] !== 'Administrador') {
            $_SESSION['error'] = "No tienes permiso para realizar esta acción";
            header('Location: index.php?controller=Incidencia&action=index');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $respuesta = trim($_POST['respuesta']);
            $estado = $_POST['estado'];

            if ($this->incidencia->responder($id, $respuesta, $estado)) {
                $_SESSION['exito'] = "Respuesta enviada correctamente";
            } else {
                $_SESSION['error'] = "Error al enviar la respuesta";
            }

            header('Location: index.php?controller=Incidencia&action=ver&id=' . $id);
            exit();
        }
    }


    // Servir imagen desde la base de datos
public function mostrarImagen()
{
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(404);
        die('Imagen no encontrada');
    }

    // ⭐ Consulta directa para asegurar que obtenemos el BLOB
    $query = "SELECT id, nombre_archivo, imagen_blob, tipo_mime, tamano 
              FROM imagenes_incidencias 
              WHERE id = :id LIMIT 1";
    
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $imagen = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$imagen) {
        http_response_code(404);
        die('Imagen no encontrada en la base de datos');
    }

    if (empty($imagen['imagen_blob'])) {
        http_response_code(500);
        die('La imagen está corrupta o vacía');
    }

    // ⭐ CRÍTICO: Limpiar cualquier output buffer previo
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Establecer headers
    header('Content-Type: ' . $imagen['tipo_mime']);
    header('Content-Length: ' . strlen($imagen['imagen_blob']));
    header('Content-Disposition: inline; filename="' . $imagen['nombre_archivo'] . '"');
    header('Cache-Control: max-age=3600, public');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

    // Enviar el binario
    echo $imagen['imagen_blob'];
    exit();
}
    
}
