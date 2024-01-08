<?php
	$faenas_permiso = array();
	$me = NULL;
	if($this->Session->read('usuario_id') == null || $this->Session->read('usuario_id') == null) {
		
	} else {
		$faenas_permiso = $this->Session->read("NombresFaenas"); 
		$faena_session = $this->Session->read("faena_id"); 
		$me_email = $this->Session->read('google_email');
		$me_image = $this->Session->read('google_image');
		$me_name = $this->Session->read('google_name');
		$usuario_id = $this->Session->read('usuario_id');
		$cargos = $this->Session->read("PermisosCargos");
	}
	
	App::import('Controller', 'Utilidades');
	$app = new UtilidadesController();	
	
	if ($usuario_id == 629){
		$estado = 7;
	}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="es">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <meta charset="utf-8" />
        <title>DBM</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="DBM" name="description" />
        <meta content="BENACO Tecnología" name="author" />
		<meta name="robots" content="noindex, nofollow">
		<meta name="googlebot-news" content="noindex">
		<meta name="googlebot-news,bingbot" content="noindex">

        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
		<!-- BEGIN PAGE LEVEL STYLES -->
		<link href="/assets/pages/css/error.min.css" rel="stylesheet" type="text/css">
		<link href="/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css">
		<!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="/assets/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="/assets/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" />
        <link href="/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css" />
        <link href="/assets/layouts/layout3/css/custom.dbm.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="/favicon.ico" /> 
        
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-120061583-2"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-120061583-2');
		</script>

		
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
		  google.charts.load('current', {packages: ['corechart']});
		</script>
		<style>
			label.error {
				color: #e20a1b;
				font-size: .8em;
			}
			
			@media (min-width: 992px) {
				.container-fluid {
				    padding-left: 20px !important;
				    padding-right: 20px !important;
				}
			}
			
			.radio-detalle-answer {
                height: 20px;
                width: 20px;
                border-radius: 100%;
                left: 15px;                
            }

			.span-answer {
				margin-left: 26px;
			}

			.help-block-detail {
				color : #d02323;
			}			
		</style>
	</head>
    <!-- END HEAD -->

    <body class="page-container-bg-solid page-header-menu-fixed">
        <div class="page-wrapper">
            <div class="page-wrapper-row">
                <div class="page-wrapper-top">
                    <!-- BEGIN HEADER -->
                    <div class="page-header">
                        <!-- BEGIN HEADER TOP -->
                        <div class="page-header-top">
                            <div class="container-fluid">
                                <!-- BEGIN LOGO -->
                                <div class="page-logo">
                                    <a href="/Dashboard">
                                        <img src="/img/logo-dbm-default.png" alt="DBM" class="logo-default">
                                    </a>
                                </div>
                                <!-- END LOGO -->
                                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                                <a href="javascript:;" class="menu-toggler"></a>
                                <!-- END RESPONSIVE MENU TOGGLER -->
                                <!-- BEGIN TOP NAVIGATION MENU -->
                                <div class="top-menu">
                                    <ul class="nav navbar-nav pull-right">
                                        <!-- BEGIN NOTIFICATION DROPDOWN -->
                                        <!-- DOC: Apply "dropdown-hoverable" class after "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
                                        <!-- DOC: Remove "dropdown-hoverable" and add data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to the below A element with dropdown-toggle class -->
                                        
                                       
                                        <!-- END TODO DROPDOWN -->
                                      
                                        <!-- BEGIN USER LOGIN DROPDOWN -->
                                        <li class="dropdown dropdown-user dropdown-dark">
                                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                                <img alt="" class="img-circle" src="<?php echo $me_image;?>">
                                                <span class="username username-hide-mobile"><?php echo $me_email;?></span>
                                            </a>
                                            <!--
                                            <ul class="dropdown-menu dropdown-menu-default">
	                                            <li>
                                                    <a href="/Logout">
                                                        <i class="icon-key"></i> Salir </a>
                                                </li>
                                            </ul>-->
                                        </li>
                                         <li class="dropdown dropdown-extended dropdown-tasks dropdown-dark" id="header_task_bar">
                                            <a href="javascript:;" class="dropdown-toggle tooltips" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" data-placement="bottom" data-original-title="Notificaciones">
                                                <i class="icon-bell"></i>
                                            </a>
                                        </li>
                                        <li class="dropdown dropdown-extended dropdown-tasks dropdown-dark" id="header_task_bar">
                                            <a href="/Logout" class="dropdown-toggle tooltips" data-close-others="true" data-placement="bottom" data-original-title="Salir">
                                                <i class="icon-logout color-cummins"></i>
                                            </a>
                                        </li>
                                        <!-- END USER LOGIN DROPDOWN -->
                                    </ul>
                                </div>
                                <!-- END TOP NAVIGATION MENU -->
                            </div>
                        </div>
                        <!-- END HEADER TOP -->
                        <!-- BEGIN HEADER MENU -->
                        <div class="page-header-menu">
                            <div class="container-fluid">
                                <!-- BEGIN HEADER SEARCH BOX -->
                                <form class="search-form" action="/Trabajo/Historial/" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Buscar folio" name="folio">
                                        <span class="input-group-btn">
                                            <a href="javascript:;" class="btn submit">
                                                <i class="icon-magnifier"></i>
                                            </a>
                                        </span>
                                    </div>
                                </form>
                                <!-- END HEADER SEARCH BOX -->
                                <!-- BEGIN MEGA MENU -->
                                <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
                                <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
                                <div class="hor-menu  ">
                                    <ul class="nav navbar-nav">
	                                    <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                            <a href="javascript:;"><i class="fa fa-industry"></i> <?php echo $faenas_permiso[$faena_session]; ?> <i class="fa fa-angle-down"></i>
                                            </a>
                                            <ul class="dropdown-menu pull-left">
	                                            <?php foreach ($faenas_permiso as $key => $value) { ?>
                                                <li aria-haspopup="true" class="dropdown ">
                                                    <a href="/Permiso/Faena/<?php echo $key;?>" class="nav-link nav-toggle ">
                                                        <?php echo $value;?>
                                                        <span class="arrow"></span>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                            </ul>
	                                    </li>
	                                    
                                        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                                            <a href="javascript:;"><i class="fa fa-bars"></i> Menú Principal <i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu pull-left">
	                                            
	                                            <?php if ($app->check_permissions_menu_item("CategoriaTecnico","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("LugarReparacion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Periodo","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Solucion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("TipoFallaElemento","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Vista","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Turno","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("CargaMasiva","Usuario",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("CuadroMando","Index",$faena_session,$usuario_id,$cargos) == TRUE || 
															$app->check_permissions_menu_item("Usuario","Index",$faena_session,$usuario_id,$cargos) == TRUE || 
															$app->check_permissions_menu_item("Cargo","Index",$faena_session,$usuario_id,$cargos) == TRUE  ||
															$app->check_permissions_menu_item("Contratos","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("EstadosEquipo","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("EstadosMotor","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("EstadosMotorInstalacion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Faena","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Flota","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Motor","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("TipoSalida","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Unidad","Aprobaciones",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Unidad","Pendientes",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Unidad","Complemento",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("FaenaFlota","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Unidad","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Fluidos","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("FluidoUnidad","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("FluidoIngreso","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Administracion","Folio",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("CargaMasiva","Diagnostico",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("CargaMasiva","Elemento",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("CargaMasiva","Pool",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("CargaMasiva","Sistema",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Administracion","Imagenes",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Matrices","Motor",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Configuracion","ReporteCorreo",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("CategoriaSintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("MotivoLlamado","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("Sintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("CargaMasiva","Sintoma",$faena_session,$usuario_id,$cargos) == TRUE ||
															$app->check_permissions_menu_item("MotivoCategoriaSintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE || 
															$app->check_permissions_menu_item("Administracion","Logs",$faena_session,$usuario_id,$cargos) == TRUE || 
															$app->check_permissions_menu_item("Administracion","Aws",$faena_session,$usuario_id,$cargos) == TRUE
			                                                ) { ?>
		                                        <li aria-haspopup="true" class="dropdown-submenu">
                                                    <a href="javascript:;" class="nav-link nav-toggle ">
                                                        <i class="fa fa-"></i> Administración
                                                        <span class="arrow"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
	                                                    <?php if ($app->check_permissions_menu_item("CargaMasiva","Usuario",$faena_session,$usuario_id,$cargos) == TRUE ||
		                                                    $app->check_permissions_menu_item("CuadroMando","Index",$faena_session,$usuario_id,$cargos) == TRUE || 
		                                                    $app->check_permissions_menu_item("Usuario","Index",$faena_session,$usuario_id,$cargos) == TRUE || 
		                                                    $app->check_permissions_menu_item("Cargo","Index",$faena_session,$usuario_id,$cargos) == TRUE  
		                                                    ) { ?>
	                                                    <li aria-haspopup="true" class="dropdown-submenu">
	                                                        <a href="javascript:;" class="nav-link ">Dotación
	                                                            <span class="arrow"></span>
	                                                        </a>
	                                                        <ul class="dropdown-menu">
		                                                        <?php if ($app->check_permissions_menu_item("CargaMasiva","Usuario",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/CargaMasiva/Usuario" class="nav-link ">Carga Masiva</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("CuadroMando","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/CuadroMando" class="nav-link ">Cuadro de mando</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("Usuario","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/Usuario" class="nav-link ">Dotación</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("Cargo","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class="dropdown-submenu">
		                                                            <a href="#" class="nav-link ">Tablas
			                                                            <span class="arrow"></span>
		                                                            </a>
		                                                            <ul class="dropdown-menu">
			                                                            <?php if ($app->check_permissions_menu_item("Cargo","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Cargo" class="nav-link ">Cargos</a>
				                                                        </li>
				                                                        <?php } ?>
		                                                            </ul>
		                                                        </li>
		                                                        <?php } ?>
		                                                    </ul>
	                                                    </li>
	                                                    <?php } ?>
	                                                    
	                                                    
	                                                    <?php if ($app->check_permissions_menu_item("Contratos","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("EstadosEquipo","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("EstadosMotor","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("EstadosMotorInstalacion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Faena","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Flota","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Motor","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("TipoSalida","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Unidad","Aprobaciones",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Unidad","Pendientes",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Unidad","Complemento",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("FaenaFlota","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Unidad","Index",$faena_session,$usuario_id,$cargos) == TRUE
			                                                        ) { ?>
	                                                    <li aria-haspopup="true" class="dropdown-submenu">
	                                                        <a href="javascript:;" class="nav-link ">Estado de motores
	                                                            <span class="arrow"></span>
	                                                        </a>
	                                                        <ul class="dropdown-menu">
		                                                        <?php if ($app->check_permissions_menu_item("Unidad","Aprobaciones",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        	  $app->check_permissions_menu_item("Unidad","Pendientes",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
																<li aria-haspopup="true" class="dropdown-submenu">
		                                                            <a href="javascript:;" class="nav-link ">Aprobaciones
			                                                            <span class="arrow"></span>
		                                                            </a>
		                                                            <ul class="dropdown-menu">
			                                                            <?php if ($app->check_permissions_menu_item("Unidad","Aprobaciones",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Unidad/Aprobaciones" class="nav-link ">Montajes/Desmontajes</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("Unidad","Pendientes",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Unidad/Pendientes" class="nav-link ">Unidades Nuevas</a>
				                                                        </li>
				                                                        <?php } ?>
		                                                            </ul>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("Unidad","Complemento",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/Unidad/Complemento" class="nav-link ">Complementos datos</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("FaenaFlota","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/FaenaFlota" class="nav-link ">Flotas</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("Contratos","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("EstadosEquipo","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("EstadosMotor","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("EstadosMotorInstalacion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Faena","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Flota","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Motor","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("TipoSalida","Index",$faena_session,$usuario_id,$cargos) == TRUE
			                                                        ) { ?>
		                                                        <li aria-haspopup="true" class="dropdown-submenu">
		                                                            <a href="javascript:;" class="nav-link ">Tablas
			                                                            <span class="arrow"></span>
		                                                            </a>
		                                                            <ul class="dropdown-menu">
			                                                            <?php if ($app->check_permissions_menu_item("Contratos","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Contratos" class="nav-link ">Contratos</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("EstadosEquipo","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/EstadosEquipo" class="nav-link ">Estados equipo</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("EstadosMotor","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/EstadosMotor" class="nav-link ">Estados motor</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("EstadosMotorInstalacion","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/EstadosMotorInstalacion" class="nav-link ">Estados motor instalación</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("Faena","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Faena" class="nav-link ">Faenas</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("Flota","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Flota" class="nav-link ">Flotas</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("Motor","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Motor" class="nav-link ">Motores</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("TipoSalida","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/TipoSalida" class="nav-link ">Tipos de salida</a>
				                                                        </li>
				                                                        <?php } ?>
		                                                            </ul>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("Unidad","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/Unidad" class="nav-link ">Unidades</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                    </ul>
	                                                    </li>
	                                                    <?php } ?>
	                                                    
	                                                    
	                                                    <?php if ($app->check_permissions_menu_item("Fluidos","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
		                                                    	  $app->check_permissions_menu_item("FluidoUnidad","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
																  $app->check_permissions_menu_item("FluidoIngreso","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
	                                                    <li aria-haspopup="true" class="dropdown-submenu">
	                                                        <a href="javascript:;" class="nav-link ">Fluidos
	                                                            <span class="arrow"></span>
	                                                        </a>
	                                                        <ul class="dropdown-menu">
		                                                        <?php if ($app->check_permissions_menu_item("Fluidos","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/Fluidos" class="nav-link ">Matriz Fluidos</a>
		                                                        </li>
																<?php } ?>
																<?php if ($app->check_permissions_menu_item("FluidoUnidad","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
																		  $app->check_permissions_menu_item("FluidoIngreso","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
																<li aria-haspopup="true" class="dropdown-submenu">
		                                                            <a href="#" class="nav-link ">Tablas
			                                                            <span class="arrow"></span>
		                                                            </a>
		                                                            <ul class="dropdown-menu">
			                                                            <?php if ($app->check_permissions_menu_item("FluidoUnidad","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/FluidoUnidad" class="nav-link ">Unidades</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("FluidoIngreso","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/FluidoIngreso" class="nav-link ">Tipo ingreso</a>
				                                                        </li>
				                                                        <?php } ?>
		                                                            </ul>
		                                                        </li>
		                                                        <?php } ?>
		                                                    </ul>
	                                                    </li>
	                                                    <?php } ?>
	                                                    
	                                                    
	                                                    <?php if ($app->check_permissions_menu_item("Administracion","Folio",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
	                                                    <li aria-haspopup="true" class="">
	                                                        <a href="/Administracion/Folio" class="nav-link ">Folios
	                                                        </a>
	                                                    </li>
	                                                    <?php } ?>
	                                                    
	                                                    
	                                                    <?php if ($app->check_permissions_menu_item("CargaMasiva","Diagnostico",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                       $app->check_permissions_menu_item("CargaMasiva","Elemento",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                       $app->check_permissions_menu_item("CargaMasiva","Pool",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                       $app->check_permissions_menu_item("CargaMasiva","Sistema",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                       $app->check_permissions_menu_item("Administracion","Imagenes",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                       $app->check_permissions_menu_item("Matrices","Motor",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
	                                                    <li aria-haspopup="true" class="dropdown-submenu">
	                                                        <a href="javascript:;" class="nav-link ">Motor
	                                                            <span class="arrow"></span>
	                                                        </a>
	                                                        <ul class="dropdown-menu">
		                                                       	<?php if ($app->check_permissions_menu_item("CargaMasiva","Diagnostico",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                       $app->check_permissions_menu_item("CargaMasiva","Elemento",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                       $app->check_permissions_menu_item("CargaMasiva","Pool",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                       $app->check_permissions_menu_item("CargaMasiva","Sistema",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
																<li aria-haspopup="true" class="dropdown-submenu">
		                                                            <a href="#" class="nav-link ">Carga Masiva
			                                                            <span class="arrow"></span>
		                                                            </a>
		                                                            <ul class="dropdown-menu">
			                                                            <?php if ($app->check_permissions_menu_item("CargaMasiva","Diagnostico",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class="">
				                                                            <a href="/CargaMasiva/Diagnostico" class="nav-link ">Diagnostico</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("CargaMasiva","Elemento",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/CargaMasiva/Elemento" class="nav-link ">Elemento</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("CargaMasiva","Pool",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/CargaMasiva/Pool" class="nav-link ">Pool</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("CargaMasiva","Sistema",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/CargaMasiva/Sistema" class="nav-link ">Sistema</a>
				                                                        </li>
				                                                        <?php } ?>
		                                                            </ul>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("Administracion","Imagenes",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class="">
		                                                            <a href="/Administracion/Imagenes" class="nav-link ">Imágenes</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("Matrices","Motor",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class="">
		                                                            <a href="/Matrices/Motor" class="nav-link ">Matriz Motor</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                    </ul>
	                                                    </li>
	                                                    <?php } ?>
	                                                    
	                                                    
	                                                    <?php if ($app->check_permissions_menu_item("Configuracion","ReporteCorreo",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
	                                                    <li aria-haspopup="true" class="dropdown-submenu">
	                                                        <a href="javascript:;" class="nav-link ">Reportes
	                                                            <span class="arrow"></span>
	                                                        </a>
	                                                        <ul class="dropdown-menu">
		                                                        <?php if ($app->check_permissions_menu_item("Configuracion","ReporteCorreo",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
																<li aria-haspopup="true">
		                                                            <a href="/Configuracion/ReporteCorreo" class="nav-link">Reporte por correo</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                    </ul>
	                                                    </li>
	                                                    <?php } ?>
	                                                    
	                                                    
	                                                    <?php if ($app->check_permissions_menu_item("CategoriaSintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("MotivoLlamado","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Sintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("CargaMasiva","Sintoma",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("MotivoCategoriaSintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
	                                                    <li aria-haspopup="true" class="dropdown-submenu">
	                                                        <a href="javascript:;" class="nav-link ">Sintoma
	                                                            <span class="arrow"></span>
	                                                        </a>
	                                                        <ul class="dropdown-menu">
		                                                        <?php if ($app->check_permissions_menu_item("CargaMasiva","Sintoma",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/CargaMasiva/Sintoma" class="nav-link ">Carga Masiva</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("MotivoCategoriaSintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/MotivoCategoriaSintoma" class="nav-link ">Matriz Sintoma</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("CategoriaSintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("MotivoLlamado","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Sintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
																<li aria-haspopup="true" class="dropdown-submenu">
		                                                            <a href="javascript:;" class="nav-link ">Tablas
			                                                            <span class="arrow"></span>
		                                                            </a>
		                                                            <ul class="dropdown-menu">
			                                                            <?php if ($app->check_permissions_menu_item("CategoriaSintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/CategoriaSintoma" class="nav-link ">Categoría síntoma</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("MotivoLlamado","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/MotivoLlamado" class="nav-link ">Motivo de llamado</a>
				                                                        </li>
																		<?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("Sintoma","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Sintoma" class="nav-link ">Sintoma</a>
				                                                        </li>
				                                                        <?php } ?>
		                                                            </ul>
		                                                        </li>
		                                                        <?php } ?>
		                                                    </ul>
	                                                    </li>
	                                                    <?php } ?>
	                                                    
	                                                    
														<?php if ($app->check_permissions_menu_item("CategoriaTecnico","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("LugarReparacion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Periodo","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Solucion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("TipoFallaElemento","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Vista","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Turno","Index",$faena_session,$usuario_id,$cargos) == TRUE
			                                                        ) { ?>
	                                                    <li aria-haspopup="true" class="dropdown-submenu">
	                                                        <a href="javascript:;" class="nav-link ">Tablas
	                                                            <span class="arrow"></span>
	                                                        </a>
	                                                        <ul class="dropdown-menu">
		                                                        <?php if ($app->check_permissions_menu_item("CategoriaTecnico","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("LugarReparacion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Periodo","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Solucion","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("TipoFallaElemento","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
			                                                        $app->check_permissions_menu_item("Turno","Index",$faena_session,$usuario_id,$cargos) == TRUE
			                                                        ) { ?>
																<li aria-haspopup="true" class="dropdown-submenu">
		                                                            <a href="javascript:;" class="nav-link ">DBM
			                                                            <span class="arrow"></span>
		                                                            </a>
		                                                            <ul class="dropdown-menu">
			                                                            <?php if ($app->check_permissions_menu_item("CategoriaTecnico","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/CategoriaTecnico" class="nav-link ">Categoría Técnicos</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("LugarReparacion","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/LugarReparacion" class="nav-link ">Lugar de reparación</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("Periodo","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Periodo" class="nav-link ">Periodo</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("Solucion","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Solucion" class="nav-link ">Solución</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("TipoFallaElemento","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/TipoFallaElemento" class="nav-link ">Tipo cambio componente</a>
				                                                        </li>
				                                                        <?php } ?>
				                                                        <?php if ($app->check_permissions_menu_item("Turno","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
				                                                        <li aria-haspopup="true" class=" ">
				                                                            <a href="/Turno" class="nav-link ">Turno</a>
				                                                        </li>
				                                                        <?php } ?>
		                                                            </ul>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("Vista","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/Vista" class="nav-link ">Vistas</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                    </ul>
	                                                    </li>
	                                                    <?php } ?>
	                                                    
														<?php if ($app->check_permissions_menu_item("Administracion","Logs",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
	                                                    <li aria-haspopup="true" class="">
	                                                        <a href="/Administracion/Logs" class="nav-link ">Logs Internos
	                                                        </a>
	                                                    </li>
	                                                    <?php } ?>
														
														<?php if ($app->check_permissions_menu_item("Administracion","Aws",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
	                                                    <li aria-haspopup="true" class="">
	                                                        <a href="/Administracion/Aws" class="nav-link ">Mensajes Aws
	                                                        </a>
	                                                    </li>
	                                                    <?php } ?>
                                                    </ul>
                                                </li>
                                                <?php } ?>
                                                <!-- Fin de menu administracion -->
                                                
                                                
                                                <?php if ($app->check_permissions_menu_item("Backlog","Index",$faena_session,$usuario_id,$cargos) == TRUE ||
		                                                  $app->check_permissions_menu_item("CargaMasiva","BacklogSpecto",$faena_session,$usuario_id,$cargos) == TRUE ||
	                                                      $app->check_permissions_menu_item("CargaMasiva","BacklogWeb",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                <li aria-haspopup="true" class="dropdown-submenu ">
                                                    <a href="javascript:;" class="nav-link nav-toggle "> 
                                                        <i class="fa fa-"></i> Backlog
                                                        <span class="arrow"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
	                                                    <?php if ($app->check_permissions_menu_item("Backlog","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                        <li aria-haspopup="true" class=" ">
                                                            <a href="/Backlog" class="nav-link ">Backlog</a>
                                                        </li>
                                                        <?php } ?>
                                                        <?php if ($app->check_permissions_menu_item("CargaMasiva","BacklogSpecto",$faena_session,$usuario_id,$cargos) == TRUE ||
	                                                        	  $app->check_permissions_menu_item("CargaMasiva","BacklogWeb",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                        <li aria-haspopup="true" class="dropdown-submenu">
                                                            <a href="#" class="nav-link ">Carga masiva
	                                                            <span class="arrow"></span>
                                                            </a>
                                                            <ul class="dropdown-menu">
	                                                            <?php if ($app->check_permissions_menu_item("CargaMasiva","BacklogSpecto",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class=" ">
		                                                            <a href="/CargaMasiva/BacklogSpecto/" class="nav-link ">Backlog Specto</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                        <?php if ($app->check_permissions_menu_item("CargaMasiva","BacklogWeb",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
		                                                        <li aria-haspopup="true" class="">
		                                                            <a href="/CargaMasiva/BacklogWeb/" class="nav-link ">Backlog web</a>
		                                                        </li>
		                                                        <?php } ?>
		                                                    </ul>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </li>
                                                <?php } ?>
                                                 
                                                 
                                                <?php if ($app->check_permissions_menu_item("Dashboard","Index",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                <li aria-haspopup="true" class="dropdown ">
                                                    <a href="/Dashboard" class="nav-link nav-toggle ">
                                                        <i class="fa fa-"></i> Dashboard
                                                        <span class="arrow"></span> 
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                
                                                
                                                <?php if ($app->check_permissions_menu_item("Unidad","Crear",$faena_session,$usuario_id,$cargos) == TRUE ||
	                                                	  $app->check_permissions_menu_item("Unidad","Desmontajes",$faena_session,$usuario_id,$cargos) == TRUE ||
														  $app->check_permissions_menu_item("Unidad","Montajes",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                <li aria-haspopup="true" class="dropdown-submenu ">
                                                    <a href="javascript:;" class="nav-link nav-toggle ">
                                                        <i class="fa fa-"></i> Estado de motores
                                                        <span class="arrow"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
	                                                    <?php if ($app->check_permissions_menu_item("Unidad","Crear",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                        <li aria-haspopup="true" class=" ">
                                                            <a href="/Unidad/Crear" class="nav-link ">Crear Equipo</a>
                                                        </li>
                                                        <?php } ?>
                                                        <?php if ($app->check_permissions_menu_item("Unidad","Desmontajes",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                        <li aria-haspopup="true" class=" ">
                                                            <a href="/Unidad/Desmontajes" class="nav-link ">Desmontar/Detener</a>
                                                        </li>
                                                        <?php } ?>
                                                        <?php if ($app->check_permissions_menu_item("Unidad","Montajes",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                        <li aria-haspopup="true" class=" ">
                                                            <a href="/Unidad/Montajes" class="nav-link ">Montar/Reactivar</a>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </li>
                                                <?php } ?>
                                                
                                                
                                                <?php if ($app->check_permissions_menu_item("Trabajo","Historial",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                <li aria-haspopup="true" class="dropdown ">
                                                    <a href="/Trabajo/Historial" class="nav-link nav-toggle ">
                                                        <i class="fa fa-"></i> Historial
                                                        <span class="arrow"></span>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                
                                                
                                                <?php if ($app->check_permissions_menu_item("Trabajo","Planificar",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                <li aria-haspopup="true" class="dropdown ">
                                                    <a href="/Trabajo/Planificar" class="nav-link nav-toggle ">
                                                        <i class="fa fa-"></i> Planificar
                                                        <span class="arrow"></span>
                                                    </a>
                                                </li>
                                                <?php } ?>
                                                
                                                
                                                <?php if ($app->check_permissions_menu_item("Reporte","Base",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                <li aria-haspopup="true" class="dropdown-submenu ">
                                                    <a href="javascript:;" class="nav-link nav-toggle ">
                                                        <i class="fa fa-"></i> Reportes
                                                        <span class="arrow"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
	                                                    <?php if ($app->check_permissions_menu_item("Reporte","Base",$faena_session,$usuario_id,$cargos) == TRUE) { ?>
                                                        <li aria-haspopup="true" class=" ">
                                                            <a href="/Reporte/Base" class="nav-link ">Reporte base</a>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </li>
                                                <?php } ?>
                                                
                                                
                                                <li aria-haspopup="true" class="dropdown ">
                                                    <a href="/Logout" class="nav-link nav-toggle ">
                                                        <i class="fa fa-"></i> Salir
                                                        <span class="arrow"></span>
                                                    </a>
                                                </li>
                                                
                                                
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <!-- END MEGA MENU -->
                            </div>
                        </div>
                        <!-- END HEADER MENU -->
                    </div>
                    <!-- END HEADER -->
                </div>
            </div>
             
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <!-- BEGIN CONTAINER -->
                    <div class="page-container">
                        <!-- BEGIN CONTENT -->
                        <div class="page-content-wrapper">
                            <!-- BEGIN CONTENT BODY -->
                            <!-- BEGIN PAGE HEAD-->
                            <div class="page-head">
                                <div class="container-fluid">
                                    <!-- BEGIN PAGE TITLE -->
                                    <div class="page-title">
                                        <h1> <?php if (isset($titulo)) { echo $titulo;}?> </h1>
                                    </div>
                                    <!-- END PAGE TITLE -->
                                </div>
                            </div>
                            <!-- END PAGE HEAD-->
                            <!-- BEGIN PAGE CONTENT BODY -->
                            <div class="page-content">
                                <div class="container-fluid">
                                    <!-- BEGIN PAGE BREADCRUMBS -->
                                    <ul class="page-breadcrumb breadcrumb">
                                        <li>
                                            <a href="/Dashboard">DBM</a>
                                            <i class="fa fa-circle"></i>
                                        </li>
                                        <li>
                                            <span><?php if (isset($breadcrumb)) { echo $breadcrumb;}?></span>
                                        </li>
                                    </ul>
                                    <!-- END PAGE BREADCRUMBS -->
                                    <!-- BEGIN PAGE CONTENT INNER -->
                                    <div class="page-content-inner">
										<?php echo $this->Session->flash(); ?>
										<?php echo $this->fetch('content'); ?>
                                    </div>
                                    <!-- END PAGE CONTENT INNER -->
                                </div>
                            </div>
                            <!-- END PAGE CONTENT BODY -->
                            <!-- END CONTENT BODY -->
                        </div>
                        <!-- END CONTENT -->
                    </div>
                    <!-- END CONTAINER -->
                </div>
            </div>
            <div class="page-wrapper-row">
                <div class="page-wrapper-bottom">
                    <!-- BEGIN FOOTER -->
                    <!-- BEGIN INNER FOOTER -->
                    <div class="page-footer">
                        <div class="container-fluid"> Logueado a través de Google con correo <strong><?php echo $me_email;?></strong> | Faena seleccionada <strong><?php echo $faenas_permiso[$faena_session]; ?></strong>
                        </div>
                    </div>
                    <div class="scroll-to-top">
                        <i class="icon-arrow-up"></i>
                    </div>
                    <!-- END INNER FOOTER -->
                    <!-- END FOOTER -->
                </div>
            </div>
        </div>
        <!--[if lt IE 9]>
<script src="/assets/global/plugins/respond.min.js"></script>
<script src="/assets/global/plugins/excanvas.min.js"></script> 
<script src="/assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="/assets/global/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>				
		<link rel="stylesheet" href="/assets/global/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css" type="text/css"/>
		<script type="text/javascript" src="/assets/global/plugins/bootstrap-select/js/bootstrap-select.js"></script>
		<link rel="stylesheet" href="/assets/global/plugins/bootstrap-select/css/bootstrap-select.css" type="text/css"/>
		<script src="/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="/assets/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
        <script src="/assets/layouts/layout3/scripts/demo.min.js" type="text/javascript"></script>
        <script src="/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="/assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        
        <script src="/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
        <script src="/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
        <script src="/assets/apps/scripts/jquery.validate.min.js" type="text/javascript"></script>
        
       
        <!-- END THEME LAYOUT SCRIPTS -->
        <script>
            $(document).ready(function(){
	            <?php if(isset($script)) { echo $script;} ?>
	            
	            /**
				* Agregado por Victor Smith
				*/
				$("#no_option_detail").click( () => {  
					if( $("#no_option_detail").is(':checked') ) {  
						$('.detail-section-added').removeClass('hidden')
						$('.help-block-detail').addClass('hidden')
						evaluarRequired( true )
					} 
				}); 
				/**
				* 1) se valida el agregar o eliminar clase hidden
				*/
				$("#yes_option_detail").click( () => {  
					if( $("#yes_option_detail").is(':checked') && ! $('.detail-section-added').hasClass('hidden') ) {  
						$('.detail-section-added').addClass('hidden')
					} 
					evaluarRequired( false )
					$('.help-block-detail').addClass('hidden')
				});
	            
				$('#select-multiple').multiselect();
				
				$('.selectpicker').selectpicker({
					size: 10
				});
				
				$("#faena_id").change(function(){
					if($(this).val() != '') {
						console.log("faena selec");
						if($("#flota_id option") != undefined) {
							console.log("flota not null");
							var faena_id = $(this).val();
							$("#flota_id option").hide();
							$("#flota_id option[value='']").show();
							$("#flota_id option[faena_id='"+faena_id+"']").show();
							$("#flota_id").removeAttr("disabled");
							$("#flota_id").val("");
							console.log("flota hide");
							$("#unidad_id").val("");
							$("#unidad_id option[value!='']").hide();
							
						}
						if($("#supervisor_responsable option") != undefined) {
							$("#supervisor_responsable option").hide();
							$("#supervisor_responsable option[value='']").show();
							$("#supervisor_responsable").val("");
							$("#supervisor_responsable option[faena_id='"+faena_id+"']").show();
						}
					}else{
						console.log("todas faena");
						if($("#flota_id") != undefined) {
							$("#flota_id").val("");
							$("#unidad_id").val("");
							$("#unidad_id option[value!='']").hide();
							$("#flota_id option[value!='']").hide();
						}
						//if($("#supervisor_responsable") != undefined) {
							$("#supervisor_responsable").val("");
							$("#supervisor_responsable option[value!='']").hide();
						//}
					}
					$("#imagen-elemento").attr("src","");
				});
				
				$("#flota_id").change(function(){
					if($("#faena_id").val() != '' && $("#flota_id").val() != '') {
						var faena_flota = $("#flota_id").val();
						$("#unidad_id option").hide();
						$("#unidad_id option[value='']").show();
						$("#unidad_id option[faena_flota='"+faena_flota+"']").show();
						$("#unidad_id").removeAttr("disabled");
						$("#unidad_id").val("");
					}else{
						$("#unidad_id").val("");
						$("#unidad_id option[value!='']").hide();
					}
					$("#imagen-elemento").attr("src","");
				});
				
				$("#supervisor_responsable option[value!='']").hide();
				$("#flota_id option[value!='']").hide();
				$("#unidad_id option[value!='']").hide();
				
				$("#tipo_evento").change(function(){
					$("#tipointervencion option").hide();
					$("#tipointervencion option[value='']").show();
					$("#tipointervencion").val("");
					if($("#tipo_evento").val() == 'PR') {
						$("#tipointervencion option[value='RP']").show();
						$("#tipointervencion option[value='OP']").show();
						$("#tipointervencion option[value='MP']").show();
					}
					if($("#tipo_evento").val() == 'NP') {
						$("#tipointervencion option[value='RI']").show();
						$("#tipointervencion option[value='EX']").show();
						$("#tipointervencion option[value='OP']").show();
					}
					if($("#tipo_evento").val() == '') {
						$("#tipointervencion option").show();
					}
				});
				
				<?php
					if(isset($faena_id) && $faena_id != '' && $faena_id != null) {
				?>
					$("#faena_id").val('<?php echo $faena_id;?>');
					$("#faena_id").change();
				<?php
					}
				?>
				<?php
					if(isset($flota_id) && isset($faena_id) && $faena_id != '' && $flota_id != '') {
				?>
					$("#flota_id").val('<?php echo $faena_id;?>_<?php echo $flota_id;?>');
					$("#flota_id").change();
				<?php
					}
				?>
				<?php
					if(isset($unidad_id) && isset($flota_id) && isset($faena_id) && $faena_id != '' && $flota_id != '' && $unidad_id != '') {
				?>
					$("#unidad_id").val('<?php echo $faena_id;?>_<?php echo $flota_id;?>_<?php echo $unidad_id;?>');
					$("#unidad_id").change();
				<?php
					}
				?>
				<?php
					if(isset($faena_id) && $faena_id != '' && isset($supervisor_responsable) && $supervisor_responsable != '') {
				?>
					$("#supervisor_responsable").val('<?php echo $faena_id;?>_<?php echo $supervisor_responsable;?>');
					$("#supervisor_responsable").change();
				<?php
					}
				?>
				<?php
					if(isset($tipo_evento)) {
				?>
					$("#tipo_evento").val('<?php echo $tipo_evento;?>');
					$("#tipo_evento").change();
				<?php
					}
				?>
				
				<?php
					if(isset($tipointervencion)) {
				?>
					$("#tipointervencion").val('<?php echo $tipointervencion;?>');
					$("#tipointervencion").change();
				<?php
					}
				?>
				
				$(".cm_cargos").change(function(){
					var cargo = $(this).attr("cargo");
					if(this.checked) {
						$(".cargo_" + cargo).removeAttr("disabled");
					} else {
						$(".cargo_" + cargo).attr("disabled", "disabled");
					}
				});
				
				$(".cm_cargos").change();
				
				$("#cambio_contrato").change(function(){
					if(this.checked) {
						$("#tipo_nuevo_contrato_id").removeAttr("disabled");
					} else {
						$("#tipo_nuevo_contrato_id").attr("disabled", "disabled");
					}
				});
				
				$("#cambio_contrato").change();
				
				$("#termino_contrato").change(function(){
					if(this.checked) {
						$("#cambio_contrato").prop('checked', false);
						$("#cambio_contrato").change();
						$("#cambio_contrato").attr("disabled", "disabled");
					} else {
						$("#cambio_contrato").removeAttr("disabled");
					}
				});
				
				$("#termino_contrato").change();
				
				$("#hr_operadas_motor").change(function(){
					if(!isNaN($(this).val()) && $(this).val().trim() != "") {
						var hr_operadas_motor = parseFloat($(this).val());
						var hr_motor_instalacion = parseFloat($("#hr_motor_instalacion").val());
						var hr_historico_motor_base = parseFloat($("#hr_historico_motor_base").val());
						$("#hr_acumuladas_motor").val(hr_operadas_motor + hr_motor_instalacion);
						$("#hr_historico_motor").val(hr_historico_motor_base + hr_operadas_motor);
					} else {
						$("#hr_acumuladas_motor").val($("#hr_motor_instalacion").val());
						$("#hr_historico_motor").val($("#hr_historico_motor_base").val());
					}
				});
				
				var seleccion_masiva = function (){
					if(this.checked) {
						$(".check-option[class!='seleccion-masiva']").each(function(){
							if($(this).closest('tr').is(":visible")){
								$(this).prop('checked', true);
							} else {
								$(this).prop('checked', false);
							}
						});
					} else {
						$(".check-option[class!='seleccion-masiva']").each(function(){
							if($(this).closest('tr').is(":visible")){
								$(this).prop('checked', false);
							} else {
								$(this).prop('checked', true);
							}
						});
					}
				}
				
				$(".filtro-carga-masiva-usuario").click(function(){
					var estado_id = $("#estado_id").val();
					var cargo_id = $("#cargo_id").val();
					var faena_id = $("#faena_id").val();
					$(".file_row").show();
					if(estado_id != '') {
						$(".file_row[estado_id!='"+estado_id+"']").hide();
					}
					if(cargo_id != '') {
						$(".file_row[cargo_id!='"+cargo_id+"']").hide();
					}
					if(faena_id != '') {
						$(".file_row[faena_id!='"+faena_id+"']").hide();
					}
					$(".seleccion-masiva").prop('checked', false);
				})
				
				$(".filtro-carga-masiva-usuario-limpiar").click(function(){
					$("#estado_id").val("");
					$("#cargo_id").val("");
					$("#faena_id").val("");
					$(".file_row").show();
					$(".seleccion-masiva").prop('checked', false);
				})
				
				$(".seleccion-masiva").click(function(){
					if(this.checked) {
						$(".check-option[class!='seleccion-masiva']").each(function(){
							if($(this).closest('.file_row').is(":visible")){
								$(this).prop('checked', true);
							}
							if($(this).closest('.data_row').is(":visible")){
								$(this).prop('checked', true);
							}
						});
					} else {
						$(".check-option[class!='seleccion-masiva']").each(function(){
							if($(this).closest('.file_row').is(":visible")){
								$(this).prop('checked', false);
							}
							if($(this).closest('.data_row').is(":visible")){
								$(this).prop('checked', false);
							}
						});
					}
				});
				
				$(".selecionar-todos").change(function(){
					if(this.checked) {
						$(".check-option[class!='selecionar-todos']").each(function(){
							if($(this).closest('tr').is(":visible")){
								$(this).prop('checked', true);
							} else {
								$(this).prop('checked', false);
							}
						});
					} else {
						$(".check-option[class!='selecionar-todos']").each(function(){
							if($(this).closest('tr').is(":visible")){
								$(this).prop('checked', false);
							} else {
								$(this).prop('checked', true);
							}
						});
					}
				});
				
				$(".check-unico").change(function(){
					var id = "f"+$(this).attr("id");
					//$(".check-firma").removeAttr("disabled");
					if(this.checked) {
						$(".check-firma[id='"+id+"']").removeAttr("disabled");
						$(".check-firma[id!='"+id+"']").attr("disabled","disabled");
						$(".check-firma[id!='"+id+"']").prop('checked', false);
					} else {
						$(".check-firma[id='"+id+"']").attr("disabled","disabled");
						$(".check-firma[id='"+id+"']").prop('checked', false);
					}
				});
				
				$(".carga-filtrar").click(function(){
					$(".data_row").show();
				
					if($(".motivo_id") != undefined && $(".motivo_id").val() != undefined) {
						var motivo_id = $(".motivo_id").val();
						if(motivo_id != ''){
							$(".data_row[motivo_id!='"+motivo_id+"']").hide();
						}
					}
					
					if($(".categoria_id") != undefined && $(".categoria_id").val() != undefined) {
						var categoria_id = $(".categoria_id").val();
						if(categoria_id != ''){
							$(".data_row[categoria_id!='"+categoria_id+"']").hide();
						}
					}
					
					if($(".sintoma_id") != undefined && $(".sintoma_id").val() != undefined) {
						var sintoma_id = $(".sintoma_id").val();
						if(sintoma_id != ''){
							$(".data_row[sintoma_id!='"+sintoma_id+"']").hide();
						}
					}
					
					if($(".motor_id") != undefined && $(".motor_id").val() != undefined) {
						var motor_id = $(".motor_id").val();
						if(motor_id != ''){
							$(".data_row[motor_id!='"+motor_id+"']").hide();
						}
					}
					
					if($(".sistema_id") != undefined && $(".sistema_id").val() != undefined) {
						var sistema_id = $(".sistema_id").val();
						if(sistema_id != ''){
							$(".data_row[sistema_id!='"+sistema_id+"']").hide();
						}
					}
					
					if($(".subsistema_id") != undefined && $(".subsistema_id").val() != undefined) {
						var subsistema_id = $(".subsistema_id").val();
						if(subsistema_id != ''){
							$(".data_row[subsistema_id!='"+subsistema_id+"']").hide();
						}
					}

					if($(".estado_id") != undefined && $(".estado_id").val() != undefined) {
						var estado_id = $(".estado_id").val();
						if(estado_id != ''){
							$(".data_row[estado_id!='"+estado_id+"']").hide();
						}
					}
					
					if($(".condicion_id") != undefined && $(".condicion_id").val() != undefined) {
						var condicion_id = $(".condicion_id").val().toLowerCase();
						if(condicion_id != ''){
							$(".data_row[condicion_id!='"+condicion_id+"']").hide();
						}
					}
					
					if($(".posicion_id") != undefined && $(".posicion_id").val() != undefined) {
						var posicion_id = $(".posicion_id").val();
						if(posicion_id != ''){
							$(".data_row[posicion_id!='"+posicion_id+"']").hide();
						}
					}
					if($(".elemento_id") != undefined && $(".elemento_id").val() != undefined) {
						var elemento_id = $(".elemento_id").val();
						if(elemento_id != ''){
							$(".data_row[elemento_id!='"+elemento_id+"']").hide();
						}
					}
					if($(".n_id") != undefined && $(".n_id").val() != undefined) {
						var n_id = $(".n_id").val();
						if(n_id != ''){
							n_id = n_id.toLowerCase();
							$(".data_row[n_id!='"+n_id+"']").hide();
						}
					}
					if($(".diagnostico_id") != undefined && $(".diagnostico_id").val() != undefined) {
						var diagnostico_id = $(".diagnostico_id").val();
						if(diagnostico_id != ''){
							$(".data_row[diagnostico_id!='"+diagnostico_id+"']").hide();
						}
					}
					if($(".pool") != undefined && $(".pool").val() != undefined) {
						var pool = $(".pool").val();
						if(pool != ''){
							$(".data_row[pool!='"+pool+"']").hide();
						}
					}
				});
				
				$(".carga-filtrar-limpiar").click(function(){
					$(".data_row").show();
					$(".motor_id").val("");
					$(".sistema_id").val("");
					$(".subsistema_id").val("");
					$(".estado_id").val("");
					$(".condicion_id").val("");
					
					if($(".posicion_id") != undefined && $(".posicion_id").val() != undefined) {
						$(".posicion_id").val("");
					}
					if($(".elemento_id") != undefined && $(".elemento_id").val() != undefined) {
						$(".elemento_id").val("");	
					}
					if($(".n_id") != undefined && $(".n_id").val() != undefined) {
						$(".n_id").val("");
					}
					if($(".diagnostico_id") != undefined && $(".diagnostico_id").val() != undefined) {
						$(".diagnostico_id").val("");
					}
					if($(".pool") != undefined && $(".pool").val() != undefined) {
						$(".pool").val("");
					}
				});
				
				<?php //if (isset($estado_equipo_id) && isset($estado_motor_id) && is_numeric($estado_equipo_id) && is_numeric($estado_motor_id)) { ?>
				if($(".form-desmontaje")!=undefined&&$(".form-desmontaje").val()==2){
					var estado_equipo_id = $(".estado_equipo_id").val();
					var estado_motor_id = $(".estado_motor_id").val();

					$("#estado_equipo_id").change(function(){
						var val = $(this).val();
						if(val==''){
							$("#estado_motor_id option").hide();
							$("#estado_motor_id").attr("disabled","disabled");
						}else{
							$("#estado_motor_id option").show();
							$("#estado_motor_id").removeAttr("disabled");
						}
					});
					
					<?php //if ($estado_equipo_id == 4 && $estado_motor_id == 2) { ?>
					if (estado_equipo_id == 4 && estado_motor_id == 2) {
						$("#estado_motor_id option").show();
						$("#estado_motor_id").val("");
						$("#estado_motor_id option[value='2']").hide();
					}
					<?php //} ?>
					
					<?php //if ($estado_equipo_id == 2 && $estado_motor_id == 2) { ?>
					if (estado_equipo_id == 2 && estado_motor_id == 2) {
						$("#estado_equipo_id option").show();
						$("#estado_equipo_id").val("");
						$("#estado_equipo_id option[value='1']").hide();
						$("#estado_equipo_id").change(function(){
							var val = $(this).val();
							$("#estado_motor_id option").show();
							$("#estado_motor_id").val("");
							if(val == 4) {
							}else if(val == 2) {
								$("#estado_motor_id option[value='2']").hide();
							}
						});
					} 
					<?php //} ?>
					
					<?php //if ($estado_equipo_id == 1 && $estado_motor_id == 2) { ?>
					if (estado_equipo_id == 1 && estado_motor_id == 2) {
						$("#estado_equipo_id option").show();
						$("#estado_equipo_id").val("");
						$("#estado_equipo_id").change(function(){
							var val = $(this).val();
							$("#estado_motor_id option").show();
							$("#estado_motor_id").val("");
							if(val == 4 || val == 2) {
							}else if(val == 1) {
								$("#estado_motor_id option[value='2']").hide();
							}
						});
					}
					<?php //} ?>
					
					$("#estado_equipo_id").change();
				}
				
				if($(".form-montaje")!=undefined&&$(".form-montaje").val()==1){
					var estado_equipo_equipo_id = $(".estado_equipo_equipo_id").val();
					var estado_equipo_motor_id = $(".estado_equipo_motor_id").val();
					var estado_esn_equipo_id = $(".estado_esn_equipo_id").val();
					var estado_esn_motor_id = $(".estado_esn_motor_id").val();

					$("#estado_equipo_id").change(function(){
						var val = $(this).val();
						if(val==''){
							$("#estado_motor_id option").hide();
							$("#estado_motor_id").attr("disabled","disabled");
						}else{
							$("#estado_motor_id option").show();
							$("#estado_motor_id").removeAttr("disabled");
						}
					});
					
					
				}
				<?php //} ?>
				
				$("#intervencion_id").change(function(){
					var val = $(this).val();
					if (val != '' && !isNaN(val)) {
						/*cambio_modulo
						desconexion_realizada
						desconexion_terminada
						conexion_realizada
						conexion_terminada*/
					}
				});
				
				$("#motor_id").change(function(){
					if ($(this).val() != null && $(this).val() != '') {
						var val = $(this).val();
						if($('#sistema_id') != null && $('#sistema_id') != undefined) {
							$("#sistema_id").val(''); 
							$("#sistema_id").change(); 
							var url = "/Matrices/select_sistema/" + val;
							$('#sistema_id').find('option:not(:first)').remove();
							$.getJSON(url, function (data) {
								var select = $('#sistema_id');
								$.each(data, function(i, value) {   
								     select.append($("<option></option>").attr("value",value.Sistema.id).text(value.Sistema.nombre)); 
								});
								<?php if(isset($sistema_id) && isset($motor_id)) { ?> 
								if ($("#motor_id").val() == '<?php echo $motor_id; ?>') {
									$("#sistema_id").val('<?php echo $sistema_id; ?>'); 
									$("#sistema_id").change();
								}
								<?php } ?>
							});
						}
					} else {
						$('#sistema_id').find('option:not(:first)').remove();
						$('#subsistema_id').find('option:not(:first)').remove();
						$('#elemento_id').find('option:not(:first)').remove();
					}
				});
				
				$("#sistema_id").change(function(){
					if ($(this).val() != null && $(this).val() != '') {
						var val = $(this).val();
						var motor_id = $("#motor_id").val();
						//console.log(motor_id);
						//console.log(val);
						if($('#subsistema_id') != null && $('#subsistema_id') != undefined) {
							$("#subsistema_id").val(''); 
							$("#subsistema_id").change();
							$('#subsistema_id').find('option:not(:first)').remove();
							var url = "/Matrices/select_subsistema/"+motor_id+"/" + val;
							$.getJSON(url, function (data) {
								var select = $('#subsistema_id');
								$.each(data, function(i, value) {
								     select.append($("<option></option>").attr("value",value.Subsistema.id).text(value.Subsistema.nombre)); 
								});
								<?php if(isset($sistema_id) && isset($motor_id) && isset($subsistema_id)) { ?> 
								if ($("#motor_id").val() == '<?php echo $motor_id; ?>' && $("#sistema_id").val() == '<?php echo $sistema_id; ?>') {
									$("#subsistema_id").val('<?php echo $subsistema_id; ?>');
									$("#subsistema_id").change();
								}
								<?php } ?>
							});
						}
					} else {
						$('#subsistema_id').find('option:not(:first)').remove();
						$('#elemento_id').find('option:not(:first)').remove();
					}
				});
				
				$("#subsistema_id").change(function(){
					if ($(this).val() != null && $(this).val() != '') {
						var val = $(this).val();
						var motor_id = $("#motor_id").val();
						var sistema_id = $("#sistema_id").val();
						if($('#elemento_id') != null && $('#elemento_id') != undefined) {
							$("#elemento_id").val(''); 
							$("#elemento_id").change();
							$('#elemento_id').find('option:not(:first)').remove();
							var url = "/Matrices/select_elemento/"+motor_id+"/"+sistema_id+"/" + val + "/99999";
							$.getJSON(url, function (data) {
								var select = $('#elemento_id');
								$.each(data, function(i, value) {   
								     select.append($("<option></option>").attr("value",value.Elemento.id).text(value.Elemento.nombre)); 
								});
								<?php if(isset($sistema_id) && isset($motor_id) && isset($subsistema_id) && isset($elemento_id)) { ?> 
								if ($("#motor_id").val() == '<?php echo $motor_id; ?>' && $("#sistema_id").val() == '<?php echo $sistema_id; ?>' && $("#subsistema_id").val() == '<?php echo $subsistema_id; ?>') {
									$("#elemento_id").val('<?php echo $elemento_id; ?>');
									$("#elemento_id").change();
								}
								<?php } ?>
							});
						}
					} else {
						$('#elemento_id').find('option:not(:first)').remove();
					}
				});
				
				<?php if(isset($motor_id)) { ?> 
					$("#motor_id").val('<?php echo $motor_id; ?>'); 
					$("#motor_id").change();
				<?php } ?>
				
				
				/* Funcionamiento /Trabajo/Planificar */
				$("#existe_backlog").change(function(){
					var val = $(this).val();
					$("#backlog_id").val("");
					$("#informacion_backlog").val("");
					var html = '';
					html += "<option value=\"\">Seleccione backlog</opcion>\n";
					$('#backlog_id').selectpicker('refresh');
					if (val == "S") {
						$("#backlog_id").removeAttr("disabled");
						$("#informacion_backlog").removeAttr("disabled");
						/* Se cargan backlogs del equipo */
						var equipo_id = $("#unidad_id").val();
						var data = equipo_id.split("_");
						var faena_id = data[0];
						var flota_id = data[1];
						var unidad_id = data[2];
						
						
						$.get( "/Backlog/select/" + unidad_id, function(data) {
							var obj = $.parseJSON(data);
							
							$.each(obj, function(i, item) {
								var criticidad = "";
								var responsable = "";
								var style = "";
								if (item.Backlog.criticidad_id == "1") {
									criticidad = "Alto";
									style = "danger";
								} else if (item.Backlog.criticidad_id == "2") {
									criticidad = "Medio";
									style = "warning";
								} else if (item.Backlog.criticidad_id == "3") {
									criticidad = "Bajo";
									style = "success";
								}
								if(item.Backlog.responsable_id == "1"){
									responsable = "DCC";
								}else if (item.Backlog.responsable_id == "2"){
									responsable = "OEM";
								}else if (item.Backlog.responsable_id == "3"){
									responsable = "MINA";
								}
								var fecha = new Date(item.Backlog.fecha_creacion);
								fecha = fecha.getDate() + "/" + (fecha.getMonth() + 1) + "/" + fecha.getFullYear();
								
								//html += "<option value=\""+item.Backlog.id+"\" style=\""+style+"\" title=\""+item.Backlog.comentario+"\" sintoma_id="+item.Backlog.sintoma_id+" categoria_sintoma_id="+item.Backlog.categoria_sintoma_id+">"+criticidad+" | </opcion>\n";
							
							
								html += "<option data-content=\"<span class='label label-"+style+"'>"+criticidad+"</span> "+fecha+" | "+ responsable +" | "+item.Sistema.nombre+"\" descripcion=\""+item.Backlog.comentario+"\" sintoma_id="+item.Backlog.sintoma_id+" categoria_sintoma_id="+item.Backlog.categoria_sintoma_id+" value=\""+item.Backlog.id+"\"></option>\n";
							
							});
							$('#backlog_id').html(html);
							$('#backlog_id').selectpicker('refresh');
							<?php if(isset($backlog_id)) { ?>
							$("#backlog_id").val("<?php echo $backlog_id;?>");
							$("#backlog_id").change();
							$('#backlog_id').selectpicker('refresh');
							<?php } ?>
						});
					} else {
						$("#backlog_id").val("");
						$("#informacion_backlog").val("");
						$("#backlog_id").attr("disabled","disabled");
						$("#informacion_backlog").attr("disabled","disabled");
						$('#backlog_id').selectpicker('refresh');
					}
				});
				
				$("#tipointervencion").off();
				
				$("#tipointervencion").change(function(){
					var val = $(this).val();
					
					$("#tipomantencion").val("");
					$("#existe_backlog").val("");
					$("#backlog_id").val("");
					$("#informacion_backlog").val("");
					$("#categoria_sintoma_id").val("");
					$("#sintoma_id").val("");
					
					$("#tipomantencion").attr("disabled","disabled");
					$("#existe_backlog").attr("disabled","disabled");
					$("#backlog_id").attr("disabled","disabled");
					$("#informacion_backlog").attr("disabled","disabled");
					$("#categoria_sintoma_id").attr("disabled","disabled");
					$("#sintoma_id").attr("disabled","disabled");
					$('#backlog_id').selectpicker('refresh');
					
					if (val == "MP") {
						$("#tipomantencion").removeAttr("disabled");
					} else if (val == "OP") {
						$("#existe_backlog").removeAttr("disabled");
						$("#categoria_sintoma_id").removeAttr("disabled");
						$("#sintoma_id").removeAttr("disabled");
					} else if (val == "RP") {
						$("#existe_backlog").removeAttr("disabled");
						$("#categoria_sintoma_id").removeAttr("disabled");
						$("#sintoma_id").removeAttr("disabled");
					}
				});	
				
				$("#backlog_id").change(function(){
					var val = $(this).val();
					if(val != "") {
						var informacion = $(this).find("option:selected").attr("descripcion");
						var sintoma_id = $(this).find("option:selected").attr("sintoma_id");
						var categoria_sintoma_id = $(this).find("option:selected").attr("categoria_sintoma_id");
						$("#informacion_backlog").removeAttr("disabled");
						$("#informacion_backlog").val(informacion);
						if(categoria_sintoma_id!='undefined' && categoria_sintoma_id != 'null' && categoria_sintoma_id != '') {
							$("#categoria_sintoma_id").val(categoria_sintoma_id);
						}
						if(sintoma_id!='undefined' && sintoma_id != 'null' && sintoma_id != '') {
							$("#sintoma_id").val(sintoma_id);
						}
					} else {
						$("#informacion_backlog").val("");
						$("#informacion_backlog").attr("disabled","disabled");
					}
				});
				
				$("#categoria_sintoma_id").change(function(){
					var val = $(this).val();
					$("#sintoma_id").val("");
					$("#sintoma_id option[value!='']").hide();
					if(val != "") {
						$("#sintoma_id option[sintoma_categoria_id='"+val+"']").show();
					}
				});
				
				$("#unidad_id").off();

				$("#unidad_id").change(function(){
					$('#esn').val("");
					var val = $("#unidad_id").val();
					if (val != "") {
						$.get( "/Unidad/get_esn_planificacion/" + val, function(data) {
							$('#esn').val(data);
							if(data!=""){
								$("#esn").removeAttr("disabled");
								$("#esn").attr("readonly","readonly");
							} else {
								$('#esn').val("ESN no ingresado, se completará automáticamente.");
								$("#esn").attr("disabled","disabled");
							}
						});
						//$("#tipointervencion").val("");
						//$("#tipointervencion").change();
						$("#tipointervencion").removeAttr("disabled");
						if($("#existe_backlog").val() == "S") {
							$("#existe_backlog").val("S");
							$("#existe_backlog").change();
							$("#backlog_id").val("");
						}
					} else {
						//$("#tipointervencion").val("");
						//$("#tipointervencion").attr("disabled","disabled");
					}
					var motor_id = $("#unidad_id").find("option:selected").attr("motor_id");
					if(motor_id != '') {
						$("#motor_id").val(motor_id);
					}
					$("#imagen-elemento").attr("src","");
				});
				
				$("#faena_id").change(function(){
					$('#esn').val("");
					//$("#tipointervencion").val("");
					//$("#tipointervencion").change();
					//$("#tipointervencion").attr("disabled","disabled");
				});
				
				$("#flota_id").change(function(){
					$('#esn').val("");
					//$("#tipointervencion").val("");
					//$("#tipointervencion").change();
					//$("#tipointervencion").attr("disabled","disabled");
				});
				
				$(".btn-cancelar-reload").click(function(){
					window.location = "/Trabajo/Planificar";
					return true;
				});
				
				/*$(".btn-guardar").click(function(){
					//1) Enable jQuery validation
					$("#form-planificar").validate({
					  	submitHandler: function(form) {
					    	// do other things for a valid form
							form.submit();
						}
						$('#static').modal('show');
					});
				});
				*/
				$(".btn-validar").click(function(){
					console.log("click validar");
				});
				
				$("#form-planificar").validate({
				  	invalidHandler: function(event, validator) {
					  	console.log("invalidHandler");
					    // 'this' refers to the form
					    var errors = validator.numberOfInvalids();
					    if (errors) {
					      var message = errors == 1
					        ? 'You missed 1 field. It has been highlighted'
					        : 'You missed ' + errors + ' fields. They have been highlighted';
					      $("div.error span").html(message);
					      $("div.error").show();
					    } else {
					      
					    }
					},
					submitHandler: function(form) {
						//form.submit();
						console.log("submitHandler");
						$('#static').modal('show');
					}
				});
				
				
				$(".btn-guardar").click(function(){
					var form = document.getElementById("form-planificar")
					form.submit();
				});
				
				
				/*
				$("#form-planificar").submit(function(){
					console.log("submit");
					return false;
				});*/
				$('#backlog_id').selectpicker();
				$('#static').modal({ show: false});
				$('#static_registrado').modal({ show: false});
				$('#static_desactivar').modal({ show: false});
				$('#static_pregunta_specto').modal({ show: false});
				$('#static_pregunta_web').modal({ show: false});
				
				$(".folio_desaprobar").change(function(){
					var numero = parseInt($(this).attr("correlativo"));
					var total = parseInt($(".trabajos").val());
					if ($(this).is(':checked')) {
						for(var i = numero; i < total + 1; i++){
							if (!$(".desaprobar_" + i).prop('disabled')) {
								$(".desaprobar_" + i).prop("checked", true);
							}
						}
				    } else {
					    $(".folio_desaprobar").prop("checked", false);
				    }
				});
				
				$(".folio_borrar").change(function(){
					var numero = parseInt($(this).attr("correlativo"));
					var total = parseInt($(".trabajos").val());
					if ($(this).is(':checked')) {
						$(".planificar_" + numero).removeAttr("disabled");
						for(var i = numero; i <= total; i++){
							$(".borrar_" + i).prop("checked", true);
						}
				    } else {
					    $(".folio_borrar").prop("checked", false);
					    $(".folio_planificar").prop("checked", false);
					    $(".folio_planificar").attr("disabled","disabled");
				    }
				});
				
				$(".backlog_desactivar").click(function(){
					//$("#correlativo").attr("disabled","disabled");
					//$("#motivo_desactivado").removeAttr("disabled");
					$("#correlativo").val("");
					$('#static_desactivar').modal('show');
				});
				
				$(".backlog_registrado").click(function(){
					//$("#motivo_desactivado").attr("disabled","disabled");
					//$("#correlativo").removeAttr("disabled");
					$("#motivo_desactivado").val("");
					$('#static_registrado').modal('show');
				});
				
				$(".btn-submit").click(function(){
					$("#correlativo").val($("#correlativo_").val());
					$("#motivo_desactivado").val($("#motivo_desactivado_").val());
					$("#form-backlogs").submit();
				});
				
				$(".backlog_agregar_specto").click(function(){
					$('#static_pregunta_specto').modal('show');
				});
				
				$(".backlog_agregar_web").click(function(){
					$('#static_pregunta_web').modal('show');
				});
				
				<?php
					if(isset($unidad_id) && isset($flota_id) && isset($faena_id) && $faena_id != '' && $flota_id != '' && $unidad_id != '') {
				?>
					$("#unidad_id").val('<?php echo $faena_id;?>_<?php echo $flota_id;?>_<?php echo $unidad_id;?>');
					$("#unidad_id").change();
				<?php
					}
				?>
				
				<?php if(isset($planificacion) && $planificacion == 'backlog'){ ?>
					$("#tipointervencion").val("OP");
					$("#tipointervencion").change();
					$("#existe_backlog").val("S");
					$("#existe_backlog").change();
				<?php } ?>
				
				<?php if(@$seccion != "MatrizMotor" && @$seccion != "AdministracionImagenes") { ?>
				$("#sistema_id").off();
				$("#sistema_id").change(function(){
					var sistema_id = $("#sistema_id").find("option:selected").val();
					var motor_id = $("#unidad_id").find("option:selected").attr("motor_id");
					var html = '';
					if (sistema_id != "") {
						html += "<option value=\"\">Seleccione una opción</opcion>\n";
						$.get( "/Select/Subsistema/" + motor_id + "/" + sistema_id, function(data) {
							var obj = $.parseJSON(data);
							$.each(obj, function(i, item) {
								html += "<option value=\""+item.Subsistema.id+"\">"+item.Subsistema.nombre+"</option>\n";
							});
							$("#subsistema_id").html(html);
							$("#subsistema_id").change();
							<?php if(isset($elemento['Sistema']["id"]) && isset($elemento['Subsistema']["id"])) { ?>
							$("#subsistema_id").val('<?php echo $elemento['Subsistema']["id"]; ?>');
							$("#subsistema_id").change();
							<?php } ?>
						});						
					} else {
						html += "<option value=\"\">Seleccione una opción</opcion>\n";
						$("#subsistema_id").html(html);
						$("#subsistema_id").change();
					}
					$("#imagen-elemento").attr("src","");			
				});
				<?php } ?>
				<?php if(isset($elemento['Sistema']["id"]) && isset($elemento['Subsistema']["id"])) { ?>
				$("#sistema_id").val('<?php echo $elemento['Sistema']["id"]; ?>');
				$("#sistema_id").change();
				<?php } ?>
				<?php if(@$seccion != "MatrizMotor" && @$seccion != "AdministracionImagenes") { ?>
				$("#subsistema_id").off();
				$("#subsistema_id").change(function(){
					
					var sistema_id = $("#sistema_id").find("option:selected").val();
					var motor_id = $("#unidad_id").find("option:selected").attr("motor_id");
					var subsistema_id = $("#subsistema_id").find("option:selected").val();
					var html = '';
					if (subsistema_id != "") {
						$.get( "/Select/PosicionesSubsistema/" + motor_id + "/" + sistema_id + "/" + subsistema_id, function(data) {
							html = '<option value=\"\">Seleccione una opción</opcion>\n';
							var obj = $.parseJSON(data);
							$.each(obj, function(i, item) {
								html += "<option value=\""+item.Posicion.id+"\">"+item.Posicion.nombre+"</option>\n";
							});
							$("#subsistema_posicion_id").html(html);
							$("#subsistema_posicion_id").change();
							<?php if(isset($elemento['Subsistema']["id"]) && isset($elemento['PosicionSubsistema']["id"])) { ?>
							$("#subsistema_posicion_id").val('<?php echo $elemento['PosicionSubsistema']["id"]; ?>');
							$("#subsistema_posicion_id").change();
							<?php } ?>
						});						
					} else {
						html = "<option value=\"\">Seleccione una opción</opcion>\n";
						$("#subsistema_posicion_id").html(html);
						$("#subsistema_posicion_id").change();
					}
					
					
					if (subsistema_id != "") {
						$.get( "/Select/Elemento/" + motor_id + "/" + sistema_id + "/" + subsistema_id, function(data) {
							html = '<option value=\"\">Seleccione una opción</opcion>\n';
							var obj = $.parseJSON(data);
							$.each(obj, function(i, item) {
								html += "<option value=\""+item.Elemento.id+"\" codigo="+item.Sistema_Subsistema_Motor_Elemento.codigo+">"+item.Sistema_Subsistema_Motor_Elemento.codigo+" - "+item.Elemento.nombre+"</option>\n";
							});
							$("#elemento_id").html(html);
							$("#elemento_id").change();
							<?php if(isset($elemento['Elemento']["id"]) && isset($elemento['IntervencionElementos']["id_elemento"])) { ?>
							$("#elemento_id option[codigo='<?php echo $elemento['IntervencionElementos']["id_elemento"]; ?>'][value='<?php echo $elemento['Elemento']["id"]; ?>']").prop('selected', true);
							$("#elemento_id").change();
							<?php } ?>
						});						
					} else {
						html = "<option value=\"\">Seleccione una opción</opcion>\n";
						$("#elemento_id").html(html);
						$("#elemento_id").change();
					}		
					
					$("#imagen-elemento").attr("src","");
					if (subsistema_id != "") {
						$.get( "/Backlog/motorimagen/" + motor_id + "/" + sistema_id + "/" + subsistema_id, function(data) {
							data = "/images/motor/" + data + ".png";
							$("#imagen-elemento").attr("src",data);
						});
					}	
				});
				<?php } ?>
				$("#id_elemento").change(function(){
					var codigo_elemento = $("#id_elemento").val();
					$("#elemento_id option[codigo='"+codigo_elemento+"']").prop('selected', true);
					$("#elemento_id").change();
				});
				<?php if(@$seccion != "MatrizMotor") { ?>
				$("#elemento_id").off();
				$("#elemento_id").change(function(){
					var sistema_id = $("#sistema_id").find("option:selected").val();
					var motor_id = $("#unidad_id").find("option:selected").attr("motor_id");
					var subsistema_id = $("#subsistema_id").find("option:selected").val();
					var elemento_id = $("#elemento_id").find("option:selected").val();
					var codigo_elemento = $("#elemento_id").find("option:selected").attr("codigo");
					$("#id_elemento").val(codigo_elemento);
					$.get( "/Select/PosicionesElemento/" + motor_id + "/" + sistema_id + "/" + subsistema_id + "/" + elemento_id + "/" + codigo_elemento, function(data) {
						html = '<option value=\"\">Seleccione una opción</opcion>\n';
						var obj = $.parseJSON(data);
						$.each(obj, function(i, item) {
							html += "<option value=\""+item.Posicion.id+"\">"+item.Posicion.nombre+"</option>\n";
						});
						$("#elemento_posicion_id").html(html);
						$("#elemento_posicion_id").change();
						<?php if(isset($elemento['Elemento']["id"]) && isset($elemento['PosicionElemento']["id"])) { ?>
						$("#elemento_posicion_id").val('<?php echo $elemento['PosicionElemento']["id"]; ?>');
						$("#elemento_posicion_id").change();
						<?php } ?>
					});	
					
					$.get( "/Select/Diagnostico/" + motor_id + "/" + sistema_id + "/" + subsistema_id + "/" + elemento_id, function(data) {
						html = '<option value=\"\">Seleccione una opción</opcion>\n';
						var obj = $.parseJSON(data);
						$.each(obj, function(i, item) {
							html += "<option value=\""+item.Diagnostico.id+"\">"+item.Diagnostico.nombre+"</option>\n";
						});
						$("#diagnostico_id").html(html);
						$("#diagnostico_id").change();
						<?php if(isset($elemento['Elemento']["id"]) && isset($elemento['Diagnostico']["id"])) { ?>
						$("#diagnostico_id").val('<?php echo $elemento['Diagnostico']["id"]; ?>');
						$("#diagnostico_id").change();
						<?php } ?>
					});	
				});
				<?php } ?>
				<?php if(isset($data["categoria_sintoma_id"]) && isset($data["sintoma_id"])) { ?>
					$("#categoria_sintoma_id").change();
					$("#sintoma_id option[value='<?php echo $data["sintoma_id"];?>'][sintoma_categoria_id='<?php echo $data["categoria_sintoma_id"];?>']").prop('selected', true);
				<?php } ?>
				
				<?php if(isset($elemento['Sistema']["id"]) && isset($elemento['Subsistema']["id"])) { ?>
					$("#unidad_id").change();
				<?php } ?>
				
				// Scripts de uso en Revisión DCC
				<?php if(isset($seccion) && $seccion == 'RevisiónDCC') { ?>
				$('#confirma_guardado').modal({ show: false});
				$('#modal_mensaje_error').modal({ show: false});
				
				var totalDelta1 = 0;
				var totalDelta2 = 0;
				var totalDelta3 = 0;
				var totalDelta4 = 0;
				var totalDelta5 = 0;
				var totalDelta6 = 0;
				var totalDelta7 = 0;
				var totalDelta8 = 0;

				$("input,select").change(function(){
					// Delta 1
					if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
						if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
							var d = 0;
							
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "1"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val) * 60;
									}
								}
							});
							
							$(".delta_minuto").each(function() {
								if($(this).attr("grupo") == "1"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val);
									}
								}
							});
							
							var i_i = (new Date($("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
							var m = i_t - i_i;
							d = m - d;
							totalDelta1 = d;
							
							var hours = Math.floor(d / 60);          
							var minutes = d % 60;
							
							if(hours==0&&minutes==0){
								$("#d1_ing").text("Todo el tiempo está asignado");
							}else if (hours>=0&&minutes>=0){
								if(hours==0){
									$("#d1_ing").text("Faltan "+minutes+"m por ingresar");
								}else if(minutes==0){
									$("#d1_ing").text("Faltan "+hours+"h por ingresar");	
								}else{
									$("#d1_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
								}
							}else if(hours<=0&&minutes<=0){
								if(minutes!=0){
									hours = hours + 1;
								}
								hours = hours * -1;
								minutes = minutes * -1;
								if(hours==0){
									$("#d1_ing").text("Hay "+minutes+"m adicionales ingresado");
								}else if(minutes==0){
									$("#d1_ing").text("Hay "+hours+"h adicional ingresada");
								}else{
									$("#d1_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
								}
							}
							
							// Update of disabled controls
							$("#llegada_fecha_2").val($("#llegada_fecha").val());
							$("#llegada_hora_2").val($("#llegada_hora").val());
							$("#llegada_min_2").val($("#llegada_min").val());
							$("#llegada_periodo_2").val($("#llegada_periodo").val());
						}
					}
					
					// Delta 2
					if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
						if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
							var d = 0;
							
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "2"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val) * 60;
									}
								}
							});
							
							$(".delta_minuto").each(function() {
								if($(this).attr("grupo") == "2"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val);
									}
								}
							});
							
							var i_i = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
							var m = i_t - i_i;
							d = m - d;
							totalDelta2 = d;
							var hours = Math.floor(d / 60);          
							var minutes = d % 60;
							if(hours==0&&minutes==0){
								$("#d2_ing").text("Todo el tiempo está asignado");
							}else if (hours>=0&&minutes>=0){
								if(hours==0){
									$("#d2_ing").text("Faltan "+minutes+"m por ingresar");
								}else if(minutes==0){
									$("#d2_ing").text("Faltan "+hours+"h por ingresar");	
								}else{
									$("#d2_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
								}
							}else if(hours<=0&&minutes<=0){
								if(minutes!=0){
									hours = hours + 1;
								}
								hours = hours * -1;
								minutes = minutes * -1;
								if(hours==0){
									$("#d2_ing").text("Hay "+minutes+"m adicionales ingresado");
								}else if(minutes==0){
									$("#d2_ing").text("Hay "+hours+"h adicional ingresada");
								}else{
									$("#d2_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
								}
							}
							// Update of disabled controls
							$("#i_i_f_2").val($("#i_i_f").val());
							$("#i_i_h_2").val($("#i_i_h").val());
							$("#i_i_m_2").val($("#i_i_m").val());
							$("#i_i_p_2").val($("#i_i_p").val());
						}
					}
					
					// Delta 3
					if ($("#tipointervencion").val() != 'MP') {
						if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
							if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
								var d = 0;
								
								$(".delta_hora").each(function() {
									if($(this).attr("grupo") == "3"){
										var val = $(this).val();
										if ($.isNumeric(val)){
											d += parseInt(val) * 60;
										}
									}
								});
								
								$(".delta_minuto").each(function() {
									if($(this).attr("grupo") == "3"){
										var val = $(this).val();
										if ($.isNumeric(val)){
											d += parseInt(val);
										}
									}
								});
								
								var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
								var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
								var m = i_t - i_i;
								d = m - d;
								totalDelta3 = d;
								var hours = Math.floor(d / 60);          
								var minutes = d % 60;
								if(hours==0&&minutes==0){ 
									$("#d3_ing").text("Todo el tiempo está asignado");
								}else if (hours>=0&&minutes>=0){
									if(hours==0){
										$("#d3_ing").text("Faltan "+minutes+"m por ingresar");
									}else if(minutes==0){
										$("#d3_ing").text("Faltan "+hours+"h por ingresar");	
									}else{
										$("#d3_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
									}
								}else if(hours<=0&&minutes<=0){
									if(minutes!=0){
										hours = hours + 1;
									}
									hours = hours * -1;
									minutes = minutes * -1;
									if(hours==0){
										$("#d3_ing").text("Hay "+minutes+"m adicionales ingresado");
									}else if(minutes==0){
										$("#d3_ing").text("Hay "+hours+"h adicional ingresada");
									}else{
										$("#d3_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
									}
								}
								// Update of disabled controls
								$("#i_t_f_2").val($("#i_t_f").val());
								$("#i_t_h_2").val($("#i_t_h").val());
								$("#i_t_m_2").val($("#i_t_m").val());
								$("#i_t_p_2").val($("#i_t_p").val());
							}
						}
					}
					
					// Delta 4
					if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
						if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
							var d = 0;
							
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "4"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val) * 60;
									}
								}
							});
							
							$(".delta_minuto").each(function() {
								if($(this).attr("grupo") == "4"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val);
									}
								}
							});
							
							var i_i = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
							var i_t = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
							var m = i_t - i_i;
							d = m - d;
							totalDelta4 = d;
							var hours = Math.floor(d / 60);          
							var minutes = d % 60;
							if(hours==0&&minutes==0){ 
								$("#d4_ing").text("Todo el tiempo está asignado");
							}else if (hours>=0&&minutes>=0){
								if(hours==0){
									$("#d4_ing").text("Faltan "+minutes+"m por ingresar");
								}else if(minutes==0){
									$("#d4_ing").text("Faltan "+hours+"h por ingresar");	
								}else{
									$("#d4_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
								}
							}else if(hours<=0&&minutes<=0){
								if(minutes!=0){
									hours = hours + 1;
								}
								hours = hours * -1;
								minutes = minutes * -1;
								if(hours==0){
									$("#d4_ing").text("Hay "+minutes+"m adicionales ingresado");
								}else if(minutes==0){
									$("#d4_ing").text("Hay "+hours+"h adicional ingresada");
								}else{
									$("#d4_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
								}
							}
						}
					}
					
					if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
						// Update of disabled controls
						$("#pp_i_f_2").val($("#pp_i_f").val());
						$("#pp_i_h_2").val($("#pp_i_h").val());
						$("#pp_i_m_2").val($("#pp_i_m").val());
						$("#pp_i_p_2").val($("#pp_i_p").val());
					}
					
					// Delta 5
					if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
						if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
							var d = 0;
							
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "5"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val) * 60;
									}
								}
							});
							
							$(".delta_minuto").each(function() {
								if($(this).attr("grupo") == "5"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val);
									}
								}
							});
							
							var i_i = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
							var i_t = (new Date($("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val())).getTime()/60000;
							var m = i_t - i_i;
							d = m - d;
							totalDelta5 = d;
							var hours = Math.floor(d / 60);          
							var minutes = d % 60;
							if(hours==0&&minutes==0){ 
								$("#d5_ing").text("Todo el tiempo está asignado");
							}else if (hours>=0&&minutes>=0){
								if(hours==0){
									$("#d5_ing").text("Faltan "+minutes+"m por ingresar");
								}else if(minutes==0){
									$("#d5_ing").text("Faltan "+hours+"h por ingresar");	
								}else{
									$("#d5_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
								}
							}else if(hours<=0&&minutes<=0){
								if(minutes!=0){
									hours = hours + 1;
								}
								hours = hours * -1;
								minutes = minutes * -1;
								if(hours==0){
									$("#d5_ing").text("Hay "+minutes+"m adicionales ingresado");
								}else if(minutes==0){
									$("#d5_ing").text("Hay "+hours+"h adicional ingresada");
								}else{
									$("#d5_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
								}
							}
						}
					}
					
					// Delta 6
					if ($("#tipointervencion").val() == 'MP') {
						if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
							if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
								var d = 0;
								
								$(".delta_hora").each(function() {
									if($(this).attr("grupo") == "6"){
										var val = $(this).val();
										if ($.isNumeric(val)){
											d += parseInt(val) * 60;
										}
									}
								});
								
								$(".delta_minuto").each(function() {
									if($(this).attr("grupo") == "6"){
										var val = $(this).val();
										if ($.isNumeric(val)){
											d += parseInt(val);
										}
									}
								});
								
								var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
								var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
								var m = i_t - i_i;
								d = m - d;
								totalDelta6 = d;
								var hours = Math.floor(d / 60);          
								var minutes = d % 60;
								if(hours==0&&minutes==0){ 
									$("#d6_ing").text("Todo el tiempo está asignado");
								}else if (hours>=0&&minutes>=0){
									if(hours==0){
										$("#d6_ing").text("Faltan "+minutes+"m por ingresar");
									}else if(minutes==0){
										$("#d6_ing").text("Faltan "+hours+"h por ingresar");	
									}else{
										$("#d6_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
									}
								}else if(hours<=0&&minutes<=0){
									if(minutes!=0){
										hours = hours + 1;
									}
									hours = hours * -1;
									minutes = minutes * -1;
									if(hours==0){
										$("#d6_ing").text("Hay "+minutes+"m adicionales ingresado");
									}else if(minutes==0){
										$("#d6_ing").text("Hay "+hours+"h adicional ingresada");
									}else{
										$("#d6_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
									}
								}
							}
						}
					}
					
					// Delta 7
					if ($("#trabajo_anterior_fecha") != null && $("#trabajo_anterior_fecha") != undefined && $("#trabajo_anterior_fecha").val() != "" && $("#trabajo_anterior_fecha").val() != undefined) {
						if ($("#trabajo_fecha") != null && $("#trabajo_fecha") != undefined && $("#trabajo_fecha").val() != "" && $("#trabajo_fecha").val() != undefined) {
							var d = 0;
							
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "7"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val) * 60;
									}
								}
							});
							
							$(".delta_minuto").each(function() {
								if($(this).attr("grupo") == "7"){
									var val = $(this).val();
									if ($.isNumeric(val)){
										d += parseInt(val);
									}
								}
							});
							
							var i_i = (new Date($("#trabajo_anterior_fecha").val() + " " + $("#trabajo_anterior_hora").val() + ":"+$("#trabajo_anterior_minuto").val() +":00 "+$("#trabajo_anterior_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#trabajo_fecha").val() + " " + $("#trabajo_hora").val() + ":"+$("#trabajo_minuto").val() +":00 "+$("#trabajo_periodo").val())).getTime()/60000;
							var m = i_t - i_i;
							d = m - d;
							totalDelta7 = d;
							var hours = Math.floor(d / 60);          
							var minutes = d % 60;
							if(hours==0&&minutes==0){ 
								$("#d7_ing").text("Todo el tiempo está asignado");
							}else if (hours>=0&&minutes>=0){
								if(hours==0){
									$("#d7_ing").text("Faltan "+minutes+"m por ingresar");
								}else if(minutes==0){
									$("#d7_ing").text("Faltan "+hours+"h por ingresar");	
								}else{
									$("#d7_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
								}
							}else if(hours<=0&&minutes<=0){
								if(minutes!=0){
									hours = hours + 1;
								}
								hours = hours * -1;
								minutes = minutes * -1;
								if(hours==0){
									$("#d7_ing").text("Hay "+minutes+"m adicionales ingresado");
								}else if(minutes==0){
									$("#d7_ing").text("Hay "+hours+"h adicional ingresada");
								}else{
									$("#d7_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
								}
							}
						}
					}
					
					// Delta 8
					if ($("#trabajo_termino_fecha") != null && $("#trabajo_termino_fecha") != undefined && $("#trabajo_termino_fecha").val() != "" && $("#trabajo_termino_fecha").val() != undefined) {
						if ($("#operacion_fecha") != null && $("#operacion_fecha") != undefined && $("#operacion_fecha").val() != "" && $("#operacion_fecha").val() != undefined) {
							if($("#operacion_fecha").val() != "" && $("#operacion_hora").val() != "" && $("#operacion_minuto").val() != "" && $("#operacion_periodo").val() != "") {
								var d = 0;
								$(".delta_hora").each(function() {
									if($(this).attr("grupo") == "8"){
										var val = $(this).val();
										if ($.isNumeric(val)){
											d += parseInt(val) * 60;
										}
									}
								});
								
								$(".delta_minuto").each(function() {
									if($(this).attr("grupo") == "8"){
										var val = $(this).val();
										if ($.isNumeric(val)){
											d += parseInt(val);
										}
									}
								});
								
								var i_i = (new Date($("#trabajo_termino_fecha").val() + " " + $("#trabajo_termino_hora").val() + ":"+$("#trabajo_termino_minuto").val() +":00 "+$("#trabajo_termino_periodo").val())).getTime()/60000;
								var i_t = (new Date($("#operacion_fecha").val() + " " + $("#operacion_hora").val() + ":"+$("#operacion_minuto").val() +":00 "+$("#operacion_periodo").val())).getTime()/60000;
								
								console.log(i_i);
								console.log(i_t);
								var m = i_t - i_i;
								d = m - d;
								totalDelta8 = d;
								var hours = Math.floor(d / 60);          
								var minutes = d % 60;
								if(hours==0&&minutes==0){ 
									$("#d8_ing").text("Todo el tiempo está asignado");
								}else if (hours>=0&&minutes>=0){
									if(hours==0){
										$("#d8_ing").text("Faltan "+minutes+"m por ingresar");
									}else if(minutes==0){
										$("#d8_ing").text("Faltan "+hours+"h por ingresar");	
									}else{
										$("#d8_ing").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
									}
								}else if(hours<=0&&minutes<=0){
									if(minutes!=0){
										hours = hours + 1;
									}
									hours = hours * -1;
									minutes = minutes * -1;
									if(hours==0){
										$("#d8_ing").text("Hay "+minutes+"m adicionales ingresado");
									}else if(minutes==0){
										$("#d8_ing").text("Hay "+hours+"h adicional ingresada");
									}else{
										$("#d8_ing").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
									}
								}
							} else {
								$("#d8_ing").text("");
							}
						}
					}
				});
				
					/**
					* Delta 9 [Agregado por Victor Smith]
					*/
					if ( ($('#inicio_delta_fecha') != null) && ($('#inicio_delta_fecha') != undefined) && ($("#inicio_delta_fecha").val() != "") && ($("#inicio_delta_fecha").val() != undefined)) {
						if ( $("#termino_turno_fecha") != null && $("#termino_turno_fecha") != undefined && $("#termino_turno_fecha").val() != "" && $("#termino_turno_fecha").val() != undefined) {
							if( $("#termino_turno_fecha").val() != "" && $("#termino_turno_hora").val() != "" && $("#termino_turno_minuto").val() != "" && $("#termino_turno_periodo").val() != "") {
								//
								var d = 0;
								$(".delta_hora").each( function () {
									if( $(this).attr("grupo") == "9" ) {
										var val = $(this).val();
										if ($.isNumeric(val)){
											d += parseInt(val) * 60;
										}
									}
								});
								//
								$(".delta_minuto").each(function() {
									if($(this).attr("grupo") == "9"){
										var val = $(this).val();
										if ($.isNumeric(val)){
											d += parseInt(val);
										}
									}
								});
								//
								var i_i = ( new Date( $("#inicio_delta_fecha").val() + " " + $("#inicio_delta_hora").val() + ":"+$("#inicio_delta_minuto").val() +":00 "+$("#inicio_delta_periodo").val()) ).getTime()/60000;
								var i_t = ( new Date( $("#termino_turno_fecha").val() + " " + $("#termino_turno_hora").val() + ":"+$("#termino_turno_minuto").val() +":00 "+$("#termino_turno_periodo").val()) ).getTime()/60000;
								//								
								var m = i_t - i_i;
								d = m - d;
								totalDelta8 = d;
								var hours = Math.floor(d / 60);          
								var minutes = d % 60;
								//
								if ( hours == 0 && minutes == 0 ) { 
									$("#d9_ing").text("Todo el tiempo está asignado");
								} else if ( hours>=0 && minutes>=0 ) {
									if ( hours == 0 ) {
										$("#d9_ing").text("Faltan " + minutes + "m por ingresar");
									} else if ( minutes == 0 ) {
										$("#d9_ing").text("Faltan " + hours+"h por ingresar");	
									} else {
										$("#d9_ing").text("Faltan " + hours + "h " + minutes + "m por ingresar" );	
									}
								} else if ( hours <= 0 && minutes <= 0 ) {
									if ( minutes!=0 ) {
										hours = hours + 1;
									}
									hours = hours * -1;
									minutes = minutes * -1;
									if ( hours == 0 ) {
										$("#d9_ing").text("Hay " + minutes + "m adicionales ingresado");
									} else if ( minutes == 0 ) {
										$("#d9_ing").text("Hay " + hours + "h adicional ingresada");
									} else {
										$("#d9_ing").text("Hay " + hours + "h " + minutes + "m adicional ingresado");	
									}
								}
								//
								
							} else {
								$("#d8_ing").text("");
							}
						}
					}
				});

					
				$(".hora_elemento_reproceso, .minuto_elemento_reproceso").change(function(){
					// Deltas de elemento
					var tiempo_elemento = 0;
					$(".hora_elemento_reproceso").each(function() {
						var val = $(this).val();
						if ($.isNumeric(val)){
							tiempo_elemento += parseInt(val) * 60;
						}
					});
					
					$(".minuto_elemento_reproceso").each(function() {
						var val = $(this).val();
						if ($.isNumeric(val)){
							tiempo_elemento += parseInt(val);
						}
					});
					
					var hours = Math.floor(tiempo_elemento / 60);          
					var minutes = tiempo_elemento % 60;
					
					$(".total_delta_hora_elemento_reproceso").val(hours);
					$(".total_delta_minutos_elemento_reproceso").val(minutes);
					
					var delta_reparacion_diagnostico = parseInt($("#delta_hora32").val()) * 60 + parseInt($("#delta_minuto32").val());
					d = delta_reparacion_diagnostico - tiempo_elemento;
					hours = Math.floor(d / 60);          
					minutes = d % 60;
					if(hours==0&&minutes==0){ 
						$(".delta_faltante_elementos_reproceso").text("Todo el tiempo está asignado");
					}else if (hours>=0&&minutes>=0){
						if(hours==0){
							$(".delta_faltante_elementos_reproceso").text("Faltan "+minutes+"m por ingresar");
						}else if(minutes==0){
							$(".delta_faltante_elementos_reproceso").text("Faltan "+hours+"h por ingresar");	
						}else{
							$(".delta_faltante_elementos_reproceso").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
						}
					}else if(hours<=0&&minutes<=0){
						if(minutes!=0){
							hours = hours + 1;
						}
						hours = hours * -1;
						minutes = minutes * -1;
						if(hours==0){
							$(".delta_faltante_elementos_reproceso").text("Hay "+minutes+"m adicionales ingresado");
						}else if(minutes==0){
							$(".delta_faltante_elementos_reproceso").text("Hay "+hours+"h adicional ingresada");
						}else{
							$(".delta_faltante_elementos_reproceso").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
						}
					}
				});
				
				$(".hora_elemento_reproceso").change();
				
				$("#delta_hora16, #delta_minuto16").change(function(){
					var hora = $("#delta_hora16").val();
					var minuto = $("#delta_minuto16").val();
					$(".delta_disponible_elementos").text(hora+"h " + minuto+"m");
					$(".hora_elemento").change();
				});
				
				$("#delta_hora32, #delta_minuto32").change(function(){
					var hora = $("#delta_hora32").val();
					var minuto = $("#delta_minuto32").val();
					$(".delta_disponible_elementos_reproceso").text(hora+"h " + minuto+"m");
					$(".hora_elemento_reproceso").change();
				});
				
				$("#llamado_fecha,#llamado_hora,#llamado_min,#llamado_periodo,#llegada_fecha,#llegada_hora,#llegada_min,#llegada_periodo").change(function(){
					if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
						if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
							var i_i = (new Date($("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
							var m = i_t - i_i;
							var hours = Math.floor(m / 60);          
							var minutes = m % 60;
							$(".delta1_duracion").text(hours+"h "+minutes+"m")
							$(".delta_hora").change();
						}
					}
				});
				$("#llamado_fecha").change();
				
				$("#i_i_f,#i_i_h,#i_i_m,#i_i_p,#llegada_fecha,#llegada_hora,#llegada_min,#llegada_periodo").change(function(){
					if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
						if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
							var i_i = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
							var m = i_t - i_i;
							var hours = Math.floor(m / 60);          
							var minutes = m % 60;
							$(".delta2_duracion").text(hours+"h "+minutes+"m")
							$(".delta_hora").change();
						}
					}
				});
				$("#i_i_f").change();
				
				$("#i_i_f,#i_i_h,#i_i_m,#i_i_p,#i_t_f,#i_t_h,#i_t_m,#i_t_p").change(function(){
					if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
						if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
							var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
							var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
							var m = i_t - i_i; 
							console.log("i_i: " + i_i);
							console.log("i_t: " + i_t);
							console.log("i_i: " + ($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val()));
							console.log("i_t: " + ($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val()));
							console.log("delta3: " + m);
							var hours = Math.floor(m / 60);          
							var minutes = m % 60;
							if ($("#tipointervencion").val() != 'MP') {
								$(".delta3_duracion").text(hours+"h "+minutes+"m")
								$(".delta_hora").change();
							} else {
								$(".delta6_duracion").text(hours+"h "+minutes+"m")
								$(".delta_hora").change();
							}
						}
					}
				});
				$("#i_t_f").change();
				
				$("#pp_i_f,#pp_i_h,#pp_i_m,#pp_i_p,#i_t_f,#i_t_h,#i_t_m,#i_t_p").change(function(){
					if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
						if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
							var i_i = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
							var i_t = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
							var m = i_t - i_i;
							var hours = Math.floor(m / 60);          
							var minutes = m % 60;
							$(".delta4_duracion").text(hours+"h "+minutes+"m")
							$(".delta_hora").change();
						}
					}
				});
				$("#pp_i_f").change();
				
				$("#pp_t_f,#pp_t_h,#pp_t_m,#pp_t_p,#repro_f,#repro_h,#repro_m,#repro_p").change(function(){
					if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
						if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
							var i_i = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
							var i_t = (new Date($("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val())).getTime()/60000;
							var m = i_t - i_i;
							var hours = Math.floor(m / 60);          
							var minutes = m % 60;
							$(".delta5_duracion").text(hours+"h "+minutes+"m")
							$(".delta_hora").change();
						}
					}
				});
				$("#pp_t_f").change();		
				
				$("#trabajo_anterior_fecha,#trabajo_anterior_hora,#trabajo_anterior_minuto,#trabajo_anterior_periodo,#trabajo_fecha,#trabajo_hora,#trabajo_minuto,#trabajo_periodo").change(function(){
					if ($("#trabajo_anterior_fecha") != null && $("#trabajo_anterior_fecha") != undefined && $("#trabajo_anterior_fecha").val() != "" && $("#trabajo_anterior_fecha").val() != undefined) {
						if ($("#trabajo_fecha") != null && $("#trabajo_fecha") != undefined && $("#trabajo_fecha").val() != "" && $("#trabajo_fecha").val() != undefined) {
							var i_i = (new Date($("#trabajo_anterior_fecha").val() + " " + $("#trabajo_anterior_hora").val() + ":"+$("#trabajo_anterior_minuto").val() +":00 "+$("#trabajo_anterior_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#trabajo_fecha").val() + " " + $("#trabajo_hora").val() + ":"+$("#trabajo_minuto").val() +":00 "+$("#trabajo_periodo").val())).getTime()/60000;
							var m = i_t - i_i;
							var hours = Math.floor(m / 60);          
							var minutes = m % 60;
							$(".delta7_duracion").text(hours+"h "+minutes+"m")
							$(".delta_hora").change();
						}
					}
				});
				$("#trabajo_anterior_fecha").change();
				
				$("#trabajo_termino_fecha,#trabajo_termino_hora,#trabajo_termino_minuto,#trabajo_termino_periodo,#operacion_fecha,#operacion_hora,#operacion_minuto,#operacion_periodo").change(function(){
					if ($("#trabajo_termino_fecha") != null && $("#trabajo_termino_fecha") != undefined && $("#trabajo_termino_fecha").val() != "" && $("#trabajo_termino_fecha").val() != undefined) {
						if ($("#operacion_fecha") != null && $("#operacion_fecha") != undefined && $("#operacion_fecha").val() != "" && $("#operacion_fecha").val() != undefined) {
							var i_i = (new Date($("#trabajo_termino_fecha").val() + " " + $("#trabajo_termino_hora").val() + ":"+$("#trabajo_termino_minuto").val() +":00 "+$("#trabajo_termino_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#operacion_fecha").val() + " " + $("#operacion_hora").val() + ":"+$("#operacion_minuto").val() +":00 "+$("#operacion_periodo").val())).getTime()/60000;
							var m = i_t - i_i;
							var hours = Math.floor(m / 60);          
							var minutes = m % 60;
							$(".delta8_duracion").text(hours+"h "+minutes+"m")
							$(".delta_hora").change();
						}
					}
				});
				$("#operacion_fecha").change();
				
				/**
				 * Agregado por Victor Smith
				 */
				$("#inicio_delta_fecha, #inicio_delta_hora, #inicio_delta_minuto, #inicio_delta_periodo, #termino_turno_fecha, #termino_turno_hora, #termino_turno_minuto, #termino_turno_periodo").change( function () {
					if ( ($("#inicio_delta_fecha") != null) && ($("#inicio_delta_fecha") != undefined ) && ($("#inicio_delta_fecha").val() != "") && ($("#inicio_delta_fecha").val() != undefined)) {
						if ( ($("#termino_turno_fecha") != null) && ($("#termino_turno_fecha") != undefined) && ($("#termino_turno_fecha").val() != "") && ($("#termino_turno_fecha").val() != undefined)) {
							var i_i = ( new Date( $("#inicio_delta_fecha").val() + " " + $("#inicio_delta_hora").val() + ":"+$("#inicio_delta_minuto").val() +":00 "+$("#inicio_delta_periodo").val())).getTime()/60000;
							var i_t = ( new Date( $("#termino_turno_fecha").val() + " " + $("#termino_turno_hora").val() + ":"+$("#termino_turno_minuto").val() +":00 "+$("#termino_turno_periodo").val())).getTime()/60000;
								console.log( i_i + 'i_i');
								console.log( i_t + 'i_t');
							var m = i_t - i_i;
							var hours = Math.floor(m / 60);          
							var minutes = m % 60;
							$(".delta9_duracion").text(hours+"h "+minutes+"m")
							$(".delta_hora").change();
						}
					}
				});	
				$("#inicio_delta_fecha").change();
				
				// Submit function
				
				$(".submit-guardar").click(function(){
					var tiempo_total = 0;
					var fechas_validas = true;
					var delta_1_valido = true;
					var fecha_termino_global = "";
					var fecha_inicio_global = "";
					var guardar_ok = true;
					
					var todos = true;
					$("select, input, textarea").each(function(){
						if ($(this).attr("required") != undefined && $(this).attr("required") != null && $(this).attr("required") == "required"){
							var val = $(this).val();
							$(this).addClass("has-error");
							if (val == ""){
								$(this).focus();
								todos = false;
							} else {
								$(this).removeClass("has-error");
							}
						}
					});
					
					if (!todos) {
						$(".mensaje_modal").text("Debe ingresar todos los datos obligatorios.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					
					if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
						if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
							var i_i = (new Date($("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
							if (i_t >= i_i) {
								tiempo_total += i_t - i_i;
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#llamado_fecha").val() + " " + $("#llamado_hora").val() + ":"+$("#llamado_min").val() +":00 "+$("#llamado_periodo").val();
								}
								fecha_termino_global = $("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val();
							}else{
								fechas_validas = false;
							}
						}
					}
					
					if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
						if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
							var i_i = (new Date($("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
							if (i_t >= i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK i_i_f es mayor o igual a llegada_fecha " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#llegada_fecha").val() + " " + $("#llegada_hora").val() + ":"+$("#llegada_min").val() +":00 "+$("#llegada_periodo").val();
								}
								fecha_termino_global = $("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val();
							}else{
								console.log("NOK i_i_f no es mayor o igual a llegada_fecha");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
						if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
							var i_i = (new Date($("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val())).getTime()/60000;
							var i_t = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
							if (i_t > i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK i_t_f es mayor a i_i_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#i_i_f").val() + " " + $("#i_i_h").val() + ":"+$("#i_i_m").val() +":00 "+$("#i_i_p").val();
								}
								fecha_termino_global = $("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val();
							}else{
								console.log("NOK i_t_f no es menor o igual a i_i_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
						if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
							var i_i = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
							var i_t = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
							if (i_t >= i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK pp_i_f es mayor a i_t_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val();
								}
								fecha_termino_global = $("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val();
							}else{
								console.log("NOK pp_i_f no es menor o igual a i_t_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
						if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
							var i_i = (new Date($("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val())).getTime()/60000;
							var i_t = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
							if (i_t > i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK pp_t_f es mayor a pp_i_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#pp_i_f").val() + " " + $("#pp_i_h").val() + ":"+$("#pp_i_m").val() +":00 "+$("#pp_i_p").val();
								}
								fecha_termino_global = $("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val();
							}else{
								console.log("NOK pp_t_f no es menor o igual a pp_i_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#desc_f") != null && $("#desc_f") != undefined && $("#desc_f").val() != "" && $("#desc_f").val() != undefined) {
						if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
							var i_i = (new Date($("#i_t_f").val() + " " + $("#i_t_h").val() + ":"+$("#i_t_m").val() +":00 "+$("#i_t_p").val())).getTime()/60000;
							var i_t = (new Date($("#desc_f").val() + " " + $("#desc_h").val() + ":"+$("#desc_m").val() +":00 "+$("#desc_p").val())).getTime()/60000;
							if (i_t < i_i) {
								$(".mensaje_modal").text("La fecha de inicio de la desconexión debe ser mayor o igual al término de la intervención.");
								$('#modal_mensaje_error').modal('show');
								return false;
							}
						}
					}
					
					if ($("#desc_f") != null && $("#desc_f") != undefined && $("#desc_f").val() != "" && $("#desc_f").val() != undefined) {
						if ($("#desct_f") != null && $("#desct_f") != undefined && $("#desct_f").val() != "" && $("#desct_f").val() != undefined) {
							var i_i = (new Date($("#desc_f").val() + " " + $("#desc_h").val() + ":"+$("#desc_m").val() +":00 "+$("#desc_p").val())).getTime()/60000;
							var i_t = (new Date($("#desct_f").val() + " " + $("#desct_h").val() + ":"+$("#desct_m").val() +":00 "+$("#desct_p").val())).getTime()/60000;
							if (i_t > i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK desct_f es mayor a desct_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#desc_f").val() + " " + $("#desc_h").val() + ":"+$("#desc_m").val() +":00 "+$("#desc_p").val();
								}
								fecha_termino_global = $("#desct_f").val() + " " + $("#desct_h").val() + ":"+$("#desct_m").val() +":00 "+$("#desct_p").val();
							}else{
								console.log("NOK desct_f no es menor o igual a desct_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#desct_f") != null && $("#desct_f") != undefined && $("#desct_f").val() != "" && $("#desct_f").val() != undefined) {
						if ($("#con_f") != null && $("#con_f") != undefined && $("#con_f").val() != "" && $("#con_f").val() != undefined) {
							var i_i = (new Date($("#desct_f").val() + " " + $("#desct_h").val() + ":"+$("#desct_m").val() +":00 "+$("#desct_p").val())).getTime()/60000;
							var i_t = (new Date($("#con_f").val() + " " + $("#con_h").val() + ":"+$("#con_m").val() +":00 "+$("#con_p").val())).getTime()/60000;
							if (i_t >= i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK desc_f es mayor a cont_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									//fecha_inicio_global = $("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val();
								}
								//fecha_termino_global = $("#desc_f").val() + " " + $("#desc_h").val() + ":"+$("#desc_m").val() +":00 "+$("#desc_p").val();
							}else{
								console.log("NOK desc_f no es menor o igual a cont_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#con_f") != null && $("#con_f") != undefined && $("#con_f").val() != "" && $("#con_f").val() != undefined) {
						if ($("#cont_f") != null && $("#cont_f") != undefined && $("#cont_f").val() != "" && $("#cont_f").val() != undefined) {
							var i_i = (new Date($("#con_f").val() + " " + $("#con_h").val() + ":"+$("#con_m").val() +":00 "+$("#con_p").val())).getTime()/60000;
							var i_t = (new Date($("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val())).getTime()/60000;
							if (i_t > i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK cont_f es mayor a con_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#con_f").val() + " " + $("#con_h").val() + ":"+$("#con_m").val() +":00 "+$("#con_p").val();
								}
								fecha_termino_global = $("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val();
							}else{
								console.log("NOK cont_f no es menor o igual a con_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#pm_i_f") != null && $("#pm_i_f") != undefined && $("#pm_i_f").val() != "" && $("#pm_i_f").val() != undefined) {
						if ($("#cont_f") != null && $("#cont_f") != undefined && $("#cont_f").val() != "" && $("#cont_f").val() != undefined) {
							var i_i = (new Date($("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val())).getTime()/60000;
							var i_t = (new Date($("#pm_i_f").val() + " " + $("#pm_i_h").val() + ":"+$("#pm_i_m").val() +":00 "+$("#pm_i_p").val())).getTime()/60000;
							if (i_t >= i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK pm_i_f es mayor a cont_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#cont_f").val() + " " + $("#cont_h").val() + ":"+$("#cont_m").val() +":00 "+$("#cont_p").val();
								}
								fecha_termino_global = $("#pm_i_f").val() + " " + $("#pm_i_h").val() + ":"+$("#pm_i_m").val() +":00 "+$("#pm_i_p").val();
							}else{
								console.log("NOK pm_i_f no es menor o igual a cont_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#pm_i_f") != null && $("#pm_i_f") != undefined && $("#pm_i_f").val() != "" && $("#pm_i_f").val() != undefined) {
						if ($("#pm_t_f") != null && $("#pm_t_f") != undefined && $("#pm_t_f").val() != "" && $("#pm_t_f").val() != undefined) {
							var i_i = (new Date($("#pm_i_f").val() + " " + $("#pm_i_h").val() + ":"+$("#pm_i_m").val() +":00 "+$("#pm_i_p").val())).getTime()/60000;
							var i_t = (new Date($("#pm_t_f").val() + " " + $("#pm_t_h").val() + ":"+$("#pm_t_m").val() +":00 "+$("#pm_t_p").val())).getTime()/60000;
							if (i_t > i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK pm_t_f es mayor a pm_i_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#pm_i_f").val() + " " + $("#pm_i_h").val() + ":"+$("#pm_i_m").val() +":00 "+$("#pm_i_p").val();
								}
								fecha_termino_global = $("#pm_t_f").val() + " " + $("#pm_t_h").val() + ":"+$("#pm_t_m").val() +":00 "+$("#pm_t_p").val();
							}else{
								console.log("NOK pm_t_f no es menor o igual a pm_i_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
						if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
							var i_i = (new Date($("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val())).getTime()/60000;
							var i_t = (new Date($("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val())).getTime()/60000;
							if (i_t > i_i) {
								tiempo_total += i_t - i_i;
								console.log("OK repro_f es mayor a pp_t_f " + (i_t - i_i));
								if(fecha_inicio_global==''){
									fecha_inicio_global = $("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val();
								}
								fecha_termino_global = $("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val();
							}else{
								console.log("NOK repro_f no es menor o igual a pp_t_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#trabajo_termino_fecha") != null && $("#trabajo_termino_fecha") != undefined && $("#trabajo_termino_fecha").val() != "" && $("#trabajo_termino_fecha").val() != undefined) {
						if ($("#operacion_fecha") != null && $("#operacion_fecha") != undefined && $("#operacion_fecha").val() != "" && $("#operacion_fecha").val() != undefined) {
							var i_i = (new Date($("#trabajo_termino_fecha").val() + " " + $("#trabajo_termino_hora").val() + ":"+$("#trabajo_termino_minuto").val() +":00 "+$("#trabajo_termino_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#operacion_fecha").val() + " " + $("#operacion_hora").val() + ":"+$("#operacion_minuto").val() +":00 "+$("#operacion_periodo").val())).getTime()/60000;
							if (i_t > i_i) {
								//tiempo_total += i_t - i_i;
								//console.log("OK repro_f es mayor a pp_t_f " + (i_t - i_i));
								//if(fecha_inicio_global==''){
							//		fecha_inicio_global = $("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val();
								//}
							//	fecha_termino_global = $("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val();
							}else{
								//console.log("NOK repro_f no es menor o igual a pp_t_f");
								fechas_validas = false;
							}
						}
					}
					
					if ($("#elementos") != null && $("#elementos") != undefined && $("#elementos").val() != "" && $("#elementos").val() != undefined) {
						if ($("#elementos").val() == "0") {
							totalDelta3 = 0;
						}
					}
					
					if(!fechas_validas){
						$(".mensaje_modal").text("Las fechas seleccionadas no son correctas, cada una debe ser mayor que la anterior.");
						$('#modal_mensaje_error').modal('show');
						console.log("Las fechas ingresadas no son válidas.");
						return false;
					}
					console.log(totalDelta1);
					if(totalDelta1 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta llamado y llegada no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta1 incorrecto");
						return false;
					}
					console.log(totalDelta2);
					if(totalDelta2 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta llegada e inicio de la intervención no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta2 incorrecto");
						return false;
					}
					console.log(totalDelta3);					
					if(totalDelta3 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta inicio y término de la intervención no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta3 incorrecto");
						return false;
					}
					if(totalDelta4 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta término de la intervención e inicio prueba de potencia no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta4 incorrecto");
						return false;
					}
					
					if(totalDelta5 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta término prueba de potencia y reproceso de elementos no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta5 incorrecto");
						return false;
					}
					if ($("#tipointervencion").val() == 'MP') {
						if(totalDelta6 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta inicio y término mantención no es correcta, por favor revisar.");
							$('#modal_mensaje_error').modal('show');
							console.log("Delta6 incorrecto");
							return false;
						}
					}
					if(totalDelta7 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta término trabajo anterior e inicio trabajo actual no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta5 incorrecto");
						return false;
					}
					if(totalDelta8 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta término trabajo e inicio operación no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta5 incorrecto");
						return false;
					}
					
					// Validacion de responsables por delta
					// Delta 1
					var delta_ok = true;
					if ($("#llamado_fecha") != null && $("#llamado_fecha") != undefined && $("#llamado_fecha").val() != "" && $("#llamado_fecha").val() != undefined) {
						if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
							var d = 0;
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "1"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									val += parseInt($("#delta_minuto"+id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta_ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					// Delta 2
					var delta_ok = true;
					if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
						if ($("#llegada_fecha") != null && $("#llegada_fecha") != undefined && $("#llegada_fecha").val() != "" && $("#llegada_fecha").val() != undefined) {
							var d = 0;
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "2"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									val += parseInt($("#delta_minuto"+id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta_ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					// Delta 3
					if ($("#tipo_intervencion").val() != 'MP') {
						if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
							if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
								var d = 0;
								$(".delta_hora").each(function() {
									if($(this).attr("grupo") == "3"){
										var id = $(this).attr("id").replace("delta_hora","");
										var val = $(this).val();
										if ($.isNumeric(val)){
											val = parseInt(val) * 60;
										}
										console.log(val);
										val += parseInt($("#delta_minuto"+id).val());
										if (val > 0 && $("#delta_responsable" + id).val() == '0') {
											delta_ok = false;
											return false;
										}
										if(val==0){
											$("#delta_responsable" + id).val("0");
											$("#delta_observacion" + id).val("")
										}
									}
								});
							}
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					delta_ok = true;
					// Delta 4
					if ($("#pp_i_f") != null && $("#pp_i_f") != undefined && $("#pp_i_f").val() != "" && $("#pp_i_f").val() != undefined) {
						if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
							var d = 0;
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "4"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									val += parseInt($("#delta_minuto"+id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta_ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					delta_ok = true;
					// Delta 5
					if ($("#repro_f") != null && $("#repro_f") != undefined && $("#repro_f").val() != "" && $("#repro_f").val() != undefined) {
						if ($("#pp_t_f") != null && $("#pp_t_f") != undefined && $("#pp_t_f").val() != "" && $("#pp_t_f").val() != undefined) {
							var d = 0;
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "5"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									val += parseInt($("#delta_minuto"+id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta_ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					delta_ok = true;
					// Delta 6
					if ($("#tipointervencion").val() == 'MP') {
						if ($("#i_i_f") != null && $("#i_i_f") != undefined && $("#i_i_f").val() != "" && $("#i_i_f").val() != undefined) {
							if ($("#i_t_f") != null && $("#i_t_f") != undefined && $("#i_t_f").val() != "" && $("#i_t_f").val() != undefined) {
								var d = 0;
								$(".delta_hora").each(function() {
									if($(this).attr("grupo") == "6"){
										var id = $(this).attr("id").replace("delta_hora", "");
										var val = $(this).val();
										if ($.isNumeric(val)){
											val = parseInt(val) * 60;
										}
										val += parseInt($("#delta_minuto"+id).val());
										if (val > 0 && $("#delta_responsable" + id).val() == '0') {
											delta_ok = false;
											return false;
										}
										if(val==0){
											$("#delta_responsable" + id).val("0");
											$("#delta_observacion" + id).val("")
										}
									}
								});
							}
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					delta_ok = true;
					// Delta 7
					if ($("#trabajo_anterior_fecha") != null && $("#trabajo_anterior_fecha") != undefined && $("#trabajo_anterior_fecha").val() != "" && $("#trabajo_anterior_fecha").val() != undefined) {
						if ($("#trabajo_fecha") != null && $("#trabajo_fecha") != undefined && $("#trabajo_fecha").val() != "" && $("#trabajo_fecha").val() != undefined) {
							var d = 0;
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "7"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									val += parseInt($("#delta_minuto"+id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta_ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					delta_ok = true;
					// Delta 8
					if ($("#trabajo_termino_fecha") != null && $("#trabajo_termino_fecha") != undefined && $("#trabajo_termino_fecha").val() != "" && $("#trabajo_termino_fecha").val() != undefined) {
						if ($("#operacion_fecha") != null && $("#operacion_fecha") != undefined && $("#operacion_fecha").val() != "" && $("#operacion_fecha").val() != undefined) {
							var d = 0;
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "8"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									val += parseInt($("#delta_minuto"+id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta_ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					/**
					* Delta 9 [Agregado por Victor Smith]
					*/
					delta_ok = true;	
					if ($("#inicio_delta_fecha") != null && $("#inicio_delta_fecha") != undefined && $("#inicio_delta_fecha").val() != "" && $("#inicio_delta_fecha").val() != undefined) {
						if ($("#termino_turno_fecha") != null && $("#termino_turno_fecha") != undefined && $("#termino_turno_fecha").val() != "" && $("#termino_turno_fecha").val() != undefined) {
							var d = 0;
							$(".delta_hora").each(function() {
								if($(this).attr("grupo") == "9"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									val += parseInt($("#delta_minuto"+id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta_ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					if(!delta_ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					//console.log("Tiempo total ingresado: " + tiempo_total);
					$("#tiempo_trabajo").val(tiempo_total);
					$("#fecha_inicio_global").val(fecha_inicio_global);
					$("#fecha_termino_global").val(fecha_termino_global); 
					
					
					// Verficacion de tecnicos
					$(".tecnico-id").each(function() {
						var val = $(this).val();
						if (val == "") {
							console.log("Tecnico desplegado pero no seleccionado");
							guardar_ok = false;
							return false;
						}
						
					});
					
					$(".tipo-tecnico-registra option").each(function(){
						// Verficiacion de tipos de tecnicos
						var val = $(this).val();
						if(val != "") {
							var tipo_unico = 0;
							var tipo_requerido = 0;
							var unico = $(this).attr("unico");
							var requerido = $(this).attr("requerido");
							
							$(".tipo-tecnico").each(function() {
								var val_interior = $(this).val();
								if (val_interior == val) {
									if  (unico == "1") {
										tipo_unico++;
									}
									if (requerido == "1"){
										tipo_requerido++;
									}
								}
							});
							
							if (unico == "1" && tipo_unico > 1) {
								console.log("ID "+val+" unico: " + unico + ", ingresados: " + tipo_unico);
								guardar_ok = false;
								return false;
							}
							
							if (requerido == "1" && tipo_requerido < 1) {
								console.log("ID "+val+" Requerido: " + requerido + ", ingresados: " + tipo_requerido);
								guardar_ok = false;
								return false;
							}
						}
					});
					
					var elementos_ok = true;
					var hora_elemento = 0;
					var minuto_elemento = 0;
					var hora_elemento_disponible = 0;
					var minuto_elemento_disponible = 0;
					
					$(".hora_elemento").each(function(){
						var val = $(this).val();
						if(val != "" && val != "0"){
							val = parseInt(val);
							hora_elemento += val * 60;
						}
					});
					
					$(".minuto_elemento").each(function(){
						var val = $(this).val();
						if(val != "" && val != "0"){
							val = parseInt(val);
							minuto_elemento += val;
						}
					});
					
					if($("#delta_hora16")!= undefined && $("#delta_hora16")!= null && $("#delta_hora16").val()!= undefined && $("#delta_hora16").val()!= null){
						var val = $("#delta_hora16").val();
						if(val != "" && val != "0"){
							hora_elemento_disponible = parseInt(val) * 60;
						}
					}
					
					if($("#delta_minuto16")!= undefined && $("#delta_minuto16")!= null && $("#delta_minuto16").val()!= undefined && $("#delta_minuto16").val()!= null){
						var val = $("#delta_minuto16").val();
						if(val != "" && val != "0"){
							minuto_elemento_disponible = parseInt(val);
						}
					}
					
					if (hora_elemento + minuto_elemento != hora_elemento_disponible + minuto_elemento_disponible){
						elementos_ok = false;
					}
					
					//console.log("hora elemento " + (hora_elemento + minuto_elemento));
					//console.log("hora disponible " + (hora_elemento_disponible + minuto_elemento_disponible));
					
					if (!elementos_ok) {
						$(".mensaje_modal").text("Los tiempos ingresados en elementos son incorrectos, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					if($("#delta_hora43")!= undefined && $("#delta_hora43")!= null && $("#delta_hora43").val()!= undefined && $("#delta_hora43").val()!= null){
						if($("#delta_minuto43")!= undefined && $("#delta_minuto43")!= null && $("#delta_minuto43").val()!= undefined && $("#delta_minuto43").val()!= null){
							var tmp = 0;
							var val_hora_mantencion = $("#delta_hora43").val();
							var val_min_mantencion = $("#delta_minuto43").val();
							if (val_hora_mantencion != "" && val_hora_mantencion != ""){
								tmp += parseInt(val_hora_mantencion) * 60;
							}
							if (val_min_mantencion != "" && val_min_mantencion != ""){
								tmp += parseInt(val_min_mantencion);
							}
							
							if (tmp == 0){
								$(".mensaje_modal").text("Debe ingresar un tiempo de mantención.");
								$('#modal_mensaje_error').modal('show');
								return false;
							}
						}
					}
					
					if (!guardar_ok) {
						$(".mensaje_modal").text("Ha ocurrido un error al intentar guardar los cambios en la intervención, por favor intente nuevamente.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					//return false;
					
					if ($(this).val() == "Guardar") {
						$('#confirma_guardado').modal('show');
						return false;
					}	
				});
				
				$(".submit-aprobar").click(function(){
					var tiempo_total = 0;
					var fechas_validas = true;
					var delta_1_valido = true;
					var fecha_termino_global = "";
					var fecha_inicio_global = "";
					var guardar_ok = true;
					
					var todos = true;
					$("select, input, textarea").each(function(){
						if ($(this).attr("required") != undefined && $(this).attr("required") != null && $(this).attr("required") == "required"){
							var val = $(this).val();
							$(this).addClass("has-error");
							if (val == ""){
								$(this).focus();
								todos = false;
							} else {
								$(this).removeClass("has-error");
							}
						}
					});
					/**
					* agregado por Victor Smith
					* 1) se valida que esté seleccionado por lo menos un radio button
					*/
					if ( !$("#yes_option_detail").is(':checked') && !$("#no_option_detail").is(':checked')) {
						todos = false;
						$('.help-block-detail').removeClass('hidden')
					} else {
						$('.help-block-detail').addClass('hidden')
					}
					/**
					*
					*/
					if (!todos) {
						$(".mensaje_modal").text("Debe ingresar todos los datos obligatorios.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					
					if ($("#trabajo_termino_fecha") != null && $("#trabajo_termino_fecha") != undefined && $("#trabajo_termino_fecha").val() != "" && $("#trabajo_termino_fecha").val() != undefined) {
						if ($("#operacion_fecha") != null && $("#operacion_fecha") != undefined && $("#operacion_fecha").val() != "" && $("#operacion_fecha").val() != undefined) {
							var i_i = (new Date($("#trabajo_termino_fecha").val() + " " + $("#trabajo_termino_hora").val() + ":"+$("#trabajo_termino_minuto").val() +":00 "+$("#trabajo_termino_periodo").val())).getTime()/60000;
							var i_t = (new Date($("#operacion_fecha").val() + " " + $("#operacion_hora").val() + ":"+$("#operacion_minuto").val() +":00 "+$("#operacion_periodo").val())).getTime()/60000;
							if (i_t >= i_i) {
								//tiempo_total += i_t - i_i;
								console.log("OK operacion es mayor a pp_t_f " + (i_t - i_i));
								//if(fecha_inicio_global==''){
							//		fecha_inicio_global = $("#pp_t_f").val() + " " + $("#pp_t_h").val() + ":"+$("#pp_t_m").val() +":00 "+$("#pp_t_p").val();
								//}
							//	fecha_termino_global = $("#repro_f").val() + " " + $("#repro_h").val() + ":"+$("#repro_m").val() +":00 "+$("#repro_p").val();
							}else{
								console.log("NOK operacion no es menor o igual a pp_t_f");
								fechas_validas = false;
							}
						}
					}
					
					if(!fechas_validas){
						$(".mensaje_modal").text("Las fechas seleccionadas no son correctas, cada una debe ser mayor o igual que la anterior.");
						$('#modal_mensaje_error').modal('show');
						console.log("Las fechas ingresadas no son válidas.");
						return false;
					}
					
					if(totalDelta7 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta término trabajo anterior e inicio trabajo actual no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta7 incorrecto");
						return false;
					}
					if(totalDelta8 != 0) {
						$(".mensaje_modal").text("La información ingresada en Delta término trabajo e inicio operación no es correcta, por favor revisar.");
						$('#modal_mensaje_error').modal('show');
						console.log("Delta8 incorrecto");
						return false;
					}
					
					
					var delta__ok = true;
					// Delta 7
					if ($("#trabajo_anterior_fecha") != null && $("#trabajo_anterior_fecha") != undefined && $("#trabajo_anterior_fecha").val() != "" && $("#trabajo_anterior_fecha").val() != undefined) {
						if ($("#trabajo_fecha") != null && $("#trabajo_fecha") != undefined && $("#trabajo_fecha").val() != "" && $("#trabajo_fecha").val() != undefined) {
							console.log("Validador delta 7");
							var d = 0;
							$(".delta_hora").each(function() {
								console.log($(this).attr("id"));
								if($(this).attr("grupo") == "7"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									val += parseInt($("#delta_minuto"+id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta__ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					
					if(!delta__ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						console.log("Resp delta 7");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					delta__ok = true;
					// Delta 8
					if ($("#trabajo_termino_fecha") != null && $("#trabajo_termino_fecha") != undefined && $("#trabajo_termino_fecha").val() != "" && $("#trabajo_termino_fecha").val() != undefined) {
						if ($("#operacion_fecha") != null && $("#operacion_fecha") != undefined && $("#operacion_fecha").val() != "" && $("#operacion_fecha").val() != undefined) {
							console.log("Validador delta 8");
							var d = 0;
							$(".delta_hora").each(function() {
								//console.log($(this).attr("id"));
								if($(this).attr("grupo") == "8"){
									var id = $(this).attr("id").replace("delta_hora", "");
									var val = $(this).val();
									//console.log($(this).val());
									if ($.isNumeric(val)){
										val = parseInt(val) * 60;
									}
									//console.log($(this).val());
									//console.log("#delta_minuto"+id);
									//console.log($("#delta_minuto"+id).val());
									val += parseInt($("#delta_minuto"+id).val());
									//console.log($(this).val());
									//console.log(val);
									//console.log($("#delta_responsable" + id).val());
									if (val > 0 && $("#delta_responsable" + id).val() == '0') {
										delta__ok = false;
										return false;
									}
									if(val==0){
										$("#delta_responsable" + id).val("0");
										$("#delta_observacion" + id).val("")
									}
								}
							});
						}
					}
					
					if(!delta__ok){
						$(".mensaje_modal").text("Debe asignar un responsable a todos los deltas ingresados.");
						console.log("Resp delta 8");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					
					if (!guardar_ok) {
						$(".mensaje_modal").text("Ha ocurrido un error al intentar guardar los cambios en la intervención, por favor intente nuevamente.");
						$('#modal_mensaje_error').modal('show');
						return false;
					}
					
					//return false;
					if ($(this).val() == "Aprobar") {
						$('#confirma_guardado').modal('show');
						return false;
					}	
				});
				
				var sumar_delta_elementos = function(){
					// Deltas de elemento
					var tiempo_elemento = 0;
					$(".hora_elemento").each(function() {
						var val = $(this).val();
						if ($.isNumeric(val)){
							tiempo_elemento += parseInt(val) * 60;
						}
					});
					
					$(".minuto_elemento").each(function() {
						var val = $(this).val();
						if ($.isNumeric(val)){
							tiempo_elemento += parseInt(val);
						}
					});
					
					var hours = Math.floor(tiempo_elemento / 60);          
					var minutes = tiempo_elemento % 60;
					
					$(".total_delta_hora_elemento").val(hours);
					$(".total_delta_minutos_elemento").val(minutes);
					
					var delta_reparacion_diagnostico = parseInt($("#delta_hora16").val()) * 60 + parseInt($("#delta_minuto16").val());
					d = delta_reparacion_diagnostico - tiempo_elemento;
					hours = Math.floor(d / 60);          
					minutes = d % 60;
					if(hours==0&&minutes==0){ 
						$(".delta_faltante_elementos").text("Todo el tiempo está asignado");
					}else if (hours>=0&&minutes>=0){
						if(hours==0){
							$(".delta_faltante_elementos").text("Faltan "+minutes+"m por ingresar");
						}else if(minutes==0){
							$(".delta_faltante_elementos").text("Faltan "+hours+"h por ingresar");	
						}else{
							$(".delta_faltante_elementos").text("Faltan "+hours+"h "+minutes+"m por ingresar");	
						}
					}else if(hours<=0&&minutes<=0){
						if(minutes!=0){
							hours = hours + 1;
						}
						hours = hours * -1;
						minutes = minutes * -1;
						if(hours==0){
							$(".delta_faltante_elementos").text("Hay "+minutes+"m adicionales ingresado");
						}else if(minutes==0){
							$(".delta_faltante_elementos").text("Hay "+hours+"h adicional ingresada");
						}else{
							$(".delta_faltante_elementos").text("Hay "+hours+"h "+minutes+"m adicional ingresado");	
						}
					}
				}
				
				$(".hora_elemento, .minuto_elemento").change(function(){
					sumar_delta_elementos();
				});
				
				$(".hora_elemento").change();
				
				$(".btn-submit-modal").click(function(){
					$("#acepta-aprobar").val("N");
					$("#form-intervencion").submit();
				}); 
				
				$(".btn-submit-siguiente-modal").click(function(){
					$("#acepta-aprobar").val("S");
					$("#form-intervencion").submit();
				}); 
					
				var ocultar_opciones_tecnico = function(){
					$(".tecnico-id option").show();
					$(".tecnico-id").each(function() {
						var val = $(this).val();
						var id = $(this).attr("id");
						//console.log("id: " + id);
						//console.log("val: " + val);
						if(val != "") {
							$(".tecnico-id[id!='"+id+"'] option[value='"+val+"']").hide();
						}
						
						<?php if (isset($estado) && $estado == "4") { ?>
						if(val != "") {
							$(".tecnico-id[id='"+id+"'] option[value!='"+val+"']").hide();
						}
						<?php } ?>
					});
				};
				
				$(".tecnico-id").change(function(){
					ocultar_opciones_tecnico();			
				});
				
				$(".tecnico-id").change();
				
				// Quitar técnicos
				$(".quitar-tecnico").click(function(){
					if(confirm("¿Realmente desea quitar el técnico seleccionado?")) {
						$(this).closest('.row').remove();
						$(".tecnico-id").change();
					}
				});
				
				$(".agregar-tecnico").click(function(){
					var id = Math.floor(Math.random() * 90000) + 10000;
					var url_tipo_tecnico = "/Matrices/Selecttipotecnico";
					var faena_id = $("#faena_id").val();
					var html = '<div class="form-group">';
						html += '	<div class="row">';
						html += '			<div class="col-lg-9">';
						html += '<select class="form-control tecnico-id" id="tecnico-id-'+id+'" name="tecnico-id[]" required="required">';
					$.getJSON("/Matrices/Selecttecnico/"+faena_id, function (data) {
						html += '<option value="" selected="selected">Seleccione una opción</option>';
						$.each(data, function(i, value) {  
							html += '<option value="'+value.Usuario.id+'">'+value.Usuario.apellidos+' '+value.Usuario.nombres+'</option>';
						});
						html += '</select>';
						html += '			</div>';
						html += '			<div class="col-lg-2">';
						html += '<select class="form-control tipo-tecnico" id="tipo-tecnico-'+id+'" name="tipo-tecnico[]" required="required">';
						$.getJSON("/Matrices/Selecttipotecnico", function (data) {
							html += '<option value="" selected="selected">Seleccione una opción</option>';
							$.each(data, function(i, value) {  
								html += '<option value="'+value.TipoTecnico.id+'" unico="'+value.TipoTecnico.unico+'" requerido="'+value.TipoTecnico.requerido+'">'+value.TipoTecnico.nombre+'</option>';
							});
							html += '</select>';
							html += '			</div>';
							html += '			<div class="col-lg-1">';
							html += '				<a class="btn btn-sm red tooltips" id="quitar-tecnico-'+id+'" data-container="body" data-placement="top" data-original-title="Quitar técnico"><i class="fa fa-trash"></i>  </a>';
							html += '			</div>';
							html += '		</div>';
							html += '	</div>';
							console.log(html);
							$(".agregar-tecnico").closest('.portlet-body').append(html);
							$("#tecnico-id-"+id).on("change", function(event){
								ocultar_opciones_tecnico();	
							});
							$("#quitar-tecnico-"+id).on("click", function(event){
							    if(confirm("¿Realmente desea quitar el técnico seleccionado?")) {
									$(this).closest('.row').remove();
									(".tecnico-id").change();
								}
							});
							$(".tecnico-id").change();
						});		
					});			
				});
				
				// Quitar elemento
				$(".quitar-elemento").click(function(){
					if(confirm("¿Realmente desea quitar el elemento seleccionado?")) {
						$(this).closest('tr').remove();
						var elementos = parseInt($("#elementos").val());
						var i = 1;
						$(".tabla-elementos tbody tr").each(function(){
						    $(this).find('td:eq(0)').text(i);
						    i++;
						});
						elementos = elementos - 1;
						$("#elementos").val(elementos);
						sumar_delta_elementos();
						
					}
				});
				
				$('#modal_imagen_elemento').modal({ show: false});
				
				$(".ver-imagen").click(function(){
					var id = $(this).attr("id").replace("ver-imagen-", "");
					var motor_id = $("#motor_id").val();
					var sistema_id = $("#sistema-id-" + id).val();
					var subsistema_id = $("#subsistema-id-" + id).val();
					if(sistema_id != "" && subsistema_id != "") {
						$.get("/Administracion/motor_imagen_source/"+motor_id+"/"+sistema_id+"/"+subsistema_id, function (data) {
							$("#imagen_elemento_url").attr("src", "/images/motor/"+data);
							$("#modal_imagen_elemento").modal('show');
						});
					} else {
						$(".mensaje_modal").text("Debe seleccionar Sistema y Subsistema para ver la imagen.");
						$("#modal_mensaje_error").modal('show');
					}
				});
				
				$(".agregar-elemento").click(function(){
					var id = Math.floor(Math.random() * 90000) + 10000;
					var motor_id = $("#motor_id").val();
					var html = '<tr>';
						html += '<td>';
						html += '</td>';
						html += '<td>';
						html += '<select class="form-control sistema-id" id="sistema-id-'+id+'" name="sistema-id[]" required="required">';
					$.getJSON("/Matrices/select_sistema/"+motor_id, function (data) {
						html += '<option value="" selected="selected">Seleccione una opción</option>';
						$.each(data, function(i, value) {  
							html += "<option value=\""+value.Sistema.id+"\">"+value.Sistema.nombre+"</option>" ;
						});
						html += '</select>';
						html += '</td>';
					
						
						html += '<td>';
						html += '	<select class="form-control subsistema-id" id="subsistema-id-'+id+'" name="subsistema-id[]" required="required">';
						html += '		<option value="" selected="selected">Seleccione una opción</option>';
						html += '	</select>';
						html += '</td>';
						
						html += '<td>';
						html += '	<select class="form-control posicion-subsistema-id" id="posicion-subsistema-id-'+id+'" name="posicion-subsistema-id[]" required="required">';
						html += '		<option value="" selected="selected">Seleccione una opción</option>';
						html += '	</select>';
						html += '</td>';
						
						html += '<td align="center">';
						html += '	<a class="btn btn-sm blue tooltips ver-imagen" id="ver-imagen-'+id+'" data-container="body" data-placement="top" data-original-title="Ver imagen">';
						html += '		<i class="fa fa-image"></i>';
						html += '	</a>';
						html += '</td>';
						
						html += '<td>';
						html += '	<input type="text" id="id-elemento-'+id+'" class="form-control" name="id-elemento[]" value="" style="width: 34px;padding-left: 8px;padding-right:  7px;" />';
						html += '</td>';
						
						html += '<td>';
						html += '	<select class="form-control elemento-id" id="elemento-id-'+id+'" name="elemento-id[]" required="required">';
						html += '		<option value="" selected="selected">Seleccione una opción</option>';
						html += '	</select>';
						html += '</td>';
						
						html += '<td>';
						html += '	<select class="form-control posicion-elemento-id" id="posicion-elemento-id-'+id+'" name="posicion-elemento-id[]" required="required">';
						html += '		<option value="" selected="selected">Seleccione una opción</option>';
						html += '	</select>';
						html += '</td>';
						
						html += '<td>';
						html += '	<select class="form-control diagnostico-id" id="diagnostico-id-'+id+'" name="diagnostico-id[]" required="required">';
						html += '		<option value="" selected="selected">Seleccione una opción</option>';
						html += '	</select>';
						html += '</td>';
						
						html += '<td>';
						html += '	<select class="form-control solucion-id" id="solucion-id-'+id+'" name="solucion-id[]" required="required">';
						html += '		<option value="" selected="selected">Seleccione una opción</option>';
						
						$.getJSON("/Matrices/select_solucion/", function (data) {
							$.each(data, function(i, value) {  
								html += "<option value=\""+value.Solucion.id+"\">"+value.Solucion.nombre+"</option>" ;
							});
							html += '	</select>';
							html += '</td>';
							
							html += '<td>';
							html += '	<select class="form-control tipo-id" id="tipo-id-'+id+'" name="tipo-id[]" required="required">';
							html += '		<option value="" selected="selected">Seleccione una opción</option>';
							$.getJSON("/Matrices/select_tipo/", function (data) {
								$.each(data, function(i, value) {  
									html += "<option value=\""+value.TipoElemento.id+"\">"+value.TipoElemento.nombre+"</option>" ;
								});
								html += '	</select>';
								html += '</td>';
							
								html += '<td>';
								html += '	<input type="text" id="pn-saliente-'+id+'" class="form-control" name="pn_saliente[]" value="" />';
								html += '</td>';
								
								html += '<td>';
								html += '	<input type="text" id="pn-entrante-'+id+'" class="form-control" name="pn_entrante[]" value="" />';
								html += '</td>';
								
								html += '<td style="width: 50px;">';
								html += '	<input name="hora_elemento[]" id="hora_elemento_'+id+'" type="number" size="4" value="0" pattern="[0-9]*" min="0" class="form-control hora_elemento" style="width: 42px;padding-right: 1px;padding-left: 8px;" />';
								html += '</td>';
								
								html += '<td style="width: 50px;">';
								html += '	<select name="minuto_elemento[]" id="minuto_elemento_'+id+'" class="form-control minuto_elemento" style="width: 43px;">';
								for (var i = 0; i < 60; i++) {
									html += '<option value="'+i+'">'+i+'</option>';
								}
								html += '	</select>';
								html += '</td>';
								
								html += '<td>';
								html += '	<a class="btn btn-sm red tooltips quitar-elemento" id="quitar-elemento-'+id+'" data-container="body" data-placement="top" data-original-title="Quitar elemento">';
								html += '		<i class="fa fa-trash"></i>';
								html += '	</a>';
								html += '</td>';
								html += "</tr>";
								
								$(".tabla-elementos").append(html);
								
								var elementos = parseInt($("#elementos").val());
								var i = 1;
								$(".tabla-elementos tbody tr").each(function(){
								    $(this).find('td:eq(0)').text(i);
								    i++;
								});
								elementos = elementos + 1;
								$("#elementos").val(elementos);
								
								$("#quitar-elemento-"+id).on("click", function(event){
								    if(confirm("¿Realmente desea quitar el elemento seleccionado?")) {
										$(this).closest('tr').remove();
										var elementos = parseInt($("#elementos").val());
										var i = 1;
										$(".tabla-elementos tbody tr").each(function(){
										    $(this).find('td:eq(0)').text(i);
										    i++;
										});
										elementos = elementos - 1;
										$("#elementos").val(elementos);
										sumar_delta_elementos();
									}
								});
								
								$(".hora_elemento, .minuto_elemento").on("change", function(event){
									sumar_delta_elementos();
								});
								
								$("#sistema-id-"+id).on("change", function(event){
									var sistema_id = $(this).val();
								    $.getJSON("/Matrices/select_subsistema/"+motor_id+"/"+sistema_id, function (data) {
										var html = "";
										$("#subsistema-id-" + id + " option[value!='']").remove();
										$.each(data, function(i, value) {
											html += "<option value=\""+value.Subsistema.id+"\">"+value.Subsistema.nombre+"</option>" ;
										});
										$("#subsistema-id-" + id).append(html);
										$("#subsistema-id-" + id).val("");
										$("#elemento-id-" + id).val("");
										$("#diagnostico-id-" + id).val("");
										$("#id-elemento-"+id).val("");
										$("#posicion-subsistema-id-" + id).val("");
										$("#posicion-elemento-id-" + id).val("");
									});
								});
								
								$("#subsistema-id-"+id).on("change", function(event){
									var subsistema_id = $(this).val();
									var sistema_id = $("#sistema-id-"+id).val();
								    $.getJSON("/Matrices/select_elemento/"+motor_id+"/"+sistema_id+"/"+subsistema_id, function (data) {
										var html = "";
										$("#elemento-id-" + id + " option[value!='']").remove();
										$.each(data, function(i, value) {
											html += "<option value=\""+value.Elemento.id+"\" codigo=\""+value.Sistema_Subsistema_Motor_Elemento.codigo+"\">"+value.Sistema_Subsistema_Motor_Elemento.codigo+" - "+value.Elemento.nombre+"</option>" ;
										});
										$("#elemento-id-" + id).append(html);
										$("#elemento-id-" + id).val("");
										$("#diagnostico-id-" + id).val("");
										$("#id-elemento-"+id).val("");
										$("#posicion-subsistema-id-" + id).val("");
										$("#posicion-elemento-id-" + id).val("");
									});
									
									$.getJSON("/Matrices/select_posicion_subsistema/"+motor_id+"/"+sistema_id+"/"+subsistema_id, function (data) {
										var posicion_id = $("#posicion-subsistema-id-"+id).attr("posicion_id");
										var html = "";
										$("#posicion-subsistema-id-" + id + " option[value!='']").remove();
										$.each(data, function(i, value) {
											html += "<option value=\""+value.Posicion.id+"\">"+value.Posicion.nombre+"</option>" ;
										});
										$("#posicion-subsistema-id-" + id).append(html);
										$("#posicion-subsistema-id-" + id).val("");
									});
								});
								
								$("#elemento-id-"+id).on("change", function(event){
									var elemento_id = $(this).val();
									var sistema_id = $("#sistema-id-"+id).val();
									var subsistema_id = $("#subsistema-id-"+id).val();
								    $.getJSON("/Matrices/select_diagnostico/"+motor_id+"/"+sistema_id+"/"+subsistema_id+"/"+elemento_id, function (data) {
										var html = "";
										$("#diagnostico-id-" + id + " option[value!='']").remove();
										$.each(data, function(i, value) {
											html += "<option value=\""+value.Diagnostico.id+"\">"+value.Diagnostico.nombre+"</option>" ;
										});
										$("#diagnostico-id-" + id).append(html);
										$("#diagnostico-id-" + id).val("");
										$("#posicion-elemento-id-" + id).val("");
									});
									if($(this).find("option:selected").attr("codigo") != $("#id-elemento-"+id).val()) {
										$("#id-elemento-"+id).val($(this).find("option:selected").attr("codigo"));
									}
									$.getJSON("/Matrices/select_posicion_elemento/"+motor_id+"/"+sistema_id+"/"+subsistema_id+"/"+elemento_id, function (data) {
										var html = "";
										$("#posicion-elemento-id-" + id + " option[value!='']").remove();
										$.each(data, function(i, value) {
											html += "<option value=\""+value.Posicion.id+"\">"+value.Posicion.nombre+"</option>" ;
										});
										$("#posicion-elemento-id-" + id).append(html);
										$("#posicion-elemento-id-" + id).val("");
									});
								});
								
								$("#id-elemento-"+id).on("change", function(event){
									var val = $(this).val();
									var elemento_codigo = $("#id-elemento-"+id).find("option:selected").attr("codigo");
									if (val != elemento_codigo) {
										$("#elemento-id-" + id + " option[value!='']").removeAttr("selected");
										$("#elemento-id-" + id + " option[codigo='"+val+"']").attr("selected","selected");
										$("#elemento-id-"+id).change();
									}
								});
								
								$("#ver-imagen-"+id).on("click", function(event){
								var id = $(this).attr("id").replace("ver-imagen-", "");
									var motor_id = $("#motor_id").val();
									var sistema_id = $("#sistema-id-" + id).val();
									var subsistema_id = $("#subsistema-id-" + id).val();
									if(sistema_id != "" && subsistema_id != "") {
										$.get("/Administracion/motor_imagen_source/"+motor_id+"/"+sistema_id+"/"+subsistema_id, function (data) {
											$("#imagen_elemento_url").attr("src", "/images/motor/"+data);
											$("#modal_imagen_elemento").modal('show');
										});
									} else {
										$(".mensaje_modal").text("Debe seleccionar Sistema y Subsistema para ver la imagen.");
										$("#modal_mensaje_error").modal('show');
									}
								});
							});	
						});							
					});			
				});
				
				
				/* http://agosto.salmonsoftware.cl/Trabajo/Detalle2/47484 */
				var motor_id = $("#motor_id").val();
				
				$.getJSON("/Matrices/select_sistema/"+motor_id, function (data) {
					$(".sistema-id").each(function(){
						var sistema_id = $(this).attr("sistema_id");
						var id = $(this).attr("id").replace("sistema-id-","");
						var html = "";
						$.each(data, function(i, value) {
							if (value.Sistema.id == sistema_id) {
								html += "<option value=\""+value.Sistema.id+"\" selected=\"selected\">"+value.Sistema.nombre+"</option>" ;
							} else {
								html += "<option value=\""+value.Sistema.id+"\">"+value.Sistema.nombre+"</option>" ;
							}
						});
						$("#sistema-id-" + id).append(html);
						$.getJSON("/Matrices/select_subsistema/"+motor_id+"/"+sistema_id, function (data) {
							var subsistema_id = $("#subsistema-id-"+id).attr("subsistema_id");
							var html = "";
							$.each(data, function(i, value) {
								if (value.Subsistema.id == subsistema_id) {
									html += "<option value=\""+value.Subsistema.id+"\" selected=\"selected\">"+value.Subsistema.nombre+"</option>" ;
								} else {
									html += "<option value=\""+value.Subsistema.id+"\">"+value.Subsistema.nombre+"</option>" ;
								}
							});
							$("#subsistema-id-" + id).append(html);
							
							$.getJSON("/Matrices/select_posicion_subsistema/"+motor_id+"/"+sistema_id+"/"+subsistema_id, function (data) {
								var posicion_id = $("#posicion-subsistema-id-"+id).attr("posicion_id");
								var html = "";
								$.each(data, function(i, value) {
									if (value.Posicion.id == posicion_id) {
										html += "<option value=\""+value.Posicion.id+"\" selected=\"selected\">"+value.Posicion.nombre+"</option>" ;
									} else {
										html += "<option value=\""+value.Posicion.id+"\">"+value.Posicion.nombre+"</option>" ;
									}
								});
								$("#posicion-subsistema-id-" + id).append(html);
							});
							
							$.getJSON("/Matrices/select_elemento/"+motor_id+"/"+sistema_id+"/"+subsistema_id, function (data) {
								var codigo = $("#id-elemento-"+id).val();
								var elemento_id = $("#elemento-id-"+id).attr("elemento_id");
								var html = "";
								$.each(data, function(i, value) {
									if (value.Elemento.id == elemento_id && codigo == value.Sistema_Subsistema_Motor_Elemento.codigo) {
										html += "<option value=\""+value.Elemento.id+"\" selected=\"selected\" codigo=\""+value.Sistema_Subsistema_Motor_Elemento.codigo+"\">"+value.Sistema_Subsistema_Motor_Elemento.codigo+" - "+value.Elemento.nombre+"</option>" ;
									} else {
										html += "<option value=\""+value.Elemento.id+"\" codigo=\""+value.Sistema_Subsistema_Motor_Elemento.codigo+"\">"+value.Sistema_Subsistema_Motor_Elemento.codigo+" - "+value.Elemento.nombre+"</option>" ;
									}
								});
								$("#elemento-id-" + id).append(html);
								
								$.getJSON("/Matrices/select_posicion_elemento/"+motor_id+"/"+sistema_id+"/"+subsistema_id+"/"+elemento_id, function (data) {
								var posicion_id = $("#posicion-elemento-id-"+id).attr("posicion_id");
								var html = "";
								$.each(data, function(i, value) {
									if (value.Posicion.id == posicion_id) {
										html += "<option value=\""+value.Posicion.id+"\" selected=\"selected\">"+value.Posicion.nombre+"</option>" ;
									} else {
										html += "<option value=\""+value.Posicion.id+"\">"+value.Posicion.nombre+"</option>" ;
									}
								});
								$("#posicion-elemento-id-" + id).append(html);
							});
								
								$.getJSON("/Matrices/select_diagnostico/"+motor_id+"/"+sistema_id+"/"+subsistema_id+"/"+elemento_id, function (data) {
									var diagnostico_id = $("#diagnostico-id-"+id).attr("diagnostico_id");
									var html = "";
									$.each(data, function(i, value) {
										if (value.Diagnostico.id == diagnostico_id) {
											html += "<option value=\""+value.Diagnostico.id+"\" selected=\"selected\">"+value.Diagnostico.nombre+"</option>" ;
										} else {
											html += "<option value=\""+value.Diagnostico.id+"\">"+value.Diagnostico.nombre+"</option>" ;
										}
									});
									$("#diagnostico-id-" + id).append(html);
									
									$("#sistema-id-"+id).on("change", function(event){
										var sistema_id = $(this).val();
									    $.getJSON("/Matrices/select_subsistema/"+motor_id+"/"+sistema_id, function (data) {
											var html = "";
											$("#subsistema-id-" + id + " option[value!='']").remove();
											$.each(data, function(i, value) {
												html += "<option value=\""+value.Subsistema.id+"\">"+value.Subsistema.nombre+"</option>" ;
											});
											$("#subsistema-id-" + id).append(html);
											$("#subsistema-id-" + id).val("");
											$("#elemento-id-" + id).val("");
											$("#diagnostico-id-" + id).val("");
											$("#id-elemento-"+id).val("");
											$("#posicion-subsistema-id-" + id).val("");
											$("#posicion-elemento-id-" + id).val("");
										});
									});
									
									$("#subsistema-id-"+id).on("change", function(event){
										var subsistema_id = $(this).val();
										var sistema_id = $("#sistema-id-"+id).val();
									    $.getJSON("/Matrices/select_elemento/"+motor_id+"/"+sistema_id+"/"+subsistema_id, function (data) {
											var html = "";
											$("#elemento-id-" + id + " option[value!='']").remove();
											$.each(data, function(i, value) {
												html += "<option value=\""+value.Elemento.id+"\" codigo=\""+value.Sistema_Subsistema_Motor_Elemento.codigo+"\">"+value.Sistema_Subsistema_Motor_Elemento.codigo+" - "+value.Elemento.nombre+"</option>" ;
											});
											$("#elemento-id-" + id).append(html);
											$("#elemento-id-" + id).val("");
											$("#diagnostico-id-" + id).val("");
											$("#id-elemento-"+id).val("");
											$("#posicion-subsistema-id-" + id).val("");
											$("#posicion-elemento-id-" + id).val("");
										});
										
										$.getJSON("/Matrices/select_posicion_subsistema/"+motor_id+"/"+sistema_id+"/"+subsistema_id, function (data) {
											var html = "";
											$("#posicion-subsistema-id-" + id + " option[value!='']").remove();
											$.each(data, function(i, value) {
												html += "<option value=\""+value.Posicion.id+"\">"+value.Posicion.nombre+"</option>" ;
											});
											$("#posicion-subsistema-id-" + id).append(html);
											$("#posicion-subsistema-id-" + id).val("");
										});
									});
									
									$("#elemento-id-"+id).on("change", function(event){
										var elemento_id = $(this).val();
										var sistema_id = $("#sistema-id-"+id).val();
										var subsistema_id = $("#subsistema-id-"+id).val();
									    $.getJSON("/Matrices/select_diagnostico/"+motor_id+"/"+sistema_id+"/"+subsistema_id+"/"+elemento_id, function (data) {
											var html = "";
											$("#diagnostico-id-" + id + " option[value!='']").remove();
											$.each(data, function(i, value) {
												html += "<option value=\""+value.Diagnostico.id+"\">"+value.Diagnostico.nombre+"</option>" ;
											});
											$("#diagnostico-id-" + id).append(html);
											$("#diagnostico-id-" + id).val("");
											$("#posicion-elemento-id-" + id).val("");
										});
										if($(this).find("option:selected").attr("codigo") != $("#id-elemento-"+id).val()) {
											$("#id-elemento-"+id).val($(this).find("option:selected").attr("codigo"));
										}
										$.getJSON("/Matrices/select_posicion_elemento/"+motor_id+"/"+sistema_id+"/"+subsistema_id+"/"+elemento_id, function (data) {
											var html = "";
											$("#posicion-elemento-id-" + id + " option[value!='']").remove();
											$.each(data, function(i, value) {
												html += "<option value=\""+value.Posicion.id+"\">"+value.Posicion.nombre+"</option>" ;
											});
											$("#posicion-elemento-id-" + id).append(html);
											$("#posicion-elemento-id-" + id).val("");
										});
									});
									
									$("#id-elemento-"+id).on("change", function(event){
										var val = $(this).val();
										var elemento_codigo = $("#id-elemento-"+id).find("option:selected").attr("codigo");
										if (val != elemento_codigo) {
											$("#elemento-id-" + id + " option[value!='']").removeAttr("selected");
											$("#elemento-id-" + id + " option[codigo='"+val+"']").attr("selected","selected");
											$("#elemento-id-"+id).change();
										}
									});
									
								});
							});
						});		
					});
				});
			
				<?php } ?>
				
				$("#motivo_id").off();
				$("#motivo_id").change(function(){
					var motivo_id = $("#motivo_id").find("option:selected").val();
					var html = '';
					if (motivo_id != "") {
						html += "<option value=\"\">Todos</opcion>\n";
						$.get( "/MotivoCategoriaSintoma/get_categoria/" + motivo_id, function(data) {
							var obj = $.parseJSON(data);
							$.each(obj, function(i, item) {
								html += "<option value=\""+i+"\">"+item+"</option>\n";
							});
							$("#categoria_id").html(html);
							$("#categoria_id").change();
							<?php if(isset($categoria_id) && isset($motivo_id)){ ?>
							if(motivo_id == '<?php echo $motivo_id;?>') {
								$("#categoria_id").val('<?php echo $categoria_id; ?>');
								$("#categoria_id").change();
							}
							<?php } ?>
						});						
					} else {
						html += "<option value=\"\">Todos</opcion>\n";
						$("#categoria_id").html(html);
						$("#categoria_id").change();
					}					
				});
				
				$("#categoria_id").off(); 
				$("#categoria_id").change(function(){
					var categoria_id = $("#categoria_id").find("option:selected").val();
					var html = '';
					if (categoria_id != "") {
						html += "<option value=\"\">Todos</opcion>\n";
						$.get( "/MotivoCategoriaSintoma/get_sintoma/" + categoria_id, function(data) {
							var obj = $.parseJSON(data);
							$.each(obj, function(i, item) {
								html += "<option value=\""+i+"\">"+item+"</option>\n";
							});
							$("#sintoma_id").html(html);
							$("#sintoma_id").change();
							<?php if(isset($sintoma_id) ){ ?>
							$("#sintoma_id").val('<?php echo $sintoma_id; ?>');
							$("#sintoma_id").change();
							<?php } ?>
						});
					} else {
						html += "<option value=\"\">Todos</opcion>\n";
						$("#sintoma_id").html(html);
						$("#sintoma_id").change(); 
					}					
				});
				
				$("#motivo_id").change();
				
				/*
					$(".portlet-title").click(function(){
					$(this).closest(".portlet-body").toggle();
				});
				*/
				
				
				<?php if(isset($pautaMantencion) && $pautaMantencion == TRUE) { ?>
				var i = 1;
				$("input[type='checkbox']").each(function(){
					var number = $(this).closest("tr").find("td:first-child").text();
					$(this).attr("name", "Pm_"+number+"_na");
					$(this).attr("id", "Pm_"+number+"_na");
					$(this).attr("number", number);
					$(this).addClass("form-control");
					$(this).addClass("na_change");
				}); 
				
				i = 1;
				$("select").each(function(){
					var number = $(this).closest("tr").find("td:first-child").text();
					$(this).attr("name", "Pm_"+number+"_responsable");
					$(this).attr("id", "Pm_"+number+"_responsable");
					<?php if(isset($usuarios) && is_array($usuarios)) { ?>
					var html = "";
					html += "<option value=\"\"></<option>"+"\n";
					<?php foreach($usuarios as $usuario) { ?>
						html += "<option value=\"<?php echo $usuario["Usuario"]["id"];?>\"><?php echo $usuario["Usuario"]["nombres"];?> <?php echo $usuario["Usuario"]["apellidos"];?></<option>"+"\n";
					<?php } ?>
					$(this).html(html);
					<?php } ?>
					$(this).addClass("form-control");
					i++;
				});
				
				$("input[type='radio']").each(function(){
					var number = $(this).closest("tr").find("td:first-child").text();
					if($(this).parent().index() == 2){
						$(this).attr("name", "Pm_"+number+"_sino");
						$(this).attr("id", "Pm_"+number+"_si");
						$(this).attr("value", "1");
					} else if($(this).parent().index() == 3){
						$(this).attr("name", "Pm_"+number+"_sino");
						$(this).attr("id", "Pm_"+number+"_no");
						$(this).attr("value", "1");
					}  
					$(this).addClass("form-control");
				});
				
				$("input[type='text']").each(function(){
					var number = $(this).closest("tr").find("td:first-child").text();
					if($(this).parent().index() == 4){
						$(this).attr("name", "Pm_"+number+"_medicion");
						$(this).attr("id", "Pm_"+number+"_medicion");
					}else if($(this).parent().index() == 7){
						$(this).attr("name", "Pm_"+number+"_obs");
						$(this).attr("id", "Pm_"+number+"_obs");
					}
					$(this).addClass("form-control");
				}); 
				
				$(".na_change").change(function(){
					var number = $(this).attr("number");
					console.log(number);
					if($(this).is(':checked')){
						console.log("is checked");
						$("#Pm_"+number+"_si").attr("disabled", "disabled");
						$("#Pm_"+number+"_no").attr("disabled", "disabled");
						$("#Pm_"+number+"_medicion").attr("disabled", "disabled");
					} else {
						console.log("not checked");
						$("#Pm_"+number+"_si").removeAttr("disabled");
						$("#Pm_"+number+"_no").removeAttr("disabled");
						$("#Pm_"+number+"_medicion").removeAttr("disabled");
					}
				});
				
				<?php if(isset($json) && is_array($json)) {
					foreach($json as $key => $value){
						if (strpos($key, 'Pm_') === 0) {
							//print_r("$key: $value");
							if (strpos($key, '_medicion') !== false) {
								echo "$(\"#$key\").val('$value');"."\n";
							}elseif (strpos($key, '_responsable') !== false) {
								echo "$(\"#$key\").val('$value');"."\n";
							}elseif (strpos($key, '_obs') !== false) {
								echo "$(\"#$key\").val('$value');"."\n";
							}elseif (strpos($key, '_na') !== false) {
								echo "$(\"#$key\").attr('checked','checked');"."\n";
							}elseif (strpos($key, '_si') !== false) {
								echo "$(\"#$key\").attr('checked','checked');"."\n";
							}elseif (strpos($key, '_no') !== false) {
								echo "$(\"#$key\").attr('checked','checked');"."\n";
							}
						}
					}
				} ?>
				
				$(".na_change").change();
				
				$(".btn-pauta-mantencion").click(function(){
					// Revision de data ingresada
					var pauta_ok = true;
					
					$("input[type='checkbox']").each(function(){
						if(!$(this).is(':checked')) {
							var number = $(this).attr("number");
							console.log(number);
							console.log($("#Pm_"+number+"_si").is(':checked'));
							console.log($("#Pm_"+number+"_no").is(':checked'));
							console.log($("#Pm_"+number+"_responsable").val());
							// Si esta seleccionado si o no y no esta seleccionado tecnico
							
							/*if($("#Pm_"+number+"_medicion") != undefined && $("#Pm_"+number+"_medicion") != null && $("#Pm_"+number+"_medicion").val() != "" && $("#Pm_"+number+"_responsable").val() == "") {
								console.log("no ok medicion y responsable " + number)
								pauta_ok = false;
							}
							
							if ($("#Pm_"+number+"_si") != undefined && $("#Pm_"+number+"_si") != undefined && ($("#Pm_"+number+"_si").is(':checked') || $("#Pm_"+number+"_no").is(':checked')) && $("#Pm_"+number+"_responsable").val() == ""){
								console.log("no ok si no responsable " + number)
								pauta_ok = false;
							}	
							
							if($("#Pm_"+number+"_responsable").val() != "" && $("#Pm_"+number+"_medicion").val() == "" && $("#Pm_"+number+"_obs").val() == "" && !$("#Pm_"+number+"_si").is(':checked') && !$("#Pm_"+number+"_no").is(':checked')) {
								console.log("no ok responsable medicion obs si no " + number)
								pauta_ok = false;
							}*/
						}
					});
					
					if(pauta_ok) {
						$('#confirma_guardado').modal('show');
					} else {
						$(".mensaje_modal").text("Debe revisar la pauta de mantención, hay datos erróneamente ingresados.");
						$('#modal_mensaje_error').modal('show');
					}
					return false;
				});
				
				$(".btn-submit-modal").unbind("click");
				$(".btn-submit-modal").click(function(){
					$("input[type='radio']").each(function(){
						$(this).attr("name", $(this).attr("id"));
					});
					$("#form-pauta-mantencion").submit();
				});
				
				<?php if (isset($estado) && $estado == "4") { ?>
					$("select, input, textarea").attr("disabled","disabled");
				<?php } ?>
				
				<?php } ?>
				
				<?php if(isset($pautaPuestaMarcha) && $pautaPuestaMarcha == TRUE) { ?>
				$("input[type='checkbox']").each(function(){
					$(this).addClass("form-control");
				}); 
				$("input[type='text']").each(function(){
					$(this).addClass("form-control");
				});
				$("input[type='radio']").each(function(){
					$(this).addClass("form-control");
				});
				$("input[type='date']").each(function(){
					$(this).addClass("form-control");
				});
				$("input[type='number']").each(function(){
					$(this).addClass("form-control"); 
				});
				$("select").each(function(){
					$(this).addClass("form-control");
				});
				$("textarea").each(function(){
					$(this).addClass("form-control");
				});
				
				<?php if(isset($json) && is_array($json)) {
					foreach($json as $key => $value){
						if (strpos($key, 'Ppm_') === 0) {
							//print_r("$key: $value");
							if (strpos($key, '_na') !== false && $value != "False") {
								echo "$(\"#$key\").attr('checked','checked');"."\n";
							}elseif (strpos($key, '_si') !== false && $value != "False") {
								echo "$(\"#$key\").attr('checked','checked');"."\n";
							}elseif (strpos($key, '_no') !== false && $value != "False") {
								echo "$(\"#$key\").attr('checked','checked');"."\n";
							}else{
								echo "$(\"#$key\").val('$value');"."\n";
							}
						}
					}
				} 
				
				if(isset($Ppm_01_fecha_inspeccion)){
					echo "$(\"#Ppm_01_fecha_inspeccion\").val('$Ppm_01_fecha_inspeccion');";
				}
				?>
				
				$(".btn-pauta-mantencion").click(function(){
					// Revision de data ingresada
					var pauta_ok = true;
					
					if(pauta_ok) {
						$('#confirma_guardado').modal('show');
					} else {
						$(".mensaje_modal").text("Debe revisar la pauta de puesta en marcha, hay datos erróneamente ingresados.");
						$('#modal_mensaje_error').modal('show');
					}
					return false;
				});
				
				$(".btn-submit-modal").unbind("click");
				$(".btn-submit-modal").click(function(){
					$("input[type='radio']").each(function(){
						$(this).attr("name", $(this).attr("id"));
					});
					$("#form-pauta-mantencion").submit();
				});
				
				<?php if (isset($estado) && $estado == "4") { ?>
					$("select, input, textarea").attr("disabled","disabled");
				<?php } ?>
				
				<?php } ?>
				
				<?php if (isset($estado) && $estado == "4") { ?>
					$("select, input, textarea").attr("readonly","readonly");
					$(".agregar-tecnico, .quitar-tecnico, .agregar-elemento, .quitar-elemento").hide();
				<?php } ?>
				
				
				<?php if ($intervencion['Planificacion']['padre'] != NULL && $intervencion['Planificacion']['padre'] != '') { ?>
					$("#motivo_id").attr("disabled","disabled");
					$("#categoria_id").attr("disabled","disabled");
					$("#sintoma_id").attr("disabled","disabled");
				<?php } ?>
            });
            /**
			 * añadido por Victor Smith
			 */
			let evaluarRequired = ( sw ) => {
				if ( sw ) {
					//inicio delta
					$('#inicio_delta_operacion_fecha').attr('required', 'required')
					$('#inicio_delta_operacion_hora').attr('required', 'required')
					$('#inicio_delta_operacion_minuto').attr('required', 'required')
					$('#inicio_delta_operacion_periodo').attr('required', 'required')
					// termino turno
					$('#termino_turno_fecha').attr('required', 'required')
					$('#termino_turno_hora').attr('required', 'required')
					$('#termino_turno_minuto').attr('required', 'required')
					$('#termino_turno_periodo').attr('required', 'required')
					$('.delta_add_bitacora').attr('required', 'required')
				} else {
					//inicio delta
					$('#inicio_delta_operacion_fecha').removeAttr('required')
					$('#inicio_delta_operacion_hora').removeAttr('required')
					$('#inicio_delta_operacion_minuto').removeAttr('required')
					$('#inicio_delta_operacion_periodo').removeAttr('required')
					// termino turno
					$('#termino_turno_fecha').removeAttr('required')
					$('#termino_turno_hora').removeAttr('required')
					$('#termino_turno_minuto').removeAttr('required')
					$('#termino_turno_periodo').removeAttr('required')
					$('.delta_add_bitacora').removeAttr('required')
				}
			}
        </script>
        <style>
			<?php if (isset($estado) && $estado == "4") { ?>
			select option {
				display: none;
			}
			<?php } ?>
		</style>
    </body>
<!--2018 &copy; Salmon Software Chile - e8078325-d0ed-4cf1-ad9c-7a46b6eba910-->
</html>
