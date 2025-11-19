<!--index-php - incidencia-->
<?php
$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
$title = "Gestión de Incidencias";
include __DIR__ . '/../layout/header.php';
?>
<div class="page-inner">
  <div class="page-header">
    <h3 class="fw-bold mb-3">Gestión de Incidencias</h3>
    <ul class="breadcrumbs mb-3">
      <li class="nav-home"><a href="index.php?controller=Dashboard&action=index"><i class="icon-home"></i></a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="#">Incidencias</a></li>
    </ul>
  </div>

  <?php if ($exito): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>¡Éxito!</strong> <?= htmlspecialchars($exito); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong>¡Error!</strong> <?= htmlspecialchars($error); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- VISTA PARA ADMINISTRADOR -->
  <?php if ($usuario['rol'] === 'Administrador'): ?>

    <!-- Estadísticas -->
    <div class="row mb-4">
      <div class="col-sm-6 col-md-4">
        <div class="card incident-card pendiente card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-warning bubble-shadow-small">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Pendientes</p>
                  <h4 class="card-title"><?= $estadisticas['Pendiente'] ?? 0 ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="card incident-card en-proceso card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-primary bubble-shadow-small">
                  <i class="fas fa-spinner"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">En Proceso</p>
                  <h4 class="card-title"><?= $estadisticas['En proceso'] ?? 0 ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="card incident-card resuelto card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-success bubble-shadow-small">
                  <i class="fas fa-check-circle"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Resueltas</p>
                  <h4 class="card-title"><?= $estadisticas['Resuelto'] ?? 0 ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Listado reducido para Admin -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Listado de Incidencias</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="incidencias-table" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th></th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Autor</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($incidencias as $inc): ?>
                    <tr>
                      <td></td>
                      <td><span class="badge bg-info"><?= htmlspecialchars($inc['tipo']) ?></span></td>
                      <td><?= date('d/m/Y H:i', strtotime($inc['fecha_reporte'])) ?></td>
                      <td><?= htmlspecialchars($inc['nombre_usuario'] ?? 'Anónimo') ?></td>
                      <td>
                        <?php
                        $badge_class = [
                          'Pendiente' => 'badge-warning',
                          'En proceso' => 'badge-primary',
                          'Resuelto' => 'badge-success'
                        ];
                        ?>
                        <span class="badge <?= $badge_class[$inc['estado']] ?>">
                          <?= htmlspecialchars($inc['estado']) ?>
                        </span>
                      </td>
                      <td>
                        <a href="index.php?controller=Incidencia&action=ver&id=<?= $inc['id'] ?>"
                          class="btn btn-sm btn-info">
                          <i class="fas fa-eye"></i> Ver
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- VISTA PARA ESTUDIANTE/CONDUCTOR -->
  <?php else: ?>

    <!-- Botón para reportar incidencia -->
    <div class="row mb-4">
      <div class="col-md-12 text-center">
        <a href="index.php?controller=Incidencia&action=crear" class="btn btn-report">
          <i class="fa fa-bullhorn" aria-hidden="true"></i>
          Reportar Incidencia
        </a>
      </div>
    </div>

    <!-- Encabezado con filtros -->
    <div class="row mb-3 align-items-center">
      <div class="col-md-6">
        <h4 class="mb-0">Mis Incidencias Reportadas</h4>
      </div>
      <div class="col-md-6">
        <div class="d-flex justify-content-end align-items-center">
          <div class="border-end pe-3 me-3" style="height: 30px;"></div>

          <label for="ordenar" class="me-2 mb-0 text-muted">Ordenar por:</label>
          <select id="ordenar" class="form-select form-select-sm me-2" style="width: auto;">
            <option value="fecha">Fecha</option>
            <option value="estado">Estado</option>
            <option value="tipo">Tipo</option>
          </select>

          <button id="toggleOrden" class="btn btn-sm btn-outline-secondary" title="Cambiar orden">
            <i class="fas fa-sort-amount-down" id="iconOrden"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Listado de incidencias del usuario en tarjetas -->
    <div class="row" id="incidenciasContainer">
      <?php if (!empty($incidencias)): ?>
        <?php foreach ($incidencias as $inc):
          $estado_class = strtolower(str_replace(' ', '-', $inc['estado']));
          $icon_map = [
            'Pendiente' => 'fa-exclamation-triangle',
            'En proceso' => 'fa-spinner',
            'Resuelto' => 'fa-check-circle'
          ];
        ?>
          <div class="col-md-12 mb-3 incidencia-item"
            data-fecha="<?= strtotime($inc['fecha_reporte']) ?>"
            data-estado="<?= htmlspecialchars($inc['estado']) ?>"
            data-tipo="<?= htmlspecialchars($inc['tipo']) ?>">
            <div class="card incident-card <?= $estado_class ?>">
              <div class="card-body">
                <div class="row align-items-center">
                  <!-- Columna del estado (icono + texto) -->
                  <div class="col-auto col-tipo">
                    <div class="d-flex flex-column align-items-center">
                      <i class="fas <?= $icon_map[$inc['estado']] ?> status-icon <?= $estado_class ?>"></i>
                      <span class="fw-bold status-icon <?= $estado_class ?>" style="font-size: 1rem;">
                        <?= htmlspecialchars($inc['estado']) ?>
                      </span>
                    </div>
                  </div>

                  <!-- Columna del contenido -->
                  <div class="col">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($inc['tipo']) ?></h5>
                        <p class="text-muted mb-0"><?= substr(htmlspecialchars($inc['descripcion']), 0, 100) ?></p>
                      </div>
                      <div class="col-md-4 text-end">
                        <small class="text-muted d-block mb-2">
                          <i class="far fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($inc['fecha_reporte'])) ?>
                        </small>
                        <!--<a href="index.php?controller=Incidencia&action=ver&id=<?= $inc['id'] ?>" 
                                   class="btn btn-sm btn-primary">
                                  <i class="fas fa-eye"></i> Ver Detalle
                                </a>-->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body text-center py-5">
              <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
              <h4>No has reportado ninguna incidencia</h4>
              <p class="text-muted">Presiona el botón "Reportar Incidencia" para crear tu primera solicitud</p>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

  <?php endif; ?>

</div>


<?php include __DIR__ . '/../layout/footer.php'; ?>