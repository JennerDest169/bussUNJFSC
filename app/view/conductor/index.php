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
            <button type="button" class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#crearConductorModal">
                <i class="bi bi-person-plus me-2"></i>Nuevo Conductor
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Lista de Conductores</div>
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
                                                    <button type="button" class="btn btn-warning btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editarConductorModal"
                                                            onclick="cargarDatosEditarConductor(
                                                                <?= $conductor['id'] ?>, 
                                                                '<?= $conductor['nombre'] ?>', 
                                                                '<?= $conductor['dni'] ?>', 
                                                                '<?= $conductor['telefono'] ?>', 
                                                                '<?= $conductor['licencia'] ?>', 
                                                                '<?= $conductor['estado'] ?>'
                                                            )">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <?php
                                                        if ($conductor['estado'] != 'Inactivo') {
                                                            echo '<a href="index.php?controller=Conductor&action=eliminar&id=' . $conductor['id'] . '" 
                                                                class="btn btn-danger btn-sm" 
                                                                onclick="return confirm(\'¿Seguro que deseas eliminar este conductor?\');">
                                                                    <i class="bi bi-trash"></i>
                                                                </a>';
                                                        }
                                                    ?>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
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

<!-- Modal para Crear Conductor -->
<div class="modal fade" id="crearConductorModal" tabindex="-1" aria-labelledby="crearConductorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearConductorModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Nuevo Conductor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Conductor&action=crear">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI *</label>
                        <input type="text" class="form-control" id="dni" name="dni" required maxlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="emaul" class="form-control" id="correo" name="correo">
                    </div>
                    <div class="mb-3">
                        <label for="licencia" class="form-label">Licencia *</label>
                        <input type="text" class="form-control" id="licencia" name="licencia" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado *</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Disponible">Disponible</option>
                            <option value="En ruta">En ruta</option>
                            <option value="Inactivo">Licencia</option>
                            <option value="Licencia">Licencia</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Conductor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Conductor -->
<div class="modal fade" id="editarConductorModal" tabindex="-1" aria-labelledby="editarConductorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarConductorModalLabel">
                    <i class="bi bi-pencil me-2"></i>Editar Conductor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="index.php?controller=Conductor&action=actualizar">
                <input type="hidden" name="id" id="editar_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editar_nombre" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="editar_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_dni" class="form-label">DNI *</label>
                        <input type="text" class="form-control" id="editar_dni" name="dni" required maxlength="8">
                    </div>
                    <div class="mb-3">
                        <label for="editar_telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="editar_telefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="emaul" class="form-control" id="correo" name="correo">
                    </div>
                    <div class="mb-3">
                        <label for="editar_licencia" class="form-label">Licencia *</label>
                        <input type="text" class="form-control" id="editar_licencia" name="licencia" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar_estado" class="form-label">Estado *</label>
                        <select class="form-select" id="editar_estado" name="estado" required>
                            <option value="Disponible">Disponible</option>
                            <option value="En ruta">En ruta</option>
                            <option value="Inactivo">Licencia</option>
                            <option value="Licencia">Licencia</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Conductor</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function cargarDatosEditarConductor(id, nombre, dni, telefono, licencia, estado) {
    document.getElementById('editar_id').value = id;
    document.getElementById('editar_nombre').value = nombre;
    document.getElementById('editar_dni').value = dni;
    document.getElementById('editar_telefono').value = telefono;
    document.getElementById('editar_licencia').value = licencia;
    document.getElementById('editar_estado').value = estado;
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>