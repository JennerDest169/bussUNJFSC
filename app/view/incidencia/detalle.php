<!--detalle.php - incidencia-->
<?php 
$title = "Incidencias | Detalle";
include __DIR__ . '/../layout/header.php'; 
?>
          <div class="page-inner">
            <div class="page-header">
              <h3 class="fw-bold mb-3">Detalle de Incidencia #<?php echo $incidencia['id']; ?></h3>
              <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                  <a href="index.php?controller=Usuario&action=index"><i class="icon-home"></i></a>
                </li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="index.php?controller=Incidencia&action=index">Incidencias</a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item"><a href="#">Detalle</a></li>
              </ul>
            </div>

            <?php if($exito): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>¡Éxito!</strong> <?php echo htmlspecialchars($exito); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <?php if($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>¡Error!</strong> <?php echo htmlspecialchars($error); ?>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>

            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                      <h4 class="card-title mb-0">Información de la Incidencia</h4>
                      <a href="index.php?controller=Incidencia&action=index" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                      </a>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <p><strong>ID:</strong> <?php echo $incidencia['id']; ?></p>
                        <?php if($usuario['rol'] === 'Administrador'): ?>
                        <p><strong>Reportado por:</strong> <?php echo htmlspecialchars($incidencia['nombre_usuario'] ?? 'Anónimo'); ?></p>
                        <p><strong>Correo:</strong> <?php echo htmlspecialchars($incidencia['correo'] ?? 'N/A'); ?></p>
                        <?php endif; ?>
                      </div>
                      <div class="col-md-6">
                        <p><strong>Tipo:</strong> <span class="badge bg-info"><?php echo htmlspecialchars($incidencia['tipo']); ?></span></p>
                        <p><strong>Estado:</strong> 
                          <?php
                          $badge_class = [
                              'Pendiente' => 'badge-warning',
                              'En proceso' => 'badge-primary',
                              'Resuelto' => 'badge-success'
                          ];
                          ?>
                          <span class="badge <?php echo $badge_class[$incidencia['estado']]; ?>">
                            <?php echo htmlspecialchars($incidencia['estado']); ?>
                          </span>
                        </p>
                        <p><strong>Fecha de Reporte:</strong> <?php echo date('d/m/Y H:i:s', strtotime($incidencia['fecha_reporte'])); ?></p>
                      </div>
                    </div>

                    <hr>

                    <div class="row">
                      <div class="col-md-12">
                        <h5>Descripción:</h5>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($incidencia['descripcion'])); ?></p>
                      </div>
                    </div>

                    <?php if(!empty($incidencia['respuesta'])): ?>
                    <hr>
                    <div class="row">
                      <div class="col-md-12">
                        <h5>Respuesta del Administrador</h5>
                        <div class="alert alert-info">
                          <p class="mb-2"><?php echo nl2br(htmlspecialchars($incidencia['respuesta'])); ?></p>
                          <small class="text-muted">
                            <strong>Fecha de respuesta:</strong> 
                            <?php echo date('d/m/Y H:i:s', strtotime($incidencia['fecha_respuesta'])); ?>
                          </small>
                        </div>
                      </div>
                    </div>
                    <?php endif; ?>

                    <?php if($usuario['rol'] === 'Administrador'): ?>
                    <hr>
                    <div class="row">
                      <div class="col-md-12">
                        <h5>Responder Incidencia</h5>
                        <form action="index.php?controller=Incidencia&action=responder" method="POST">
                          <input type="hidden" name="id" value="<?php echo $incidencia['id']; ?>">
                          
                          <div class="form-group">
                            <label for="respuesta">Respuesta</label>
                            <textarea class="form-control" id="respuesta" name="respuesta" rows="4" required 
                                      placeholder="Escribe tu respuesta aquí..."><?php echo htmlspecialchars($incidencia['respuesta'] ?? ''); ?></textarea>
                          </div>

                          <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-select form-control" id="estado" name="estado" required>
                              <option value="Pendiente" <?php echo $incidencia['estado'] === 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                              <option value="En proceso" <?php echo $incidencia['estado'] === 'En proceso' ? 'selected' : ''; ?>>En proceso</option>
                              <option value="Resuelto" <?php echo $incidencia['estado'] === 'Resuelto' ? 'selected' : ''; ?>>Resuelto</option>
                            </select>
                          </div>

                          <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                              <i class="fas fa-paper-plane"></i> Enviar Respuesta
                            </button>
                            <a href="index.php?controller=Incidencia&action=index" class="btn btn-secondary">Cancelar</a>
                          </div>
                        </form>
                      </div>
                    </div>
                    <?php endif; ?>

                  </div>
                </div>
              </div>
            </div>

          </div>
        <?php include __DIR__ . '/../layout/footer.php'; ?>