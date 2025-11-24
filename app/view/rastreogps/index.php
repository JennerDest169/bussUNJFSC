<?php 
$title = "Rastreo GPS - Sistema de Buses UNJFSC";
include __DIR__ . '/../layout/header.php'; 

// Determinar si el usuario es conductor
$esConductor = ($_SESSION['tipo_usuario'] ?? '') === 'conductor';
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map {
        height: 600px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .map-container {
        position: relative;
    }
    .info-panel {
        position: absolute;
        top: 10px;
        right: 10px;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 1000;
        max-width: 300px;
    }
    .bus-icon-active {
    background: transparent !important;
    border: none !important;
    }

    .bus-icon-inactive {
    background: transparent !important;
    border: none !important;
    }
    .conductor-active {
        background: #28a745;
        animation: pulse 1.5s infinite;
    }
    .conductor-inactive {
        background: #6c757d;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    .bus-table {
        font-size: 0.9em;
    }
    .status-badge {
        font-size: 0.8em;
    }
    .last-update {
        font-size: 0.8em;
        color: #6c757d;
    }
</style>

<div class="container-fluir">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Rastreo GPS</h3>
                <h6 class="op-7 mb-2">Seguimiento en tiempo real de buses</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <?php if (($_SESSION['rol'] ?? '') === 'Conductor'): ?>
                    <button id="btnIniciarRastreo" class="btn btn-success btn-round me-2">
                        <i class="fas fa-play"></i> Iniciar Rastreo
                    </button>
                    <button id="btnDetenerRastreo" class="btn btn-danger btn-round me-2" style="display: none;">
                        <i class="fas fa-stop"></i> Detener Rastreo
                    </button>
                <?php endif; ?>
                <button id="btnActualizarMapa" class="btn btn-label-info btn-round me-2">
                    <i class="fas fa-sync"></i> Actualizar Mapa
                </button>
                <button id="btnCentrarMapa" class="btn btn-primary btn-round">
                    <i class="fas fa-crosshairs"></i> Centrar Mapa
                </button>
            </div>
        </div>

        <!-- Panel para conductor -->
        <?php if ($_SESSION['rol'] ?? ''): ?>
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card card-round card-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Modo Conductor Activado</h5>
                                <p class="card-text mb-0" id="estadoRastreo">
                                    Presiona "Iniciar Rastreo" para comenzar a compartir tu ubicación
                                </p>
                                <small class="text-muted" id="ultimaUbicacion">
                                    Última ubicación: No disponible
                                </small>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="badge badge-warning" id="badgeEstado">Inactivo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Mapa de Rastreo en Tiempo Real</div>
                            <div class="card-tools">
                                <span class="badge badge-success" id="contadorBuses">0 buses activos</span>
                                <span class="badge badge-info ml-2" id="ultimaActualizacion">Actualizando...</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="map-container">
                            <div id="map"></div>
                            <div class="info-panel">
                                <h6 class="fw-bold text-primary mb-3">Buses Activos</h6>
                                <div id="busesList" class="small">
                                    <div class="text-muted">Cargando buses...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de buses activos -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Información Detallada de Buses</div>
                            <div class="card-tools">
                                <button class="btn btn-sm btn-link" id="btnToggleTabla">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover bus-table" id="tablaBuses">
                                <thead>
                                    <tr>
                                        <th>Conductor</th>
                                        <th>Bus</th>
                                        <th>Ubicación</th>
                                        <th>Última Actualización</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaBuses">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            Cargando información de buses...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Variables globales
let map;
let markers = {};
let rastreoActivo = false;
let watchId = null;
let pollingInterval = null;
let ultimoTimestamp = 0;
const esConductor = <?= ($_SESSION['rol'] ?? '') ? 'true' : 'false'; ?>;

// Iconos personalizados
const busIcon = L.divIcon({
    className: 'bus-icon-active',
    html: `
        <div style="
            width: 24px; 
            height: 24px; 
            background: #007bff; 
            border: 3px solid white; 
            border-radius: 50%; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 10px;
        ">🚌</div>
    `,
    iconSize: [24, 24],
    iconAnchor: [12, 12]
});

const inactiveBusIcon = L.divIcon({
    className: 'bus-icon-inactive',
    html: `
        <div style="
            width: 20px; 
            height: 20px; 
            background: #6c757d; 
            border: 2px solid white; 
            border-radius: 50%; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        "></div>
    `,
    iconSize: [20, 20],
    iconAnchor: [10, 10]
});

// Inicializar mapa
function initMap() {
    // Centro inicial (Lima, Perú - ajusta según tu ubicación)
    const defaultCenter = [-12.046374, -77.042793];
    
    map = L.map('map').setView(defaultCenter, 12);
    
    // Capa de OpenStreetMap (gratuita)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Inicializar el manager de GPS
    initGPSManager();
    
    // Configurar eventos
    initEventos();
}

function initGPSManager() {
    // Cargar ubicaciones iniciales
    cargarUbicaciones();
    
    // Iniciar polling automático para usuarios
    if (!esConductor) {
        iniciarPolling();
    }
}

function initEventos() {
    // Botón actualizar mapa
    document.getElementById('btnActualizarMapa').addEventListener('click', () => {
        cargarUbicaciones();
        mostrarMensaje('Mapa actualizado manualmente', 'success');
    });

    // Botón centrar mapa
    document.getElementById('btnCentrarMapa').addEventListener('click', () => {
        centrarMapaEnBuses();
    });

    // Botón toggle tabla
    document.getElementById('btnToggleTabla').addEventListener('click', function() {
        const icono = this.querySelector('i');
        const tablaBody = document.getElementById('cuerpoTablaBuses').parentNode.parentNode;
        
        if (tablaBody.style.display === 'none') {
            tablaBody.style.display = '';
            icono.className = 'fas fa-chevron-down';
        } else {
            tablaBody.style.display = 'none';
            icono.className = 'fas fa-chevron-up';
        }
    });

    // Eventos para conductores
    if (esConductor) {
        document.getElementById('btnIniciarRastreo').addEventListener('click', iniciarRastreoConductor);
        document.getElementById('btnDetenerRastreo').addEventListener('click', detenerRastreoConductor);
    }
}

// Short Polling cada 5 segundos
function iniciarPolling() {
    pollingInterval = setInterval(() => {
        cargarUbicaciones();
    }, 5000);
}

function detenerPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
}

