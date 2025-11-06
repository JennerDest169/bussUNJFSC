<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo isset($title) ? $title : 'Sistema de Buses UNJFSC'; ?></title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="../../public/assets/img/kaiadmin/favicon.ico" type="image/x-icon" />

    <!-- Fonts and icons -->
    <script src="../../public/assets/js/plugin/webfont/webfont.min.js"></script>
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
                urls: ["../../public/assets/css/fonts.min.css"],
            },
            active: function () {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../../public/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../../public/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../../public/assets/css/kaiadmin.min.css" />
    <link rel="stylesheet" href="../../public/assets/css/demo.css" />
    
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
                    <a href="dashboard.php" class="logo">
                        <img src="../../public/assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="160" />
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
                        
                        <li class="nav-item <?= $is_dashboard ? 'active' : '' ?>">
                            <a href="dashboard.php">
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
                        <a href="../../public/index.php" class="logo">
                            <img src="../../public/assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
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
                        <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pe-1">
                                        <i class="fa fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="text" placeholder="Search ..." class="form-control" />
                            </div>
                        </nav>

                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <!-- Menú de mensajes -->
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link dropdown-toggle" href="#" id="messageDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-envelope"></i>
                                </a>
                                <ul class="dropdown-menu messages-notif-box animated fadeIn" aria-labelledby="messageDropdown">
                                    <li>
                                        <div class="dropdown-title d-flex justify-content-between align-items-center">
                                            Mensajes
                                            <a href="#" class="small">Marcar todos como leídos</a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-notif-scroll scrollbar-outer">
                                            <div class="notif-center">
                                                <a href="#">
                                                    <div class="notif-content">
                                                        <span class="subject">Sistema de Buses</span>
                                                        <span class="block">Nuevo conductor registrado</span>
                                                        <span class="time">5 minutos ago</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            
                            <!-- Notificaciones -->
                            <li class="nav-item topbar-icon dropdown hidden-caret">
                                <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-bell"></i>
                                    <span class="notification">3</span>
                                </a>
                                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                                    <li>
                                        <div class="dropdown-title">Tienes 3 nuevas notificaciones</div>
                                    </li>
                                    <li>
                                        <div class="notif-scroll scrollbar-outer">
                                            <div class="notif-center">
                                                <a href="#">
                                                    <div class="notif-icon notif-primary">
                                                        <i class="fa fa-bus"></i>
                                                    </div>
                                                    <div class="notif-content">
                                                        <span class="block">Nuevo bus agregado</span>
                                                        <span class="time">Hace 2 horas</span>
                                                    </div>
                                                </a>
                                                <a href="#">
                                                    <div class="notif-icon notif-success">
                                                        <i class="fa fa-route"></i>
                                                    </div>
                                                    <div class="notif-content">
                                                        <span class="block">Ruta actualizada</span>
                                                        <span class="time">Hace 5 horas</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <!-- Perfil de usuario -->
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="/BUSS/public/assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hola,</span>
                                        <span class="fw-bold">Administrador</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="/BUSS/public/assets/img/profile.jpg" alt="image profile" class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">
                                                    <h4>Administrador</h4>
                                                    <p class="text-muted">admin@unjfsc.edu.pe</p>
                                                    <a href="#" class="btn btn-xs btn-secondary btn-sm">Ver Perfil</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Mi Perfil</a>
                                            <a class="dropdown-item" href="#">Configuración</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Cerrar Sesión</a>
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