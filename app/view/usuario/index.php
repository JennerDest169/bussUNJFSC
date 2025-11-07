<?php
$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
session_start();
$title = "Dashboard - Sistema de Buses UNJFSC";
include __DIR__ . '/../layout/header.php'; 
?>

  <body>
        <div class="container-fluir">
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
            <div class="row">
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Tabla de usuarios</h4>
                    <a href="index.php?controller=Usuario&action=crear" class="btn btn-primary">
                      Crear Usuario
                    </a>
                  </div>

                  <div class="card-body">
                    <div class="table-responsive">
                      <table
                        id="basic-datatables"
                        class="display table table-striped table-hover"
                      >
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
            <td><?= $usuario['estado'] ?></td>
            <td><?= $usuario['fecha_registro'] ?></td>
            <td>
                <a href="index.php?controller=Usuario&action=crear" class="btn btn-warning">
                Editar
              </a>
              <a href="index.php?controller=Usuario&action=crear" class="btn btn-danger">
                Eliminar
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
          </div>
        </div>
<?php
        include __DIR__ . '/../layout/footer.php'; 
?>
      </div>
      <!-- End Custom template -->
    </div>
  </body>
