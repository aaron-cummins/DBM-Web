<?php
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Reporte');
App::import('Controller', 'Utilidades');
App::import('Controller', 'Cron');
App::import('Controller', 'UtilidadesReporte');
App::import('Vendor', 'Classes/PHPExcel');

//require '../../vendor/autoload.php';

use Aws\Ses;

class NotificacionShell extends AppShell {
    public $uses = array('Faena','FaenaFlota','ReporteBase','Unidad','DeltaDetalle','IntervencionElementos','IntervencionFechas','Planificacion','IntervencionFluido','Fluido','FluidoUnidad','FluidoTipoIngreso','UsuarioFaena','UsuarioNivel','Usuario','Inactividad');

    public function main() {
		$this->out("cron Notificaciones iniciado");
		$util = new UtilidadesController();
		$cron = new CronController();
		$resultados = $this->Planificacion->find('all', array(
			'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre', 'Unidad.unidad'),
			'conditions' => array("(Planificacion.alert_mail IS NULL OR Planificacion.alert_mail = 2) AND Planificacion.estado = 7 AND Planificacion.terminado = 'S'"),
			'recursive' => 1
		));
		$email = new CakeEmail();
		$this->out("Se encontraron ".count($resultados)." intervenciones.");
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

									$this->sendMail($destinatarios, 'Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"], $html);
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
								$this->sendMail($destinatarios, 'Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"], $html2);
							}
						
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
									$this->sendMail($destinatarios, 'Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"], $html);
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
								$this->sendMail($destinatarios, 'Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"], $html2);
							}		
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
								$this->sendMail($destinatarios, 'Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"], $html);
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
							$this->sendMail($destinatarios, 'Intervención Registrada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"], $html2);
						}
						
					}
					{
						// Alertas a todos los usuarios que tienen notificacion sincronizacion duplicada activada
						$folios_duplicados = $cron->eventos_duplicados($resultado);
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
									$this->sendMail($destinatarios,'Intervención Duplicada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"], $html2);
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
								$this->sendMail($destinatarios,'Intervención Duplicada / '.$resultado["Faena"]["nombre"].' / Folio '.$resultado["Planificacion"]["id"], $html2);
							}
							$email->reset();
						}	
					}
				}
			}
		}
		$Email = NULL;
   }


   function sendMail($to, $subject, $body) {
       
       $this->ses = new Aws\Ses\SesClient([
			'version' => '2010-12-01',
			'region' => AMAZON_SES_REGION,
			'credentials' => [
				'key' => AMAZON_SES_ACCESS_KEY_ID,
				'secret' => AMAZON_SES_SECRET_ACCESS_KEY
			]
		]);
		
	if(is_string($to)) {
            $to = [$to];
        }

        $destination = array(
                'ToAddresses' => $to
        );
        $message = array(
                'Subject' => array(
                        'Data' => $subject
                ),
                'Body' => array()
        );



        if ($body != NULL) {
                $message['Body']['Html'] = array(
                        'Data' => $body
                );
        }

        $char_set = 'UTF-8';
        $ok = true;

        try {

        $response = $this->ses->sendEmail([
        'Destination' => $destination,
        'Source' => AMAZON_SES_FROM_EMAIL,
        'Message' => $message


        ]);

        } catch (AwsException $e) {
        $ok = false;
        $this->log('Error sending email from AWS SES: ' .$e->getMessage(), 'error');
        $this->log('Error sending email from AWS SES: ' .$e->getAwsErrorMessage(), 'error');
        }

        return $ok;
   }

}
?>