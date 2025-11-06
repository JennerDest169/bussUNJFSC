<?php 
$title = "Gestión de Buses";
include __DIR__ . '/../layout/header.php'; 
?>

<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Gestión de Buses</h3>
            <h6 class="op-7 mb-2">Administre los buses del sistema de transporte</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/BUSS/index.php?controller=bus&action=create" class="btn btn-primary btn-round">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Bus
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Lista de Buses</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-head-bg-info table-bordered-bd-info">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Placa</th>
                                    <th>Capacidad</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($buses)): ?>
                                    <?php foreach ($buses as $bus): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($bus['id']) ?></td>
                                            <td>
                                                <span class="fw-bold"><?= htmlspecialchars($bus['placa']) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($bus['capacidad']) ?> pasajeros</td>
                                            <td>
                                                <span class="badge <?= $bus['estado'] === 'Activo' ? 'badge-success' : 'badge-danger' ?>">
                                                    <?= htmlspecialchars($bus['estado']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="/BUSS/index.php?controller=bus&action=edit&id=<?= $bus['id'] ?>" 
                                                       class="btn btn-warning btn-sm">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="/BUSS/index.php?controller=bus&action=delete&id=<?= $bus['id'] ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('¿Seguro que deseas eliminar este bus?');">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-bus fa-3x text-muted mb-3"></i>
                                                <h4 class="text-muted">No hay buses registrados</h4>
                                                <p class="text-muted">Comience agregando un nuevo bus al sistema.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>