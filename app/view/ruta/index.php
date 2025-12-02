<?php 
$title = "Gestión de Rutas";
include __DIR__ . '/../layout/header.php'; 
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .route-card {
        border-left: 4px solid #1e3c72;
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
        height: 500px; /* Más alto para mejor visualización */
        border-radius: 10px;
        margin-bottom: 20px;
        border: 2px solid #dee2e6;
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
        height: 250px; /* Más alto para mejor interacción */
        margin-top: 10px;
        border: 2px solid #dee2e6;
        border-radius: 8px;
    }
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1001;
        border-radius: 8px;
    }
    .route-stats {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .distance-badge, .duration-badge {
        font-size: 0.9em;
        padding: 8px 12px;
        border-radius: 20px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
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
                        <div id="mainMap">
                            <div class="loading-overlay" id="mainMapLoading">
                                <div class="text-center">
                                    <div class="spinner-border text-primary mb-2"></div>
                                    <p class="mb-0">Cargando mapa...</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3" id="routeInfoPanel" style="display: none;">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 id="currentRouteName" class="fw-bold text-primary mb-1"></h6>
                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                <small><strong><i class="bi bi-geo-alt-fill text-primary"></i> Origen:</strong> <span id="currentRouteOrigin"></span></small>
                                                <small><strong><i class="bi bi-geo-alt text-success"></i> Destino:</strong> <span id="currentRouteDestiny"></span></small>
                                                <small><strong><i class="bi bi-clock"></i> Horario:</strong> <span id="currentRouteSchedule"></span></small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row text-center">
                                                <div class="col-6">
                                                    <span class="badge bg-success mb-1" id="currentRouteDistance">
                                                        <i class="bi bi-signpost me-1"></i><br><small>-- km</small>
                                                    </span>
                                                </div>
                                                <div class="col-6">
                                                    <span class="badge bg-info mb-1" id="currentRouteDuration">
                                                        <i class="bi bi-clock me-1"></i><br><small>-- min</small>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
            <div class="col-md-6 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-route text-primary me-2"></i>
                                <?= htmlspecialchars($ruta['nombre']) ?>
                            </h5>
                            <span class="badge <?= $ruta['estado'] === 'Activa' ? 'bg-success' : 'bg-danger' ?>">
                                <?= htmlspecialchars($ruta['estado']) ?>
                            </span>
                        </div>
                        
                        <!-- Información básica -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3 text-primary">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="small text-muted">Origen</div>
                                    <div class="fw-semibold"><?= htmlspecialchars($ruta['origen']) ?></div>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <div class="me-3 text-success">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="flex-fill">
                                    <div class="small text-muted">Destino</div>
                                    <div class="fw-semibold"><?= htmlspecialchars($ruta['destino']) ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Horarios -->
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="small text-muted">Salida</div>
                                <div class="fw-semibold">
                                    <i class="bi bi-clock text-primary me-1"></i>
                                    <?= htmlspecialchars($ruta['hora_salida']) ?>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="small text-muted">Llegada</div>
                                <div class="fw-semibold">
                                    <i class="bi bi-clock-fill text-success me-1"></i>
                                    <?= htmlspecialchars($ruta['hora_llegada']) ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Descripción (si existe) -->
                        <?php if (!empty($ruta['descripcion'])): ?>
                            <div class="mb-3">
                                <div class="small text-muted">Descripción</div>
                                <div class="small"><?= htmlspecialchars($ruta['descripcion']) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Fecha de creación (similar a usuarios) -->
                        <div class="small text-muted mt-2">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= date('d/m/Y', strtotime($ruta['fecha_creacion'] ?? 'now')) ?>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white border-top-0 pt-0">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-sm btn-outline-primary" 
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
                                <i class="bi bi-eye me-1"></i>Ver
                            </button>
                            
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-warning" 
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
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('¿Seguro que deseas eliminar esta ruta?');">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                <?php endif; ?>
                            </div>
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

<!-- Modales (mantener igual que antes) -->
<!-- Modal para Crear Ruta -->
<div class="modal fade" id="crearRutaModal" tabindex="-1" aria-labelledby="crearRutaModalLabel" aria-hidden="true">
    <!-- ... mantener el mismo código del modal crear ... -->
</div>

<!-- Modal para Editar Ruta -->
<div class="modal fade" id="editarRutaModal" tabindex="-1" aria-labelledby="editarRutaModalLabel" aria-hidden="true">
    <!-- ... mantener el mismo código del modal editar ... -->
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Configuración de OpenRouteService
const ORS_API_KEY = '5b3ce3597851110001cf6248ea7b4bf3a5de4c598c33447e45d8a32d';
const ORS_BASE_URL = 'https://api.openrouteservice.org/v2/directions/driving-car';

// Mapas principales
let mainMap;
let currentMarkers = [];
let currentRouteLayer = null;

// Mapas para formularios (mantener igual)
let mapOrigen, mapDestino, mapEditarOrigen, mapEditarDestino;
let markerOrigen, markerDestino, markerEditarOrigen, markerEditarDestino;

// Inicializar mapa principal
document.addEventListener('DOMContentLoaded', function() {
    mainMap = L.map('mainMap').setView([-10.75, -77.76], 12);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mainMap);

    // Ocultar loading
    document.getElementById('mainMapLoading').style.display = 'none';

    // Mostrar primera ruta por defecto si existe
    <?php if (!empty($rutas)): ?>
        setTimeout(() => {
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
        }, 1000);
    <?php endif; ?>
});

// Función para obtener ruta real de OpenRouteService
async function obtenerRutaReal(startCoords, endCoords) {
    try {
        const url = `${ORS_BASE_URL}?api_key=${ORS_API_KEY}&start=${startCoords[1]},${startCoords[0]}&end=${endCoords[1]},${endCoords[0]}`;
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.features && data.features[0]) {
            return {
                geometry: data.features[0].geometry,
                distance: (data.features[0].properties.segments[0].distance / 1000).toFixed(1), // km
                duration: Math.round(data.features[0].properties.segments[0].duration / 60) // minutos
            };
        }
    } catch (error) {
        console.error('Error obteniendo ruta:', error);
        return null;
    }
}

