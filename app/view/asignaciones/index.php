<?php
// app/view/asignaciones/index.php
$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
$title = "Asignaciones - Sistema de Transporte UNJFSC";
include __DIR__ . '/../layout/header.php'; 
?>
<body>
  <div class="container-fluid">
    <div class="page-inner">
      <div class="page-header">
        <h3 class="fw-bold mb-3">Asignaciones</h3>
        <ul class="breadcrumbs mb-3">
          <li class="nav-home">
            <a href="#">
              <i class="icon-home"></i>
            </a>
          </li>
          <li class="separator">
            <i class="icon-arrow-right"></i>
          </li>
          <li class="nav-item">
            <a href="#">Asignaciones</a>
          </li>
        </ul>
      </div>
      
      <!-- Mensajes de éxito/error -->
      <?php if ($exito): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($exito) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      
      <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($error) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4 class="card-title mb-0">Lista de Asignaciones</h4>
              <button type="button" 
                      class="btn btn-primary btn-sm" 
                      data-bs-toggle="modal" 
                      data-bs-target="#modalCrear">
                Nueva Asignación
              </button>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Bus</th>
                      <th>Conductor</th>
                      <th>Ruta</th>
                      <th>Estado</th>
                      <th>Observación</th>
                      <th>Fecha Asignación</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Bus</th>
                      <th>Conductor</th>
                      <th>Ruta</th>
                      <th>Estado</th>
                      <th>Observación</th>
                      <th>Fecha Asignación</th>
                      <th>Acciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php if (!empty($asignaciones)): ?>
                      <?php foreach ($asignaciones as $asignacion): ?>
                        <?php 
                        // Buscar información del bus
                        $bus_info = 'ID: ' . $asignacion['bus_id'];
                        foreach ($buses as $bus) {
                          if ($bus['id'] == $asignacion['bus_id']) {
                            $bus_info = $bus['placa'] . ' - ' . $bus['modelo'];
                            break;
                          }
                        }
                        
                        // Buscar información del conductor
                        $conductor_info = 'ID: ' . $asignacion['conductor_id'];
                        foreach ($conductores as $conductor) {
                          if ($conductor['id'] == $asignacion['conductor_id']) {
                            $conductor_info = $conductor['nombre'];
                            break;
                          }
                        }
                        
                        // Buscar información de la ruta
                        $ruta_info = 'ID: ' . $asignacion['ruta_id'];
                        foreach ($rutas as $ruta) {
                          if ($ruta['id'] == $asignacion['ruta_id']) {
                            $ruta_info = $ruta['nombre'] . ' (' . $ruta['origen'] . ' - ' . $ruta['destino'] . ')';
                            break;
                          }
                        }
                        ?>
                        <tr>
                          <td><?= $asignacion['id'] ?></td>
                          <td><?= htmlspecialchars($bus_info) ?></td>
                          <td><?= htmlspecialchars($conductor_info) ?></td>
                          <td><?= htmlspecialchars($ruta_info) ?></td>
                          <td>
                            <?php 
                            $estado = $asignacion['estado'] ?? 'Desconocido';
                            $color = 'secondary';
                            if ($estado == 'Completado') $color = 'success';
                            if ($estado == 'En ruta') $color = 'warning';
                            ?>
                            <span class="badge bg-<?= $color ?>">
                              <?= $estado ?>
                            </span>
                          </td>
                          <td><?= htmlspecialchars($asignacion['observacion'] ?? 'Sin observación') ?></td>
                          <td><?= $asignacion['fecha_asignacion'] ?? '' ?></td>
                          <td>
                            <div class="btn-group" role="group">
                              <button type="button" 
                                      class="btn btn-warning btn-sm btn-editar" 
                                      data-bs-toggle="modal" 
                                      data-bs-target="#modalEditar"
                                      data-id="<?= $asignacion['id'] ?>"
                                      data-bus_id="<?= $asignacion['bus_id'] ?>"
                                      data-conductor_id="<?= $asignacion['conductor_id'] ?>"
                                      data-ruta_id="<?= $asignacion['ruta_id'] ?>"
                                      data-estado="<?= $asignacion['estado'] ?>"
                                      data-observacion="<?= htmlspecialchars($asignacion['observacion'] ?? '') ?>"
                                      data-fecha_asignacion="<?= $asignacion['fecha_asignacion'] ?>">
                                Editar
                              </button>
                              <?php if ($asignacion['estado'] == 'En ruta'): ?>
                                <button type="button" 
                                        class="btn btn-danger btn-sm btn-eliminar" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEliminar"
                                        data-id="<?= $asignacion['id'] ?>"
                                        data-bus_info="<?= htmlspecialchars($bus_info) ?>"
                                        data-conductor_info="<?= htmlspecialchars($conductor_info) ?>"
                                        data-ruta_info="<?= htmlspecialchars($ruta_info) ?>">
                                  Finalizar
                                </button>
                              <?php endif; ?>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center">No hay asignaciones registradas</td>
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
  </div>

  <!-- Modal para Crear Asignación -->
  <div class="modal fade" id="modalCrear" tabindex="-1" role="dialog" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearLabel">Nueva Asignación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="index.php?controller=Asignaciones&action=crear">
          <div class="modal-body">
            <div class="mb-3">
              <label for="bus_id" class="form-label">Bus *</label>
              <select class="form-control" id="bus_id" name="bus_id" required>
                <option value="">Seleccionar bus</option>
                <?php if (!empty($buses)): ?>
                  <?php foreach($buses as $bus): ?>
                    <option value="<?= $bus['id'] ?>">
                      <?= htmlspecialchars($bus['placa']) ?> - <?= htmlspecialchars($bus['modelo']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No hay buses disponibles</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="conductor_id" class="form-label">Conductor *</label>
              <select class="form-control" id="conductor_id" name="conductor_id" required>
                <option value="">Seleccionar conductor</option>
                <?php if (!empty($conductores)): ?>
                  <?php foreach($conductores as $conductor): ?>
                    <option value="<?= $conductor['id'] ?>">
                      <?= htmlspecialchars($conductor['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No hay conductores disponibles</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="ruta_id" class="form-label">Ruta *</label>
              <select class="form-control" id="ruta_id" name="ruta_id" required>
                <option value="">Seleccionar ruta</option>
                <?php if (!empty($rutas)): ?>
                  <?php foreach($rutas as $ruta): ?>
                    <option value="<?= $ruta['id'] ?>">
                      <?= htmlspecialchars($ruta['nombre']) ?> 
                      (<?= htmlspecialchars($ruta['origen']) ?> - <?= htmlspecialchars($ruta['destino']) ?>)
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No hay rutas disponibles</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="estado" class="form-label">Estado</label>
              <select class="form-control" id="estado" name="estado">
                <option value="En ruta">En ruta</option>
                <option value="Completado">Completado</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="observacion" class="form-label">Observación</label>
              <textarea class="form-control" id="observacion" name="observacion" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal para Editar Asignación -->
  <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarLabel">Editar Asignación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="index.php?controller=Asignaciones&action=actualizar">
          <input type="hidden" id="edit_id" name="id">
          <div class="modal-body">
            <div class="mb-3">
              <label for="edit_bus_id" class="form-label">Bus *</label>
              <select class="form-control" id="edit_bus_id" name="bus_id" required>
                <option value="">Seleccionar bus</option>
                <?php if (!empty($buses)): ?>
                  <?php foreach($buses as $bus): ?>
                    <option value="<?= $bus['id'] ?>">
                      <?= htmlspecialchars($bus['placa']) ?> - <?= htmlspecialchars($bus['modelo']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No hay buses disponibles</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_conductor_id" class="form-label">Conductor *</label>
              <select class="form-control" id="edit_conductor_id" name="conductor_id" required>
                <option value="">Seleccionar conductor</option>
                <?php if (!empty($conductores)): ?>
                  <?php foreach($conductores as $conductor): ?>
                    <option value="<?= $conductor['id'] ?>">
                      <?= htmlspecialchars($conductor['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No hay conductores disponibles</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_ruta_id" class="form-label">Ruta *</label>
              <select class="form-control" id="edit_ruta_id" name="ruta_id" required>
                <option value="">Seleccionar ruta</option>
                <?php if (!empty($rutas)): ?>
                  <?php foreach($rutas as $ruta): ?>
                    <option value="<?= $ruta['id'] ?>">
                      <?= htmlspecialchars($ruta['nombre']) ?> 
                      (<?= htmlspecialchars($ruta['origen']) ?> - <?= htmlspecialchars($ruta['destino']) ?>)
                    </option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="">No hay rutas disponibles</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_estado" class="form-label">Estado</label>
              <select class="form-control" id="edit_estado" name="estado">
                <option value="En ruta">En ruta</option>
                <option value="Completado">Completado</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="edit_observacion" class="form-label">Observación</label>
              <textarea class="form-control" id="edit_observacion" name="observacion" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="edit_fecha_asignacion" class="form-label">Fecha Asignación</label>
              <input type="datetime-local" class="form-control" id="edit_fecha_asignacion" name="fecha_asignacion">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Actualizar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal para Finalizar Asignación -->
  <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEliminarLabel">Finalizar Asignación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="index.php?controller=Asignaciones&action=eliminar">
          <input type="hidden" id="delete_id" name="id">
          <div class="modal-body">
            <p>¿Está seguro de que desea finalizar esta asignación?</p>
            <p class="text-danger"><strong>Esta acción cambiará el estado a "Completado".</strong></p>
            <div id="delete_info">
              <!-- Información se llenará con JavaScript -->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Finalizar Asignación</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Script para cargar datos en los modales -->
  <script>
    // Modal Editar
    document.querySelectorAll('.btn-editar').forEach(button => {
      button.addEventListener('click', function() {
        // IDs y valores básicos
        document.getElementById('edit_id').value = this.dataset.id;
        document.getElementById('edit_bus_id').value = this.dataset.bus_id;
        document.getElementById('edit_conductor_id').value = this.dataset.conductor_id;
        document.getElementById('edit_ruta_id').value = this.dataset.ruta_id;
        document.getElementById('edit_estado').value = this.dataset.estado;
        document.getElementById('edit_observacion').value = this.dataset.observacion;
        
        // Formatear fecha para datetime-local si existe
        if (this.dataset.fecha_asignacion) {
          const fecha = new Date(this.dataset.fecha_asignacion);
          const fechaFormateada = fecha.toISOString().slice(0, 16);
          document.getElementById('edit_fecha_asignacion').value = fechaFormateada;
        }
      });
    });

    // Modal Eliminar (Finalizar)
    document.querySelectorAll('.btn-eliminar').forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('delete_id').value = this.dataset.id;
        
        // Crear HTML con la información
        const infoHTML = `
          <p><strong>Información de la asignación:</strong></p>
          <ul>
            <li><strong>Bus:</strong> ${this.dataset.bus_info}</li>
            <li><strong>Conductor:</strong> ${this.dataset.conductor_info}</li>
            <li><strong>Ruta:</strong> ${this.dataset.ruta_info}</li>
          </ul>
        `;
        
        document.getElementById('delete_info').innerHTML = infoHTML;
      });
    });
  </script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>