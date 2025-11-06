<?php 
$title = "Gestión de Conductores";
include __DIR__ . '/../layout/header.php'; 
?>

<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Gestión de Conductores</h3>
            <h6 class="op-7 mb-2">Administre los conductores del sistema de transporte</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="/BUSS/index.php?controller=Conductor&action=create" class="btn btn-primary btn-round">
                <i class="bi bi-person-plus me-2"></i>Nuevo Conductor
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Lista de Conductores</div>
                        <div class="card-tools">
                            <a href="#" class="btn btn-info btn-border btn-round btn-sm mr-2">
                                <span class="btn-label">
                                    <i class="fa fa-print"></i>
                                </span>
                                Exportar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-head-bg-primary table-bordered-bd-primary">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>DNI</th>
                                    <th>Teléfono</th>
                                    <th>Licencia</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($conductores)): ?>
                                    <?php foreach ($conductores as $conductor): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($conductor['id']) ?></td>
                                            <td>
                                                <span class="fw-bold"><?= htmlspecialchars($conductor['nombre']) ?></span>
                                            </td>
                                            <td><?= htmlspecialchars($conductor['dni']) ?></td>
                                            <td><?= htmlspecialchars($conductor['telefono']) ?></td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?= htmlspecialchars($conductor['licencia']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge <?= $conductor['estado'] === 'Disponible' ? 'badge-success' : 'badge-danger' ?>">
                                                    <?= htmlspecialchars($conductor['estado']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="/BUSS/index.php?controller=Conductor&action=edit&id=<?= $conductor['id'] ?>" 
                                                       class="btn btn-warning btn-sm"
                                                       data-toggle="tooltip" 
                                                       title="Editar conductor">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="/BUSS/index.php?controller=Conductor&action=delete&id=<?= $conductor['id'] ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('¿Seguro que deseas eliminar este conductor?');"
                                                       data-toggle="tooltip" 
                                                       title="Eliminar conductor">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                                                <h4 class="text-muted">No hay conductores registrados</h4>
                                                <p class="text-muted">Comience agregando un nuevo conductor al sistema.</p>
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