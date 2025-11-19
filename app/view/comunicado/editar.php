<!--editar.php - comunicado-->
<?php 
$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
$title = "Comunicados | Editar";
include __DIR__ . '/../layout/header.php'; 
?>

<!-- Modal de Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="previewModalLabel">
          <i class="fas fa-eye me-2"></i>Vista Previa del Comunicado
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="preview-card">
          <div class="preview-header">
            <div class="d-flex justify-content-between align-items-start w-100">
              <div>
                <span class="preview-label">Tipo:</span>
                <span class="badge bg-info" id="previewTipoEdit">No seleccionado</span>
              </div>
              <div>
                <span class="preview-label">Prioridad:</span>
                <span class="badge" id="previewPrioridadEdit" style="background-color: #6c757d;">No seleccionada</span>
              </div>
            </div>
          </div>
          <div class="preview-divider"></div>
          <div class="mb-3">
            <span class="preview-label">Título:</span>
            <h4 id="previewTituloEdit" class="mt-2"><em class="text-muted">Sin título</em></h4>
          </div>
          <div class="preview-divider"></div>
          <div class="preview-content">
            <span class="preview-label">Contenido:</span>
            <div class="preview-text" id="previewContenidoEdit">
              <em class="text-muted">Sin contenido</em>
            </div>
          </div>
          <div class="preview-divider"></div>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <span class="preview-label">Estado:</span>
              <span class="badge" id="previewEstadoEdit">Activo</span>
            </div>
            <div>
              <span class="preview-label">Vigencia:</span>
              <span id="previewVigenciaEdit" class="text-muted">Sin fecha límite</span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">
          <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="text-center py-3">
          <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
          <h5>¿Estás seguro de eliminar este comunicado?</h5>
          <p class="text-muted mb-0">Esta acción no se puede deshacer. El comunicado será eliminado permanentemente.</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times"></i> Cancelar
        </button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          <i class="fas fa-trash"></i> Sí, Eliminar
        </button>
      </div>
    </div>
  </div>
</div>

