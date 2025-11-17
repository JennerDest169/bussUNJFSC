<?php
$title = "Gestión de Comunicados";
include __DIR__ . '/../layout/header.php';
?>

<div class="page-inner">
  <div class="page-header">
    <h3 class="fw-bold mb-3">Gestión de Comunicados</h3>
    <ul class="breadcrumbs mb-3">
      <li class="nav-home"><a href="index.php?controller=Usuario&action=index"><i class="icon-home"></i></a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="#">Comunicación</a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="#">Comunicados</a></li>
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

    <!-- Estadísticas por prioridad -->
    <div class="row mb-4">
      <?php
      // Contar comunicados por prioridad
      $query_stats = "SELECT prioridad, COUNT(*) as total FROM comunicados GROUP BY prioridad";
      $stmt_stats = $this->db->prepare($query_stats);
      $stmt_stats->execute();
      $stats = [];
      while ($row = $stmt_stats->fetch(PDO::FETCH_ASSOC)) {
        $stats[$row['prioridad']] = $row['total'];
      }
      ?>

      <div class="col-sm-6 col-md-4">
        <div class="card comunicado-card normal card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center bubble-shadow-small" style="background-color: #9C27B0; border-radius: 10%;">
                  <i class="fas fa-info-circle" style="color: white;"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Normal</p>
                  <h4 class="card-title"><?= $stats['Normal'] ?? 0 ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-4">
        <div class="card comunicado-card importante card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center bubble-shadow-small" style="background-color: #FF9800; border-radius: 10%;">
                  <i class="fas fa-exclamation-circle" style="color: white;"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Importante</p>
                  <h4 class="card-title"><?= $stats['Importante'] ?? 0 ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-4">
        <div class="card comunicado-card urgente card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center bubble-shadow-small" style="background-color: #F44336; border-radius: 10%;">
                  <i class="fas fa-exclamation-triangle" style="color: white;"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Urgente</p>
                  <h4 class="card-title"><?= $stats['Urgente'] ?? 0 ?></h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Botón para crear nuevo comunicado -->
    <div class="row mb-4">
      <div class="col-md-12 text-center">
        <a href="index.php?controller=Comunicado&action=nuevo" class="btn btn-nuevo-comunicado">
          <i class="fa fa-comment" aria-hidden="true"></i>
          Nuevo Comunicado
        </a>
      </div>
    </div>

    <!-- Tabla de comunicados -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Listado de Comunicados</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="comunicados-table" class="display table table-striped table-hover">
                <thead>
                  <tr>
                    <th>Autor</th>
                    <th>Tipo</th>
                    <th>Fecha Publicación</th>
                    <th>Prioridad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($comunicados)): ?>
                    <?php foreach ($comunicados as $com): ?>
                      <tr>
                        <td><?= htmlspecialchars($com['autor'] ?? 'Sistema') ?></td>
                        <td><span class="badge bg-info"><?= htmlspecialchars($com['tipo']) ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($com['fecha_publicacion'])) ?></td>
                        <td>
                          <?php
                          $prioridad_colors = [
                            'Normal' => 'secondary',
                            'Importante' => 'warning',
                            'Urgente' => 'danger'
                          ];
                          ?>
                          <span class="badge badge-<?= $prioridad_colors[$com['prioridad']] ?>">
                            <?= htmlspecialchars($com['prioridad']) ?>
                          </span>
                        </td>
                        <td>
                          <span class="badge badge-<?= $com['estado'] === 'Activo' ? 'success' : 'danger' ?>">
                            <?= htmlspecialchars($com['estado']) ?>
                          </span>
                        </td>
                        <td>
                          <a href="index.php?controller=Comunicado&action=editar&id=<?= $com['id'] ?>"
                            class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Editar
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center">No hay comunicados registrados</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- VISTA PARA ESTUDIANTE/CONDUCTOR -->
  <?php else: ?>

    <!-- Encabezado con filtros -->
    <div class="row mb-3 align-items-center">
      <div class="col-md-6">
        <h4 class="mb-0">Comunicados Institucionales</h4>
      </div>
      <div class="col-md-6">
        <div class="d-flex justify-content-end align-items-center">
          <div class="border-end pe-3 me-3" style="height: 30px;"></div>

          <label for="ordenarCom" class="me-2 mb-0 text-muted">Ordenar por:</label>
          <select id="ordenarCom" class="form-select form-select-sm me-2" style="width: auto;">
            <option value="fecha">Fecha</option>
            <option value="prioridad">Prioridad</option>
            <option value="tipo">Tipo</option>
          </select>

          <button id="toggleOrdenCom" class="btn btn-sm btn-outline-secondary" title="Cambiar orden">
            <i class="fas fa-sort-amount-down" id="iconOrdenCom"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Listado de comunicados en tarjetas -->
    <div class="row" id="comunicadosContainer">
      <?php if (!empty($comunicados)): ?>
        <?php foreach ($comunicados as $com):
          $prioridad_class = strtolower(str_replace(' ', '-', $com['prioridad']));
          $icon_map = [
            'Normal' => 'fa-info-circle',
            'Importante' => 'fa-exclamation-circle',
            'Urgente' => 'fa-exclamation-triangle'
          ];
        ?>
          <div class="col-md-12 mb-3 comunicado-item"
            data-fecha="<?= strtotime($com['fecha_publicacion']) ?>"
            data-prioridad="<?= htmlspecialchars($com['prioridad']) ?>"
            data-tipo="<?= htmlspecialchars($com['tipo']) ?>">
            <div class="card comunicado-card <?= $prioridad_class ?>">
              <div class="card-body">
                <div class="row align-items-center">
                  <!-- Columna de prioridad (icono + texto) -->
                  <div class="col-auto col-prioridad">
                    <div class="d-flex flex-column align-items-center">
                      <i class="fas <?= $icon_map[$com['prioridad']] ?> prioridad-icon <?= $prioridad_class ?>"></i>
                      <span class="fw-bold prioridad-icon <?= $prioridad_class ?>" style="font-size: 1rem;">
                        <?= htmlspecialchars($com['prioridad']) ?>
                      </span>
                    </div>
                  </div>

                  <!-- Columna del contenido -->
                  <div class="col">
                    <div class="row">
                      <div class="col-md-8">
                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($com['tipo']) ?></h5>
                        <p class="text-muted mb-0"><?= substr(htmlspecialchars($com['contenido']), 0, 150) ?>...</p>
                      </div>
                      <div class="col-md-4 text-end">
                        <small class="text-muted d-block mb-2">
                          <i class="far fa-calendar"></i> <?= date('d/m/Y H:i', strtotime($com['fecha_publicacion'])) ?>
                        </small>
                        <span class="estado-badge <?= strtolower($com['estado']) ?>">
                          <span class="circle"></span>
                          <?= htmlspecialchars($com['estado']) ?>
                        </span>
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
              <h4>No hay comunicados disponibles</h4>
              <p class="text-muted">Aún no se han publicado comunicados institucionales</p>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

  <?php endif; ?>

</div>


<?php include __DIR__ . '/../layout/footer.php'; ?>