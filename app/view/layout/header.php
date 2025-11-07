<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo isset($title) ? $title : 'Sistema de Buses UNJFSC'; ?></title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../../../public/assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../../../public/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../../../public/assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../public/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../../public/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../../public/assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="../../../public/assets/css/demo.css" />
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-background-color="dark">
            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="index.php?controller=Dashboard&action=index" class="logo">
                        <img src="../../../public/assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="160" />
                    </a>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>
            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">
                        <!-- Determinar qué página está activa -->
                        <?php
                        $current_page = basename($_SERVER['PHP_SELF']);
            
                        $is_dashboard = ($current_page == 'index.php' || $current_page == 'dashboard.php');
                        $is_buses = ($current_page == 'buses.php');
                        $is_conductores = ($current_page == 'conductores.php');
                        $is_rutas = ($current_page == 'rutas.php');
                        ?>
                        
                        <li class="nav-item">
                            <a class="dropdown-item" href="index.php?controller=Dashboard&action=index">
                                <i class="fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-section">
                            <span class="sidebar-mini-icon">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <h4 class="text-section">Gestión de Transporte</h4>
                        </li>
                        <li class="nav-item <?= $is_buses ? 'active' : '' ?>">
                            <a href="buses.php">
                                <i class="fas fa-bus"></i>
                                <p>Buses</p>
                            </a>
                        </li>
                        <li class="nav-item <?= $is_conductores ? 'active' : '' ?>">
                            <a href="conductores.php">
                                <i class="fas fa-user-tie"></i>
                                <p>Conductores</p>
                            </a>
                        </li>
                        <li class="nav-item <?= $is_rutas ? 'active' : '' ?>">
                            <a href="rutas.php">
                                <i class="fas fa-route"></i>
                                <p>Rutas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="dropdown-item" href="index.php?controller=Usuario&action=index">
                                <i class="fas fa-user"></i>
                                <p>Usuarios</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.php?controller=Dashboard&action=index"> class="logo">
                            <img src="../../../public/assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <!-- Perfil de usuario -->
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="/BUSS/public/assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hola,</span>
                                        <span class="fw-bold">
                                            <?php
                                            $usuario = $_SESSION['usuario'];
                                            echo "" . $usuario['nombre'];
                                            ?>
                                        </span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="u-text">
                                                    <h4>
                                                        <?php
                                                $usuario = $_SESSION['usuario'];
                                                echo "" . $usuario['nombre'];
                                                ?>
                                                    </h4>
                                                    <p class="text-muted">
                                                        <?php
                                                $usuario = $_SESSION['usuario'];
                                                echo "" . $usuario['correo'];
                                                ?>
                                                    </p>
                                                    <p class="text-muted">
                                                        <?php
                                                $usuario = $_SESSION['usuario'];
                                                echo "" . $usuario['rol'];
                                                ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="index.php?controller=Auth&action=logout">Logout</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>

            <!-- Content Area -->
            <div class="content" style="margin-top: 40px;">
                <div class="page-inner">
                        <!--   Core JS Files   -->
    <script src="../../../public/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../../../public/assets/js/core/popper.min.js"></script>
    <script src="../../../public/assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="../../../public/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <!-- Datatables -->
    <script src="../../../public/assets/js/plugin/datatables/datatables.min.js"></script>
    <!-- Kaiadmin JS -->
    <script src="../../../public/assets/js/kaiadmin.min.js"></script>
    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="../../../public/assets/js/setting-demo2.js"></script>
    <script>
      $(document).ready(function () {
        $("#basic-datatables").DataTable({});

        $("#multi-filter-select").DataTable({
          pageLength: 5,
          initComplete: function () {
            this.api()
              .columns()
              .every(function () {
                var column = this;
                var select = $(
                  '<select class="form-select"><option value=""></option></select>'
                )
                  .appendTo($(column.footer()).empty())
                  .on("change", function () {
                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                    column
                      .search(val ? "^" + val + "$" : "", true, false)
                      .draw();
                  });

                column
                  .data()
                  .unique()
                  .sort()
                  .each(function (d, j) {
                    select.append(
                      '<option value="' + d + '">' + d + "</option>"
                    );
                  });
              });
          },
        });

        // Add Row
        $("#add-row").DataTable({
          pageLength: 5,
        });

        var action =
          '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

        $("#addRowButton").click(function () {
          $("#add-row")
            .dataTable()
            .fnAddData([
              $("#addName").val(),
              $("#addPosition").val(),
              $("#addOffice").val(),
              action,
            ]);
          $("#addRowModal").modal("hide");
        });
      });
    </script>
  </body>
