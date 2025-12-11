<!--crear.php - comunicado-->
<?php 
$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
$title = "Comunicados | Nuevo";
include __DIR__ . '/../layout/header.php'; 
?>

<!-- Modal de Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="previewModalLabel">
          <i class="fas fa-eye me-2"></i>Vista Previa del Comunicado
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="preview-card">
          <div class="preview-header">
            <div class="d-flex justify-content-between align-items-start w-100">
              <div>
                <span class="preview-label">Tipo:</span>
                <span class="badge bg-info" id="previewTipoCom">No seleccionado</span>
              </div>
              <div>
                <span class="preview-label">Prioridad:</span>
                <span class="badge" id="previewPrioridad" style="background-color: #9C27B0;">No seleccionada</span>
              </div>
            </div>
          </div>
          <div class="preview-divider"></div>
          <div class="mb-3">
            <span class="preview-label">Título:</span>
            <h4 id="previewTitulo" class="mt-2"><em class="text-muted">Sin título</em></h4>
          </div>
          <div class="preview-divider"></div>
          <div class="preview-content">
            <span class="preview-label">Contenido:</span>
            <div class="preview-text" id="previewContenido">
              <em class="text-muted">Aún no has escrito nada...</em>
            </div>
          </div>
          <div class="preview-divider"></div>
          <div class="preview-footer">
            <small class="text-muted">
              <i class="fas fa-calendar me-1"></i>Fecha de publicación: <strong><?= date('d/m/Y H:i') ?></strong>
            </small>
            <small class="text-muted">
              <i class="fas fa-calendar-check me-1"></i>Vigencia: <strong id="previewVigencia">Sin fecha límite</strong>
            </small>
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

<div class="page-inner">
  <div class="page-header">
    <h3 class="fw-bold mb-3">Crear Nuevo Comunicado</h3>
    <ul class="breadcrumbs mb-3">
      <li class="nav-home"><a href="index.php?controller=Dashboard&action=index"><i class="icon-home"></i></a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="index.php?controller=Comunicado&action=index">Comunicados</a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="#">Crear</a></li>
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
              <i class="fas fa-bullhorn me-2"></i>
              Formulario de Comunicado
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
          <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Importante:</strong> Los comunicados serán visibles para todos los usuarios según su estado y vigencia. Asegúrate de redactar con claridad.
          </div>

          <form action="index.php?controller=Comunicado&action=guardar" method="POST" id="formComunicado">
            
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
                    Escribe un título claro y descriptivo
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="right" 
                       title="El título debe ser breve pero informativo. Será lo primero que vean los usuarios."></i>
                  </label>
                  <input type="text" class="form-control" id="titulo" name="titulo" 
                         placeholder="Ej: Suspensión de servicio por mantenimiento" 
                         required minlength="5" maxlength="200">
                  <small class="form-text text-muted">Mínimo 5 caracteres, máximo 200</small>
                </div>
              </div>
            </div>

            <!-- TIPO, PRIORIDAD Y FECHA -->
            <div class="separator-dashed"></div>

            <div class="row">
              <div class="col-md-12">
                <h5 class="section-label">
                  Clasificación del Comunicado
                  <span class="campo-obligatorio">*</span>
                </h5>
              </div>
            </div>

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="tipo">
                    Tipo de Comunicado
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="right" 
                       title="Selecciona la categoría que mejor describa el comunicado."></i>
                  </label>
                  <select class="form-select form-control" id="tipo" name="tipo" required>
                    <option value="">-- Selecciona --</option>
                    <option value="Aviso">📢 Aviso</option>
                    <option value="Suspensión">🚫 Suspensión</option>
                    <option value="Cambio de horario">🕐 Cambio de horario</option>
                    <option value="Mantenimiento">🔧 Mantenimiento</option>
                    <option value="Otro">📝 Otro</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="prioridad">
                    Nivel de Prioridad
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="right" 
                       title="Define qué tan urgente es este comunicado. Urgente aparecerá destacado."></i>
                  </label>
                  <select class="form-select form-control" id="prioridad" name="prioridad" required>
                    <option value="">-- Selecciona --</option>
                    <option value="Normal">🔵 Normal</option>
                    <option value="Importante">🟠 Importante</option>
                    <option value="Urgente">🔴 Urgente</option>
                  </select>
                  <small class="form-text text-muted">Define la importancia del comunicado</small>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label for="fecha_vigencia">
                    Fecha de Vigencia
                    <span class="badge badge-secondary">Opcional</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="right" 
                       title="Si defines una fecha, el comunicado se ocultará automáticamente después de esa fecha."></i>
                  </label>
                  <input type="date" class="form-control" id="fecha_vigencia" name="fecha_vigencia" 
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
                    Escribe el mensaje completo
                    <span class="campo-obligatorio">*</span>
                    <i class="fas fa-question-circle ms-1 text-muted" 
                       data-bs-toggle="tooltip" 
                       data-bs-placement="right" 
                       title="Redacta el comunicado de forma clara y profesional. Incluye todos los detalles necesarios."></i>
                  </label>
                  <textarea class="form-control textarea-descripcion" id="contenido" name="contenido" 
                            rows="8" required minlength="20"
                            placeholder="Escribe aquí el contenido completo del comunicado. Sé claro y específico con la información que deseas comunicar..."></textarea>
                  
                  <!-- Contador de caracteres -->
                  <div class="report-quality-container mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <small class="text-muted">Longitud del contenido:</small>
                      <small class="text-muted">
                        <span id="charCountCom" class="fw-bold">0</span> caracteres
                      </small>
                    </div>
                    <div class="progress" style="height: 8px;">
                      <div class="progress-bar bg-info" id="qualityBarCom" role="progressbar" 
                           style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="quality-message text-muted mt-1 d-block" id="qualityMessageCom">
                      Escribe al menos 20 caracteres
                    </small>
                  </div>
                </div>
              </div>
            </div>

            <div class="separator-dashed"></div>

            <!-- Botones de acción -->
            <div class="card-action d-flex justify-content-between flex-wrap gap-2">
              <div class="action-buttons-left">
                <button type="button" class="btn btn-secondary btn-lg" id="btnLimpiarCom">
                  <i class="fas fa-eraser"></i> Limpiar
                </button>
                <a href="index.php?controller=Comunicado&action=index" class="btn btn-outline-secondary btn-lg">
                  <i class="fas fa-times"></i> Cancelar
                </a>
              </div>
              <div class="action-buttons-right">
                <button type="submit" class="btn btn-success btn-lg" id="btnPublicar">
                  <i class="fas fa-paper-plane"></i> Publicar Comunicado
                </button>
              </div>
            </div>
          </form>

        </div>
      </div>

      <!-- Tarjeta informativa -->
      <div class="card bg-light mt-3">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-lightbulb text-warning"></i> Consejos para un buen comunicado</h5>
          <ul class="mb-0">
            <li><strong>Sé claro y conciso:</strong> Usa un lenguaje simple y directo</li>
            <li><strong>Incluye fechas específicas:</strong> Si aplica, menciona cuándo inicia y termina</li>
            <li><strong>Proporciona alternativas:</strong> Si hay cambios, indica opciones disponibles</li>
            <li><strong>Revisa antes de publicar:</strong> Usa la vista previa para verificar cómo se verá</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>