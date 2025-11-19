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
    .form-map {
        height: 200px;
        margin-top: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
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
                            <?php if (!empty($ruta['descripcion'])): ?>
                                <div class="d-flex align-items-center text-muted">
                                    <i class="bi bi-signpost me-2"></i>
                                    <small><strong>Descripción:</strong> <?= htmlspecialchars($ruta['descripcion']) ?></small>
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
                                            '<?= $ruta['hora_llegada'] ?>',
                                            <?= $ruta['lat_origen'] ?? -10.75 ?>, 
                                            <?= $ruta['lng_origen'] ?? -77.76 ?>, 
                                            <?= $ruta['lat_destino'] ?? -10.74 ?>, 
                                            <?= $ruta['lng_destino'] ?? -77.76 ?>
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
                                            '<?= addslashes($ruta['descripcion'] ?? '') ?>',
                                            <?= $ruta['lat_origen'] ?? -10.75 ?>, 
                                            <?= $ruta['lng_origen'] ?? -77.76 ?>, 
                                            <?= $ruta['lat_destino'] ?? -10.74 ?>, 
                                            <?= $ruta['lng_destino'] ?? -77.76 ?>
                                        )">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <?php if ($ruta['estado'] != 'Suspendida'): ?>
                                    <a href="index.php?controller=Ruta&action=eliminar&id=<?= $ruta['id'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('¿Seguro que deseas eliminar esta ruta?');">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                <?php endif; ?>
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

<!-- Modal para Crear Ruta -->
<div class="modal fade" id="crearRutaModal" tabindex="-1" aria-labelledby="crearRutaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearRutaModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Nueva Ruta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Ruta&action=crear">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Ruta *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <!-- Selector de Origen -->
                    <div class="mb-3">
                        <label class="form-label">Origen *</label>
                        <input type="text" class="form-control" id="origen" name="origen" required 
                               placeholder="Escribe la dirección o haz clic en el mapa">
                        <small class="text-muted">Haz clic en el mapa para seleccionar la ubicación exacta</small>
                        <div id="mapOrigen" class="form-map"></div>
                        <input type="hidden" id="lat_origen" name="lat_origen" value="-10.750000">
                        <input type="hidden" id="lng_origen" name="lng_origen" value="-77.760000">
                    </div>
                    
                    <!-- Selector de Destino -->
                    <div class="mb-3">
                        <label class="form-label">Destino *</label>
                        <input type="text" class="form-control" id="destino" name="destino" required 
                               placeholder="Escribe la dirección o haz clic en el mapa">
                        <small class="text-muted">Haz clic en el mapa para seleccionar la ubicación exacta</small>
                        <div id="mapDestino" class="form-map"></div>
                        <input type="hidden" id="lat_destino" name="lat_destino" value="-10.740000">
                        <input type="hidden" id="lng_destino" name="lng_destino" value="-77.760000">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hora_salida" class="form-label">Hora Salida *</label>
                            <input type="time" class="form-control" id="hora_salida" name="hora_salida" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_llegada" class="form-label">Hora Llegada *</label>
                            <input type="time" class="form-control" id="hora_llegada" name="hora_llegada" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Descripción de la ruta..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Activa">Activa</option>
                            <option value="Suspendida">Suspendida</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Ruta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Ruta -->
<div class="modal fade" id="editarRutaModal" tabindex="-1" aria-labelledby="editarRutaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarRutaModalLabel">
                    <i class="bi bi-pencil me-2"></i>Editar Ruta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Ruta&action=actualizar">
                <input type="hidden" name="id" id="editar_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editar_nombre" class="form-label">Nombre de la Ruta *</label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                    </div>
                    
                    <!-- Selector de Origen Editar -->
                    <div class="mb-3">
                        <label class="form-label">Origen *</label>
                        <input type="text" class="form-control" id="editar_origen" name="origen" required>
                        <small class="text-muted">Haz clic en el mapa para seleccionar la ubicación exacta</small>
                        <div id="mapEditarOrigen" class="form-map"></div>
                        <input type="hidden" id="editar_lat_origen" name="lat_origen">
                        <input type="hidden" id="editar_lng_origen" name="lng_origen">
                    </div>
                    
                    <!-- Selector de Destino Editar -->
                    <div class="mb-3">
                        <label class="form-label">Destino *</label>
                        <input type="text" class="form-control" id="editar_destino" name="destino" required>
                        <small class="text-muted">Haz clic en el mapa para seleccionar la ubicación exacta</small>
                        <div id="mapEditarDestino" class="form-map"></div>
                        <input type="hidden" id="editar_lat_destino" name="lat_destino">
                        <input type="hidden" id="editar_lng_destino" name="lng_destino">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editar_hora_salida" class="form-label">Hora Salida *</label>
                            <input type="time" class="form-control" id="editar_hora_salida" name="hora_salida" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editar_hora_llegada" class="form-label">Hora Llegada *</label>
                            <input type="time" class="form-control" id="editar_hora_llegada" name="hora_llegada" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editar_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="editar_descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editar_estado" class="form-label">Estado *</label>
                        <select class="form-select" id="editar_estado" name="estado" required>
                            <option value="Activa">Activa</option>
                            <option value="Suspendida">Suspendida</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Ruta</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Mapas principales
