<?php
	App::uses('ConnectionManager', 'Model'); 
	App::uses('CakeEmail', 'Network/Email');
	App::import('Controller', 'Utilidades');
	App::import('Controller', 'UtilidadesReporte');
	App::import('Vendor', 'Classes/PHPExcel');
	
	class CronController extends AppController {

		public $components = array('AWSSES','Session');

		public function index (){
			$this->layout = null;
			$this->envio_correos();
			$this->reporte_correo_pendientes();
			exit;
		}
		
		public function envio_correos(){
			$this->layout = null;
			$util = new UtilidadesController();
			$this->loadModel('Planificacion');
			$this->loadModel('UsuarioFaena');
			$this->loadModel('UsuarioNivel');
			$this->loadModel('Inactividad');
			$this->loadModel('Usuario');
			$this->layout = NULL;
			$resultados = $this->Planificacion->find('all', array(
				'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad'),
				'conditions' => array("(Planificacion.alert_mail IS NULL OR Planificacion.alert_mail = 2) AND Planificacion.estado = 7 AND Planificacion.terminado = 'S'"),
				'recursive' => 1
			));
			$email = new CakeEmail();
			foreach ($resultados as $resultado) {
				if (isset($resultado['Planificacion'])) {
					if (!is_numeric($resultado['Planificacion']['alert_mail'])) {
						print_r($resultado["Planificacion"]["id"]);
						$data = array(
							'id' => $resultado["Planificacion"]["id"],
							'alert_mail' => "1"
						);
						$this->Planificacion->save($data);
						if($resultado["Planificacion"]["tipointervencion"] == 'BL') {
							$resultado["Planificacion"]["tipointervencion"] = 'OP';
						}
						$faena_id = $resultado["Planificacion"]["faena_id"];
						$json = str_replace(array('\\"',"'",'\\n','\\r'),array('','','',''),$resultado["Planificacion"]["json"]);
						$json = json_decode($json, true);
						$tecnico_id = "";
						$tecnico_id = $json["UserID"];
						$supervisor_d = "";
						$supervisor_d = @$resultado["Planificacion"]["supervisor_responsable"];
						$tecnico = $util->getTecnicoFull($json["UserID"]);
						$supervisor = $util->getTecnicoFull($supervisor_d);
						$fecha_intervencion = strtotime($resultado["Planificacion"]["fecha"].' '.$resultado["Planificacion"]["hora"]);
						$resultado["Faena"]["nombre"] = strtoupper($resultado["Faena"]["nombre"]);
						$resultado["Flota"]["nombre"] = strtoupper($resultado["Flota"]["nombre"]);
						$resultado["Unidad"]["unidad"] = strtoupper($resultado["Unidad"]["unidad"]);
						
						$html = "<html>";
						$html .= "<body>";
						$html .= "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
						$html .= "<tr style=\"background-color: #808080; color: white;\">";
						$html .= "<td style=\"background-color: #808080; color: white; text-align: center;\" colspan=\"2\">Resumen Intervención</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td width=\"25%\">Folio</td>";
						$html .= "<td>{$resultado["Planificacion"]["id"]}</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td width=\"25%\">Estado</td>";
						$html .= "<td>SINCRONIZADA</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td>Faena</td>";
						$html .= "<td>{$resultado["Faena"]["nombre"]}</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td>Flota</td>";
						$html .= "<td>{$resultado["Flota"]["nombre"]}</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td>Equipo</td>";
						$html .= "<td>{$resultado["Unidad"]["unidad"]}</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td nowrap>Fecha Intervención</td>";
						$html .= "<td nowrap>".date("d-m-Y h:i A", $fecha_intervencion)."</td>";
						$html .= "</tr>";
						$html .= "<td nowrap>Duración Intervención</td>";
						$html .= "<td nowrap>".$resultado["Planificacion"]["tiempo_trabajo"]."</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td nowrap>Fecha Guardado</td>";
						$html .= "<td nowrap>".date("d-m-Y h:i A",@strtotime($resultado['Planificacion']['fecha_guardado']))."</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td>Tipo</td>";
						if ($resultado["Planificacion"]["padre"] != null && $resultado["Planificacion"]["padre"] != "") {
							$html .= "<td>C{$resultado["Planificacion"]["tipointervencion"]}</td>";
						} else { 
							$html .= "<td>{$resultado["Planificacion"]["tipointervencion"]}</td>";
						}
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td>Supervisor</td>";
						$html .= "<td>$supervisor</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td>Técnico</td>";
						$html .= "<td>$tecnico</td>";
						$html .= "</tr>";
						$html .= "<tr>";
						$html .= "<td>URL</td>";
						$tipo = strtolower($resultado["Planificacion"]["tipointervencion"]);
						$folio = $resultado["Planificacion"]["id"];
						$html .= "<td><a href=\"".DBM_URL."/Trabajo/Detalle2/$folio\">".DBM_URL."/Trabajo/Detalle2/$folio</a></td>";
						$html .= "</tr>";
						{ 
							// Alertas a tecnicos y supervisores, primero se verifica que tenga activada la opción de recibir correo
							// Tecnico principal
							if ($util->check_permissions_mails(1, $resultado["Planificacion"]["faena_id"], $json["UserID"]) == TRUE) {
								$email->config('amazon');
								$email->emailFormat('html');
								$destinatarios = array();
								$usuario = $this->Usuario->find('first', array(
									'fields' => array("Usuario.id", 'Usuario.correo_electronico'),
									'conditions' => array("Usuario.id" => $json["UserID"]),
									'recursive' => -1
								));
								if(isset($usuario["Usuario"]["correo_electronico"])) {
									$destinatarios[] = $usuario["Usuario"]["correo_electronico"];
								}
								if(MAIL_DEBUG == ""){
									if(is_array($destinatarios) && count($destinatarios) > 0) {

										$this->AWSSES->to = $destinatarios;
										$this->AWSSES->from = 'alerta@zeke.cl';
										$this->AWSSES->send('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"],$html);

									}
								}
								if(MAIL_DEBUG != ""){
									$html2 = $html;
									$html2 .= "<tr>";
									$html2 .= "	<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Técnico registro: ".(implode(",", $destinatarios))."</td>";
									$html2 .= "</tr>";
									$html2 .= "</table>";
									$destinatarios = array();
									$destinatarios[] = MAIL_DEBUG;
									$this->AWSSES->to = $destinatarios;
									$this->AWSSES->from = 'alerta@zeke.cl';
									$this->AWSSES->send('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"],$html2);
								}
								$email->reset();
							}
							// Supervisor responsable
							if ($util->check_permissions_mails(1, $resultado["Planificacion"]["faena_id"], $resultado["Planificacion"]["supervisor_responsable"]) == TRUE) {
								$email->config('amazon');
								$email->emailFormat('html');
								$destinatarios = array();
								$usuario = $this->Usuario->find('first', array(
									'fields' => array("Usuario.id", 'Usuario.correo_electronico'),
									'conditions' => array("Usuario.id" => $resultado["Planificacion"]["supervisor_responsable"]),
									'recursive' => -1
								));
								if(isset($usuario["Usuario"]["correo_electronico"])) {
									$destinatarios[] = $usuario["Usuario"]["correo_electronico"];
								}
								if(MAIL_DEBUG == ""){
									if(is_array($destinatarios) && count($destinatarios) > 0) {

										$this->AWSSES->to = $destinatarios;
										$this->AWSSES->from = 'alerta@zeke.cl';
										$this->AWSSES->send('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"],$html);
									}
								}
								if(MAIL_DEBUG != ""){
									$html2 = $html;
									$html2 .= "<tr>";
									$html2 .= "	<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Supervisor Responsable: ".(implode(",", $destinatarios))."</td>";
									$html2 .= "</tr>";	
									$html2 .= "</table>";
									$destinatarios = array();
									$destinatarios[] = MAIL_DEBUG;
									$this->AWSSES->to = $destinatarios;
									$this->AWSSES->from = 'alerta@zeke.cl';
									$this->AWSSES->send('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"],$html2);
								}		
								$email->reset();
							}
						}
						{
							// Alertas a todos los usuarios que tienen notificacion sincronizacion activada, excepto perfiles tecnicos y supervisores
							$destinatarios = array();
							$usuarios = $util->get_users_with_permissions_mail(1, $resultado["Planificacion"]["faena_id"]);
							$email->config('amazon');
							$email->emailFormat('html');
							foreach($usuarios as $usuario_id) {
								$usuario = $this->Usuario->find('first', array(
									'fields' => array("Usuario.id", 'Usuario.correo_electronico'),
									'conditions' => array("Usuario.id" => $usuario_id),
									'recursive' => -1
								));
								if(isset($usuario["Usuario"]["correo_electronico"])) {
									$destinatarios[] = $usuario["Usuario"]["correo_electronico"];
								}	
							}
							if(MAIL_DEBUG == ""){
								if(is_array($destinatarios) && count($destinatarios) > 0) {
									$this->AWSSES->to = $destinatarios;
									$this->AWSSES->from = 'alerta@zeke.cl';
									$this->AWSSES->send('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"],$html);
								}
							}
							if(MAIL_DEBUG != ""){
								$html2 = $html;
								$html2 .= "<tr>";
								$html2 .= "	<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Destinatarios configurados: ".(implode(",", $destinatarios))."</td>";
								$html2 .= "</tr>";
								$html2 .= "</table>";
								$destinatarios = array();
								$destinatarios[] = MAIL_DEBUG;
								$this->AWSSES->to = $destinatarios;
								$this->AWSSES->from = 'alerta@zeke.cl';
								$this->AWSSES->send('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"],$html2);
							}
							$email->reset();
						}
						{
							// Alertas a todos los usuarios que tienen notificacion sincronizacion duplicada activada
							$folios_duplicados = $this->eventos_duplicados($resultado);
							if(is_array($folios_duplicados) && count($folios_duplicados) > 1){
								$email->config('amazon');
								$email->emailFormat('html');
								$destinatarios = array();
								$usuarios = $util->get_users_with_permissions_mail(2, $resultado["Planificacion"]["faena_id"]);
								foreach($usuarios as $usuario_id) {
									$usuario = $this->Usuario->find('first', array(
										'fields' => array("Usuario.id", 'Usuario.correo_electronico'),
										'conditions' => array("Usuario.id" => $usuario_id),
										'recursive' => -1
									));
									if(isset($usuario["Usuario"]["correo_electronico"])) {
										$destinatarios[] = $usuario["Usuario"]["correo_electronico"];
									}	
								}
								if(MAIL_DEBUG == ""){
									if(is_array($destinatarios) && count($destinatarios) > 0) {
										
										$html2 = $html . "</table><table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
										$html2 .= "<tr style=\"background-color: red; color: white;\">";
										$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">INTERVENCIÓN DUPLICADA CON FOLIO(S) $folios_duplicados</td>";
										$html2 .= "</tr>";
										$html2 .= "</table>";	

										$this->AWSSES->to = $destinatarios;
										$this->AWSSES->from = 'alerta@zeke.cl';
										$this->AWSSES->send('Intervención Duplicada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"],$html2);
									}
								}								
								if(MAIL_DEBUG != ""){
									$html2 = $html . "</table><table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
									$html2 .= "<tr style=\"background-color: red; color: white;\">";
									$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">INTERVENCIÓN DUPLICADA CON FOLIO(S) $folios_duplicados</td>";
									$html2 .= "</tr>";
									$html2 .= "</table>";
									$html2 .= "<tr>";
									$html2 .= "	<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Destinatarios configurados: ".(implode(",", $destinatarios))."</td>";
									$html2 .= "</tr>";
									
									$destinatarios = array();
									$destinatarios[] = MAIL_DEBUG;
									$this->AWSSES->to = $destinatarios;
									$this->AWSSES->from = 'alerta@zeke.cl';
									$this->AWSSES->send('Intervención Duplicada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"],$html2);
								}
								$email->reset();
							}	
						}
					}
				}
			}
			$Email = NULL;
		}
		
		public function eventos_duplicados($intervencion){
			$this->layout = null;
			$util = new UtilidadesController();
			$this->loadModel('Planificacion');
			$this->layout = NULL;
			$duplicados = array();
			$conditions = array();
			$conditions["tipointervencion"] = $intervencion["Planificacion"]["tipointervencion"];
			$conditions["faena_id"] = $intervencion["Planificacion"]["faena_id"];
			$conditions["flota_id"] = $intervencion["Planificacion"]["flota_id"];
			$conditions["unidad_id"] = $intervencion["Planificacion"]["unidad_id"];
			$conditions["fecha"] = $intervencion["Planificacion"]["fecha"];
			$conditions["hora"] = $intervencion["Planificacion"]["hora"];
			$conditions["fecha_termino"] = $intervencion["Planificacion"]["fecha_termino"];
			$conditions["hora_termino"] = $intervencion["Planificacion"]["hora_termino"];
			$conditions["tecnico_principal"] = $intervencion["Planificacion"]["tecnico_principal"];
			$conditions["motivo_llamado_id"] = $intervencion["Planificacion"]["motivo_llamado_id"];
			$conditions["categoria_sintoma_id"] = $intervencion["Planificacion"]["categoria_sintoma_id"];
			$conditions["sintoma_id"] = $intervencion["Planificacion"]["sintoma_id"];
			$conditions["turno_id"] = $intervencion["Planificacion"]["turno_id"];
			$conditions["periodo_id"] = $intervencion["Planificacion"]["periodo_id"];
			$conditions["lugar_reparacion_id"] = $intervencion["Planificacion"]["lugar_reparacion_id"];
			$conditions["supervisor_responsable"] = $intervencion["Planificacion"]["supervisor_responsable"];
			$resultados = $this->Planificacion->find('all', array(
				'fields' => array('Planificacion.id'),
				'conditions' => $conditions,
				'recursive' => -1
			));
			foreach($resultados as $resultado){
				$duplicados[] = $resultado["Planificacion"]["id"];
			}
			return array_unique($duplicados);
		}
		
		public function actualizarBacklogs(){
			$this->loadModel('Planificacion');
			$this->loadModel('Backlog');
			$this->loadModel('Sistema_Motor');
			$this->layout = null;
			
			// Backlog folio programado
			$results=$this->Backlog->find('all', array(
				'fields' => array('Backlog.id','Backlog.folio_programador'),
				'conditions' => array("Backlog.realizado='S' AND Backlog.folio_programador IS NULL"),
				'recursive' => -1
			));
			foreach($results as $result){
				$intervencion=$this->Planificacion->find('first', array(
					'fields' => array('Planificacion.id','Planificacion.backlog_id'),
					'conditions' => array("Planificacion.backlog_id={$result["Backlog"]["id"]}"),
					'recursive' => -1
				));
				if(isset($intervencion["Planificacion"]["id"])){
					$data=array();
					$data["id"]=$result["Backlog"]["id"];
					$data["folio_programador"]=$intervencion["Planificacion"]["id"];
					$this->Backlog->save($data);
				}
			}
			
			// Backlog folio creador
			$results=$this->Backlog->find('all', array(
				'fields' => array('Backlog.id','Backlog.folio_creador','Backlog.ts'),
				'conditions' => array("Backlog.folio_creador IS NULL AND Backlog.ts IS NOT NULL"),
				'recursive' => -1
			));
			foreach($results as $result){
				$intervencion=$this->Planificacion->find('first', array(
					'fields' => array('Planificacion.id','Planificacion.json'),
					'conditions' => array("Planificacion.json LIKE"=>'%,"sync_inte":"'.$result["Backlog"]["ts"].'",%'),
					'recursive' => -1
				));
				if(isset($intervencion["Planificacion"]["id"])){
					$data=array();
					$data["id"]=$result["Backlog"]["id"];
					$data["folio_creador"]=$intervencion["Planificacion"]["id"];
					$this->Backlog->save($data);
				}
			}
			
			// Backlog sistema global
			$results=$this->Backlog->find('all', array(
				'fields' => array('Backlog.id','Backlog.sist_id','Backlog.sistema_id'),
				'conditions' => array("Backlog.sist_id IS NULL AND Backlog.sistema_id IS NOT NULL"),
				'recursive' => -1
			));
			foreach($results as $result){
				$sistema=$this->Sistema_Motor->find('first', array(
					'fields' => array('Sistema_Motor.id','Sistema_Motor.sistema_id'),
					'conditions' => array("Sistema_Motor.id={$result["Backlog"]["sistema_id"]}"),
					'recursive' => -1
				));
				if(isset($sistema["Sistema_Motor"]["sistema_id"])){
					$data=array();
					$data["id"]=$result["Backlog"]["id"];
					$data["sist_id"]=$sistema["Sistema_Motor"]["sistema_id"];
					$this->Backlog->save($data);
				}
			}
			
			$backlogs=$this->Backlog->find('all', array(
				'fields' => array('Backlog.id', 'Backlog.estado_id'),
				'conditions' => array("Backlog.estado_id IS NULL"),
				'recursive' => -1
			));
			
			foreach($backlogs as $backlog){
				$data=array();
				$data["id"]=$backlog["Backlog"]["id"];
				$data["estado_id"]='8';
				$this->Backlog->save($data);
			}
			
			$backlogs=$this->Backlog->find('all', array(
				'fields' => array('Backlog.id', 'Unidad.flota_id', 'Backlog.flota_id'),
				'conditions' => array("Backlog.flota_id IS NULL"),
				'recursive' => 1
			));
			
			foreach($backlogs as $backlog){
				$data=array();
				$data["id"]=$backlog["Backlog"]["id"];
				$data["flota_id"]=$backlog["Unidad"]["flota_id"];
				$this->Backlog->save($data);
			}
			exit;
		}
		
		public function reporte_correo_pendientes(){
			$this->loadModel('Usuario');
			$this->loadModel('ConfiguracionReporteCorreo');
			$util = new UtilidadesController();
			$this->layout = null;
			$configuraciones = $this->ConfiguracionReporteCorreo->find('all', array(
				'recursive' => -1
			));
			
			$data_return = array();
			//print_r($configuraciones);
			foreach($configuraciones as $configuracion){
				$data = array();
				$data["faena_id"] = $configuracion["ConfiguracionReporteCorreo"]["faena_id"];
				$data["ultimo_envio"] = strtotime($configuracion["ConfiguracionReporteCorreo"]["ultimo_envio"]);
				$data["hora"] = strtotime($configuracion["ConfiguracionReporteCorreo"]["hora_envio"]);
				
				$fecha_ultimo_envio = date("Y-m-d", $data["ultimo_envio"]);
				$fecha_actual = date("Y-m-d");
				
				$hora_actual = date("H:i");
				$hora_programada = date("H:i",$data["hora"]);
				
				if ($fecha_actual > $fecha_ultimo_envio) {
					if ($hora_actual > $hora_programada) {
						
						$destinatarios = array();
						$usuarios = $util->get_users_with_permissions_mail(8, $data["faena_id"]);
						
						foreach($usuarios as $usuario_id) {
							$usuario = $this->Usuario->find('first', array(
								'fields' => array("Usuario.id", 'Usuario.correo_electronico'),
								'conditions' => array("Usuario.id" => $usuario_id),
								'recursive' => -1
							));
							if(isset($usuario["Usuario"]["correo_electronico"])) {
								$destinatarios[] = $usuario["Usuario"]["correo_electronico"];
							}	
						}
						
						if(MAIL_DEBUG == ""){
							if(is_array($destinatarios) && count($destinatarios) > 0) {
								$this->intervenciones_proceso($data["faena_id"], $destinatarios);
							}
						}
						
						if(MAIL_DEBUG != ""){
							$destinatarios = array();
							$destinatarios[] = MAIL_DEBUG;
							$this->intervenciones_proceso($data["faena_id"], $destinatarios);
						}
						
						$data = array();
						$data["id"] = $configuracion["ConfiguracionReporteCorreo"]["id"];
						$data["ultimo_envio"] = $fecha_actual . ' ' . $hora_actual . ':00';
						$this->ConfiguracionReporteCorreo->save($data);
					}
				}
			}
		}
		
		public function intervenciones_proceso($faena_id, $destinatarios) {
			$this->layout = null;
			$util = new UtilidadesController();
			$fecha = date("Y-m-d H:i");
			$this->loadModel('Planificacion');
			$this->loadModel('Sintoma');
			$this->loadModel('IntervencionElementos');
			
			$intervenciones = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Sintoma.nombre', 'Sintoma.codigo', 'Sintoma.id'),
								'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
								'conditions' => array(
									"Planificacion.fecha <=" => "$fecha",
									"Planificacion.fecha >" => "2000-01-01 00:00:00",
									"Planificacion.faena_id" => $faena_id,
									'Planificacion.id <> Planificacion.correlativo_final',
									'Planificacion.estado' => '2'),
								'recursive' => 1));

			$return = array();			
			foreach($intervenciones as $intervencion){
				$int = array ();
				$int["Subsistemas"] = array();
				$int["Faena"] = $intervencion["Faena"]["nombre"];
				$int["Flota"] = $intervencion["Flota"]["nombre"];
				$int["Unidad"] = $intervencion["Unidad"]["unidad"];
				$int["Folio"] = $intervencion["Planificacion"]["id"];
				
				if (is_numeric($intervencion["Sintoma"]["codigo"]) && $intervencion["Sintoma"]["codigo"] != '0') {
					$int["Sintoma"] = 'FC ' . $intervencion["Sintoma"]["codigo"] . ' ' .$intervencion["Sintoma"]["nombre"];
				} else {
					$int["Sintoma"] = $intervencion["Sintoma"]["nombre"];
				}
				
				if($intervencion["Planificacion"]["tipointervencion"] == "MP"){
					$int["Sintoma"] = $intervencion["Planificacion"]["tipomantencion"];
				}
					
				$int["Tipo"] = $intervencion["Planificacion"]["tipointervencion"];
				$int["Correlativo"] = $intervencion["Planificacion"]["correlativo_final"];
				$int["Fecha"] = $intervencion["Planificacion"]["fecha"];
				$int["Hora"] = $intervencion["Planificacion"]["hora"];
				$int["Fecha_Correlativo"] = $intervencion["Planificacion"]["fecha"];
				$int["Hora_Correlativo"] = $intervencion["Planificacion"]["hora"];
				$int["Folio_Interno"] = $intervencion["Planificacion"]["folio"];
				$int["Correlativo_Interno"] = $intervencion["Planificacion"]["correlativo"];
				
				$int["ESN"] = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
				
				$int["Comentarios"] = array();
				//if($intervencion["Planificacion"]["id"] != $intervencion["Planificacion"]["correlativo_final"]){
					// Es continuacion, se busca primera intervencion del correlativo para asignar fecha inicial
					$inicial = $this->Planificacion->find('first', array(
								'fields' => array('Planificacion.fecha', 'Planificacion.hora'),
								'conditions' => array(
									'Planificacion.id' => $intervencion["Planificacion"]["correlativo_final"]),
								'recursive' => -1));
					$int["Fecha_Correlativo"] = $inicial["Planificacion"]["fecha"];
					$int["Hora_Correlativo"] = $inicial["Planificacion"]["hora"];
					
					$comentarios = $this->Planificacion->find('all', array(
						'fields' => array('Planificacion.id','Planificacion.fecha','Planificacion.hora','Planificacion.json','Planificacion.folio'),
						'order' => array('Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
						'conditions' => array(
							'Planificacion.faena_id' => $faena_id,
							"Planificacion.correlativo_final" => $intervencion["Planificacion"]["correlativo_final"],
							'Planificacion.estado NOT IN' => array(2, 0)),
						'recursive' => -1));
					foreach($comentarios as $comentario){
						$json = json_decode($comentario["Planificacion"]["json"], true);
						$int["Comentarios"][] = "- <strong>" .$comentario["Planificacion"]["id"] . ' ' . $comentario["Planificacion"]["fecha"] . ' ' . $comentario["Planificacion"]["hora"] . ':</strong> ' . $json["Comentarios"];
						
						$subsistemas = $this->IntervencionElementos->find('all', array(
							'fields' => array('Subsistema.nombre'),
							'order' => array('Subsistema.nombre' => 'asc'),
							'conditions' => array(
								'IntervencionElementos.folio' => $comentario["Planificacion"]["folio"]),
							'recursive' => 1));
						foreach($subsistemas as $subsistema){
							$int["Subsistemas"][] = "- " . $subsistema["Subsistema"]["nombre"];
						}
						
						$int["Subsistemas"] = array_unique($int["Subsistemas"]);
					}
				//}
				
				$int["Comentarios"] = implode("<br /><br />", $int["Comentarios"]);
				$int["Subsistemas"] = implode("<br />", $int["Subsistemas"]);
				$tiempo = strtotime($int["Fecha_Correlativo"] . ' ' . $int["Hora_Correlativo"]);
				$tiempo = time() - $tiempo;
				$dtF = new \DateTime('@0');
				$dtT = new \DateTime("@$tiempo");
				$tiempo = $dtF->diff($dtT)->format('%a días<br />%h horas<br />%i minutos');
				
				$int["Fecha_Correlativo"] = strtotime($int["Fecha_Correlativo"] . ' ' . $int["Hora_Correlativo"]);
				$int["Fecha_Correlativo"] = date("d-m-Y h:i A", $int["Fecha_Correlativo"]);
				
				$int["Tiempo"] = $tiempo;// / 60;// Tiempo en minutos
				$return[] = $int;	
			}

			$html="<html>";
			$html.="<body>";
			$html.= "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
			$html.= "<tr style=\"background-color: red; color: white;\">";
			$html.= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">INTERVENCIONES PENDIENTES ".strtoupper($int["Faena"])."</td>";
			$html.= "</tr>";
			$html.= "</table><br /><br />";	
			
			if (count($return) == 0) {
				$html.= "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
				$html.= "<tr>";
				$html.= "<td style=\"text-align: center;\">No hay intervenciones pendientes de ejecución.</td>";
				$html.= "</tr>";
				$html.= "</table><br /><br />";	
			} else {
				$html.= "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
				$html.= "<tr>";
				$html.= "<td style=\"text-align: center;\">Hay ".count($return)." intervenciones pendientes de ejecución.</td>";
				$html.= "</tr>";
				$html.= "</table><br /><br />";	
				$html.= "<table width=\"100%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
				$html.= "	<tr style=\"background-color: black; color: white;\">";
				$html.= '		<th style="text-align: center; background-color: black; color: white;">#</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Faena</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Flota</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Equipo</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">ESN</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Horas de motor</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Tipo</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Fecha inicio</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Síntoma</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Subsistema</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Tiempo</th>';
				$html.= '		<th style="text-align: center; background-color: black; color: white;">Comentarios</th>';
				$html.= '	</tr>';
				$i = 0;
				foreach($return as $intervencion){
					$i++;
					$html.= '<tr>';
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>$i</td>";
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>{$intervencion["Faena"]}</td>";
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>{$intervencion["Flota"]}</td>";
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>{$intervencion["Unidad"]}</td>";
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>{$intervencion["ESN"]}</td>";
					$html.= "		<td></td>";
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>{$intervencion["Tipo"]}</td>";
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>{$intervencion["Fecha_Correlativo"]}</td>";
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>{$intervencion["Sintoma"]}</td>";
					$html.= "		<td valign=\"middle\" nowrap>{$intervencion["Subsistemas"]}</td>";
					$html.= "		<td valign=\"middle\" style=\"text-align:center;\" nowrap>{$intervencion["Tiempo"]}</td>";
					$html.= "		<td valign=\"top\">{$intervencion["Comentarios"]}</td>";
					$html.= '</tr>';
				}
				$html.= '</table>';			
			}
			$html.="</body>";
			$html.="</html>";
			$asunto = "Intervenciones pendientes " . $int["Faena"];

			$this->AWSSES->to = $destinatarios;
			$this->AWSSES->from = 'alerta@zeke.cl';
			

			$Email->from(array(MAIL_FROM => 'Alerta DBM QA'));
			$Email->to($destinatarios);
			$Email->subject('Alerta DBM / ' . $asunto);
			if (count($return) > 0) {
				$archivo_adjunto = $this->generar_excel_pendientes($faena_id);
				$this->AWSSES->attachment = $archivo_adjunto;
			}
			$this->AWSSES->sendRaw("Intervenciones pendientes " . $int["Faena"],$html);
			$this->AWSSES->reset();
			
			$this->set("intervenciones", $return);
		}
		
		public function generar_excel_pendientes($faena_id){
			$utilReporte = new UtilidadesReporteController();
			$util = new UtilidadesController();
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
			$objPHPExcel = new PHPExcel();
			/*header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header ('Cache-Control: max-age=0');
			header ('Content-Disposition: attachment;filename="Intervenciones-Pendientes-'.date("Y-m-d").'".xlsx');
			header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
			header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header ('Pragma: public'); // HTTP/1.0*/
			$objPHPExcel->
		    getProperties()
		        ->setCreator("DBM")
		        ->setLastModifiedBy("DBM")
		        ->setTitle("Int. Pendientes");
				
			$objPHPExcel->setActiveSheetIndex(0)->setTitle('Int. Pendientes');
			
			// Encabezados
			$encabezados = array("Faena","Flota","Equipo","ESN","Horas Motor","Tipo","Fecha inicio","Comentarios","Categoria","Sintoma","Sistema","Subsistema","Posición Subsistema", "ID", "Elemento", "Posición Elemento", "Diagnóstico", "Solucion", "Tiempo");
			$count = count($encabezados);
			
			for($i=0;$i<$count;$i++){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
			}
			
			$utilReporte->cellColor("A1:S1","FF0000","FFFFFF",$objPHPExcel);
			
			$this->layout = null;
			$util = new UtilidadesController();
			$fecha = date("Y-m-d H:i");
			$this->loadModel('Planificacion');
			$this->loadModel('Sintoma');
			$this->loadModel('IntervencionElementos');
			
			$intervenciones = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad', 'Sintoma.nombre', 'Sintoma.codigo', 'Sintoma.id'),
								'order' => array('Faena.nombre' => "asc", 'Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
								'conditions' => array(
									"Planificacion.fecha <=" => "$fecha",
									"Planificacion.fecha >" => "2000-01-01 00:00:00",
									"Planificacion.faena_id" => $faena_id,
									'Planificacion.id <> Planificacion.correlativo_final',
									'Planificacion.estado' => "2"),
								'recursive' => 1));
								
			$return = array();
			$folios = array();			
			foreach($intervenciones as $intervencion){
				$int = array ();
				$int["Subsistemas"] = array();
				$int["Faena"] = $intervencion["Faena"]["nombre"];
				$int["Flota"] = $intervencion["Flota"]["nombre"];
				$int["Unidad"] = $intervencion["Unidad"]["unidad"];
				$int["Folio"] = $intervencion["Planificacion"]["id"];
				
				if (is_numeric($intervencion["Sintoma"]["codigo"]) && $intervencion["Sintoma"]["codigo"] != '0') {
					$int["Sintoma"] = 'FC ' . $intervencion["Sintoma"]["codigo"] . ' ' .$intervencion["Sintoma"]["nombre"];
				} else {
					$int["Sintoma"] = $intervencion["Sintoma"]["nombre"];
				}
				
				$resultado = $this->Sintoma->find('first', array(
														'conditions' => array('Sintoma.id' => $intervencion["Sintoma"]["id"]),
														'recursive' => 1
														));
				if(isset($resultado["Categoria"]["nombre"])){
					$int["Categoria_Sintoma"] = $resultado["Categoria"]["nombre"];
				}
				
				
				if($intervencion["Planificacion"]["tipointervencion"] == "MP"){
					$int["Sintoma"] = $intervencion["Planificacion"]["tipomantencion"];
				}
					
				$int["Tipo"] = $intervencion["Planificacion"]["tipointervencion"];
				$int["Correlativo"] = $intervencion["Planificacion"]["correlativo_final"];
				$int["Fecha"] = $intervencion["Planificacion"]["fecha"];
				$int["Hora"] = $intervencion["Planificacion"]["hora"];
				$int["Fecha_Correlativo"] = $intervencion["Planificacion"]["fecha"];
				$int["Hora_Correlativo"] = $intervencion["Planificacion"]["hora"];
				$int["Folio_Interno"] = $intervencion["Planificacion"]["folio"];
				$int["Correlativo_Interno"] = $intervencion["Planificacion"]["correlativo"];
				
				
				$int["ESN"] = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
				
				$int["Comentarios"] = array();
				//if($intervencion["Planificacion"]["id"] != $intervencion["Planificacion"]["correlativo_final"]){
					// Es continuacion, se busca primera intervencion del correlativo para asignar fecha inicial
					$inicial = $this->Planificacion->find('first', array(
								'fields' => array('Planificacion.fecha', 'Planificacion.hora'),
								'conditions' => array(
									'Planificacion.id' => $intervencion["Planificacion"]["correlativo_final"]),
								'recursive' => -1));
					$int["Fecha_Correlativo"] = $inicial["Planificacion"]["fecha"];
					$int["Hora_Correlativo"] = $inicial["Planificacion"]["hora"];
					
					$comentarios = $this->Planificacion->find('all', array(
						'fields' => array('Planificacion.id','Planificacion.fecha','Planificacion.hora','Planificacion.json','Planificacion.folio'),
						'order' => array('Planificacion.fecha' => 'asc', 'Planificacion.hora' => 'asc'),
						'conditions' => array(
							'Planificacion.faena_id' => $faena_id,
							"not" => array ("Planificacion.fecha" => null),
							"Planificacion.correlativo_final" => $intervencion["Planificacion"]["correlativo_final"],
							'Planificacion.estado NOT IN' => array(2, 0)),
						'recursive' => -1));
					foreach($comentarios as $comentario){
						$json = json_decode($comentario["Planificacion"]["json"], true);
						$int["Comentarios"][] = "- " .$comentario["Planificacion"]["id"] . ' ' . $comentario["Planificacion"]["fecha"] . ' ' . $comentario["Planificacion"]["hora"] . ': ' . $json["Comentarios"];
						
						$subsistemas = $this->IntervencionElementos->find('all', array(
							'fields' => array('Subsistema.nombre'),
							'order' => array('Subsistema.nombre' => 'asc'),
							'conditions' => array(
								'IntervencionElementos.folio' => $comentario["Planificacion"]["folio"]),
							'recursive' => 1));
						foreach($subsistemas as $subsistema){
							$int["Subsistemas"][] = "- " . $subsistema["Subsistema"]["nombre"];
						}
						
						$int["Subsistemas"] = array_unique($int["Subsistemas"]);
					}
				//}
				
				
				
				$int["Comentarios"] = implode("<br />", $int["Comentarios"]);
				$int["Subsistemas"] = implode("<br />", $int["Subsistemas"]);
				$tiempo = strtotime($int["Fecha_Correlativo"] . ' ' . $int["Hora_Correlativo"]);
				$tiempo = time() - $tiempo;
				$dtF = new \DateTime('@0');
				$dtT = new \DateTime("@$tiempo");
				$tiempo = $dtF->diff($dtT)->format('%a días, %h horas y %i minutos');
				
				$int["Fecha_Correlativo"] = strtotime($int["Fecha_Correlativo"] . ' ' . $int["Hora_Correlativo"]);
				$int["Fecha_Correlativo"] = date("d-m-Y h:i A", $int["Fecha_Correlativo"]);
				
				$int["Tiempo"] = $tiempo;// / 60;// Tiempo en minutos
				
				// Elementos 
				$intervenciones2 = $this->Planificacion->find('all', array(
								'fields' => array('Planificacion.folio'),
								'conditions' => array(
									"Planificacion.correlativo_final" => $intervencion["Planificacion"]["correlativo_final"],
									'Planificacion.estado NOT IN' => array(2, 0)),
								'recursive' => 1));
				$num_elementos = 0;
				
				unset($int["Fecha"],$int["Folio_Interno"],$int["Folio"],$int["Correlativo"]);
				$data = array();
				//print_r($intervenciones2);
				$data["Faena"] = $int["Faena"];
				$data["Flota"] = $int["Flota"];
				$data["Equipo"] = $int["Unidad"];
				$data["ESN"] = $int["ESN"];
				$data["Horas Motor"] = "";
				$data["Tipo"] = $int["Tipo"];
				$data["Fecha Inicio"] = $int["Fecha_Correlativo"];
				$data["Categoria"] = $int["Categoria_Sintoma"];
				$data["Sintoma"] = $int["Sintoma"];
				
				foreach($intervenciones2 as $intervencion2){
					$elementos = $this->IntervencionElementos->find('all', array(
						'conditions' => array(
							"IntervencionElementos.folio" => $intervencion2["Planificacion"]["folio"]),
						'recursive' => 1
					));
					foreach($elementos as $elemento){
						//print_r($elemento);
						$num_elementos++;
						$data["Sistema"] = $elemento["Sistema"]["nombre"];
						$data["Subsistema"] = $elemento["Subsistema"]["nombre"];
						$data["Pos.subsistema"] = $elemento["Posiciones_Subsistema"]["nombre"];
						$data["Codigo"] = $elemento["IntervencionElementos"]["id_elemento"];
						$data["Elemento"] = $elemento["Elemento"]["nombre"];
						$data["Pos.elemento"] = $elemento["Posiciones_Elemento"]["nombre"];
						$data["Diagnostico"] = $elemento["Diagnostico"]["nombre"];
						$data["Solucion"] = $elemento["TipoElemento"]["nombre"];
						$data["Tiempo"] = $int["Tiempo"];
						$data["Comentarios"] = $int["Comentarios"];
						unset($int["Subsistemas"]);
						$return[] = $data;
						//print_r($int);
					}
				}
				if ($num_elementos == 0) {
					$data["Sistema"] = "";
					$data["Subsistema"] = "";
					$data["Pos.subsistema"] = "";
					$data["Codigo"] = "";
					$data["Elemento"] = "";
					$data["Pos.elemento"] = "";
					$data["Diagnostico"] = "";
					$data["Solucion"] = "";
					unset($int["Subsistemas"]);
					$data["Tiempo"] = $int["Tiempo"];
					$data["Comentarios"] = $int["Comentarios"];
					$return[] = $data;
				}
			}
			
			$objPHPExcel->getActiveSheet()->fromArray($return, NULL, 'A2');
			$objPHPExcel->getActiveSheet()->getStyle('A1:S1')->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
			$objWriter->save('/tmp/Intervenciones-Pendientes-'.$int["Faena"].".xlsx");
			return '/tmp/Intervenciones-Pendientes-'.$int["Faena"].".xlsx";
		}

	}
?>