<?php 
$title = "Dashboard - Sistema de Buses UNJFSC";
include __DIR__ . '/../layout/header.php'; 

// Obtener el rol del usuario
$rol = $_SESSION['usuario']['rol'] ?? 'Estudiante';
$esAdmin = ($rol === 'Administrador');
?>

<?php if ($esAdmin): ?>
    <!-- ========================================
         VISTA DE ADMINISTRADOR
    ======================================== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <div class="container-fluid">
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
                <div class="col-md-12">
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
                            <p class="card-category">Mapa de las rutas activas en el sistema</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive table-hover table-sales">
                                        <table class="table">
                                            <tbody>
                                                <?php foreach($distribucionRutas as $dis): ?>
                                                <tr>
                                                    <td>
                                                        <div class="flag">
                                                            <i class="fas fa-route text-danger fa-2x"></i>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($dis['nombre']) ?></td>
                                                    <td class="text-end"><?= htmlspecialchars($dis['cantidad_buses']) ?></td>
                                                    <td class="text-end">100%</td>
                                                </tr>
                                                <?php endforeach; ?>
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
                                <?php foreach ($conductoresActivos as $conductor): ?>
                                <div class="item-list">
                                    <div class="avatar">
                                        <span class="avatar-title rounded-circle border border-white bg-success">CA</span>
                                    </div>
                                    <div class="info-user ms-3">
                                        <div class="username"><?= htmlspecialchars($conductor['nombre']); ?></div>
                                        <div class="status"><?= $conductor['ruta'] ? htmlspecialchars($conductor['ruta']) : 'Sin ruta asignada'; ?></div>
                                    </div>
                                    <button class="btn btn-icon btn-link op-8 me-1">
                                        <i class="far fa-envelope"></i>
                                    </button>
                                    <button class="btn btn-icon btn-link btn-primary op-8">
                                        <i class="fas fa-phone"></i>
                                    </button>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($conductoresActivos)): ?>
                                <p class="text-center text-muted">No hay conductores disponibles en este momento.</p>
                                <?php endif; ?>
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
                                        <?php foreach ($asignacionesRecientes as $activo): ?>
                                        <tr>
                                            <th scope="row">
                                                <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <?= htmlspecialchars($activo['ruta']) ?>
                                            </th>
                                            <td class="text-end"><?= htmlspecialchars($activo['fecha_asignacion']) ?></td>
                                            <td class="text-end"><?= htmlspecialchars($activo['bus']) ?></td>
                                            <td class="text-end">
                                                <span class="badge badge-success"><?= htmlspecialchars($activo['estado']) ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        let worldMap;
        let worldMapMarkers = [];
        let worldMapPolylines = [];
        initWorldMap()

        function initWorldMap() {
            worldMap = L.map('world-map').setView([-10.75, -77.76], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(worldMap);
            cargarRutasActivas();
        }

        function cargarRutasActivas() {
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
            
            const colores = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F'];
            const bounds = L.latLngBounds();
            
            rutas.forEach((ruta, index) => {
                const coordsOrigen = [ruta.latOrigen, ruta.lngOrigen];
                const coordsDestino = [ruta.latDestino, ruta.lngDestino];
                const color = colores[index % colores.length];
                
                const markerOrigen = L.circleMarker(coordsOrigen, {
                    radius: 8,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(worldMap)
                .bindPopup(`<div style="min-width: 200px;">
                    <h6 style="margin: 0 0 8px 0; color: ${color};">${ruta.nombre}</h6>
                    <b>Origen:</b> ${ruta.origen}<br>
                    <b>Salida:</b> ${ruta.horaSalida}
                </div>`);
                
                const markerDestino = L.circleMarker(coordsDestino, {
                    radius: 8,
                    fillColor: color,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(worldMap)
                .bindPopup(`<div style="min-width: 200px;">
                    <h6 style="margin: 0 0 8px 0; color: ${color};">${ruta.nombre}</h6>
                    <b>Destino:</b> ${ruta.destino}<br>
                    <b>Llegada:</b> ${ruta.horaLlegada}
                </div>`);
                
                worldMapMarkers.push(markerOrigen, markerDestino);
                
                const polyline = L.polyline([coordsOrigen, coordsDestino], {
                    color: color,
                    weight: 3,
                    opacity: 0.7,
                    dashArray: '10, 5'
                }).addTo(worldMap);
                
                worldMapPolylines.push(polyline);
                bounds.extend(coordsOrigen);
                bounds.extend(coordsDestino);
            });
            
            if (rutas.length > 0) {
                worldMap.fitBounds(bounds, { padding: [30, 30] });
            }
            <?php endif; ?>
        }

        document.addEventListener('DOMContentLoaded', function() {
            const mesActual = new Date().getMonth() + 1;
            document.getElementById('mesSelect').value = mesActual;
            cargarGrafica();
            document.getElementById('mesSelect').addEventListener('change', cargarGrafica);
            document.getElementById('anoSelect').addEventListener('change', cargarGrafica);
        });

        let chartInstance = null;

        function cargarGrafica() {
            const mes = document.getElementById('mesSelect').value;
            const ano = document.getElementById('anoSelect').value;
            
            fetch('index.php?controller=Dashboard&action=obtenerDatosGrafica', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `mes=${mes}&ano=${ano}`
            })
            .then(response => response.json())
            .then(data => actualizarGrafica(data))
            .catch(error => console.error('Error al cargar la gráfica:', error));
        }

        function actualizarGrafica(datos) {
            const ctx = document.getElementById('statisticsChart').getContext('2d');
            const labels = datos.map(item => item.nombre);
            const valores = datos.map(item => parseInt(item.total_viajes));
            
            const coloresBase = [
                'rgba(255, 99, 132, 0.8)', 'rgba(54, 162, 235, 0.8)', 'rgba(255, 206, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)', 'rgba(153, 102, 255, 0.8)', 'rgba(255, 159, 64, 0.8)'
            ];
            
            const coloresBorde = [
                'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
            ];
            
            if (chartInstance) chartInstance.destroy();
            
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
                                font: { size: 12, weight: '600' },
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
                            font: { size: 18, weight: 'bold' },
                            color: '#333'
                        }
                    }
                }
            });
        }

        <?php
            $fechas = [];
            $viajes = [];
            foreach($ultimo5Dias as $row) {
                $fechas[] = $row['fecha'];
                $viajes[] = $row['total'];
            }
        ?>

        const ctxDaily = document.getElementById('dailySalesChart').getContext('2d');
        const dailyChart = new Chart(ctxDaily, {
            type: 'bar',
            data: {
                labels: <?= json_encode($fechas) ?>,
                datasets: [{
                    label: 'Viajes Diarios',
                    data: <?= json_encode($viajes) ?>,
                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 3,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'top', labels: { color: '#ffffff' } },
                    title: { display: true, text: 'Cantidad de Viajes por Día', color: '#ffffff' }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#ffffff' } },
                    x: { ticks: { color: '#ffffff' } }
                }
            }
        });
    </script>

