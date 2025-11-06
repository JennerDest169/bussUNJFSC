<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-3">Detalle de Ruta</h2>

    <?php if (!empty($detalleRuta)): ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?= htmlspecialchars($detalleRuta['nombre_ruta']) ?></h5>
            </div>
            <div class="card-body">
                <p><strong>Origen:</strong> <?= htmlspecialchars($detalleRuta['origen']) ?></p>
                <p><strong>Destino:</strong> <?= htmlspecialchars($detalleRuta['destino']) ?></p>
                <p><strong>Horario:</strong> <?= htmlspecialchars($detalleRuta['horario']) ?></p>

                <hr>

                <p><strong>Bus Asignado:</strong> <?= htmlspecialchars($detalleRuta['bus_modelo']) ?> (<?= htmlspecialchars($detalleRuta['bus_placa']) ?>)</p>
                <p><strong>Capacidad:</strong> <?= htmlspecialchars($detalleRuta['capacidad']) ?> pasajeros</p>

                <hr>

                <p><strong>Conductor:</strong> <?= htmlspecialchars($detalleRuta['conductor_nombre']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($detalleRuta['telefono']) ?></p>

                <div class="mt-4">
                    <a href="index.php?controller=Transporte&action=index" class="btn btn-secondary">Volver</a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No se encontró información para esta ruta.</div>
    <?php endif; ?>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
