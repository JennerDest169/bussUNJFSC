<!--crear.php - incidencia-->
<?php 
$title = "Incidencias | Nuevo";
include __DIR__ . '/../layout/header.php'; 
?>
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Reportar Nueva Incidencia</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php?controller=Usuario&action=index"><i class="icon-home"></i></a></li>
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
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Formulario de Reporte de Incidencia
                      </div>
                      <a href="index.php?controller=Incidencia&action=index" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                      </a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="alert alert-info" role="alert">
                      <i class="fas fa-info-circle me-2"></i>
                      <strong>Importante:</strong> Describe detalladamente el problema para que podamos ayudarte mejor. 
                      La descripción debe tener al menos 10 caracteres.
                    </div>

                    <form action="index.php?controller=Incidencia&action=crear" method="POST" id="formIncidencia">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="tipo">Tipo de Incidencia *</label>
                            <select class="form-select form-control" id="tipo" name="tipo" required>
                              <option value="">-- Selecciona una opción --</option>
                              <option value="Falla mecánica">🔧 Falla mecánica</option>
                              <option value="Retraso">⏰ Retraso</option>
                              <option value="Sugerencia">💡 Sugerencia</option>
                              <option value="Otro">📝 Otro</option>
                            </select>
                            <small class="form-text text-muted">Selecciona el tipo que mejor describa tu situación</small>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="descripcion">Descripción Detallada *</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" 
                                      rows="6" required 
                                      placeholder="Describe detalladamente la incidencia. Incluye: ¿Qué pasó? ¿Cuándo ocurrió? ¿En qué ruta o bus? Cualquier detalle adicional que consideres importante..."
                                      minlength="10" maxlength="1000"></textarea>
                            <div class="d-flex justify-content-between">
                              <small class="form-text text-muted">Mínimo 10 caracteres</small>
                              <small class="form-text text-muted">
                                <span id="charCount">0</span>/1000 caracteres
                              </small>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="separator-dashed"></div>

                      <div class="card-action">
                        <button type="submit" class="btn btn-success btn-lg">
                          <i class="fas fa-paper-plane"></i> Enviar Reporte
                        </button>
                        <button type="reset" class="btn btn-danger btn-lg">
                          <i class="fas fa-eraser"></i> Limpiar Formulario
                        </button>
                        <a href="index.php?controller=Incidencia&action=index" class="btn btn-secondary btn-lg">
                          <i class="fas fa-times"></i> Cancelar
                        </a>
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