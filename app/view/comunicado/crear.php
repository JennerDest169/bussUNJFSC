<?php 
$title = "Comunicados | Nuevo";
include __DIR__ . '/../layout/header.php'; 
?>
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Crear Nuevo Comunicado</h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home"><a href="index.php?controller=Usuario&action=index"><i class="icon-home"></i></a></li>
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
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="card-title mb-0">
                        <i class="fas fa-bullhorn text-danger me-2"></i>
                        Formulario de Comunicado
                      </div>
                      <a href="index.php?controller=Comunicado&action=index" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                      </a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="alert alert-info" role="alert">
                      <i class="fas fa-info-circle me-2"></i>
                      <strong>Importante:</strong> Los comunicados serán visibles para todos los usuarios según su estado y vigencia.
                    </div>

                    <form action="index.php?controller=Comunicado&action=guardar" method="POST" id="formComunicado">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="titulo">Título del Comunicado *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" 
                                   placeholder="Título del comunicado" required minlength="5" maxlength="200">
                            <small class="form-text text-muted">Mínimo 5 caracteres, máximo 200</small>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="tipo">Tipo de Comunicado *</label>
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
                            <label for="prioridad">Prioridad *</label>
                            <select class="form-select form-control" id="prioridad" name="prioridad" required>
                              <option value="Normal">Normal</option>
                              <option value="Importante">Importante</option>
                              <option value="Urgente">Urgente</option>
                            </select>
                            <small class="form-text text-muted">Define la importancia del comunicado</small>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="fecha_vigencia">Fecha de Vigencia (Opcional)</label>
                            <input type="date" class="form-control" id="fecha_vigencia" name="fecha_vigencia" 
                                   min="<?= date('Y-m-d') ?>">
                            <small class="form-text text-muted">Dejar vacío si no caduca</small>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="contenido">Contenido del Comunicado *</label>
                            <textarea class="form-control" id="contenido" name="contenido" rows="8" 
                                      placeholder="Escribe el contenido del comunicado aquí..." required></textarea>
                            <div class="d-flex justify-content-between">
                              <small class="form-text text-muted">Describe detalladamente el comunicado</small>
                              <small class="form-text text-muted">
                                <span id="charCountCom">0</span> caracteres
                              </small>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="separator-dashed"></div>

                      <div class="card-action">
                        <button type="submit" class="btn btn-success btn-lg">
                          <i class="fas fa-check"></i> Publicar Comunicado
                        </button>
                        <button type="reset" class="btn btn-danger btn-lg">
                          <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <a href="index.php?controller=Comunicado&action=index" class="btn btn-secondary btn-lg">
                          <i class="fas fa-times"></i> Cancelar
                        </a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          </div>

<script>
  $(document).ready(function() {
    $('#contenido').on('input', function() {
      const length = $(this).val().length;
      $('#charCountCom').text(length);
    });
  });
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>