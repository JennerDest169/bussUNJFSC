<?php 

$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
$title = "Incidencias | Nuevo";
include __DIR__ . '/../layout/header.php'; 
?>

<!-- Modal de Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="previewModalLabel">
          <i class="fas fa-eye me-2"></i>Vista Previa del Reporte
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="preview-card">
          <div class="preview-header">
            <span class="preview-label">Tipo de Incidencia:</span>
            <span class="badge bg-info" id="previewTipo">No seleccionado</span>
          </div>
          <div class="preview-divider"></div>
          <div class="preview-content">
            <span class="preview-label">Descripción:</span>
            <div class="preview-text" id="previewDescripcion">
              <em class="text-muted">Aún no has escrito nada...</em>
            </div>
          </div>
          <?php if($usuario): ?>
          <div class="preview-footer">
            <small class="text-muted">
              <i class="fas fa-user me-1"></i>Reportado por: <strong><?= htmlspecialchars($usuario['nombre']) ?></strong>
            </small>
            <small class="text-muted ms-3">
              <i class="far fa-calendar me-1"></i><?= date('d/m/Y H:i') ?>
            </small>
          </div>
          <?php endif; ?>
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

          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Reportar Nueva Incidencia</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php?controller=Dashboard&action=index"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?controller=Incidencia&action=index">Incidencias</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Reportar</a></li>
              </ul>
            </div>

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
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Formulario de Reporte de Incidencia
                      </div>
                      <div class="header-buttons">
                        <button type="button" class="btn btn-preview me-2" data-bs-toggle="modal" data-bs-target="#previewModal">
                          <i class="fas fa-eye"></i> Vista Previa
                        </button>
                        <a href="index.php?controller=Incidencia&action=index" class="btn btn-volver">
                          <i class="fas fa-arrow-left"></i> Volver
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="alert alert-info" role="alert">
                      <i class="fas fa-info-circle me-2"></i>
                      <strong>Importante:</strong> Describe detalladamente el problema para que podamos ayudarte mejor. 
                      Tu progreso se guarda automáticamente.
                    </div>

                    <form action="index.php?controller=Incidencia&action=crear" method="POST" id="formIncidencia" enctype="multipart/form-data">
                      
                      <!-- Separador antes de Tipo de Incidencia -->
                      <div class="separator-dashed"></div>

                      <div class="row">
                        <div class="col-md-12">
                          <h5 class="section-label">
                            Tipo de Incidencia
                            <span class="campo-obligatorio">*</span>
                          </h5>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group position-relative">
                            <label for="tipo">
                              Selecciona el tipo 
                              <span class="campo-obligatorio">*</span>
                              <i class="fas fa-question-circle ms-1 text-muted" 
                                 data-bs-toggle="tooltip" 
                                 data-bs-placement="right" 
                                 title="Selecciona la categoría que mejor describa tu problema. Esto ayuda al equipo a priorizar y atender tu reporte correctamente."></i>
                            </label>
                            <select class="form-select form-control" id="tipo" name="tipo" required>
                              <option value="">-- Selecciona una opción --</option>
                              <option value="Falla mecánica" data-icon="fas fa-wrench">🔧 Falla mecánica</option>
                              <option value="Retraso" data-icon="fas fa-clock">⏱️ Retraso</option>
                              <option value="Sugerencia" data-icon="fas fa-lightbulb">🗣️ Sugerencia</option>
                              <option value="Otro" data-icon="fas fa-edit">📝 Otro</option>
                            </select>
                            
                            <small class="form-text text-muted">Selecciona el tipo que mejor describa tu situación</small>
                          </div>
                        </div>
                      </div>

                      <!-- Separador antes de Descripción Detallada -->
                      <div class="separator-dashed"></div>

                      <div class="row">
                        <div class="col-md-12">
                          <h5 class="section-label">
                            Descripción Detallada
                            <span class="campo-obligatorio">*</span>
                          </h5>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="descripcion">
                              Escribe tu reporte 
                              <span class="campo-obligatorio">*</span>
                              <i class="fas fa-question-circle ms-1 text-muted" 
                                 data-bs-toggle="tooltip" 
                                 data-bs-placement="right" 
                                 title="Describe detalladamente: ¿Qué pasó? ¿Cuándo ocurrió? ¿En qué ruta o bus? Cuanto más detallado sea tu reporte, más rápido podremos ayudarte."></i>
                            </label>
                            <textarea class="form-control textarea-descripcion" id="descripcion" name="descripcion" 
                                      rows="6" required 
                                      placeholder="Describe detalladamente la incidencia. Incluye: ¿Qué pasó? ¿Cuándo ocurrió? ¿En qué ruta o bus? Cualquier detalle adicional que consideres importante..."
                                      minlength="10" maxlength="1000"></textarea>
                            
                            <!-- Barra de calidad del reporte -->
                            <div class="report-quality-container mt-2">
                              <div class="d-flex justify-content-between align-items-center mb-1">
                                <small class="text-muted">Calidad del reporte:</small>
                                <small class="text-muted">
                                  <span id="charCount" class="fw-bold">0</span>/1000 caracteres
                                </small>
                              </div>
                              <div class="progress" style="height: 8px;">
                                <div class="progress-bar" id="qualityBar" role="progressbar" 
                                     style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              <small class="quality-message text-muted mt-1 d-block" id="qualityMessage">
                                Escribe al menos 10 caracteres para enviar el reporte
                              </small>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Separador antes de Adjuntar Imágenes -->
                      <div class="separator-dashed"></div>

                      <div class="row">
                        <div class="col-md-12">
                          <h5 class="section-label">
                            Adjuntar Imágenes 
                            <span class="badge badge-secondary">Opcional</span>
                          </h5>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="imagenes">
                              Sube imágenes relacionadas 
                              <i class="fas fa-question-circle ms-1 text-muted" 
                                 data-bs-toggle="tooltip" 
                                 data-bs-placement="right" 
                                 title="Puedes subir hasta 3 imágenes (máximo 5MB cada una). Formatos permitidos: JPG, PNG, GIF"></i>
                            </label>
                            <input type="file" class="form-control" id="imagenes" name="imagenes[]" 
                                   accept="image/jpeg,image/png,image/gif" multiple max="3">
                            <small class="form-text text-muted">
                              <i class="fas fa-info-circle"></i> Máximo 3 imágenes (JPG, PNG, GIF - 5MB c/u)
                            </small>
                            <div id="imagePreviewContainer" class="mt-3 d-flex flex-wrap gap-2"></div>
                          </div>
                        </div>
                      </div>

                      <div class="separator-dashed"></div>

                      <!-- Botones de acción -->
                      <div class="card-action d-flex justify-content-between flex-wrap gap-2">
                        <div class="action-buttons-left">
                          <button type="button" class="btn btn-secondary btn-lg" id="btnLimpiar">
                            <i class="fas fa-eraser"></i> Limpiar
                          </button>
                          <a href="index.php?controller=Incidencia&action=index" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-times"></i> Cancelar
                          </a>
                        </div>
                        <div class="action-buttons-right">
                          <button type="submit" class="btn btn-success btn-lg" id="btnEnviar">
                            <i class="fas fa-paper-plane"></i> Enviar Reporte
                          </button>
                        </div>
                      </div>
                    </form>
                    
                  </div>
                </div>

                <!-- Tarjeta informativa -->
                <div class="card bg-light mt-3">
                  <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-question-circle text-primary"></i> ¿Qué sucede después de reportar?</h5>
                    <ul class="mb-0">
                      <li>Tu reporte será revisado por el equipo administrativo</li>
                      <li>Recibirás actualizaciones sobre el estado de tu incidencia</li>
                      <li>Puedes ver el progreso desde la sección "Mis Incidencias"</li>
                      <li>El equipo puede contactarte para más información si es necesario</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

          </div>



<?php include __DIR__ . '/../layout/footer.php'; ?>