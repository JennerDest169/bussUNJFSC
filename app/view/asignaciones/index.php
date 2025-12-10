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
              <a href="index.php?controller=Asignaciones&action=nuevo" class="btn btn-primary btn-sm">
                Nueva Asignación
              </a>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Bus ID</th>
                      <th>Conductor ID</th>
                      <th>Ruta ID</th>
                      <th>Estado</th>
                      <th>Observación</th>
                      <th>Fecha Asignación</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Bus ID</th>
                      <th>Conductor ID</th>
                      <th>Ruta ID</th>
                      <th>Estado</th>
                      <th>Observación</th>
                      <th>Fecha Asignación</th>
                      <th>Acciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php if (!empty($asignaciones)): ?>
                      <?php foreach ($asignaciones as $asignacion): ?>
                        <tr>
                          <td><?= $asignacion['J23_id'] ?? $asignacion['id'] ?></td>
                          <td><?= $asignacion['J23_bus_id'] ?? $asignacion['bus_id'] ?></td>
                          <td><?= $asignacion['J23_conductor_id'] ?? $asignacion['conductor_id'] ?></td>
                          <td><?= $asignacion['J23_ruta_id'] ?? $asignacion['ruta_id'] ?></td>
                          <td>
                            <?php 
                            $estado = $asignacion['A2_estado'] ?? $asignacion['estado'] ?? 'Desconocido';
                            $color = 'secondary';
                            if ($estado == 'Completado') $color = 'success';
                            if ($estado == 'En ruta') $color = 'warning';
                            ?>
                            <span class="badge bg-<?= $color ?>">
                              <?= $estado ?>
                            </span>
                          </td>
                          <td><?= htmlspecialchars($asignacion['A2_observacion'] ?? $asignacion['observacion'] ?? 'Sin observación') ?></td>
                          <td><?= $asignacion['A2_fecha_asignacion'] ?? $asignacion['fecha_asignacion'] ?? '' ?></td>
                          <td>
                            <div class="btn-group" role="group">
                              <a href="index.php?controller=Asignaciones&action=editar&id=<?= $asignacion['J23_id'] ?? $asignacion['id'] ?>" 
                                 class="btn btn-warning btn-sm">
                                Editar
                              </a>
                              <a href="index.php?controller=Asignaciones&action=ver&id=<?= $asignacion['J23_id'] ?? $asignacion['id'] ?>" 
                                 class="btn btn-info btn-sm">
                                Ver
                              </a>
                              <?php if (($asignacion['A2_estado'] ?? $asignacion['estado']) == 'En ruta'): ?>
                                <a href="index.php?controller=Asignaciones&action=eliminar&id=<?= $asignacion['J23_id'] ?? $asignacion['id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Está seguro de finalizar esta asignación?')">
                                  Finalizar
                                </a>
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

<?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>