<?php 
$title = "Gestión de Rutas";
include __DIR__ . '/../layout/header.php'; 
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .route-card {
        border-left: 4px solid #ff6b35;
        transition: transform 0.3s ease;
        margin-bottom: 20px;
    }
    .route-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    .route-path {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin: 10px 0;
        border-left: 3px solid #007bff;
    }
    .path-arrow {
        color: #6c757d;
        margin: 0 10px;
    }
    .time-badge {
        font-size: 0.85em;
        padding: 6px 10px;
        border-radius: 15px;
    }
    .status-badge {
        font-size: 0.8em;
    }
    #mainMap {
        height: 400px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .map-container {
        position: relative;
    }
    .route-info-panel {
        position: absolute;
        top: 10px;
        left: 10px;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        max-width: 300px;
    }
</style>

<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Gestión de Rutas</h3>
            <h6 class="op-7 mb-2">Administre las rutas del sistema de transporte</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <button type="button" class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#crearRutaModal">
                <i class="bi bi-plus-circle me-2"></i>Nueva Ruta
            </button>
        </div>
    </div>

    <!-- Mapa Principal -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title"><i class="bi bi-map me-2"></i>Mapa de Rutas</h5>
                </div>
                <div class="card-body">
                    <div class="map-container">
                        <div id="mainMap"></div>
                        <div class="route-info-panel" id="routeInfoPanel" style="display: none;">
                            <h6 id="currentRouteName" class="fw-bold text-primary"></h6>
                            <div class="mt-2">
                                <small><strong>Origen:</strong> <span id="currentRouteOrigin"></span></small><br>
                                <small><strong>Destino:</strong> <span id="currentRouteDestiny"></span></small><br>
                                <small><strong>Horario:</strong> <span id="currentRouteSchedule"></span></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Rutas -->
    <div class="row">
        <?php if (!empty($rutas)): ?>
            <?php foreach ($rutas as $ruta): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card route-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($ruta['nombre']) ?></h5>
                                <span class="badge <?= $ruta['estado'] === 'Activa' ? 'badge-success' : 'badge-danger' ?> status-badge">
                                    <?= htmlspecialchars($ruta['estado']) ?>
                                </span>
                            </div>
                            
                            <!-- Ruta visual -->
                            <div class="route-path">
                                <div class="d-flex align-items-center justify-content-between text-center">
                                    <div class="flex-fill">
                                        <i class="bi bi-geo-alt-fill text-primary"></i>
                                        <small class="d-block fw-bold mt-1"><?= htmlspecialchars($ruta['origen']) ?></small>
                                    </div>
                                    <div class="path-arrow">
                                        <i class="bi bi-arrow-right"></i>
                                    </div>
                                    <div class="flex-fill">
                                        <i class="bi bi-geo-alt text-success"></i>
                                        <small class="d-block fw-bold mt-1"><?= htmlspecialchars($ruta['destino']) ?></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Horarios -->
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <span class="badge badge-primary time-badge">
                                        <i class="bi bi-clock me-1"></i>
                                        <?= htmlspecialchars($ruta['hora_salida']) ?>
                                    </span>
                                    <small class="d-block text-muted mt-1">Salida</small>
                                </div>
                                <div class="col-6">
                                    <span class="badge badge-success time-badge">
                                        <i class="bi bi-clock-fill me-1"></i>
                                        <?= htmlspecialchars($ruta['hora_llegada']) ?>
                                    </span>
                                    <small class="d-block text-muted mt-1">Llegada</small>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <?php if (!empty($ruta['paradas'])): ?>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-signpost me-2"></i>
                                    <small><strong>Paradas:</strong> <?= $ruta['paradas'] ?></small>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($ruta['duracion'])): ?>
                                <div class="d-flex align-items-center text-muted mt-1">
                                    <i class="bi bi-stopwatch me-2"></i>
                                    <small><strong>Duración:</strong> <?= $ruta['duracion'] ?> min</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-transparent p-2">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-info btn-sm" 
                                        onclick="mostrarRutaEnMapa(
                                            <?= $ruta['id'] ?>, 
                                            '<?= $ruta['nombre'] ?>', 
                                            '<?= $ruta['origen'] ?>', 
                                            '<?= $ruta['destino'] ?>', 
                                            '<?= $ruta['hora_salida'] ?>', 
                                            '<?= $ruta['hora_llegada'] ?>'
                                        )">
                                    <i class="bi bi-eye"></i> Ver
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editarRutaModal"
                                        onclick="cargarDatosEditarRuta(
                                            <?= $ruta['id'] ?>, 
                                            '<?= $ruta['nombre'] ?>', 
                                            '<?= $ruta['origen'] ?>', 
                                            '<?= $ruta['destino'] ?>', 
                                            '<?= $ruta['hora_salida'] ?>', 
                                            '<?= $ruta['hora_llegada'] ?>', 
                                            '<?= $ruta['estado'] ?>',
                                            '<?= $ruta['paradas'] ?? '' ?>',
                                            '<?= $ruta['duracion'] ?? '' ?>'
                                        )">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <a href="ruta.php?eliminar=<?= $ruta['id'] ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Seguro que deseas eliminar esta ruta?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-route fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay rutas registradas</h4>
                        <p class="text-muted">Comience agregando una nueva ruta al sistema.</p>
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearRutaModal">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Primera Ruta
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modales de Crear y Editar (igual que antes) -->
<div class="modal fade" id="crearRutaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Nueva Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="ruta.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Ruta *</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Origen *</label>
                            <input type="text" class="form-control" name="origen" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destino *</label>
                            <input type="text" class="form-control" name="destino" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora Salida *</label>
                            <input type="time" class="form-control" name="hora_salida" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora Llegada *</label>
                            <input type="time" class="form-control" name="hora_llegada" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select class="form-select" name="estado" required>
                            <option value="Activa">Activa</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Paradas Intermedias</label>
                        <textarea class="form-control" name="paradas" rows="2" placeholder="Separadas por coma..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control" name="duracion" placeholder="Ej: 45">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarRutaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Editar Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="ruta.php">
                <input type="hidden" name="id" id="editar_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la Ruta *</label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Origen *</label>
                            <input type="text" class="form-control" id="editar_origen" name="origen" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Destino *</label>
                            <input type="text" class="form-control" id="editar_destino" name="destino" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora Salida *</label>
                            <input type="time" class="form-control" id="editar_hora_salida" name="hora_salida" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Hora Llegada *</label>
                            <input type="time" class="form-control" id="editar_hora_llegada" name="hora_llegada" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado *</label>
                        <select class="form-select" id="editar_estado" name="estado" required>
                            <option value="Activa">Activa</option>
                            <option value="Inactiva">Inactiva</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Paradas Intermedias</label>
                        <textarea class="form-control" id="editar_paradas" name="paradas" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control" id="editar_duracion" name="duracion">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let mainMap;