<?php else: ?>
    <!-- ========================================
         VISTA DE ESTUDIANTE - INFORMATIVA
    ======================================== -->
    
    <!-- Hero Section con Imagen de Fondo - SIN PADDING -->
    <div class="hero-dashboard" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.3) 40%, rgba(0,0,0,0.1) 70%, #f6f7fc 100%), url('/public/assets/img/FondoBuses.png'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 450px; display: flex; align-items: center; justify-content: center; color: white; text-align: center; padding: 40px 20px; margin: 0; width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw;">
        <div style="position: relative; z-index: 2;">
            <h1 style="font-size: 3rem; font-weight: bold; text-shadow: 3px 3px 6px rgba(0,0,0,0.8); margin-bottom: 15px;">
                ¡Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>!
            </h1>
            <p style="font-size: 1.4rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.7); margin-bottom: 0;">
                Sistema de Transporte Universitario UNJFSC
            </p>
        </div>
    </div>

    <div class="container-fluid">
        <div class="page-inner">
            <!-- Secciones Disponibles -->
            <div class="row mt-4">
                <div class="col-12 mb-4">
                    <h3 class="fw-bold text-center">Nuestros servicios</h3>
                    <p class="text-center text-muted">Explora las funcionalidades disponibles</p>
                </div>

                <!-- Card: Buses 
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-round h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-big text-center bubble-shadow-small" style="background-color: #495057; width: 80px; height: 80px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-bus fa-3x" style="color: white;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Buses</h4>
                            <p class="text-muted mb-3">Consulta información sobre la flota de buses disponibles</p>
                            
                            <div class="collapse" id="collapseBuses">
                                <div class="alert alert-light mb-3">
                                    <small><strong>¿Qué puedes consultar?</strong></small>
                                    <ul class="text-start mb-0 mt-2">
                                        <li>Estado de buses activos</li>
                                        <li>Capacidad disponible</li>
                                        <li>Información de cada unidad</li>
                                        <li>Historial de mantenimiento</li>
                                    </ul>
                                </div>
                                <a href="index.php?controller=Bus&action=index" class="btn btn-block w-100 mb-2" style="background-color: #495057; color: white; border: none;">
                                    <i class="fas fa-arrow-right me-2"></i>Ver Buses
                                </a>
                            </div>
                            
                            <button class="btn btn-outline-secondary btn-sm w-100" onclick="toggleInfo('collapseBuses', this)">
                                <span class="toggle-text" style="font-weight: bolder;">Más...</span>
                            </button>
                        </div>
                    </div>
                </div>
                -->
                <!-- Card: Conductores 
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-round h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-big text-center bubble-shadow-small" style="background-color: #8e44ad; width: 80px; height: 80px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-tie fa-3x" style="color: white;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Conductores</h4>
                            <p class="text-muted mb-3">Información sobre los conductores y su disponibilidad</p>
                            
                            <div class="collapse" id="collapseConductores">
                                <div class="alert alert-light mb-3">
                                    <small><strong>¿Qué encontrarás?</strong></small>
                                    <ul class="text-start mb-0 mt-2">
                                        <li>Conductores activos</li>
                                        <li>Asignaciones actuales</li>
                                        <li>Información de contacto</li>
                                        <li>Calificaciones y experiencia</li>
                                    </ul>
                                </div>
                                <a href="index.php?controller=Conductor&action=index" class="btn btn-block w-100 mb-2" style="background-color: #8e44ad; color: white; border: none;">
                                    <i class="fas fa-arrow-right me-2"></i>Ver Conductores
                                </a>
                            </div>
                            
                            <button class="btn btn-dsh-purple btn-sm w-100" onclick="toggleInfo('collapseConductores', this)">
                                <span class="toggle-text" style="font-weight: bolder;">Más...</span>
                            </button>
                        </div>
                    </div>  
                </div>
                -->
                <!-- Card: Rastreo GPS -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-round h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-big text-center bubble-shadow-small" style="background-color: #1572E8; width: 80px; height: 80px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-map-marked-alt fa-3x" style="color: white;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Rastreo GPS</h4>
                            <p class="text-muted mb-3">Rastrea en tiempo real la ubicación de los buses</p>
                            
                            <div class="collapse" id="collapseGPS">
                                <div class="alert alert-light mb-3">
                                    <small><strong>¿Qué puedes rastrear?</strong></small>
                                    <ul class="text-start mb-0 mt-2">
                                        <li>Ubicación en tiempo real</li>
                                        <li>Tiempo estimado de llegada</li>
                                        <li>Ruta del bus en el mapa</li>
                                        <li>Historial de recorridos</li>
                                    </ul>
                                </div>
                                <a href="index.php?controller=RastreoGPS&action=index" class="btn btn-block w-100 mb-2" style="background-color: #1572E8; color: white; border: none;">
                                    <i class="fas fa-arrow-right me-2"></i>Ver Rastreo
                                </a>
                            </div>
                            
                            <button class="btn btn-dsh-blue btn-sm w-100" onclick="toggleInfo('collapseGPS', this)">
                                <span class="toggle-text" style="font-weight: bolder;">Más...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card: Rutas -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-round h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-big text-center bubble-shadow-small" style="background-color: #31CE36; width: 80px; height: 80px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-route fa-3x" style="color: white;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Rutas</h4>
                            <p class="text-muted mb-3">Consulta los horarios de buses y rutas disponibles</p>
                            
                            <div class="collapse" id="collapseRutas">
                                <div class="alert alert-light mb-3">
                                    <small><strong>¿Qué puedes consultar?</strong></small>
                                    <ul class="text-start mb-0 mt-2">
                                        <li>Horarios de salida y llegada</li>
                                        <li>Rutas disponibles</li>
                                        <li>Puntos de parada</li>
                                        <li>Tiempo estimado de viaje</li>
                                    </ul>
                                </div>
                                <a href="index.php?controller=Ruta&action=index" class="btn btn-block w-100 mb-2" style="background-color: #31CE36; color: white; border: none;">
                                    <i class="fas fa-arrow-right me-2"></i>Ver Rutas
                                </a>
                            </div>
                            
                            <button class="btn btn-dsh-green btn-sm w-100" onclick="toggleInfo('collapseRutas', this)">
                                <span class="toggle-text" style="font-weight: bolder;">Más...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card: Mis Incidencias -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-round h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-big text-center bubble-shadow-small" style="background-color: #FFC107; width: 80px; height: 80px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-bullhorn fa-3x" style="color: white;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Mis Incidencias</h4>
                            <p class="text-muted mb-3">Reporta problemas o consulta el estado de tus reportes anteriores</p>
                            
                            <div class="collapse" id="collapseIncidencias">
                                <div class="alert alert-light mb-3">
                                    <small><strong>¿Qué puedes hacer?</strong></small>
                                    <ul class="text-start mb-0 mt-2">
                                        <li>Reportar nuevas incidencias</li>
                                        <li>Ver tus reportes anteriores</li>
                                        <li>Consultar respuestas del administrador</li>
                                        <li>Adjuntar evidencia fotográfica</li>
                                    </ul>
                                </div>
                                <a href="index.php?controller=Incidencia&action=index" class="btn btn-block w-100 mb-2" style="background-color: #FFC107; color: white; border: none;">
                                    <i class="fas fa-arrow-right me-2"></i>Ir a Incidencias
                                </a>
                            </div>
                            
                            <button class="btn btn-dsh-yellow btn-sm w-100" onclick="toggleInfo('collapseIncidencias', this)">
                                <span class="toggle-text" style="font-weight: bolder;">Más...</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card: Comunicados -->
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-round h-100">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <div class="icon-big text-center bubble-shadow-small" style="background-color: #F44336; width: 80px; height: 80px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-comment fa-3x" style="color: white;"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-3">Comunicados</h4>
                            <p class="text-muted mb-3">Mantente informado sobre avisos importantes y novedades del servicio</p>
                            
                            <div class="collapse" id="collapseComunicados">
                                <div class="alert alert-light mb-3">
                                    <small><strong>¿Qué encontrarás?</strong></small>
                                    <ul class="text-start mb-0 mt-2">
                                        <li>Avisos importantes del sistema</li>
                                        <li>Cambios de horarios</li>
                                        <li>Mantenimientos programados</li>
                                        <li>Novedades y mejoras del servicio</li>
                                    </ul>
                                </div>
                                <a href="index.php?controller=Comunicado&action=index" class="btn btn-block w-100 mb-2" style="background-color: #F44336; color: white; border: none;">
                                    <i class="fas fa-arrow-right me-2"></i>Ver Comunicados
                                </a>
                            </div>
                            
                            <button class="btn btn-dsh-red btn-sm w-100" onclick="toggleInfo('collapseComunicados', this)">
                                <span class="toggle-text" style="font-weight: bolder;">Más...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Rápida -->
            <div class="row mt-4 mb-5">
                <div class="col-12">
                    <div class="card card-round">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3">
                                Ofrecemos...
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                        <div>
                                            <strong>Puntualidad:</strong>
                                            <small class="d-block text-muted">Nuestros buses mantienen un 92% de puntualidad en sus horarios</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-shield-alt text-primary me-2 mt-1"></i>
                                        <div>
                                            <strong>Seguridad:</strong>
                                            <small class="d-block text-muted">Todos nuestros buses cuentan con GPS y cámaras de seguridad</small>
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

<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>