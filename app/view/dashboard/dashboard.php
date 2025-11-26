<?php 
$title = "Dashboard - Sistema de Buses UNJFSC";
include __DIR__ . '/../layout/header.php'; 
?>

<div class="container-fluir">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <h6 class="op-7 mb-2">Sistema de Gestión de Transporte Universitario</h6>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
                <a href="#" class="btn btn-label-info btn-round me-2">Generar Reporte</a>
                <a href="#" class="btn btn-primary btn-round">Nueva Asignación</a>
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
                            <div class="card-tools">
                                <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                                    <span class="btn-label">
                                        <i class="fa fa-pencil"></i>
                                    </span>
                                    Exportar
                                </a>
                                <a href="#" class="btn btn-label-info btn-round btn-sm">
                                    <span class="btn-label">
                                        <i class="fa fa-print"></i>
                                    </span>
                                    Imprimir
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 375px">
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
                            <div class="card-tools">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-label-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Hoy
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="#">Hoy</a>
                                        <a class="dropdown-item" href="#">Ayer</a>
                                        <a class="dropdown-item" href="#">Esta Semana</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-category"><?= date('d M Y') ?></div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="mb-4 mt-2">
                            <h1><?= $viajesCompletados ?? '18' ?>/<?= $viajesHoy ?? '24' ?></h1>
                        </div>
                        <div class="pull-in">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="card card-round">
                    <div class="card-body pb-0">
                        <div class="h1 fw-bold float-end text-primary">+8%</div>
                        <h2 class="mb-2"><?= $busesEnRuta ?? '8' ?></h2>
                        <p class="text-muted">Buses en ruta</p>
                        <div class="pull-in sparkline-fix">
                            <div id="lineChart"></div>
                        </div>
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
                            <div class="card-tools">
                                <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                    <span class="fa fa-sync-alt"></span>
                                </button>
                            </div>
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
                                    <div id="world-map" class="w-100" style="height: 300px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                                            <p>Mapa de Rutas UNJFSC</p>
                                            <small>Visualización de rutas activas</small>
                                        </div>
                                    </div>
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
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>