let mainMap;
let currentMarkers = [];
let currentPolyline = null;

// Mapas para formularios
let mapOrigen, mapDestino, mapEditarOrigen, mapEditarDestino;
let markerOrigen, markerDestino, markerEditarOrigen, markerEditarDestino;

// Inicializar mapa principal
document.addEventListener('DOMContentLoaded', function() {
    mainMap = L.map('mainMap').setView([-10.75, -77.76], 12);
    
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
            '<?= $rutas[0]['hora_llegada'] ?>',
            <?= $rutas[0]['lat_origen'] ?? -10.75 ?>, 
            <?= $rutas[0]['lng_origen'] ?? -77.76 ?>, 
            <?= $rutas[0]['lat_destino'] ?? -10.74 ?>, 
            <?= $rutas[0]['lng_destino'] ?? -77.76 ?>
        );
    <?php endif; ?>
});

// Función para mostrar ruta en el mapa principal
function mostrarRutaEnMapa(id, nombre, origen, destino, horaSalida, horaLlegada, latOrigen, lngOrigen, latDestino, lngDestino) {
    // Scrollear suavemente hacia el mapa
    document.getElementById('mainMap').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });

    // Limpiar marcadores anteriores
    currentMarkers.forEach(marker => mainMap.removeLayer(marker));
    currentMarkers = [];
    
    if (currentPolyline) {
        mainMap.removeLayer(currentPolyline);
    }

    // Usar coordenadas reales
    const coordsOrigen = [parseFloat(latOrigen), parseFloat(lngOrigen)];
    const coordsDestino = [parseFloat(latDestino), parseFloat(lngDestino)];
    
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

    // Ajustar vista
    mainMap.fitBounds(currentPolyline.getBounds());

    // Actualizar panel de información
    const panel = document.getElementById('routeInfoPanel');
    document.getElementById('currentRouteName').textContent = nombre;
    document.getElementById('currentRouteOrigin').textContent = origen;
    document.getElementById('currentRouteDestiny').textContent = destino;
    document.getElementById('currentRouteSchedule').textContent = `${horaSalida} - ${horaLlegada}`;
    panel.style.display = 'block';
}

// INICIALIZAR MAPAS MODALES CORRECTAMENTE
function initMapOrigen() {
    if (!mapOrigen) {
        mapOrigen = L.map('mapOrigen').setView([-10.75, -77.76], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapOrigen);
        
        markerOrigen = L.marker([-10.75, -77.76], {draggable: true}).addTo(mapOrigen);
        
        mapOrigen.on('click', function(e) {
            markerOrigen.setLatLng(e.latlng);
            document.getElementById('lat_origen').value = e.latlng.lat;
            document.getElementById('lng_origen').value = e.latlng.lng;
        });
        
        markerOrigen.on('dragend', function(e) {
            document.getElementById('lat_origen').value = e.target.getLatLng().lat;
            document.getElementById('lng_origen').value = e.target.getLatLng().lng;
        });
    } else {
        // Forzar actualización del mapa
        setTimeout(() => {
            mapOrigen.invalidateSize();
        }, 100);
    }
}

function initMapDestino() {
    if (!mapDestino) {
        mapDestino = L.map('mapDestino').setView([-10.74, -77.76], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapDestino);
        
        markerDestino = L.marker([-10.74, -77.76], {draggable: true}).addTo(mapDestino);
        
        mapDestino.on('click', function(e) {
            markerDestino.setLatLng(e.latlng);
            document.getElementById('lat_destino').value = e.latlng.lat;
            document.getElementById('lng_destino').value = e.latlng.lng;
        });
        
        markerDestino.on('dragend', function(e) {
            document.getElementById('lat_destino').value = e.target.getLatLng().lat;
            document.getElementById('lng_destino').value = e.target.getLatLng().lng;
        });
    } else {
        setTimeout(() => {
            mapDestino.invalidateSize();
        }, 100);
    }
}