// Función mejorada para mostrar ruta en el mapa
async function mostrarRutaEnMapa(id, nombre, origen, destino, horaSalida, horaLlegada, latOrigen, lngOrigen, latDestino, lngDestino) {
    // Mostrar loading
    const loadingOverlay = document.getElementById('mainMapLoading');
    loadingOverlay.style.display = 'flex';
    
    // Scrollear suavemente hacia el mapa
    document.getElementById('mainMap').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });

    // Limpiar capas anteriores
    currentMarkers.forEach(marker => mainMap.removeLayer(marker));
    currentMarkers = [];
    
    if (currentRouteLayer) {
        mainMap.removeLayer(currentRouteLayer);
    }

    // Usar coordenadas reales
    const startCoords = [parseFloat(latOrigen), parseFloat(lngOrigen)];
    const endCoords = [parseFloat(latDestino), parseFloat(lngDestino)];
    
    // Obtener ruta real
    const rutaData = await obtenerRutaReal(startCoords, endCoords);
    
    // Agregar marcadores
    const markerOrigen = L.marker(startCoords).addTo(mainMap)
        .bindPopup(`
            <div class="text-center">
                <i class="bi bi-geo-alt-fill text-primary fs-4"></i>
                <h6 class="mt-1 mb-1"><strong>Origen</strong></h6>
                <p class="mb-1">${origen}</p>
                <small class="text-muted">Salida: ${horaSalida}</small>
            </div>
        `)
        .openPopup();
    
    const markerDestino = L.marker(endCoords).addTo(mainMap)
        .bindPopup(`
            <div class="text-center">
                <i class="bi bi-geo-alt text-success fs-4"></i>
                <h6 class="mt-1 mb-1"><strong>Destino</strong></h6>
                <p class="mb-1">${destino}</p>
                <small class="text-muted">Llegada: ${horaLlegada}</small>
            </div>
        `);

    currentMarkers.push(markerOrigen, markerDestino);
    
    // Agregar línea de ruta (real o simulación)
    if (rutaData && rutaData.geometry) {
        // Ruta real de OpenRouteService
        const routeCoordinates = rutaData.geometry.coordinates.map(coord => [coord[1], coord[0]]);
        currentRouteLayer = L.polyline(routeCoordinates, {
            color: '#1e3c72',
            weight: 6,
            opacity: 0.8,
            lineJoin: 'round'
        }).addTo(mainMap);
        
        // Ajustar vista a la ruta completa
        mainMap.fitBounds(currentRouteLayer.getBounds());
        
        // Actualizar estadísticas
        document.getElementById('currentRouteDistance').textContent = `${rutaData.distance} km`;
        document.getElementById('currentRouteDuration').textContent = `${rutaData.duration} min`;
    } else {
        // Ruta de línea recta (fallback)
        currentRouteLayer = L.polyline([startCoords, endCoords], {
            color: '#1e3c72',
            weight: 4,
            opacity: 0.7,
            dashArray: '5, 10'
        }).addTo(mainMap);
        
        // Ajustar vista
        mainMap.fitBounds(currentRouteLayer.getBounds());
        
        // Estadísticas estimadas
        document.getElementById('currentRouteDistance').textContent = '-- km';
        document.getElementById('currentRouteDuration').textContent = '-- min';
    }

    // Actualizar panel de información
    const panel = document.getElementById('routeInfoPanel');
    document.getElementById('currentRouteName').textContent = nombre;
    document.getElementById('currentRouteOrigin').textContent = origen;
    document.getElementById('currentRouteDestiny').textContent = destino;
    document.getElementById('currentRouteSchedule').textContent = `${horaSalida} - ${horaLlegada}`;
    panel.style.display = 'block';
    
    // Ocultar loading
    loadingOverlay.style.display = 'none';
}

// Las funciones de inicialización de mapas modales se mantienen igual
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
        setTimeout(() => {
            mapOrigen.invalidateSize();
        }, 100);
    }
}

// ... (mantener las demás funciones de inicialización de mapas igual)

// Eventos para modales (mantener igual)
$('#crearRutaModal').on('shown.bs.modal', function () {
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

// Función para cargar datos en editar (mantener igual)
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
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>