let currentMarkers = [];
let currentPolyline = null;

// Inicializar mapa principal
document.addEventListener('DOMContentLoaded', function() {
    mainMap = L.map('mainMap').setView([-11.117, -77.600], 10);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mainMap);

    // Mostrar primera ruta por defecto si existe
    <?php if (!empty($rutas)): ?>
        mostrarRutaEnMapa(
            <?= $rutas[0]['id'] ?>, 
            '<?= $rutas[0]['nombre'] ?>', 
            '<?= $rutas[0]['origen'] ?>', 
            '<?= $rutas[0]['destino'] ?>', 
            '<?= $rutas[0]['hora_salida'] ?>', 
            '<?= $rutas[0]['hora_llegada'] ?>'
        );
    <?php endif; ?>
});

// Función para mostrar ruta en el mapa y scrollear hacia él
function mostrarRutaEnMapa(id, nombre, origen, destino, horaSalida, horaLlegada) {
    // Scrollear suavemente hacia el mapa
    document.getElementById('mainMap').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });

    // Limpiar marcadores y polilínea anteriores
    currentMarkers.forEach(marker => mainMap.removeLayer(marker));
    currentMarkers = [];
    
    if (currentPolyline) {
        mainMap.removeLayer(currentPolyline);
    }

    // Coordenadas de ejemplo (aquí integrarías con geocodificación real)
    const coordsOrigen = [-11.117 + (Math.random() * 0.05 - 0.025), -77.600 + (Math.random() * 0.05 - 0.025)];
    const coordsDestino = [-11.100 + (Math.random() * 0.05 - 0.025), -77.550 + (Math.random() * 0.05 - 0.025)];
    
    // Agregar marcadores
    const markerOrigen = L.marker(coordsOrigen).addTo(mainMap)
        .bindPopup(`<b>Origen:</b> ${origen}<br><b>Salida:</b> ${horaSalida}`)
        .openPopup();
    
    const markerDestino = L.marker(coordsDestino).addTo(mainMap)
        .bindPopup(`<b>Destino:</b> ${destino}<br><b>Llegada:</b> ${horaLlegada}`);

    currentMarkers.push(markerOrigen, markerDestino);
    
    // Agregar línea de ruta
    currentPolyline = L.polyline([coordsOrigen, coordsDestino], {
        color: 'blue', 
        weight: 4,
        opacity: 0.7
    }).addTo(mainMap);

    // Ajustar vista para mostrar la ruta completa
    mainMap.fitBounds(currentPolyline.getBounds());

    // Actualizar panel de información
    const panel = document.getElementById('routeInfoPanel');
    document.getElementById('currentRouteName').textContent = nombre;
    document.getElementById('currentRouteOrigin').textContent = origen;
    document.getElementById('currentRouteDestiny').textContent = destino;
    document.getElementById('currentRouteSchedule').textContent = `${horaSalida} - ${horaLlegada}`;
    panel.style.display = 'block';
}

// Cargar datos para editar
function cargarDatosEditarRuta(id, nombre, origen, destino, horaSalida, horaLlegada, estado, paradas, duracion) {
    document.getElementById('editar_id').value = id;
    document.getElementById('editar_nombre').value = nombre;
    document.getElementById('editar_origen').value = origen;
    document.getElementById('editar_destino').value = destino;
    document.getElementById('editar_hora_salida').value = horaSalida;
    document.getElementById('editar_hora_llegada').value = horaLlegada;
    document.getElementById('editar_estado').value = estado;
    document.getElementById('editar_paradas').value = paradas || '';
    document.getElementById('editar_duracion').value = duracion || '';
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>