// Cargar ubicaciones desde el servidor
async function cargarUbicaciones() {
    try {
        const url = `index.php?controller=RastreoGPS&action=obtenerUbicaciones&ultima_actualizacion=${ultimoTimestamp}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data.ubicaciones) {
            actualizarMarcadores(data.ubicaciones);
            actualizarTablaBuses(data.ubicaciones);
            actualizarPanelBuses(data.ubicaciones);
            ultimoTimestamp = data.timestamp || ultimoTimestamp;
            
            // Actualizar UI
            document.getElementById('contadorBuses').textContent = 
                `${data.ubicaciones.length} buses activos`;
            document.getElementById('ultimaActualizacion').textContent = 
                `Actualizado: ${new Date().toLocaleTimeString()}`;
        }
    } catch (error) {
        console.error('Error cargando ubicaciones:', error);
        mostrarMensaje('Error al cargar ubicaciones', 'error');
    }
}

// Actualizar marcadores en el mapa (FIJOS)
function actualizarMarcadores(ubicaciones) {
    // Limpiar solo marcadores que ya no existen
    Object.keys(markers).forEach(conductorId => {
        const existe = ubicaciones.some(u => u.conductor_id == conductorId);
        if (!existe && markers[conductorId]) {
            markers[conductorId].remove();
            delete markers[conductorId];
        }
    });

    if (ubicaciones.length === 0) {
        mostrarMensaje('No hay buses activos en este momento', 'info');
        return;
    }

    ubicaciones.forEach(ubicacion => {
        const position = [parseFloat(ubicacion.latitud), parseFloat(ubicacion.longitud)];
        
        // Determinar si la ubicación es reciente
        const fechaActualizacion = new Date(ubicacion.fecha_actualizacion);
        const ahora = new Date();
        const diferenciaMinutos = Math.floor((ahora - fechaActualizacion) / (1000 * 60));
        const esActivo = diferenciaMinutos <= 5;

        // Si el marcador ya existe, ACTUALIZAR su posición
        if (markers[ubicacion.conductor_id]) {
            markers[ubicacion.conductor_id].setLatLng(position);
            markers[ubicacion.conductor_id].setIcon(esActivo ? busIcon : inactiveBusIcon);
        } else {
            // Crear NUEVO marcador
            const marker = L.marker(position, {
                icon: esActivo ? busIcon : inactiveBusIcon
            }).addTo(map);

            // Popup de información
            const popupContent = `
                <div style="min-width: 200px;">
                    <h6 class="fw-bold text-primary mb-2">${ubicacion.conductor_nombre || 'Conductor'}</h6>
                    <div class="mb-2">
                        <span class="badge badge-success">${ubicacion.placa || 'No asignado'}</span>
                        <span class="badge badge-info">${ubicacion.modelo || 'N/A'}</span>
                    </div>
                    <p class="mb-1 small">
                        <i class="fas fa-clock text-muted"></i> 
                        ${fechaActualizacion.toLocaleTimeString()}
                    </p>
                    <p class="mb-0 small text-muted">
                        <i class="fas fa-map-marker-alt"></i> 
                        ${position[0].toFixed(6)}, ${position[1].toFixed(6)}
                    </p>
                    <p class="mb-0 small mt-1">
                        Estado: <span class="badge ${esActivo ? 'badge-success' : 'badge-warning'}">
                            ${esActivo ? 'Activo' : 'Inactivo'}
                        </span>
                    </p>
                </div>
            `;

            marker.bindPopup(popupContent);
            markers[ubicacion.conductor_id] = marker;
        }
    });

    // Ajustar vista del mapa SOLO si es la primera carga
    if (Object.keys(markers).length > 0 && !map.hasInitialFit) {
        setTimeout(() => {
            centrarMapaEnBuses();
            map.hasInitialFit = true; // Marcar que ya se ajustó
        }, 500);
    }
}

// Centrar mapa en todos los buses
function centrarMapaEnBuses() {
    const markerGroup = new L.featureGroup(Object.values(markers));
    if (markerGroup.getLayers().length > 0) {
        map.fitBounds(markerGroup.getBounds(), { padding: [20, 20] });
    }
}

// Actualizar panel lateral de buses
function actualizarPanelBuses(ubicaciones) {
    const busesList = document.getElementById('busesList');
    
    if (ubicaciones.length === 0) {
        busesList.innerHTML = '<div class="text-muted">No hay buses activos</div>';
        return;
    }

    busesList.innerHTML = ubicaciones.map(ubicacion => {
        const fechaActualizacion = new Date(ubicacion.fecha_actualizacion);
        const ahora = new Date();
        const diferenciaMinutos = Math.floor((ahora - fechaActualizacion) / (1000 * 60));
        const esActivo = diferenciaMinutos <= 5;

        return `
            <div class="d-flex align-items-center mb-2 p-2 border-bottom">
                <div class="flex-shrink-0">
                    <div class="bus-marker ${esActivo ? 'conductor-active' : 'conductor-inactive'}" 
                         style="width: 12px; height: 12px;"></div>
                </div>
                <div class="flex-grow-1 ms-2">
                    <div class="fw-bold">${ubicacion.conductor_nombre || 'Conductor'}</div>
                    <small class="text-muted">${ubicacion.placa || 'Sin placa'}</small>
                    <div class="last-update">Hace ${diferenciaMinutos} min</div>
                </div>
            </div>
        `;
    }).join('');
}

// Actualizar tabla de buses
function actualizarTablaBuses(ubicaciones) {
    const tbody = document.getElementById('cuerpoTablaBuses');
    
    if (ubicaciones.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No hay buses activos en este momento
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = ubicaciones.map(ubicacion => {
        const fechaActualizacion = new Date(ubicacion.fecha_actualizacion);
        const ahora = new Date();
        const diferenciaMinutos = Math.floor((ahora - fechaActualizacion) / (1000 * 60));
        
        let estado = 'success';
        let estadoTexto = 'Activo';
        
        if (diferenciaMinutos > 5) {
            estado = 'warning';
            estadoTexto = 'Inactivo';
        } else if (diferenciaMinutos > 2) {
            estado = 'info';
            estadoTexto = 'Reciente';
        }

        return `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3">
                            <span class="avatar-title bg-primary rounded-circle">
                                ${(ubicacion.conductor_nombre || 'C').charAt(0)}
                            </span>
                        </div>
                        <div>
                            <strong>${ubicacion.conductor_nombre || 'Conductor'}</strong>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="fw-bold">${ubicacion.placa || 'N/A'}</span>
                    <br>
                    <small class="text-muted">${ubicacion.modelo || ''}</small>
                </td>
                <td>
                    <small>
                        ${parseFloat(ubicacion.latitud).toFixed(6)}, ${parseFloat(ubicacion.longitud).toFixed(6)}
                    </small>
                </td>
                <td>
                    ${fechaActualizacion.toLocaleTimeString()}
                    <br>
                    <small class="text-muted">Hace ${diferenciaMinutos} min</small>
                </td>
                <td>
                    <span class="badge badge-${estado}">${estadoTexto}</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-outline-primary" onclick="centrarEnBus(${ubicacion.conductor_id})">
                        <i class="fas fa-search-location"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-info" onclick="verDetallesBus(${ubicacion.conductor_id})">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}

// Funciones para conductores
function iniciarRastreoConductor() {
    if (!navigator.geolocation) {
        mostrarMensaje('Tu navegador no soporta geolocalización', 'error');
        return;
    }

    // Solicitar permisos
    navigator.geolocation.getCurrentPosition(
        function(position) {
            // Permiso concedido
            rastreoActivo = true;
            actualizarUIConductor(true);
            
            // Enviar ubicación inicial
            enviarUbicacionConductor(position.coords.latitude, position.coords.longitude);
            
            // Seguimiento continuo
            watchId = navigator.geolocation.watchPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    enviarUbicacionConductor(lat, lng);
                    actualizarUltimaUbicacion(lat, lng);
                },
                function(error) {
                    console.error('Error en geolocalización:', error);
                    mostrarMensaje('Error de GPS: ' + error.message, 'error');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );

            // Iniciar polling para ver otros buses
            iniciarPolling();
            mostrarMensaje('Rastreo GPS activado correctamente', 'success');
        },
        function(error) {
            let mensaje = 'Para usar el rastreo GPS, necesitas permitir el acceso a tu ubicación';
            if (error.code === error.PERMISSION_DENIED) {
                mensaje = 'Permiso de ubicación denegado. Actívalo en la configuración de tu navegador.';
            }
            mostrarMensaje(mensaje, 'error');
        }
    );
}

function detenerRastreoConductor() {
    if (watchId) {
        navigator.geolocation.clearWatch(watchId);
    }
    rastreoActivo = false;
    actualizarUIConductor(false);
    detenerPolling();
    mostrarMensaje('Rastreo GPS detenido', 'warning');
}

function actualizarUIConductor(activo) {
    const btnIniciar = document.getElementById('btnIniciarRastreo');
    const btnDetener = document.getElementById('btnDetenerRastreo');
    const estadoRastreo = document.getElementById('estadoRastreo');
    const badgeEstado = document.getElementById('badgeEstado');
    const ultimaUbicacion = document.getElementById('ultimaUbicacion');

    // ✅ VERIFICAR que los elementos existan antes de usarlos
    if (!btnIniciar || !btnDetener || !estadoRastreo || !badgeEstado || !ultimaUbicacion) {
        console.error('❌ Elementos del UI no encontrados');
        return;
    }

    if (activo) {
        btnIniciar.style.display = 'none';
        btnDetener.style.display = 'inline-block';
        estadoRastreo.textContent = 'Rastreo activo - Compartiendo ubicación en tiempo real';
        badgeEstado.textContent = 'Activo';
        badgeEstado.className = 'badge badge-success';
    } else {
        btnIniciar.style.display = 'inline-block';
        btnDetener.style.display = 'none';
        estadoRastreo.textContent = 'Rastreo detenido - No se comparte ubicación';
        badgeEstado.textContent = 'Inactivo';
        badgeEstado.className = 'badge badge-warning';
        ultimaUbicacion.textContent = 'Última ubicación: No disponible';
    }
}

function actualizarUltimaUbicacion(lat, lng) {
    document.getElementById('ultimaUbicacion').textContent = 
        `Última ubicación: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
}

async function enviarUbicacionConductor(lat, lng) {
    try {
        const conductor_id = <?= $_SESSION['usuario_id'] ?? 'null' ?>;
        
        console.log('📍 Enviando ubicación:', { lat, lng, conductor_id });

        const response = await fetch('index.php?controller=RastreoGPS&action=actualizarUbicacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                latitud: lat,
                longitud: lng,
                conductor_id: conductor_id
            })
        });

        const data = await response.json();
        if (!data.success) {
            console.error('Error enviando ubicación:', data.error);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// Funciones auxiliares
function centrarEnBus(conductorId) {
    const marker = markers[conductorId];
    if (marker) {
        map.setView(marker.getLatLng(), 15);
        marker.openPopup();
        
        // Animación temporal
        const originalIcon = marker.options.icon;
        marker.setIcon(busIcon);
        
        setTimeout(() => {
            marker.setIcon(originalIcon);
        }, 2000);
    }
}

function verDetallesBus(conductorId) {
    const marker = markers[conductorId];
    if (marker) {
        marker.openPopup();
    }
}

function mostrarMensaje(mensaje, tipo = 'info') {
    // Crear toast notification simple
    const toast = document.createElement('div');
    toast.className = `alert alert-${tipo} alert-dismissible fade show`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        min-width: 300px;
    `;
    toast.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}

// Inicializar cuando la página cargue
document.addEventListener('DOMContentLoaded', function() {
    // Precargar estilos
    const estilo = document.createElement('style');
    estilo.textContent = `
        .avatar-sm {
            width: 32px;
            height: 32px;
        }
        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    `;
    document.head.appendChild(estilo);
    
    // Inicializar mapa
    initMap();
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>