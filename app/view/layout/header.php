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

    <link rel="stylesheet" href="../../../public/assets/css/extra.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

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
                            <a href="index.php?controller=Bus&action=index">
                                <i class="fas fa-bus"></i>
                                <p>Buses</p>
                            </a>
                        </li>
                        <li class="nav-item <?= $is_RastreoGPS ? 'active' : '' ?>">
                            <a href="index.php?controller=RastreoGPS&action=index">
                                <i class="fas fa-map-marked-alt"></i>
                                <p>RastreoGPS</p>
                            </a>
                        </li>
                        <li class="nav-item <?= $is_conductores ? 'active' : '' ?>">
                            <a href="index.php?controller=Conductor&action=listar">
                                <i class="fas fa-user-tie"></i>
                                <p>Conductores</p>
                            </a>
                        </li>
                        <li class="nav-item <?= $is_rutas ? 'active' : '' ?>">
                            <a href="index.php?controller=Ruta&action=index">
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
                         <!-- INCIDENCIAS con badge de notificación -->
                        <li class="nav-item" id="nav-incidencias">
                            <a class="dropdown-item" href="index.php?controller=Incidencia&action=index">
                                <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                <p>Incidencias</p>
                                <!-- Badge de notificación (se muestra dinámicamente) -->
                                <span class="notification-badge incidencias" id="badge-incidencias" style="display: none;">0</span>
                            </a>
                        </li>

                        <!-- COMUNICADOS con badge de notificación -->
                        <li class="nav-item" id="nav-comunicados">
                            <a class="dropdown-item" href="index.php?controller=Comunicado&action=index">
                                <i class="fa fa-comment" aria-hidden="true"></i>
                                <p>Comunicados</p>
                                <!-- Badge de notificación (se muestra dinámicamente) -->
                                <span class="notification-badge comunicados" id="badge-comunicados" style="display: none;">0</span>
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