</html>
=======
<!--header.php-->
<?php
$exito = $_SESSION['exito'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['exito'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo isset($title) ? $title : 'Sistema de Buses UNJFSC'; ?></title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="<?= BASE_URL ?>public\assets\img\LogoPNG.png" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="<?= BASE_URL ?>public/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["<?= BASE_URL ?>public/assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/demo.css" />


    <style>
      /* Estilos para las tarjetas de incidencias */
      .incident-card {
        border-left: 5px solid;
        transition: transform 0.2s, box-shadow 0.2s;
      }
      .incident-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      }
      .incident-card.pendiente { border-left-color: #FFC107; }
      .incident-card.en-proceso { border-left-color: #1572E8; }
      .incident-card.resuelto { border-left-color: #31CE36; }
      
      /* Estilos para el icono de estado */
      .status-icon {
        font-size: 2.5rem;
        margin-right: 15px;
      }
      .status-icon.pendiente { color: #FFC107; }
      .status-icon.en-proceso { color: #1572E8; }
      .status-icon.resuelto { color: #31CE36; }
      
      /* Estilos para el botón de reportar incidencia */
      .btn-report {
        padding: 20px 40px;
        font-size: 1.2rem;
        font-weight: 600;
        background-color: #FFC107 !important;
        border: none !important;
        color: #000 !important;
        border-radius: 10px;
        transition: all 0.3s;
        display: inline-block;
        text-decoration: none;
      }
      .btn-report:hover {
        background-color: #FFB300 !important;
        color: #000 !important;
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
      }
      .btn-report i {
        margin-right: 10px;
        font-size: 1.4rem;
      }
    </style>


  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.php?controller=Usuario&action=index" class="logo">
              <img 
              src="<?= BASE_URL ?>public\assets\img\logounjfsc2.png" 
              alt="navbar brand" 
              class="navbar-brand" 
              height="55" />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">

              <li class="nav-item">
                <a data-bs-toggle="collapse"
                  href="#dashboard"
                  class="collapsed"
                  aria-expanded="false">

                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                  <span class="caret"></span>
                </a><div class="collapse" id="dashboard">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="../usuario/index.php">
                        <span class="sub-item">Dashboard 1</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>

              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Módulos</h4>
              </li>
              
              <!--
              <?php if($usuario['rol'] === 'Administrador'): ?>
              <li class="nav-item">
                <a href="index.php?controller=Usuario&action=index">
                  <i class="fas fa-users"></i>
                  <p>Usuarios</p>
                </a>
              </li>
              <?php endif; ?>-->

              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#comunicacion">
                  <i class="fas fa-bullhorn"></i>
                  <p>Comunicación</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="comunicacion">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="index.php?controller=Comunicado&action=index">
                        <span class="sub-item">Comunicados</span>
                      </a>
                    </li>
                    <li class="active">
                      <a href="index.php?controller=Incidencia&action=index">
                        <span class="sub-item">Incidencias</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <div class="logo-header" data-background-color="dark">
              <a href="index.php?controller=Usuario&action=index" class="logo">
                <img src="<?= BASE_URL ?>public\assets\img\LogoPNG.png" alt="navbar brand" class="navbar-brand" height="20" />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
          </div>
          
          <!-- Navbar Header -->
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
            <div class="container-fluid">
              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

              <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="notifDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-bell"></i>
                    <span class="notification">4</span>
                  </a>
                  <ul
                    class="dropdown-menu notif-box animated fadeIn"
                    aria-labelledby="notifDropdown"
                  >
                    <li>
                      <div class="dropdown-title">
                        You have 4 new notification
                      </div>
                    </li>
                    <li>
                      <div class="notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-icon notif-primary">
                              <i class="fa fa-user-plus"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> New user registered </span>
                              <span class="time">5 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-success">
                              <i class="fa fa-comment"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Rahmad commented on Admin
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="<?= BASE_URL ?>public/assets/img/profile2.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Reza send messages to you
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-danger">
                              <i class="fa fa-heart"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> Farrah liked Admin </span>
                              <span class="time">17 minutes ago</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="javascript:void(0);"
                        >See all notifications<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">

                  <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                      <img src="<?= BASE_URL ?>public/assets/img/profiledefault2.png" alt="Img Profile" class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                      
                      <span class="op-7">Hola,</span>

                      <span class="fw-bold">
                        <?php if ($usuario): ?>
                          <?= htmlspecialchars($usuario['nombre']); ?>
                        <?php endif; ?>
                      </span>

                    </span>
                  </a>

                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="u-text">
                            <h4><?php echo htmlspecialchars($usuario['nombre']); ?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($usuario['correo']); ?></p>
                            <p class="text-muted"><?php echo htmlspecialchars($usuario['rol']); ?></p>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="index.php?controller=Auth&action=logout">Cerrar Sesión</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <div class="container">
>>>>>>> 669d71246efa76798f9d9bbe9ac59b7d79333bca
