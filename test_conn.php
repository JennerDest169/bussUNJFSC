<?php
require_once 'app/config/Database.php'; // ajusta la ruta si es diferente

$db = new Database();
$conn = $db->connect();

if ($conn) {
    echo "✅ Conexión exitosa a la base de datos";
} else {
    echo "❌ Error al conectar";
}
?>
