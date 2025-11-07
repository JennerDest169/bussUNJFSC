<?php
$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
$title = "Dashboard - Sistema de Buses UNJFSC";
include __DIR__ . '/../layout/header.php'; 
?>
<body>
  <div class="container-fluid">
    <div class="page-inner">
      <div class="page-header">
        <h3 class="fw-bold mb-3">Usuarios</h3>
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
            <a href="#">Usuarios</a>
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
              <h4 class="card-title mb-0">Tabla de usuarios</h4>
              <button type="button" 
                                  class="btn btn-primary btn-sm btn-add" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#addModal">
                            Crear Usuario
                          </button>
            </div>

            <div class="card-body">
              <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Email</th>
                      <th>Nombres</th>
                      <th>Rol</th>
                      <th>Estado</th>
                      <th>Fecha Creacion</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>Email</th>
                      <th>Nombres</th>
                      <th>Rol</th>
                      <th>Estado</th>
                      <th>Fecha Creacion</th>
                      <th>Acciones</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                      <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= htmlspecialchars($usuario['correo']) ?></td>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= $usuario['rol'] ?></td>
                        <td>
                          <span class="badge bg-<?= $usuario['estado'] == 'Activo' ? 'success' : 'secondary' ?>">
                            <?= $usuario['estado'] ?>
                          </span>
                        </td>
                        <td><?= $usuario['fecha_registro'] ?></td>
                        <td>
                          <button type="button"
                                  class="btn btn-warning btn-sm btn-editar" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#modalEditar"
                                  data-id="<?= $usuario['id'] ?>"
                                  data-nombre="<?= htmlspecialchars($usuario['nombre']) ?>"
                                  data-rol="<?= $usuario['rol'] ?>"
                                  data-estado="<?= $usuario['estado'] ?>">
                            Editar
                          </button>
                          <?php if($usuario['estado'] == 'Activo')
                            {
                              ?>
                          <button type="button" 
                                  class="btn btn-danger btn-sm btn-eliminar" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#modalEliminar"
                                  data-id="<?= $usuario['id'] ?>"
                                  data-nombre="<?= htmlspecialchars($usuario['nombre']) ?>">
                            Eliminar
                          </button>
                          <?php }
                              ?>
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
    </div>
  </div>

  <!-- Incluir modales -->
  <?php include __DIR__ . '/addModal.php'; ?>
  <?php include __DIR__ . '/editModal.php'; ?>
  <?php include __DIR__ . '/deletModal.php'; ?>

  <!-- Script para cargar datos en los modales -->
  <script>
    // Modal Editar
    document.querySelectorAll('.btn-editar').forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('edit_id').value = this.dataset.id;
        document.getElementById('edit_nombre').value = this.dataset.nombre;
        document.getElementById('edit_rol').value = this.dataset.rol;
        document.getElementById('edit_estado').value = this.dataset.estado;
      });
    });

    // Modal Eliminar
    document.querySelectorAll('.btn-eliminar').forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('delete_id').value = this.dataset.id;
        document.getElementById('delete_nombre').value = this.dataset.nombre;
      });
    });
  </script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
</body>