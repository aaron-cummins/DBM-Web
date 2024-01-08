<?php
App::uses('ConnectionManager', 'Model'); 
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Utilidades');

/*
	Esta clase define el funcionamiento de las alertas enviadas por correo electronico al sincronizar una intervencion desde Bitacora.
*/
class AlertaController extends AppController {
	/*
		Este metodo se ejecuta cada un minuto a traves de un cron definido en el servidor de aplicaciones.
	*/
	public function email_test(){
		/*$this->layout = NULL;
		$Email = new CakeEmail();
		$Email->config('default');
		$Email->emailFormat('html');
		$Email->from(array('alertas@sisrai.cummins.cl' => 'Alerta SISRAI'));
		$Email->to(array("daniel@salmonsoftware.cl", "daniel.fuentes.b@gmail.com"));
		$Email->subject('Envío de Prueba');
		$Email->send("<strong>Testing</strong>");*/
	}
	
	public function intervencionguardada() {
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
		echo count($resultados);
		foreach ($resultados as $resultado) {
			if (isset($resultado['Planificacion'])) {
				if (!is_numeric($resultado['Planificacion']['alert_mail'])) {
					$data = array(
						'id' => $resultado["Planificacion"]["id"],
						'alert_mail' => 1
					);
					$faena_id=$resultado["Planificacion"]["faena_id"];
					$json = str_replace('\n', '', $resultado["Planificacion"]["json"]);
					$json = json_decode($json, true);
					$tecnico_id="";
					$tecnico_id=$json["tecnico_principal"];
					$supervisor_d="";
					$supervisor_d=@$json["supervisor_d"];
					$tecnico = $util->getTecnicoFull($json["tecnico_principal"]);
					$supervisor = $util->getTecnicoFull($supervisor_d);
					$fecha_intervencion = strtotime($resultado["Planificacion"]["fecha"].' '.$resultado["Planificacion"]["hora"]);
					$resultado["Faena"]["nombre"]=strtoupper($resultado["Faena"]["nombre"]);
					$resultado["Flota"]["nombre"]=strtoupper($resultado["Flota"]["nombre"]);
					$resultado["Unidad"]["unidad"]=strtoupper($resultado["Unidad"]["unidad"]);
					$html="<html>";
					$html.="<body>";
					$html .= "<table width=\"50%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
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
					$html .= "<td nowrap>Fecha Sincronización</td>";
					$html .= "<td nowrap>".date("d-m-Y h:i A",@strtotime($resultado['Planificacion']['fecha_registro']))."</td>";
					//$html .= "<td nowrap>".(@$resultado['Planificacion']['fecha_registro'])."</td>";
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
					$html .= "<td><a href=\"http://cummins.sisrai.cl/Trabajo/$tipo/$folio\">http://cummins.sisrai.cl/Trabajo/$tipo/$folio</a></td>";
					$html .= "</tr>";
					$html .= "</table>";
					$html .="</body></html>";
					
					$destinatarios=array();
					// Se obtienen correos de alertas segun configuracion, administradores
					$usuariosniveles = $this->UsuarioNivel->find('all', array(
						'fields' => array('Usuario.correo_electronico'),
						'conditions' => array("UsuarioNivel.e='1' AND Usuario.e='1' AND UsuarioNivel.nivel_id=4 AND UsuarioNivel.correo='1'"),
						'recursive' => 1
					));
					foreach ($usuariosniveles as $usuarionivel) {
						if($usuarionivel["Usuario"]["correo_electronico"]!=""){
							$destinatarios[]=@$usuarionivel["Usuario"]["correo_electronico"];
						}
					}
					
					//print_r($destinatarios);
					//$destinatarios=array();
					//$destinatarios[]="daniel@salmonsoftware.cl";
					$Email = new CakeEmail();
					$Email->config('default');
					$Email->emailFormat('html');
					$Email->from(array('alerta@cummins.sisrai.cl' => 'Alerta SISRAI'));
					$Email->to($destinatarios);
					$Email->subject('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"]);
					//var_dump($Email);
					$html2 = $html . "<table width=\"50%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
					$html2 .= "<tr style=\"background-color: red; color: white;\">";
					$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">ALERTA ADMINISTRADOR</td>";
					$html2 .= "</tr>";
					$html2 .= "</table>";	
					//$Email->send($html2);
					//$Email->send($html.'<br /><br />DEBUG:<br /><br />'.$resultado["Planificacion"]["json"]);
					//print_r($destinatarios);
					//echo "intervencion<br />";
					//echo "$html";
					
					$duplicados = $util->existenDuplicados($data["id"]);
					if (is_array($duplicados) && count($duplicados)) {
						try {
							// Alerta Planificador
							//$faena_id;
							$nivel_id = 7;
							$usuariosfaenas = $this->UsuarioFaena->find('all', array(
								'fields' => array('UsuarioFaena.*'),
								'conditions' => array("Usuario.e='1' AND UsuarioFaena.e='1' AND UsuarioFaena.faena_id=$faena_id AND UsuarioFaena.nivel_id=$nivel_id"),
								'recursive' => 1
							));
							foreach ($usuariosfaenas as $usuariofaena) {
								//echo $usuariofaena["UsuarioFaena"]["usuario_id"]."-";
								$usuario_id = $usuariofaena["UsuarioFaena"]["usuario_id"];
								$usuario = $this->UsuarioNivel->find('first', array(
									'fields' => array('Usuario.correo_electronico'),
									'conditions' => array("UsuarioNivel.e='1' AND UsuarioNivel.usuario_id=$usuario_id AND UsuarioNivel.nivel_id=$nivel_id AND UsuarioNivel.correo='1'"),
									'recursive' => 1
								));
								if(isset($usuario["Usuario"]["correo_electronico"])) {
									$destinatarios[]=@$usuario["Usuario"]["correo_electronico"];
								}
							}
						} catch (Exception $e) {
							echo 'Excepción capturada: ',  $e->getMessage(), "\n";
						}
					
						$html2 = $html . "<table width=\"50%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
						$html2 .= "<tr style=\"background-color: red; color: white;\">";
						$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Folio {$data["id"]} está duplicado con ". implode(", ", $duplicados).".</td>";
						$html2 .= "</tr>";
						$html2 .= "</table>";						
						//$correo = array("daniel@salmonsoftware.cl");
						$Email3 = new CakeEmail();
						$Email3->config('default');
						$Email3->emailFormat('html');
						$Email3->from(array('alerta@cummins.sisrai.cl' => 'Alerta SISRAI'));
						$Email3->to($destinatarios);
						$Email3->subject('Evento Duplicado / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"]);
						//$Email3->send($html2);
						//echo "duplicado<br />";
					}
					
					// Alerta a técnico
					$usuario = $this->UsuarioNivel->find('first', array(
						'fields' => array('Usuario.correo_electronico'),
						'conditions' => array("UsuarioNivel.e='1' AND Usuario.e='1' AND Usuario.id=$tecnico_id AND UsuarioNivel.correo='1'"),
						'recursive' => 1
					));
					if(isset($usuario["Usuario"]["correo_electronico"])&&$usuario["Usuario"]["correo_electronico"]!=""&&$usuario["Usuario"]["correo_electronico"]!=null){
						$Email = new CakeEmail();
						$Email->config('default');
						$Email->emailFormat('html');
						$Email->from(array('alerta@cummins.sisrai.cl' => 'Alerta SISRAI'));
						//$destinatarios=array();
						//$destinatarios[]="daniel@salmonsoftware.cl";
						//$Email->to($destinatarios);
						$Email->to(array($usuario["Usuario"]["correo_electronico"]));
						$Email->bcc(array("daniel@salmonsoftware.cl"));
						$Email->subject('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"]);
						$html2 = $html . "<table width=\"50%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
						$html2 .= "<tr style=\"background-color: red; color: white;\">";
						$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">ALERTA TÉCNICO</td>";
						$html2 .= "</tr>";
						$html2 .= "</table>";	
						//$Email->send($html2);
					}
					
					// Alerta a supervisor
					if($supervisor_d!=null&&$supervisor_d!=""&&is_numeric($supervisor_d)){
						$usuario = $this->UsuarioNivel->find('first', array(
							'fields' => array('Usuario.correo_electronico'),
							'conditions' => array("UsuarioNivel.e='1' AND Usuario.e='1' AND Usuario.id=$supervisor_d AND UsuarioNivel.correo='1'"),
							'recursive' => 1
						));
						if(isset($usuario["Usuario"]["correo_electronico"])&&$usuario["Usuario"]["correo_electronico"]!=""&&$usuario["Usuario"]["correo_electronico"]!=null){
							$Email = new CakeEmail();
							$Email->config('default');
							$Email->emailFormat('html');
							$Email->from(array('alerta@cummins.sisrai.cl' => 'Alerta SISRAI'));
							//$destinatarios=array();
							//$destinatarios[]="daniel@salmonsoftware.cl";
							//$Email->to($destinatarios);
							$Email->to(array($usuario["Usuario"]["correo_electronico"]));
							$Email->bcc(array("daniel@salmonsoftware.cl"));
							$Email->subject('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"]);
							$html2 = $html . "<table width=\"50%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
							$html2 .= "<tr style=\"background-color: red; color: white;\">";
							$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">ALERTA SUPERVISOR</td>";
							$html2 .= "</tr>";
							$html2 .= "</table>";	
							//$Email->send($html2);
						}
					}
					
					try {
						// Alerta Planificador
						//$faena_id;
						$nivel_id = 7;
						$usuariosfaenas = $this->UsuarioFaena->find('all', array(
							'fields' => array('UsuarioFaena.*'),
							'conditions' => array("Usuario.e='1' AND UsuarioFaena.e='1' AND UsuarioFaena.faena_id=$faena_id AND UsuarioFaena.nivel_id=$nivel_id"),
							'recursive' => 1
						));
						//echo "PLA-";
						$planificadores=array();
						foreach ($usuariosfaenas as $usuariofaena) {
							//echo $usuariofaena["UsuarioFaena"]["usuario_id"]."-";
							$usuario_id = $usuariofaena["UsuarioFaena"]["usuario_id"];
							$usuario = $this->UsuarioNivel->find('first', array(
								'fields' => array('Usuario.correo_electronico'),
								'conditions' => array("UsuarioNivel.e='1' AND UsuarioNivel.usuario_id=$usuario_id AND UsuarioNivel.nivel_id=$nivel_id AND UsuarioNivel.correo='1'"),
								'recursive' => 1
							));
							if(isset($usuario["Usuario"]["correo_electronico"])) {
								$planificadores[]=@$usuario["Usuario"]["correo_electronico"];
							}
						}
						//echo implode(",",$planificadores);
						if(is_array($planificadores)){
							$Email5 = new CakeEmail();
							$Email5->config('default');
							$Email5->emailFormat('html');
							$Email5->from(array('alerta@cummins.sisrai.cl' => 'Alerta SISRAI'));
							//$destinatarios=array();
							//$destinatarios[]="daniel@salmonsoftware.cl";
							$Email5->to($planificadores);
							//$Email->to(array($usuario["Usuario"]["correo_electronico"]));
							$Email5->bcc(array("daniel@salmonsoftware.cl"));
							$Email5->subject('Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"]);
							$html2 = $html . "<table width=\"50%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
							$html2 .= "<tr style=\"background-color: red; color: white;\">";
							$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">ALERTA PLANIFICADOR</td>";
							$html2 .= "</tr>";
							//$html2 .= "<tr style=\"background-color: red; color: white;\">";
							//$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">".implode(",",$planificadores)."</td>";
							//$html2 .= "</tr>";
							$html2 .= "</table>";	
							//$Email5->send($html2);
						}
					} catch (Exception $e) {
						echo 'Excepción capturada: ',  $e->getMessage(), "\n";
					}
					
					$this->Planificacion->save($data);
				}
			}
		}
		
		// Alertas por inactividad
		$resultados = $this->Inactividad->find('all', array(
			'fields' => array('Inactividad.*', 'Tecnico.nombres','Tecnico.apellidos','Tecnico.id'),
			'conditions' => array("(Inactividad.alerta IS NULL OR Inactividad.alerta='0')"),
			'recursive' => 1
		));
		
		foreach ($resultados as $resultado) {
			if (isset($resultado['Inactividad'])) {
				if ($resultado['Inactividad']['alerta']!=1) {
					$destinatarios=array();
					$data = array(
						'id' => $resultado["Inactividad"]["id"],
						'alerta' => 1
					);
					// Se obtienen correos de alertas segun configuracion, adminsitradores
					$usuariosniveles = $this->UsuarioNivel->find('all', array(
						'fields' => array('Usuario.correo_electronico'),
						'conditions' => array("UsuarioNivel.e='1' AND Usuario.e='1' AND UsuarioNivel.nivel_id=4 AND UsuarioNivel.correo='1'"),
						'recursive' => 1
					));
					foreach ($usuariosniveles as $usuarionivel) {
						if($usuarionivel["Usuario"]["correo_electronico"]!=""){
							$destinatarios[]=@$usuarionivel["Usuario"]["correo_electronico"];
						}
					}
					
					// Obtenemos faena del tecnico
					$uf = $this->UsuarioFaena->find('first', array(
						'fields' => array('UsuarioFaena.faena_id','Faena.nombre'),
						'conditions' => array("UsuarioFaena.usuario_id={$resultado['Tecnico']['id']}"),
						'recursive' => 1
					));
					
					if(isset($uf["UsuarioFaena"]["faena_id"])&&is_numeric($uf["UsuarioFaena"]["faena_id"])){
						$pf = $this->UsuarioFaena->find('all', array(
							'fields' => array('Usuario.correo_electronico'),
							'conditions' => array("UsuarioFaena.faena_id={$uf["UsuarioFaena"]["faena_id"]} AND UsuarioFaena.nivel_id=7 AND UsuarioFaena.e='1' AND Usuario.e='1'"),
							'recursive' => 1
						));
						foreach ($pf as $var) {
							if($var["Usuario"]["correo_electronico"]!=""){
								$destinatarios[]=@$var["Usuario"]["correo_electronico"];
							}
						}
					}
					
					$faena=strtoupper(@$uf["Faena"]["nombre"]);
					$tecnico=@($resultado["Tecnico"]["nombres"]). " " . (@$resultado["Tecnico"]["apellidos"]);
					$html="<html>";
					$html.="<body>";
					$html .= "<table width=\"50%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
					$html .= "<tr style=\"background-color: #808080; color: white;\">";
					$html .= "<td style=\"background-color: #808080; color: white; text-align: center;\" colspan=\"2\">Resumen Inactividad</td>";
					$html .= "</tr>";
					$html .= "<tr>";
					$html .= "<td>Técnico</td>";
					$html .= "<td>$tecnico</td>";
					$html .= "</tr>";
					$html .= "<tr>";
					$html .= "<td>Faena</td>";
					$html .= "<td>$faena</td>";
					$html .= "</tr>";
					$html .= "<tr>";
					$html .= "<td nowrap>Fecha Cierre</td>";
					$html .= "<td nowrap>".date("d-m-Y h:i A", $resultado['Inactividad']["fecha"]/1000)."</td>";
					$html .= "</tr>";
					$html .= "</table>";
					$html .="</body></html>";	
					$Email4 = new CakeEmail();
					$Email4->config('default');
					$Email4->emailFormat('html');
					$Email4->from(array('alerta@cummins.sisrai.cl' => 'Alerta SISRAI'));
					$destinatarios=array();
					$destinatarios[]="daniel@salmonsoftware.cl";
					$Email4->to($destinatarios);
					$Email4->subject('Cierre por Inactividad / '.$faena.' / Técnico '.$tecnico);
					$html2 = $html . "<table width=\"50%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\">";
					$html2 .= "<tr style=\"background-color: red; color: white;\">";
					$html2 .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">ALERTA INACTIVIDAD</td>";
					$html2 .= "</tr>";
					$html2 .= "</table>";	
					//$Email4->send($html2);
					//echo "$html";
					//echo "inactividad <br />" .date("d-m-Y h:i A", $resultado['Inactividad']["fecha"]/1000);
					$this->Inactividad->save($data);
					//print_r($destinatarios);
				}
			}
		}
		
		$util->actualizar_correlativos();
		$util->actualizar_tiempo_dcc();
		$util->actualizar_tiempo_oem();
		$util->actualizar_tiempo_mina();
		$util->actualizarBacklogs();
		die("OK " . date("Y-m-d H:i:s"));
	}
}

?>