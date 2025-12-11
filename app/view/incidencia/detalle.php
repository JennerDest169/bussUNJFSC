<!--detalle.php - incidencia-->
<?php
$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
$title = "Incidencias | Detalle";
include __DIR__ . '/../layout/header.php';
?>
<div class="page-inner">
  <!-- Botón Volver -->
  <div class="mb-3">
    <a href="index.php?controller=Incidencia&action=index" class="btn btn-volver">
      <i class="fas fa-arrow-left"></i> Volver a Incidencias
    </a>
  </div>

  <div class="page-header">
    <h3 class="fw-bold mb-3">Detalle de Incidencia</h3>
    <ul class="breadcrumbs mb-3">
      <li class="nav-home"><a href="index.php?controller=Dashboard&action=index"><i class="icon-home"></i></a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="index.php?controller=Incidencia&action=index">Incidencias</a></li>
      <li class="separator"><i class="icon-arrow-right"></i></li>
      <li class="nav-item"><a href="#">Detalle</a></li>
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

  <?php
  // Determinar la clase del estado
  $estado_class = strtolower(str_replace(' ', '-', $incidencia['estado']));
  $icon_map = [
    'Pendiente' => 'fa-exclamation-triangle',
    'En proceso' => 'fa-spinner',
    'Resuelto' => 'fa-check-circle'
  ];
  $color_map = [
    'Pendiente' => '#FFC107',
    'En proceso' => '#1572E8',
    'Resuelto' => '#31CE36'
  ];
  ?>

  <!-- Tarjeta principal con borde de color según estado -->
  <div class="row">
    <div class="col-md-12">
      <div class="card card-form-incidencia incident-detail-card <?= $estado_class ?>">
        <div class="card-header card-header-dark">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="card-title mb-0">
              <i class="fas <?= $icon_map[$incidencia['estado']] ?> me-2"></i>
              Incidencia #<?= $incidencia['id'] ?>
            </div>
            <div>
              <span class="badge" style="background-color: <?= $color_map[$incidencia['estado']] ?>; font-size: 1rem; padding: 8px 15px;">
                <?= htmlspecialchars($incidencia['estado']) ?>
              </span>
            </div>
          </div>
        </div>

        <div class="card-body">
          <!-- Información del usuario -->
          <div class="info-section">
            <h5 class="mb-3">
              <i class="fas fa-user text-primary me-2"></i>Información del Reporte
            </h5>
            <div class="row">
              <?php if ($usuario['rol'] === 'Administrador'): ?>
                <div class="col-md-6 mb-3">
                  <span class="info-label">Reportado por:</span>
                  <span class="info-value"><?= htmlspecialchars($incidencia['nombre_usuario'] ?? 'Anónimo') ?></span>
                </div>
                <div class="col-md-6 mb-3">
                  <span class="info-label">Correo:</span>
                  <span class="info-value"><?= htmlspecialchars($incidencia['correo'] ?? 'N/A') ?></span>
                </div>
              <?php endif; ?>
              <div class="col-md-6 mb-3">
                <span class="info-label">Tipo de Incidencia:</span>
                <span class="badge bg-info" style="font-size: 0.9rem;">
                  <?= htmlspecialchars($incidencia['tipo']) ?>
                </span>
              </div>
              <div class="col-md-6 mb-3">
                <span class="info-label">Fecha de Reporte:</span>
                <span class="info-value">
                  <i class="far fa-calendar me-1"></i>
                  <?= date('d/m/Y H:i:s', strtotime($incidencia['fecha_reporte'])) ?>
                </span>
              </div>
            </div>
          </div>

          <div class="separator-dashed"></div>

          <!-- Descripción -->
          <div class="mb-4">
            <h5 class="section-label">
              <i class="fas fa-file-alt text-warning me-2"></i>
              Descripción Detallada
            </h5>
            <div class="descripcion-box">
              <?= nl2br(htmlspecialchars($incidencia['descripcion'])) ?>
            </div>
          </div>

          <!-- Imágenes adjuntas -->
          <?php if (!empty($imagenes)): ?>
            <div class="separator-dashed"></div>
            <div class="mb-4">
              <h5 class="section-label">
                <i class="fas fa-images text-success me-2"></i>
                Imágenes Adjuntas (<?= count($imagenes) ?>)
              </h5>
              <div class="imagenes-galeria">
                <?php foreach ($imagenes as $index => $img): ?>
                  <div class="imagen-item" data-index="<?= $index ?>">
                    <img src="index.php?controller=Incidencia&action=mostrarImagen&id=<?= $img['id'] ?>"
                      alt="<?= htmlspecialchars($img['nombre_archivo']) ?>"
                      onclick="openLightbox(<?= $index ?>)">
                    <div class="imagen-overlay">
                      <i class="fas fa-search-plus"></i>
                      <?= htmlspecialchars($img['nombre_archivo']) ?>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
          
          <!-- Respuesta del administrador -->
          <?php if (!empty($incidencia['respuesta'])): ?>
            <div class="separator-dashed"></div>
            <div class="respuesta-admin">
              <h5>
                <i class="fas fa-reply me-2"></i>Respuesta del Administrador
              </h5>
              <p class="mb-2" style="line-height: 1.8;">
                <?= nl2br(htmlspecialchars($incidencia['respuesta'])) ?>
              </p>
              <small class="d-block mt-3" style="opacity: 0.9;">
                <i class="far fa-clock me-1"></i>
                <strong>Respondido el:</strong>
                <?= date('d/m/Y H:i:s', strtotime($incidencia['fecha_respuesta'])) ?>
              </small>
            </div>
          <?php endif; ?>

          <!-- Formulario para responder (solo admin) -->
          <?php if ($usuario['rol'] === 'Administrador'): ?>
            <div class="separator-dashed"></div>
            <div class="form-responder">
              <h5 class="section-label">
                <i class="fas fa-reply-all text-primary me-2"></i>
                Responder Incidencia
              </h5>
              <form action="index.php?controller=Incidencia&action=responder" method="POST">
                <input type="hidden" name="id" value="<?= $incidencia['id'] ?>">

                <div class="form-group">
                  <label for="respuesta">
                    Mensaje de Respuesta
                    <i class="fas fa-question-circle ms-1 text-muted"
                      data-bs-toggle="tooltip"
                      title="Escribe una respuesta clara y detallada para el usuario"></i>
                  </label>
                  <textarea class="form-control textarea-descripcion" id="respuesta" name="respuesta"
                    rows="5" required
                    placeholder="Escribe tu respuesta aquí..."><?= htmlspecialchars($incidencia['respuesta'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                  <label for="estado">
                    Cambiar Estado
                    <i class="fas fa-question-circle ms-1 text-muted"
                      data-bs-toggle="tooltip"
                      title="Actualiza el estado según el avance de la resolución"></i>
                  </label>
                  <select class="form-select form-control" id="estado" name="estado" required>
                    <option value="Pendiente" <?= $incidencia['estado'] === 'Pendiente' ? 'selected' : '' ?>>
                      ⏳ Pendiente
                    </option>
                    <option value="En proceso" <?= $incidencia['estado'] === 'En proceso' ? 'selected' : '' ?>>
                      🔄 En proceso
                    </option>
                    <option value="Resuelto" <?= $incidencia['estado'] === 'Resuelto' ? 'selected' : '' ?>>
                      ✅ Resuelto
                    </option>
                  </select>
                </div>

                <div class="d-flex justify-content-between flex-wrap gap-2 mt-4">
                  <a href="index.php?controller=Incidencia&action=index" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times"></i> Cancelar
                  </a>
                  <button type="submit" class="btn btn-success btn-lg">
                    <i class="fas fa-paper-plane"></i> Enviar Respuesta
                  </button>
                </div>
              </form>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

</div>


<!-- Lightbox para imágenes -->
<div class="lightbox-backdrop" id="lightbox" onclick="closeLightbox(event)">
  <button class="lightbox-close" onclick="closeLightbox(event)">
    <i class="fas fa-times"></i>
  </button>
  <button class="lightbox-nav prev" onclick="prevImage(event)">
    <i class="fas fa-chevron-left"></i>
  </button>
  <div class="lightbox-content">
    <img id="lightbox-img" src="" alt="Imagen ampliada">
    <div class="lightbox-counter">
      <span id="current-image">1</span> / <span id="total-images">1</span>
    </div>
  </div>
  <button class="lightbox-nav next" onclick="nextImage(event)">
    <i class="fas fa-chevron-right"></i>
  </button>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>