<div class="page-inner">
  <div class="page-header">
    <h3 class="fw-bold mb-3">Editar Comunicado</h3>
    <ul class="breadcrumbs mb-3">
      <li class="nav-home"><a href="index.php?controller=Dashboard&action=index"><i class="icon-home"></i></a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="index.php?controller=Comunicado&action=index">Comunicados</a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="#">Editar</a></li>
    </ul>
  </div>

  <?php if($exito): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>¡Éxito!</strong> <?= htmlspecialchars($exito); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <?php if($error): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>¡Error!</strong> <?= htmlspecialchars($error); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <div class="row">
    <div class="col-md-12">
      <div class="card card-form-incidencia">
        <div class="card-header card-header-dark">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="card-title mb-0">
              <i class="fas fa-edit me-2"></i>
              Editar Comunicado
            </div>
            <div class="header-buttons">
              <button type="button" class="btn btn-preview me-2" data-bs-toggle="modal" data-bs-target="#previewModal">
                <i class="fas fa-eye"></i> Vista Previa
              </button>
              <a href="index.php?controller=Comunicado&action=index" class="btn btn-volver">
                <i class="fas fa-arrow-left"></i> Volver
              </a>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="alert alert-warning" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Editando comunicado:</strong> Los cambios se aplicarán inmediatamente después de guardar.
          </div>

          <form action="index.php?controller=Comunicado&action=actualizar" method="POST" id="formEditarComunicado">
            <input type="hidden" name="id" value="<?= $comunicado['id'] ?>">
            
            <!-- TÍTULO -->
            <div class="separator-dashed"></div>

            <div class="row">
              <div class="col-md-12">
                <h5 class="section-label">
                  Título del Comunicado
                  <span class="campo-obligatorio">*</span>
                </h5>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="titulo">
                    Título claro y descriptivo
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="right" 
                       title="El título debe ser breve pero informativo."></i>
                  </label>
                  <input type="text" class="form-control" id="titulo" name="titulo" 
                         value="<?= htmlspecialchars($comunicado['titulo']) ?>"
                         placeholder="Título del comunicado" required minlength="5" maxlength="200">
                  <small class="form-text text-muted">Mínimo 5 caracteres, máximo 200</small>
                </div>
              </div>
            </div>

            <!-- CLASIFICACIÓN -->
            <div class="separator-dashed"></div>

            <div class="row">
              <div class="col-md-12">
                <h5 class="section-label">
                  Clasificación y Estado
                  <span class="campo-obligatorio">*</span>
                </h5>
              </div>
            </div>

            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="tipo">
                    Tipo
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       title="Categoría del comunicado"></i>
                  </label>
                  <select class="form-select form-control" id="tipo" name="tipo" required>
                    <option value="Aviso" <?= $comunicado['tipo'] === 'Aviso' ? 'selected' : '' ?>>📢 Aviso</option>
                    <option value="Suspensión" <?= $comunicado['tipo'] === 'Suspensión' ? 'selected' : '' ?>>🚫 Suspensión</option>
                    <option value="Cambio de horario" <?= $comunicado['tipo'] === 'Cambio de horario' ? 'selected' : '' ?>>🕐 Cambio de horario</option>
                    <option value="Mantenimiento" <?= $comunicado['tipo'] === 'Mantenimiento' ? 'selected' : '' ?>>🔧 Mantenimiento</option>
                    <option value="Otro" <?= $comunicado['tipo'] === 'Otro' ? 'selected' : '' ?>>📝 Otro</option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="prioridad">
                    Prioridad
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       title="Nivel de urgencia del comunicado"></i>
                  </label>
                  <select class="form-select form-control" id="prioridad" name="prioridad" required>
                    <option value="Normal" <?= $comunicado['prioridad'] === 'Normal' ? 'selected' : '' ?>>🔵 Normal</option>
                    <option value="Importante" <?= $comunicado['prioridad'] === 'Importante' ? 'selected' : '' ?>>🟠 Importante</option>
                    <option value="Urgente" <?= $comunicado['prioridad'] === 'Urgente' ? 'selected' : '' ?>>🔴 Urgente</option>
                  </select>
                  <small class="form-text text-muted">Define la urgencia</small>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="estado">
                    Estado
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       title="Si está inactivo, no será visible para los usuarios"></i>
                  </label>
                  <select class="form-select form-control" id="estado" name="estado" required>
                    <option value="Activo" <?= $comunicado['estado'] === 'Activo' ? 'selected' : '' ?>>✅ Activo</option>
                    <option value="Inactivo" <?= $comunicado['estado'] === 'Inactivo' ? 'selected' : '' ?>>⭕ Inactivo</option>
                  </select>
                  <small class="form-text text-muted">Visibilidad del comunicado</small>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="fecha_vigencia">
                    Fecha de Vigencia
                    <span class="badge badge-secondary">Opcional</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       title="Se ocultará automáticamente después de esta fecha"></i>
                  </label>
                  <input type="date" class="form-control" id="fecha_vigencia" name="fecha_vigencia" 
                         value="<?= $comunicado['fecha_vigencia'] ?? '' ?>"
                         min="<?= date('Y-m-d') ?>">
                  <small class="form-text text-muted">Dejar vacío si no caduca</small>
                </div>
              </div>
            </div>

            <!-- CONTENIDO -->
            <div class="separator-dashed"></div>

            <div class="row">
              <div class="col-md-12">
                <h5 class="section-label">
                  Contenido del Comunicado
                  <span class="campo-obligatorio">*</span>
                </h5>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="contenido">
                    Mensaje completo
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       title="Redacta el comunicado de forma clara y profesional"></i>
                  </label>
                  <textarea class="form-control textarea-descripcion" id="contenido" name="contenido" 
                            rows="8" required minlength="20"><?= htmlspecialchars($comunicado['contenido']) ?></textarea>
                  
                  <!-- Contador de caracteres -->
                  <div class="report-quality-container mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <small class="text-muted">Longitud del contenido:</small>
                      <small class="text-muted">
                        <span id="charCountEdit" class="fw-bold">0</span> caracteres
                      </small>
                    </div>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar bg-info" id="qualityBarEdit" role="progressbar" 
                           style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="quality-message text-muted mt-1 d-block" id="qualityMessageEdit">
                      Mínimo 20 caracteres
                    </small>
                  </div>
                </div>
              </div>
            </div>

            <div class="separator-dashed"></div>

            <!-- Información adicional -->
            <div class="row mb-3">
              <div class="col-md-12">
                <div class="info-section">
                  <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Información de Registro</h6>
                  <div class="row">
                    <div class="col-md-6">
                      <small class="text-muted">
                        <strong>Fecha de publicación:</strong> 
                        <?= date('d/m/Y H:i', strtotime($comunicado['fecha_publicacion'])) ?>
                      </small>
                    </div>
                    <div class="col-md-6">
                      <small class="text-muted">
                        <strong>Última actualización:</strong> 
                        <?= isset($comunicado['fecha_actualizacion']) 
                            ? date('d/m/Y H:i', strtotime($comunicado['fecha_actualizacion'])) 
                            : 'Sin actualizaciones' ?>
                      </small>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Botones de acción -->
            <div class="card-action d-flex justify-content-between flex-wrap gap-2">
              <div class="action-buttons-left">
                <a href="index.php?controller=Comunicado&action=index" class="btn btn-secondary btn-lg">
                  <i class="fas fa-times"></i> Cancelar
                </a>
              </div>
              <div class="action-buttons-right d-flex gap-2">
                <button type="button" class="btn btn-danger btn-lg" data-bs-toggle="modal" data-bs-target="#deleteModal">
                  <i class="fas fa-trash"></i> Eliminar
                </button>
                <button type="submit" class="btn btn-success btn-lg">
                  <i class="fas fa-save"></i> Guardar Cambios
                </button>
              </div>
            </div>
          </form>

        </div>
      </div>

      <!-- Tarjeta informativa -->
      <div class="card bg-light mt-3">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-lightbulb text-warning"></i> Sobre la edición</h5>
          <ul class="mb-0">
            <li><strong>Los cambios son inmediatos:</strong> Al guardar, el comunicado se actualizará al instante</li>
            <li><strong>Puedes cambiar el estado:</strong> Si lo marcas como "Inactivo", dejará de ser visible</li>
            <li><strong>Revisa antes de guardar:</strong> Usa la vista previa para verificar cómo se verá</li>
            <li><strong>Eliminar es permanente:</strong> Si eliminas el comunicado, no podrás recuperarlo</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

</div>

<script>
  // Variable global para el ID del comunicado
  const comunicadoId = <?= $comunicado['id'] ?>;
  
  // Configurar el botón de eliminar en el modal
  document.getElementById('confirmDelete').addEventListener('click', function() {
    window.location.href = 'index.php?controller=Comunicado&action=eliminar&id=' + comunicadoId;
  });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>