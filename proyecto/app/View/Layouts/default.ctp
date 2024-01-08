<?php
	ini_set('memory_limit','1024M');
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	//$this->Session->write("ref", $actual_link);
	$usuario = $this->Session->read('Usuario');
	$nivel = $this->Session->read('Nivel');
	$admin = $this->Session->read('esAdmin');
	$faena = $this->Session->read('faena');
	$faenas = $this->Session->read('faenas'); 
	$faena_id = $this->Session->read('faena_id');
	
	if ($usuario == NULL) {
		//$this->Session->setFlash('Su sesión ha expirado, debe ingresar nuevamente al sistema.', 'guardar_exito');
		//header("Location: /Login?r=$actual_link");
		//exit;
	}
	
	$esSupervisorDCC=false;
	$esSupervisorCliente=false;
	$esAdministrador=false;
	$esGestion=false;
	$esAsesor=false;
	$esPlanificador=false;
	
	//switch ($nivel['id']) {
	switch ($nivel) {
		case 2:
			$nivel=array();
			$nivel['nombre']="Supervisor DCC";
			$esSupervisorDCC=true;
			break;
		case 3:
			$nivel=array();
			$nivel['nombre']="Supervisor Cliente";
			$esSupervisorCliente=true;
			break;
		case 4:
			$nivel=array();
			$nivel['nombre']="Administrador";
			$esAdministrador=true;
			break;
		case 5:
			$nivel=array();
			$nivel['nombre']="Gestión";
			$esGestion=true;
			break;
		case 6:
			$nivel=array();
			$nivel['nombre']="Asesor Técnico";
			$esAsesor=true;
			break;
		case 7:
			$nivel=array();
			$nivel['nombre']="Planificador";
			$esPlanificador=true;
			break;
	}
	
	$faenaActual = "Todas las Faenas";
	
	if ($this->Session->read('st') == "1") {
		$nivel['nombre'] = "Supervisor Temporal";
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/2.2.2/js/bootstrap.min.js" charset="utf-8"></script>-->

	<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
	
	<!-- Latest compiled and minified CSS -->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>-->
	<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
	<!--
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
-->
	
	<script type="text/javascript" src="/js/plugins/spinner/ui.spinner.js"></script>
	<script type="text/javascript" src="/js/plugins/spinner/jquery.mousewheel.js"></script>
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	
	<script type="text/javascript" src="/js/plugins/charts/excanvas.min.js"></script>
	<script type="text/javascript" src="/js/plugins/charts/jquery.sparkline.min.js"></script>
	
	<script type="text/javascript" src="/js/plugins/forms/uniform.js"></script>
	
	<script type="text/javascript" src="/js/plugins/forms/jquery.cleditor.js"></script>
	
	<script type="text/javascript" src="/js/plugins/forms/jquery.validationEngine-en.js"></script>
	<script type="text/javascript" src="/js/plugins/forms/jquery.validationEngine.js"></script>

	<script type="text/javascript" src="/js/plugins/forms/jquery.tagsinput.min.js"></script>
	<script type="text/javascript" src="/js/plugins/forms/autogrowtextarea.js"></script>
	<script type="text/javascript" src="/js/plugins/forms/jquery.maskedinput.min.js"></script>
	<script type="text/javascript" src="/js/plugins/forms/jquery.dualListBox.js"></script>
	<script type="text/javascript" src="/js/plugins/forms/jquery.inputlimiter.min.js"></script>
	<script type="text/javascript" src="/js/plugins/forms/chosen.jquery.min.js"></script>
	
	<script type="text/javascript" src="/js/plugins/wizard/jquery.form.js"></script>
	<script type="text/javascript" src="/js/plugins/wizard/jquery.validate.min.js"></script>
	<script type="text/javascript" src="/js/plugins/wizard/jquery.form.wizard.js"></script>

	<script type="text/javascript" src="/js/plugins/uploader/plupload.js"></script>
	<script type="text/javascript" src="/js/plugins/uploader/plupload.html5.js"></script>
	<script type="text/javascript" src="/js/plugins/uploader/plupload.html4.js"></script>
	<script type="text/javascript" src="/js/plugins/uploader/jquery.plupload.queue.js"></script>

	<script type="text/javascript" src="/js/plugins/tables/datatable.js"></script>
	<script type="text/javascript" src="/js/plugins/tables/tablesort.min.js"></script>
	<script type="text/javascript" src="/js/plugins/tables/resizable.min.js"></script>

	<script type="text/javascript" src="/js/plugins/calendar.min.js"></script>
	<script type="text/javascript" src="/js/plugins/elfinder.min.js"></script>

	<script type="text/javascript" src="/js/plugins/ui/jquery.tipsy.js"></script>
	<script type="text/javascript" src="/js/plugins/ui/jquery.collapsible.min.js"></script>
	<script type="text/javascript" src="/js/plugins/ui/jquery.prettyPhoto.js"></script>
	<script type="text/javascript" src="/js/plugins/ui/jquery.progress.js"></script>
	<script type="text/javascript" src="/js/plugins/ui/jquery.timeentry.min.js"></script>
	<script type="text/javascript" src="/js/plugins/ui/jquery.colorpicker.js"></script>
	<script type="text/javascript" src="/js/plugins/ui/jquery.jgrowl.js"></script>
	<script type="text/javascript" src="/js/plugins/ui/jquery.breadcrumbs.js"></script>
	<script type="text/javascript" src="/js/plugins/ui/jquery.sourcerer.js"></script>

	<script type="text/javascript" src="/js/custom.js?<?php echo time();?>"></script>
	
	<script type="text/javascript" src="/fusioncharts/fusioncharts.js"></script>
	<script type="text/javascript" src="/fusioncharts/themes/fusioncharts.theme.zune.js"></script>
	
	<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
	
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
	<?php
		if (strpos($_SERVER["REQUEST_URI"], '/admin/') !== false) {
			echo $this->Html->css('cake.generic');
		}
		
		//echo $this->Html->meta('icon');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	
	<link type="text/css" href="/css/main.css?<?php echo time();?>" rel="stylesheet" />
	<link type="text/css" href="/css/menu_fix.css?<?php echo time();?>" rel="stylesheet" />
	<link rel="shortcut icon" href="/favicon.ico" />

	<title>DBM - <?php echo @$titulo; ?></title>
</head>

<body>
	<div id="transparentbg"></div>
	<nav>
		<ul>
			<li><a href="/Principal">Inicio</a></li>
			<?php if ($esSupervisorDCC||$esPlanificador) { ?>
			<li><a href="#">Trabajos<!--<strong style="margin-top: -5px;-moz-border-radius: 2px;-webkit-border-radius: 2px;-khtml-border-radius: 2px;border-radius: 2px;background-color:#DC3F3E;  margin-left: 10px;padding: 2px;font-size: 10px;color: white;"><?php echo $this->Session->read('Trabajos');?></strong>--></a>
				<ul class="sub">
					<li><a href="/Planificacion" title="">Planificar</a></li>
					<?php if ($esSupervisorDCC==true){ ?>
						<li><a href="/Planificacion/historial?estado=7" title="">Historial</a></li>		
					<?php } else { ?>
						<li><a href="/Planificacion/historial" title="">Historial</a></li>	
					<?php } ?>					
				</ul>
			</li>
			<li class="typo"><a href="#"><span>Registros</span></a>
				<ul class="sub">
					<li><a href="/Backlog/administrar" title="">Backlogs</a></li>
				</ul>
			</li>
			<?php } elseif ($esSupervisorCliente) { ?>
			<li class="notebook"><a href="#"><span>Trabajos</span><strong style="margin-top: -5px;-moz-border-radius: 2px;-webkit-border-radius: 2px;-khtml-border-radius: 2px;border-radius: 2px;background-color:#DC3F3E;  margin-left: 10px;padding: 2px;font-size: 10px;color: white;"><?php echo $this->Session->read('Trabajos');?></strong></a>
				<ul class="sub">
					<li><a href="/Cliente" title="">Revisar<strong style="margin-top: -5px;-moz-border-radius: 2px;-webkit-border-radius: 2px;-khtml-border-radius: 2px;border-radius: 2px;background-color:#DC3F3E;  margin-left: 10px;padding: 2px;font-size: 10px;"><?php echo $this->Session->read('Trabajos');?></strong></a></li>
					<li><a href="/Cliente/historial" title="">Historial</a></li>       
				</ul>
			</li>
			<?php } elseif ($esGestion||$esAsesor) { ?>
			<li><a href="#" title=""><span>Reportes</span></a>
				<ul class="sub">
					<li><a href="#" title="">Incidencia Espera</a></li>
					<li><a href="#" title="">Incidencia Aprobación</a></li>  
					<li><a href="#" title="">Operacional</a></li> 
					<li><a href="/Reporte/base_report" title="">Base</a></li> 
					<li><a href="/Reporte/control_detenciones" title="">Control de Detenciones</a></li> 
					<li><a href="/Reporte/efectividad_trabajos" title="">Efectividad Trabajos</a></li> 
					<li><a href="#" title="">Horómetro</a></li>
					<li><a href="#" title="">Consolidada</a></li>      
					<li><a href="#" title="">Backlog</a></li>    
					<li><a href="#" title="">Puesta en Marcha</a></li>  
				</ul>
			</li>
			<?php } else if ($esAdministrador) { ?>
			<li class="forms"><a href="#"><span>Cargar Archivos</span></a>
				<ul class="sub">
					<li><a href="/Herramientas/CargarDotacion" title="">Dotación</a></li>
					<li><a href="/Herramientas/Estado" title="">Estado Motor</a></li>
					<li><a href="#" title="">Matriz Motor</a>
						<ul class="sub">
							<li><a href="/Herramientas/SistemaSubsistema" title="">Sistema</a></li>  
							<li><a href="/Herramientas/ElementoGeneral" title="">Elemento</a></li>
							<li><a href="/Herramientas/ElementoDiagnostico" title="">Diagnóstico</a></li>  
							<li><a href="/Herramientas/ElementoPool" title="">Pool</a></li>  
						</ul>
					</li>  
				</ul>
			</li>
			
			<li class="forms"><a href="#"><span>Matrices</span></a>
				<ul class="sub">  
					<li><a href="/matrices/faenas" title="">Faenas</a></li> 
					<li><a href="/matrices/motores" title="">Motores</a></li>
					<li><a href="/matrices/flotas" title="">Flotas</a></li> 
					<li><a href="/matrices/equipos" title="">Estado Motor</a></li>
                    <li><a href="/matrices/soluciones" title="">Soluciones</a></li>
                    <li><a href="/matrices/sintomas" title="">Sintomas</a></li>
                    <li><a href="/Usuario/" title="">Dotación</a></li>
					<li><a href="#" title="">Matriz Motor</a>
						<ul class="sub">
							<li><a href="/matrices/sistemas" title="">Sistema</a></li>
							<li><a href="/matrices/elementos" title="">Elemento</a></li>
							<li><a href="/matrices/diagnosticos" title="">Diagnóstico</a></li>
							<li><a href="/matrices/pool" title="">Pool</a></li>
							
						</ul>
					</li>
				</ul>
			</li>
            <li class="typo"><a href="#"><span>Registros</span></a>
				<ul class="sub">
					<li><a href="/Backlog/administrar" title="">Backlogs</a></li>
					<li><a href="/Administracion/fix_intervenciones/" title="">Corrección Int.</a></li>
				</ul>
			</li>
            <!--
            <li class="forms"><a href="#"><span>Formularios</span></a>
				<ul class="sub">
					<li><a href="#" title="">Pautas de Mantención</a></li>
					<li><a href="#" title="">Puesta en Marcha</a></li>
				</ul>
			</li>
            -->
            <!--
			<li class="forms"><a href="#"><span>Configuración</span></a>
				<ul class="sub">
					<li><a href="/Configuracion/escalagraficos" title="">Escala Gráficos</a></li>
					<li><a href="/Herramientas/clavetemporal" title="">Clave Temporal</a></li>
				</ul>
			</li>
            -->
			<?php } ?>
		</ul>
	</nav>
	<!-- Contenido -->
	<div id="rightSide">
		<!-- Navegación Superior -->
		<div class="topNav">
			<div class="wrapper"> 
				<div class="welcome" style="display:none;"><a href="#" title=""><img src="/images/userPic.png" alt="" /></a><span title="Su tipo de usuario es <?php echo $nivel['nombre'];?>." style="cursor: help;"><?php echo $usuario['usuario'];?>! (<?php echo $nivel['nombre'];?>)</span></div>
				<div class="userNav">
					<ul>
						<li>
						<?php if ($this->Session->read('CambiaPerfil') && $faena_id == "0") { ?>
						<a href="#" style="cursor: default;" title="Usted está trabajando en <?php echo $faenaActual;?>."><img src="/images/icons/topnav/settings.png" alt="" /><span><?php echo $faenaActual;?></span></a>
						<?php } else { ?>
						<a href="#" style="cursor: default;" title="Usted está trabajando en <?php echo $faena;?>."><img src="/images/icons/topnav/settings.png" alt="" /><span><?php echo $faena;?></span></a>
						<?php } ?>
						</li>
						<li><a href="/Usuario/Perfil" title=""><img src="/images/userPic.png" alt="" style="margin-top: 8px;" /><span title="Ver Perfil"><?php echo $usuario['usuario'];?> (<?php echo $nivel['nombre'];?>)</span></a></li>
						<?php /* if (!$esAdministrador && !$esGestion) { ?>
							<?php 
								$url = "";
								if ($esSupervisorDCC) {$url = "/Trabajo/revisar";}
								if ($esSupervisorCliente) {$url = "/Cliente";}
							?>
						<!--<li><a href="<?php echo $url;?>" title="<?php if ($this->Session->read('Trabajos') == 0) {echo "No tiene trabajos pendientes de revisión.";} elseif ($this->Session->read("Trabajos") == 1) { echo "Tiene un trabajo pendiente de revisión.";} else {echo "Tiene {$this->Session->read("Trabajos")} trabajos pendientes de revisión.";} ?>"><img src="/images/icons/topnav/tasks.png" alt="" /><span>Revisar</span><span class="numberTop"><?php echo $this->Session->read('Trabajos');?></span></a></li>-->
						<?php } */ ?>
                        <?php if ($this->Session->read('CambiaPerfil') || is_array($faenas)) { ?>
                        <li class="dd2"><a title=""><img src="/images/icons/topnav/settings.png" alt=""><span>Cambiar Faena</span></a>
							<ul class="userDropdown2" style="display: none;">
								<?php if ($this->Session->read('CambiaPerfil')) { ?>
								<li><a href="/Nivel/faena/0" title="">Todas las Faenas</a></li>
								<?php } ?>
								<?php foreach ($faenas as $key => $value) { 
                                    if ($faena_id == $key) {
                                        $faenaActual = $value;
                                    }
                                ?>
                                <li><a href="/Nivel/faena/<?php echo $value["Faena"]["id"];?>" title=""><?php echo $value["Faena"]["nombre"]; ?></a></li>
                                <?php } ?>
							</ul>
						</li>
                        <?php } ?>
						<?php if ($this->Session->read('CambiaPerfil')) { ?>
						<li class="dd"><a title=""><img src="/images/icons/topnav/profile.png" alt=""><span>Cambiar Perfil</span></a>
							<ul class="userDropdown" style="display: none;">
								<li><a href="/Nivel/cambiar/4" title="" class="">Administrador</a></li>
								<li><a href="/Nivel/cambiar/2" title="" class="">Supervisor DCC</a></li>
								<li><a href="/Nivel/cambiar/3" title="" class="">Supervisor Cliente</a></li>
								<li><a href="/Nivel/cambiar/7" title="" class="">Planificador</a></li>
								<li><a href="/Nivel/cambiar/5" title="" class="">Gestión</a></li>
								<li><a href="/Nivel/cambiar/6" title="" class="">Asesor Técnico</a></li>
								
							</ul>
						</li>
						<?php } ?>
						<?php
							if (is_array($this->Session->read('niveles'))) {
						?>
						<li class="dd3"><a title=""><img src="/images/icons/topnav/profile.png" alt=""><span>Cambiar Perfil</span></a>
							<ul class="userDropdown3" style="display: none;">
						<?php
								foreach ($this->Session->read('niveles') as $key => $value) { 
									if ($value == 2) {
										$nivel = "Supervisor DCC";
									} elseif ($value == 5) {
										$nivel = "Gestión";
									} elseif ($value == 3) {
										$nivel = "Supervisor Cliente";
									} elseif ($value == 6) {
										$nivel = "Asesor Técnico";
									} elseif ($value == 7) {
										$nivel = "Planificador";
									}
						?>
								<li><a href="/Nivel/cambiar/<?php echo $value;?>" title="" class=""><?php echo $nivel;?></a></li>
						<?php
								}
						?>
							</ul>
						</li>
						<?php
							}
						?>
						<li><a href="/Login" onclick="return confirm('¿Está seguro que desea salir del sistema?');" title=""><img src="/images/icons/topnav/logout.png" alt="" /><span>Salir</span></a></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="titleArea">
		  <img src="/images/icon_dbm.png" alt="" style="float: left; height: 80px;margin-left: 10px;margin-right: 10px;" />
		  <div class="wrapper">
				<div class="pageTitle">
					<h5 style="font-size: 2.0em;margin-top: 7px;line-height: normal;">DBM<br />Data Business Mining</h5>
					<span>Cummins Chile S.A.</span> 
				</div>
			  <div class="clear"></div>
			</div>
		</div>
		<div class="line"></div>
		<?php echo $this->fetch('content'); ?>
		<div id="footer">
			<div class="wrapper">DBM 3.0</div>
		</div>
	</div>
	<div class="clear"></div>
</body>
</html>