function initMapEditarOrigen(lat, lng) {
    if (!mapEditarOrigen) {
        mapEditarOrigen = L.map('mapEditarOrigen').setView([lat || -10.75, lng || -77.76], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapEditarOrigen);
        
        markerEditarOrigen = L.marker([lat || -10.75, lng || -77.76], {draggable: true}).addTo(mapEditarOrigen);
        
        mapEditarOrigen.on('click', function(e) {
            markerEditarOrigen.setLatLng(e.latlng);
            document.getElementById('editar_lat_origen').value = e.latlng.lat;
            document.getElementById('editar_lng_origen').value = e.latlng.lng;
        });
        
        markerEditarOrigen.on('dragend', function(e) {
            document.getElementById('editar_lat_origen').value = e.target.getLatLng().lat;
            document.getElementById('editar_lng_origen').value = e.target.getLatLng().lng;
        });
    } else {
        markerEditarOrigen.setLatLng([lat || -10.75, lng || -77.76]);
        setTimeout(() => {
            mapEditarOrigen.invalidateSize();
        }, 100);
    }
}

function initMapEditarDestino(lat, lng) {
    if (!mapEditarDestino) {
        mapEditarDestino = L.map('mapEditarDestino').setView([lat || -10.74, lng || -77.76], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapEditarDestino);
        
        markerEditarDestino = L.marker([lat || -10.74, lng || -77.76], {draggable: true}).addTo(mapEditarDestino);
        
        mapEditarDestino.on('click', function(e) {
            markerEditarDestino.setLatLng(e.latlng);
            document.getElementById('editar_lat_destino').value = e.latlng.lat;
            document.getElementById('editar_lng_destino').value = e.latlng.lng;
        });
        
        markerEditarDestino.on('dragend', function(e) {
            document.getElementById('editar_lat_destino').value = e.target.getLatLng().lat;
            document.getElementById('editar_lng_destino').value = e.target.getLatLng().lng;
        });
    } else {
        markerEditarDestino.setLatLng([lat || -10.74, lng || -77.76]);
        setTimeout(() => {
            mapEditarDestino.invalidateSize();
        }, 100);
    }
}

// EVENTOS PARA MODALES
$('#crearRutaModal').on('shown.bs.modal', function () {
    // Pequeño delay para asegurar que el modal esté completamente visible
    setTimeout(() => {
        initMapOrigen();
        initMapDestino();
    }, 300);
});

$('#editarRutaModal').on('shown.bs.modal', function () {
    setTimeout(() => {
        const latOrigen = document.getElementById('editar_lat_origen').value || -10.75;
        const lngOrigen = document.getElementById('editar_lng_origen').value || -77.76;
        const latDestino = document.getElementById('editar_lat_destino').value || -10.74;
        const lngDestino = document.getElementById('editar_lng_destino').value || -77.76;
        
        initMapEditarOrigen(parseFloat(latOrigen), parseFloat(lngOrigen));
        initMapEditarDestino(parseFloat(latDestino), parseFloat(lngDestino));
    }, 300);
});

// Función para cargar datos en editar
function cargarDatosEditarRuta(id, nombre, origen, destino, horaSalida, horaLlegada, estado, descripcion, latOrigen, lngOrigen, latDestino, lngDestino) {
    document.getElementById('editar_id').value = id;
    document.getElementById('editar_nombre').value = nombre;
    document.getElementById('editar_origen').value = origen;
    document.getElementById('editar_destino').value = destino;
    document.getElementById('editar_hora_salida').value = horaSalida;
    document.getElementById('editar_hora_llegada').value = horaLlegada;
    document.getElementById('editar_estado').value = estado;
    document.getElementById('editar_descripcion').value = descripcion || '';
    
    // Coordenadas
    document.getElementById('editar_lat_origen').value = latOrigen || '-10.750000';
    document.getElementById('editar_lng_origen').value = lngOrigen || '-77.760000';
    document.getElementById('editar_lat_destino').value = latDestino || '-10.740000';
    document.getElementById('editar_lng_destino').value = lngDestino || '-77.760000';
}

// Limpiar al cerrar modales
$('#crearRutaModal').on('hidden.bs.modal', function () {
    // Resetear coordenadas a valores por defecto
    document.getElementById('lat_origen').value = '-10.750000';
    document.getElementById('lng_origen').value = '-77.760000';
    document.getElementById('lat_destino').value = '-10.740000';
    document.getElementById('lng_destino').value = '-77.760000';
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>