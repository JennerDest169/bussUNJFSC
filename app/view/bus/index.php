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
            <button type="button" class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#crearBusModal">
                <i class="bi bi-plus-circle me-2"></i>Nuevo Bus
            </button>
        </div>
    </div>

    <div class="row">
        <?php if (!empty($buses)): ?>
            <?php foreach ($buses as $bus): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card bus-card h-100" style="border-left: 4px solid #1e3c72;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold text-primary"><?= htmlspecialchars($bus['placa']) ?></h5>
                                <span class="badge <?= $bus['estado'] === 'Activo' ? 'badge-success' : 'badge-danger' ?>">
                                    <?= htmlspecialchars($bus['estado']) ?>
                                </span>
                            </div>
                            <p class="card-text">
                                <i class="bi bi-car-front me-2 text-muted"></i>
                                <?= htmlspecialchars($bus['modelo']) ?>
                            </p>
                            <p class="card-text">
                                <i class="bi bi-people me-2 text-muted"></i>
                                Capacidad: <?= htmlspecialchars($bus['capacidad']) ?> pasajeros
                            </p>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editarBusModal"
                                        onclick="cargarDatosEditar(<?= $bus['id'] ?>, '<?= $bus['placa'] ?>', '<?= $bus['modelo'] ?>', <?= $bus['capacidad'] ?>, '<?= $bus['estado'] ?>')">
                                    <i class="bi bi-pencil"></i> Editar
                                </button>
                                <?php
                                    if ($bus['estado'] != 'Inactivo') {
                                        echo '<a href="index.php?controller=Bus&action=eliminar&id=' . $bus['id'] . '" 
                                            class="btn btn-danger btn-sm" 
                                            onclick="return confirm(\'¿Seguro que deseas eliminar este bus?\');">
                                            <i class="bi bi-trash"></i> Eliminar </a>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-bus-front fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay buses registrados</h4>
                        <p class="text-muted">Comience agregando un nuevo bus al sistema.</p>
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#crearBusModal">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Primer Bus
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para Crear Bus -->
<div class="modal fade" id="crearBusModal" tabindex="-1" aria-labelledby="crearBusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearBusModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Nuevo Bus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Bus&action=crear">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="placa" class="form-label">Placa *</label>
                        <input type="text" class="form-control" id="placa" name="placa" required>
                    </div>
                    <div class="mb-3">
                        <label for="modelo" class="form-label">Modelo *</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacidad" class="form-label">Capacidad *</label>
                        <input type="number" class="form-control" id="capacidad" name="capacidad" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Mantenimiento">En Mantenimiento</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Bus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Bus -->
<div class="modal fade" id="editarBusModal" tabindex="-1" aria-labelledby="editarBusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarBusModalLabel">
                    <i class="bi bi-pencil me-2"></i>Editar Bus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Bus&action=actualizar">
                <input type="hidden" name="id" id="editar_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editar_placa" class="form-label">Placa *</label>
                        <input type="text" class="form-control" id="editar_placa" name="placa" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_modelo" class="form-label">Modelo *</label>
                        <input type="text" class="form-control" id="editar_modelo" name="modelo" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_capacidad" class="form-label">Capacidad *</label>
                        <input type="number" class="form-control" id="editar_capacidad" name="capacidad" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="editar_estado" class="form-label">Estado *</label>
                        <select class="form-select" id="editar_estado" name="estado" required>
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Mantenimiento">En Mantenimiento</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Bus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cargarDatosEditar(id, placa, modelo, capacidad, estado) {
    document.getElementById('editar_id').value = id;
    document.getElementById('editar_placa').value = placa;
    document.getElementById('editar_modelo').value = modelo;
    document.getElementById('editar_capacidad').value = capacidad;
    document.getElementById('editar_estado').value = estado;
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>