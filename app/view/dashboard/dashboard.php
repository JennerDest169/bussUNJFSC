<?php 
$title = "Dashboard - Sistema de Buses UNJFSC";
include __DIR__ . '/../layout/header.php'; 
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<div class="container-fluir">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Sistema de Gestión de Transporte Universitario</h6>
            </div>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-bus"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Buses Activos</p>
                                    <h4 class="card-title"><?= $totalBuses ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Conductores</p>
                                    <h4 class="card-title"><?= $totalConductores ?? '15' ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                    <i class="fas fa-route"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Rutas Activas</p>
                                    <h4 class="card-title"><?= $totalRutas ?? '8' ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Viajes Hoy</p>
                                    <h4 class="card-title"><?= $viajesHoy ?? '24' ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos y Contenido Principal -->
        <div class="row">
            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Estadísticas de Rutas</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros de Mes y Año -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="mesSelect">Mes:</label>
                                <select id="mesSelect" class="form-control">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="anoSelect">Año:</label>
                                <select id="anoSelect" class="form-control">
                                    <?php 
                                    $anoActual = date('Y');
                                    for($i = 2010; $i <= $anoActual; $i++) {
                                        $selected = ($i == $anoActual) ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Contenedor de la gráfica -->
                        <div class="chart-container" style="min-height: 275px">
                            <canvas id="statisticsChart"></canvas>
                        </div>
                        <div id="myChartLegend"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-primary card-round">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Viajes del Día</div>
                        </div>
                        <div class="card-category"><?= date('d M Y') ?></div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="mb-4 mt-2">
                            <h1><?= $viajesCompletados ?? '18' ?>/<?= $viajesHoy ?? '24' ?></h1>
                        </div>
                        <div class="pull-in" style="height: 340px; width: 100%; padding: 10px;">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="div col-md-12">
                <div class="card card-round">
                    <div class="card-body pb-0">
                        <div class="h1 fw-bold float-end text-primary">+69%</div>
                        <h2 class="mb-2"><?= $busesEnRuta ?? '8' ?></h2>
                        <p class="text-muted">Buses en ruta</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa y Distribución -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <h4 class="card-title">Distribución de Rutas</h4>
                        </div>
                        <p class="card-category">
                            Mapa de las rutas activas en el sistema
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive table-hover table-sales">
                                    <table class="table">
                                        <tbody>
                                            <?php
                                                foreach($distribucionRutas as $dis)
                                                {
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="flag">
                                                        <i class="fas fa-route text-danger fa-2x"></i>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($dis['nombre']) ?></td>
                                                <td class="text-end"><?php echo htmlspecialchars($dis['cantidad_buses']) ?></td>
                                                <td class="text-end">100%</td>
                                            </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mapcontainer">
                                    <div id="world-map" class="w-100" style="height: 300px; border-radius: 8px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conductores Recientes y Asignaciones -->
        <div class="row">
            <div class="col-md-4">
                <div class="card card-round">
                    <div class="card-body">
                        <div class="card-head-row card-tools-still-right">
                            <div class="card-title">Conductores Activos</div>
                        </div>
                        <div class="card-list py-4">
                            <?php 
                                foreach ($conductoresActivos as $conductor) {
                            ?>
                                    <div class="item-list">
                                        <div class="avatar">
                                            <span class="avatar-title rounded-circle border border-white bg-success">CA</span>
                                        </div>
                                        <div class="info-user ms-3">
                                            <div class="username"><?php echo htmlspecialchars($conductor['nombre']); ?></div>
                                            <div class="status"><?php echo $conductor['ruta'] ? htmlspecialchars($conductor['ruta']) : 'Sin ruta asignada'; ?></div>
                                        </div>
                                        <button class="btn btn-icon btn-link op-8 me-1">
                                            <i class="far fa-envelope"></i>
                                        </button>
                                        <button class="btn btn-icon btn-link btn-primary op-8">
                                            <i class="fas fa-phone"></i>
                                        </button>
                                    </div>
                            <?php 
                                }
                                if (empty($conductoresActivos)) {
                                    echo '<p class="text-center text-muted">No hay conductores disponibles en este momento.</p>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <div class="card-title">Asignaciones Recientes</div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Asignación</th>
                                        <th scope="col" class="text-end">Fecha & Hora</th>
                                        <th scope="col" class="text-end">Bus</th>
                                        <th scope="col" class="text-end">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        foreach ($asignacionesRecientes as $activo) {
                                    ?>
                                    <tr>
                                        <th scope="row">
                                            <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <?php echo htmlspecialchars($activo['ruta']) ?>
                                        </th>
                                        <td class="text-end"><?php echo htmlspecialchars($activo['fecha_asignacion']) ?></td>
                                        <td class="text-end"><?php echo htmlspecialchars($activo['bus']) ?></td>
                                        <td class="text-end">
                                            <span class="badge badge-success"><?php echo htmlspecialchars($activo['estado']) ?></span>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Agregar al inicio con las otras variables globales
let worldMap;
let worldMapMarkers = [];
let worldMapPolylines = [];
initWorldMap()

// Agregar esta función después de DOMContentLoaded
function initWorldMap() {
    // Inicializar el mapa de rutas activas
    worldMap = L.map('world-map').setView([-10.75, -77.76], 12);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(worldMap);
    
    // Cargar todas las rutas activas
    cargarRutasActivas();
}

// Función para cargar y mostrar todas las rutas activas
function cargarRutasActivas() {
    // Limpiar marcadores y líneas anteriores
    worldMapMarkers.forEach(marker => worldMap.removeLayer(marker));
    worldMapPolylines.forEach(polyline => worldMap.removeLayer(polyline));
    worldMapMarkers = [];
    worldMapPolylines = [];
    
    <?php if (!empty($rutas)): ?>
    const rutas = [
        <?php foreach ($rutas as $index => $ruta): ?>
        {
            id: <?= $ruta['id'] ?>,
            nombre: '<?= addslashes($ruta['nombre']) ?>',
            origen: '<?= addslashes($ruta['origen']) ?>',
            destino: '<?= addslashes($ruta['destino']) ?>',
            horaSalida: '<?= $ruta['hora_salida'] ?>',
            horaLlegada: '<?= $ruta['hora_llegada'] ?>',
            latOrigen: <?= $ruta['lat_origen'] ?? -10.75 ?>,
            lngOrigen: <?= $ruta['lng_origen'] ?? -77.76 ?>,
            latDestino: <?= $ruta['lat_destino'] ?? -10.74 ?>,
            lngDestino: <?= $ruta['lng_destino'] ?? -77.76 ?>
        }<?= $index < count($rutas) - 1 ? ',' : '' ?>
        <?php endforeach; ?>
    ];
    
    // Array de colores para diferenciar rutas
    const colores = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F'];
    
    // Crear un bounds para ajustar la vista
    const bounds = L.latLngBounds();
    
    rutas.forEach((ruta, index) => {
        const coordsOrigen = [ruta.latOrigen, ruta.lngOrigen];
        const coordsDestino = [ruta.latDestino, ruta.lngDestino];
        
        const color = colores[index % colores.length];
        
        // Marcador de origen
        const markerOrigen = L.circleMarker(coordsOrigen, {
            radius: 8,
            fillColor: color,
            color: '#fff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(worldMap)
        .bindPopup(`
            <div style="min-width: 200px;">
                <h6 style="margin: 0 0 8px 0; color: ${color};">${ruta.nombre}</h6>
                <b>Origen:</b> ${ruta.origen}<br>
                <b>Salida:</b> ${ruta.horaSalida}
            </div>
        `);
        
        // Marcador de destino
        const markerDestino = L.circleMarker(coordsDestino, {
            radius: 8,
            fillColor: color,
            color: '#fff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.8
        }).addTo(worldMap)
        .bindPopup(`
            <div style="min-width: 200px;">
                <h6 style="margin: 0 0 8px 0; color: ${color};">${ruta.nombre}</h6>
                <b>Destino:</b> ${ruta.destino}<br>
                <b>Llegada:</b> ${ruta.horaLlegada}
            </div>
        `);
        
        worldMapMarkers.push(markerOrigen, markerDestino);
        
        // Línea de ruta con flecha animada
        const polyline = L.polyline([coordsOrigen, coordsDestino], {
            color: color,
            weight: 3,
            opacity: 0.7,
            dashArray: '10, 5'
        }).addTo(worldMap);
        
        worldMapPolylines.push(polyline);
        
        // Agregar al bounds
        bounds.extend(coordsOrigen);
        bounds.extend(coordsDestino);
    });
    
    // Ajustar la vista para mostrar todas las rutas
    if (rutas.length > 0) {
        worldMap.fitBounds(bounds, { padding: [30, 30] });
    }
    <?php endif; ?>
}

// Modificar el DOMContentLoaded existente
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar mapa principal
    mainMap = L.map('mainMap').setView([-10.75, -77.76], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mainMap);
    
    // Inicializar mapa de rutas activas
    initWorldMap();
    
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
    </script>

    <script>
// Seleccionar el mes actual automáticamente al cargar
document.addEventListener('DOMContentLoaded', function() {
    const mesActual = new Date().getMonth() + 1; // Enero = 0, por eso +1
    document.getElementById('mesSelect').value = mesActual;
    
    // Cargar gráfica inicial
    cargarGrafica();
    
    // Event listeners para los selects
    document.getElementById('mesSelect').addEventListener('change', cargarGrafica);
    document.getElementById('anoSelect').addEventListener('change', cargarGrafica);
});

let chartInstance = null; // Guardar instancia del chart para actualizarlo

function cargarGrafica() {
    const mes = document.getElementById('mesSelect').value;
    const ano = document.getElementById('anoSelect').value;
    
    // Hacer petición AJAX
    fetch('index.php?controller=Dashboard&action=obtenerDatosGrafica', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mes=${mes}&ano=${ano}`
    })
    .then(response => response.json())
    .then(data => {
        actualizarGrafica(data);
    })
    .catch(error => {
        console.error('Error al cargar la gráfica:', error);
    });
}

function actualizarGrafica(datos) {
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    
    // Preparar datos para la gráfica
    const labels = datos.map(item => item.nombre);
    const valores = datos.map(item => parseInt(item.total_viajes));
    
    // Colores vibrantes para cada ruta
    const coloresBase = [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)',
        'rgba(199, 199, 199, 0.8)',
        'rgba(83, 102, 255, 0.8)',
        'rgba(255, 99, 255, 0.8)',
        'rgba(99, 255, 132, 0.8)'
    ];
    
    const coloresBorde = [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(199, 199, 199, 1)',
        'rgba(83, 102, 255, 1)',
        'rgba(255, 99, 255, 1)',
        'rgba(99, 255, 132, 1)'
    ];
    
    // Destruir gráfica anterior si existe
    if (chartInstance) {
        chartInstance.destroy();
    }
    
    // Crear nueva gráfica
    chartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Viajes por Ruta',
                data: valores,
                backgroundColor: coloresBase.slice(0, labels.length),
                borderColor: coloresBorde.slice(0, labels.length),
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'right',
                    labels: {
                        color: '#333',
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        padding: 15,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return {
                                        text: `${label}: ${value} (${percentage}%)`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Distribución de Viajes por Ruta',
                    font: {
                        size: 18,
                        weight: 'bold'
                    },
                    color: '#333'
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: 'rgba(255, 255, 255, 0.3)',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} viajes (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Actualizar leyenda personalizada si existe
    actualizarLeyendaPersonalizada(labels, valores, coloresBase);
}

function actualizarLeyendaPersonalizada(labels, valores, colores) {
    const legendContainer = document.getElementById('myChartLegend');
    if (!legendContainer) return;
    
    const total = valores.reduce((a, b) => a + b, 0);
    let html = '<div class="row mt-3">';
    
    labels.forEach((label, index) => {
        const percentage = ((valores[index] / total) * 100).toFixed(1);
        html += `
            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background-color: ${colores[index]}; border-radius: 3px; margin-right: 10px;"></div>
                    <div>
                        <strong>${label}</strong>: ${valores[index]} viajes (${percentage}%)
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    legendContainer.innerHTML = html;
}
</script>
                                    
    <script>
        <?php
            $fechas = [];
            $viajes = [];
        
            foreach($ultimo5Dias as $row) {
                $fechas[] = $row['fecha'];
                $viajes[] = $row['total'];
            }
        
        ?>

    console.log(<?php echo json_encode($fechas); ?>)
    const ctx = document.getElementById('dailySalesChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($fechas); ?>,
        datasets: [{
            label: 'Viajes Diarios',
            data: <?php echo json_encode($viajes); ?>,
            backgroundColor: 'rgba(255, 255, 255, 0.9)',
            borderColor: 'rgba(255, 193, 7, 1)',
            borderWidth: 3,
            borderRadius: 8,
            hoverBackgroundColor: 'rgba(255, 193, 7, 0.9)',
            hoverBorderColor: 'rgba(255, 255, 255, 1)',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top',
                labels: {
                    color: '#ffffff',
                    font: {
                        size: 14,
                        weight: 'bold'
                    },
                    padding: 15
                }
            },
            title: {
                display: true,
                text: 'Cantidad de Viajes por Día',
                color: '#ffffff',
                font: {
                    size: 20,
                    weight: 'bold'
                },
                padding: {
                    top: 10,
                    bottom: 20
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: '#ffffff',
                bodyColor: '#ffffff',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 2,
                padding: 12,
                displayColors: true,
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y + ' viajes';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                suggestedMax: function(context) {
                    const maxValue = Math.max(...context.chart.data.datasets[0].data);
                    // Redondea hacia arriba al siguiente múltiplo de 5
                    const maxRounded = Math.ceil(maxValue / 5) * 5;
                    // Si el máximo es 0 o menor a 5, muestra hasta 5
                    return maxRounded < 5 ? 5 : maxRounded;
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)',
                    drawBorder: false
                },
                ticks: {
                    stepSize: 5,
                    color: '#ffffff',
                    font: {
                        size: 12,
                        weight: '600'
                    },
                    callback: function(value) {
                        return value;
                    }
                },
                title: {
                    display: true,
                    text: 'Número de Viajes',
                    color: '#ffffff',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            },
            x: {
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)',
                    drawBorder: false
                },
                ticks: {
                    color: '#ffffff',
                    font: {
                        size: 12,
                        weight: '600'
                    }
                },
                title: {
                    display: true,
                    text: 'Fecha',
                    color: '#ffffff',
                    font: {
                        size: 14,
                        weight: 'bold'
                    }
                }
            }
        }
    }
});
    </script>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>