<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Gestión del Transporte Universitario</h2>

    <div class="row">
        <!-- Tarjetas resumen -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Buses Registrados</h5>
                    <h3 class="text-primary"><?= $totalBuses ?? 0 ?></h3>
                    <a href="index.php?controller=Bus&action=index" class="btn btn-outline-primary btn-sm mt-2">Ver Buses</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Conductores</h5>
                    <h3 class="text-success"><?= $totalConductores ?? 0 ?></h3>
                    <a href="index.php?controller=Conductor&action=index" class="btn btn-outline-success btn-sm mt-2">Ver Conductores</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Rutas Activas</h5>
                    <h3 class="text-warning"><?= $totalRutas ?? 0 ?></h3>
                    <a href="index.php?controller=Ruta&action=index" class="btn btn-outline-warning btn-sm mt-2">Ver Rutas</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla general -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <span>Resumen General del Transporte</span>
            <a href="index.php?controller=Ruta&action=create" class="btn btn-light btn-sm">Agregar Nueva Ruta</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>Ruta</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Horario</th>
                        <th>Bus</th>
                        <th>Conductor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($resumenTransporte)): ?>
                        <?php foreach ($resumenTransporte as $fila): ?>
                            <tr>
                                <td><?= htmlspecialchars($fila['nombre_ruta']) ?></td>
                                <td><?= htmlspecialchars($fila['origen']) ?></td>
                                <td><?= htmlspecialchars($fila['destino']) ?></td>
                                <td><?= htmlspecialchars($fila['horario']) ?></td>
                                <td><?= htmlspecialchars($fila['bus_placa']) ?></td>
                                <td><?= htmlspecialchars($fila['conductor_nombre']) ?></td>
                                <td>
                                    <a href="index.php?controller=Transporte&action=detalle&id=<?= $fila['id_ruta'] ?>" class="btn btn-info btn-sm">Ver</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No hay información disponible.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
