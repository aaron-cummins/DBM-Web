<?php
//ini_set('memory_limit','128M');
App::uses('ConnectionManager', 'Model'); 
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Utilidades');
App::import('Vendor', 'Classes/fpdf');
App::import('Vendor', 'Classes/PHPExcel');
App::import('Controller', 'UtilidadesReporte');

/* Esta clase define las funcionalidades del flujo de revision y aprobacion de las intervenciones */
class TrabajoController extends AppController {

	public $components = array('AWSSES');

	/* Este metodo genera el despliegue de las intervenciones en espera de revision por parte del cliente */
	public function cliente() {
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
		if ($faena_id != 0) {
			$faena = "Planificacion.faena_id = $faena_id";
		} else {
			$faena = 'Planificacion.faena_id <> 0';
		}
		$this->loadModel('Planificacion');
		$intervenciones = $this->Planificacion->find('all', array('order' => array('Planificacion.fecha desc', 'Planificacion.hora desc'),  'conditions' => array('estado' => array(4, 6), $faena), 'recursive' => 1));
		$this->set('intervenciones', $intervenciones);
		$this->set('titulo', 'Intervenciones revisadas por cliente');
	}
	
	/* Este metodo genera el despliegue del detalle de los deltas de supervisor */
	public function detalle($id) {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$util = new UtilidadesController();
		$intervencion_anterior = array();
		$this->loadModel('Planificacion');
		$this->loadModel('Faena');
		$this->loadModel('DeltaDetalle');
		$this->loadModel('DeltaItem');
		$this->loadModel('IntervencionFechas');
		$this->loadModel('ReporteBase');
		$this->loadModel('Backlog');
		$this->set("seccion","RevisiónDCC");
        $this->loadModel('Prebacklog');
        $this->loadModel('Prebacklog_comentario');

		/**
		 * Se recibe la data desde el formulario 
		 */
		if ( isset($this->request->data) && count($this->request->data) > 2) {
			try {
				$folio = $this->request->data["folio"];
				/**
				 * INSERTA TODOS LOS DELTAS QUE TENGAN DATOS
				 */

				if($this->request->data["delta"] != null){
					//foreach($this->request->data["delta"] as $key => $value) {
					foreach($this->request->data["delta_r"] as $key => $value) {
						$this->DeltaDetalle->deleteAll(array('DeltaDetalle.folio' => $folio,'DeltaDetalle.delta_item_id' => $key), false);
						if(isset($this->request->data["delta_r"][$key]) && $this->request->data["delta_r"][$key] != '0'){
							//$this->DeltaDetalle->deleteAll(array('DeltaDetalle.folio' => $folio, 'DeltaDetalle.delta_item_id' => $key), false);
							$data = array();
							$data["delta_responsable_id"] = $this->request->data["delta_r"][$key];
							$data["delta_item_id"] = $key;
							$data["folio"] = $folio;
							$data["tiempo"] = intval($this->request->data["delta_m"][$key]) + intval($this->request->data["delta"][$key]) * 60;
							$data["observacion"] = $this->request->data["delta_o"][$key];
							$this->DeltaDetalle->create();
							$this->DeltaDetalle->save($data);
						}
					}
				}
                                
                                
				/**
				 * se insertan los deltas que tengan un responsable seleccionado
				 */
				/*foreach($this->request->data["delta_r"] as $key => $value) {
                                    
					if(isset($this->request->data["delta_r"][$key]) && $this->request->data["delta_r"][$key] != '0'){
							$this->DeltaDetalle->deleteAll(array('DeltaDetalle.folio' => $folio, 'DeltaDetalle.delta_item_id' => $key), false);
							$data = array();
							$data["delta_responsable_id"] = $this->request->data["delta_r"][$key];
							$data["delta_item_id"] = $key;
							$data["folio"] = $folio;
							$data["tiempo"] = intval($this->request->data["delta_m"][$key]) + intval($this->request->data["delta"][$key]) * 60;
							$data["observacion"] = $this->request->data["delta_o"][$key];
							$this->DeltaDetalle->create();
							$this->DeltaDetalle->save($data);
					}
				}*/

				/**Intervención**/
				$intervencion = $this->Planificacion->find('first', array(
					'conditions' => array('Planificacion.id' => $id),
					'recursive' => 1
				));

					
				$data = array();
				$data["id"] = $this->request->data["id"];
				$data["aprobador_id"] = $this->getUsuarioID();
				$data["fecha_aprobacion"] = date("Y-m-d H:i:s");
				$data["estado"] = "4";
                                
				if(isset($this->request->data["os_sap"]) && is_numeric($this->request->data["os_sap"])) {
					$data["os_sap"] = $this->request->data["os_sap"];
				}else{
					$data["os_sap"] = 0;
				}

				/**
				 * Se elimina el reporte base para recrear con nuevos datos
				*/

				$this->Planificacion->updateAll(
					array('Planificacion.reporte_base' => null),
					array('Planificacion.correlativo_final' => $intervencion["Planificacion"]["correlativo_final"]));

				$this->ReporteBase->deleteAll(
						array('ReporteBase.correlativo' => $intervencion["Planificacion"]["correlativo_final"]), false);
				
                /**
                * Cuando la respuesta es "NO" la columna debe guardarse como true
                * Esto es debido a que la pregunta es : "¿La continuación de este evento será realizada en su turno actual?"
                */
				$data["respuesta_continuacion_turno"] = ( isset($this->request->data['yes_no_option']) && $this->request->data['yes_no_option'] == true ) ? true : false ;
				
				if ( isset($this->request->data["operacion"]) ) {
					//$fecha_operacion = 
					$data["fecha_operacion"] = $this->request->data["operacion"][0].' '.$this->request->data["operacion"][1].':'.$this->request->data["operacion"][2].' '.$this->request->data["operacion"][3];
				}
				
				$this->Planificacion->save( $data );

				$this->intervencionLogger($intervencion, "intervencion_aprueba", $data);
				
				$data_intervencion_fechas = array();
				
				/**Intervención Fechas**/
				$intervencion_fechas = $this->IntervencionFechas->find('first', array(
					'conditions' => array(
						"IntervencionFechas.folio" => $intervencion["Planificacion"]["folio"]
					), 
					'recursive' => -1
				));
				/*se identifica las fechas relacionadas a la intervencion*/
				//$data_intervencion_fechas['id'] = $this->request->data["int_fech"];

				//$data_intervencion_fechas['folio'] = $intervencion["Planificacion"]["folio"];
				/**
				 * agregado por Victor Smith
				 * verifica fecha_inicio_delta
				 */
				if ( ( $this->request->data['yes_no_option'] == true ) ) {
					//$data_intervencion_fechas["fecha_inicio_delta"] = $this->request->data["inicio_delta"][0].' '.$this->request->data["inicio_delta"][1].':'.$this->request->data["inicio_delta"][2].' '.$this->request->data["inicio_delta"][3];
					//$data_intervencion_fechas["fecha_inicio_delta"] = $this->request->data["termino_intervencion"][0].' '.$this->request->data["termino_intervencion"][1].':'.$this->request->data["termino_intervencion"][2].' '.$this->request->data["termino_intervencion"][3];
				}	
				/**
				 * agregado por Victor Smith
				 * verifica fecha fecha_termino_turno
				 */
				if ( isset( $this->request->data["termino_turno"] ) && ( $this->request->data['yes_no_option'] == true ) ) {
					$intervencion_fechas["IntervencionFechas"]["fecha_termino_turno"] = $this->request->data["termino_turno"][0].' '.$this->request->data["termino_turno"][1].':'.$this->request->data["termino_turno"][2].' '.$this->request->data["termino_turno"][3];
				}
				/**
				 * agregado por Victor Smith
				 * guarda fechas en IntervencionFechas
				 */
				$this->IntervencionFechas->save( $intervencion_fechas );
				
				$this->Session->setFlash('Intervención aprobada con éxito.', 'guardar_exito');
			} catch (Exception $e) { 
				$this->Session->setFlash('Ocurrió un error al intentar aprobar la intervención, por favor intente nuevamente.'.$e->getMessage() , 'guardar_error');
				$this->logger($this, $e->getMessage());
			}
			
			/// ******* MODIFICA ESTADO DE BACKLOGS
			try{
				//si existen backlogs con este id de intervencion, se dejan en 0 para volver a asignarlos
				$bl = $this->Backlog->find('all',
						array(
							'fields' => array("Backlog.id"),
							'conditions' => array('Backlog.intervencion_id' => $intervencion["Planificacion"]["correlativo_final"]),
							)
						);
                            
				foreach($bl as $v) {
					$this->Backlog->query("Update Backlog Set estado_id = 8, realizado ='N', intervencion_id = null
											Where id = " . $v["Backlog"]["id"]);
				}

				//VUELVO A ASIGNAR ID DE INTERVENCION  LOS BACKLOGS que se seleccionaron
				if($this->request->data('backlog_id') != null){
					foreach($this->request->data('backlog_id') as $value) {
						if(is_numeric($value) && $value > 0) {
							$data_backlog = array();
							$data_backlog["id"] = $value;
							$data_backlog["realizado"] = "S";
							$data_backlog["estado_id"] = "11";
							$data_backlog["intervencion_id"] = $intervencion["Planificacion"]["correlativo_final"];
							$this->Backlog->save($data_backlog);

							$this->intervencionLogger($intervencion, "intervencion_backlog", $data_backlog);

							//obtengo valorde de backlog
							$bklg = $this->Backlog->find('first',
													array(
														'fields' => array("Backlog.prebacklog_id"),
														'conditions' => array('Backlog.id' => $value),
														));


								//di tiene prebacklog lo modifico a estado realizado
							if($bklg["Backlog"]['prebacklog_id'] != "" ||$bklg["Backlog"]['prebacklog_id'] != 0){

								//Cambia estado de prebacklog a planificado
								$this->Prebacklog->updateAll(
										array('Prebacklog.estado_id' => 11),
										array('Prebacklog.id' => $bklg["Backlog"]['prebacklog_id']));

								//inserta un comentario con la aprobacion de la bitacora
								$comentario_inte = $util->getComentario($intervencion['Planificacion']['folio']);
								$comen['comentario'] = 'Trabajo realizado, intervención terminada N°'.$intervencion["Planificacion"]["correlativo_final"] . ', Comnetario -> ' .$comentario_inte;
								$comen['usuario_id'] = $this->getUsuarioID();
								$comen['prebacklog_id'] = $bklg["Backlog"]['prebacklog_id'];
								$comen['fecha'] = date("Y-m-d H:i:s");

								$this->Prebacklog_comentario->create();
								$this->Prebacklog_comentario->save($comen);
							}

						}
					}
					$this->Backlog->updateAll(
								array('Backlog.estado_id' => 11),
								array('Backlog.intervencion_id' => $intervencion["Planificacion"]["correlativo_final"]));
				}
			} catch (Exception $e) {
				$this->Session->setFlash('Ocurrió un error al intentar modificar los backlogs asociados, favor contactar a soporte DBM.', 'guardar_error');
				$this->logger($this, $e->getMessage());
			}
			/// ******* FIN MODIFICA ESTADO DE BACKLOGS
                        
                        
			try {	
				// Envio alerta de aprobación de intervención
				if(isset($this->request->data['faena_id']) && is_numeric($this->request->data['faena_id'])) {
					$faena_id = $this->request->data['faena_id'];
				}
				$faena = $this->Faena->find("first",array("fields" => array("nombre"),"conditions"=>array("id" => $faena_id), "recursive" => -1));
				$email = new CakeEmail();
				$email->config('amazon');
				$email->emailFormat('html');
				$destinatarios = array();
				$usuarios = $util->get_users_with_permissions_mail(7, $faena_id);
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
				$html = "<table width=\"90%\" border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">";
				$html .= "<tr style=\"background-color: red; color: white;\">";
				$html .= "<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">INTERVENCIÓN APROBADA</td>";
				$html .= "</tr>";
				$html .= "<tr style=\"background-color: white; color: black;\">";
				$html .= "<td style=\"background-color: white; color: black; text-align: center;\" colspan=\"2\">REVISAR EN <a href=\"".DBM_URL."/Trabajo/Historial?folio=".($this->request->data["id"])."\">".DBM_URL."/Trabajo/Historial</a></td>";
				$html .= "</tr>";						
				
				if(MAIL_DEBUG != ""){
					$html .= "<tr>";
					$html .= "	<td style=\"background-color: red; color: white; text-align: center;\" colspan=\"2\">Destinatarios: ".(implode(",", $destinatarios))."</td>";
					$html .= "</tr>";					
				}
				
				$html .= "</table>";
				
				if(MAIL_DEBUG == ""){
					if(is_array($destinatarios) && count($destinatarios) > 0) {
						$this->AWSSES->to = $destinatarios;
						$this->AWSSES->send('Intervención Aprobada / ' . strtoupper($faena["Faena"]["nombre"]) . " / Folio " . ($this->request->data["id"]),$html);
					}
				} else {
					$destinatarios = array();
					$destinatarios[] = MAIL_DEBUG;
					$this->AWSSES->to = $destinatarios;
					$this->AWSSES->send('Intervención Aprobada / ' . strtoupper($faena["Faena"]["nombre"]) . " / Folio " . ($this->request->data["id"]),$html);
				}
			} catch (Exception $e) { 
				$this->Session->setFlash('Ocurrió un error al intentar aprobar la intervención, por favor intente nuevamente.' . $e->getMessage(), 'guardar_error');
				$this->logger($this, $e->getMessage());
			}	
			
			$newdate = strtotime("-5 year",time());
				
			$this->redirect(array(
				'controller' => 'Trabajo',
				'action' => 'Historial',
				'?' => array(
					'folio' => $this->request->data["id"],
					'fecha_termino' => date("Y-m-d"),
					'fecha_inicio' => date("Y-m-d", $newdate)
				)
			));
		}

		$intervencion = $this->Planificacion->find('first', array(
			'conditions' => array('Planificacion.id' => $id),
			'recursive' => 1
		));


		/** Detecta si correlativo tiene o no Numero de OS SAP adignado desde la planificación */

		$os_sap_ = "0";

		$trabajo_principal = $this->Planificacion->find('first', array(
			'conditions' => array('Planificacion.id' => $intervencion['Planificacion']['correlativo_final']),
			'recursive' => 0
		));

		$os_sap_ = $trabajo_principal["Planificacion"]["os_sap"];

		$this->set('os_sap_', $os_sap_);


		/** ***** FIN OS SAP *********** */



		$this->set('estado', $intervencion["Planificacion"]["estado"]);
		
                if(isset($this->request->query['edt'])) {
                    $edt = $this->request->query['edt'];
                    $edt = substr($edt, 0, 1);
                }else{
                    $edt = "0";
                }
                $this->set('edt', $edt);
                
		if ($intervencion['Planificacion']['padre'] != null) {
			$link_code = $intervencion['Planificacion']['padre'];
			$intervencion_anterior = $this->getIntervencionPadre($link_code);
		}
		

		$tiene_padre = false;
		$estado_padre = 0;
		
		try {
			$padre = $this->Planificacion->find('first', array(
				'fields' => array('Planificacion.id', 'Planificacion.estado'),
				'conditions' => array('Planificacion.folio' => $intervencion['Planificacion']['padre']),
				'recursive' => -1
			));	
			if(isset($padre) && isset($padre['Planificacion']['id'])) {
				$tiene_padre = true;
				$estado_padre = $padre['Planificacion']['estado'];
				$padre_folio = $padre['Planificacion']['id'];
			}
		} catch (Exception $e){
			$this->logger($this, $e->getMessage());
		}
		$this->set(compact('tiene_padre'));
		$this->set(compact('estado_padre'));
		$this->set(compact('padre_folio'));
				
		$tiene_continuacion = false;
		try {
			$hijo = $this->Planificacion->find('first', array(
				'fields' => array('Planificacion.id'),
				'conditions' => array('Planificacion.padre' => $intervencion['Planificacion']['folio']),
				'recursive' => -1
			));	
			if(isset($hijo) && isset($hijo['Planificacion']['id'])) {
				$tiene_continuacion = true;
			}
		} catch (Exception $e){
			$this->logger($this, $e->getMessage());
		}

		$this->set(compact('tiene_continuacion'));
		
		if ($intervencion['Planificacion']['fecha_operacion'] != NULL) {
			$fecha_operacion = $intervencion['Planificacion']['fecha_operacion'];
			$this->set(compact('fecha_operacion'));
		}

		
		$delta_detalles = $this->DeltaDetalle->find('all', array('conditions' => array("DeltaDetalle.folio" => $intervencion["Planificacion"]["folio"]), 'recursive' => -1));
		$deltas = array();

		foreach ($delta_detalles as $key => $value) {
			$deltas[$value["DeltaDetalle"]["delta_item_id"]] = $value["DeltaDetalle"];
		}
		
		$this->set('deltas', $deltas);
		
		$deltas_ = $this->DeltaItem->find('all', array('order' => array('grupo' => 'ASC','nombre' => 'ASC'), 'recursive' => -1));
		$this->set('deltas_', $deltas_);

		$intervencion_fechas = $this->IntervencionFechas->find('first', 
			array('conditions' => 
				array(
					"IntervencionFechas.folio" => $intervencion["Planificacion"]["folio"]
				),
			 'recursive' => -1)
		);

		$this->set('fechas', $intervencion_fechas);

		if ($intervencion['Planificacion']['padre'] != null) {
			$intervencion_anterior_fechas = $this->IntervencionFechas->find(
				'first', 
				array(
					'conditions' => array(
						"IntervencionFechas.folio" => $intervencion['Planificacion']['padre']), 
						'recursive' => -1
					)
			);
			$this->set('fechas_anterior', $intervencion_anterior_fechas);	
		}
                
                /*** 
                 * SI tienes BAcklogs los muestra
                 */
                $backlogs = array();
                //if($intervencion['Planificacion']['correlativo_final'] != ''){
                    
                $backlogs = $this->Backlog->find('all',
                                            array(
                                                'conditions' => array(
                                                    "Backlog.intervencion_id" => $intervencion['Planificacion']['correlativo_final']),
                                                    'recursive' => 1
                                                )
                                        );
                $bklgs = $this->Backlog->find('all',
                                            array(
                                                'conditions' => array(
                                                    "Backlog.equipo_id" => $intervencion['Planificacion']['unidad_id'],
                                                    'Backlog.estado_id IN' => array(8,2), 'Backlog.e'=>'1'
                                                    ),
                                                'recursive' => 1
                                                )
                                        );
                //$backlogs = $bklg;
                $backlogs = array_merge($backlogs,$bklgs);
                
                $this->set('backlogs', $backlogs);
		$this->set('intervencion', $intervencion);
		$this->set('intervencion_anterior', $intervencion_anterior);
		$this->set('titulo', 'Detalle de la Intervención');

	}
	
	public function getIntervencionPadre($link_code){
		$this->loadModel('Planificacion');
		$intervencion = $this->Planificacion->find('first', array(
			'fields' => array("Planificacion.*"),
			'conditions' => array('Planificacion.folio' => $link_code),
			'recursive' => -1
		));
		if(isset($intervencion["Planificacion"])){
			if($intervencion["Planificacion"]["estado"] != 10){
				return $intervencion;
			}else{
				return $this->getIntervencionPadre($intervencion["Planificacion"]["padre"]);
			}
		}else{
			return null;
		}
	}
	
	public function detalle2($id, $despliegue = '') {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$log_file = "../../log/dbm-".date("Y-m-d").".log";
		$usuarioID = $this->getUsuarioID();
		$this->loadModel('Planificacion');
		$this->loadModel('PermisoUsuario');
		$this->loadModel('UsuarioFaena');
		$this->loadModel('DeltaDetalle');
		$this->loadModel('MotivoLlamado');
		$this->loadModel('DeltaItem');
		$this->loadModel('IntervencionFechas');
		$this->loadModel('IntervencionComentarios');
		$this->loadModel('IntervencionElementos');
		$this->loadModel('IntervencionDecisiones');
		$this->loadModel('IntervencionFluido');
		$this->loadModel('TipoTecnico');
		$this->loadModel('TipoElemento');
		$this->loadModel('Solucion');
		$this->loadModel('Fluido');
		$this->loadModel('FluidoTipoIngreso');
		$this->loadModel('Turno');
		$this->loadModel('Periodo');
		$this->loadModel('LugarReparacion');
		
		$this->loadModel('Unidad');
		$this->loadModel('Sintoma');
		$this->loadModel('Backlog');
		$this->loadModel('ReporteBase');
		$this->loadModel('LogIntervencion');
		
		$util = new UtilidadesController();
		$this->set('util', $util);
		$this->set("seccion","RevisiónDCC");

		if (isset($this->request->data) && count($this->request->data) > 5) {

			$guardar_detalle = $this->request->data["acepta-aprobar"];

			$folio = $this->request->data["folio_inicial"];
			$tipointervencion = $this->request->data["tipointervencion"];
			unset($this->request->data["guardar_detalle"]);
			$id = $this->request->data["id"];

            $planificacion = $this->Planificacion->find('first', array(
                //'fields' => array('id', 'json'),
                'conditions' => array('Planificacion.id' => $id),
                'recursive' => -1
            ));

			$fechas = array();
			$fechas["id"] = $this->request->data["id_fechas"];
			
			try {
				if(isset($this->request->data["llamado"]))
					$fechas["llamado"] = $this->request->data["llamado"][0].' '.$this->request->data["llamado"][1].':'.$this->request->data["llamado"][2].' '.$this->request->data["llamado"][3];
				if(isset($this->request->data["llegada"]))
					$fechas["llegada"] = $this->request->data["llegada"][0].' '.$this->request->data["llegada"][1].':'.$this->request->data["llegada"][2].' '.$this->request->data["llegada"][3];
				if(isset($this->request->data["inicio_intervencion"]))
					$fechas["inicio_intervencion"] = $this->request->data["inicio_intervencion"][0].' '.$this->request->data["inicio_intervencion"][1].':'.$this->request->data["inicio_intervencion"][2].' '.$this->request->data["inicio_intervencion"][3];
				if(isset($this->request->data["termino_intervencion"]))
					$fechas["termino_intervencion"] = $this->request->data["termino_intervencion"][0].' '.$this->request->data["termino_intervencion"][1].':'.$this->request->data["termino_intervencion"][2].' '.$this->request->data["termino_intervencion"][3];
				if(isset($this->request->data["inicio_prueba_potencia"]))
					$fechas["inicio_prueba_potencia"] = $this->request->data["inicio_prueba_potencia"][0].' '.$this->request->data["inicio_prueba_potencia"][1].':'.$this->request->data["inicio_prueba_potencia"][2].' '.$this->request->data["inicio_prueba_potencia"][3];
				if(isset($this->request->data["termino_prueba_potencia"]))
					$fechas["termino_prueba_potencia"] = $this->request->data["termino_prueba_potencia"][0].' '.$this->request->data["termino_prueba_potencia"][1].':'.$this->request->data["termino_prueba_potencia"][2].' '.$this->request->data["termino_prueba_potencia"][3];
				if(isset($this->request->data["inicio_desconexion"]))
					$fechas["inicio_desconexion"] = $this->request->data["inicio_desconexion"][0].' '.$this->request->data["inicio_desconexion"][1].':'.$this->request->data["inicio_desconexion"][2].' '.$this->request->data["inicio_desconexion"][3];
				if(isset($this->request->data["termino_desconexion"]))
					$fechas["termino_desconexion"] = $this->request->data["termino_desconexion"][0].' '.$this->request->data["termino_desconexion"][1].':'.$this->request->data["termino_desconexion"][2].' '.$this->request->data["termino_desconexion"][3];
				if(isset($this->request->data["inicio_conexion"]))
					$fechas["inicio_conexion"] = $this->request->data["inicio_conexion"][0].' '.$this->request->data["inicio_conexion"][1].':'.$this->request->data["inicio_conexion"][2].' '.$this->request->data["inicio_conexion"][3];
				if(isset($this->request->data["termino_conexion"]))
					$fechas["termino_conexion"] = $this->request->data["termino_conexion"][0].' '.$this->request->data["termino_conexion"][1].':'.$this->request->data["termino_conexion"][2].' '.$this->request->data["termino_conexion"][3];
				if(isset($this->request->data["inicio_puesta_marcha"]))
					$fechas["inicio_puesta_marcha"] = $this->request->data["inicio_puesta_marcha"][0].' '.$this->request->data["inicio_puesta_marcha"][1].':'.$this->request->data["inicio_puesta_marcha"][2].' '.$this->request->data["inicio_puesta_marcha"][3];
				if(isset($this->request->data["termino_puesta_marcha"]))
					$fechas["termino_puesta_marcha"] = $this->request->data["termino_puesta_marcha"][0].' '.$this->request->data["termino_puesta_marcha"][1].':'.$this->request->data["termino_puesta_marcha"][2].' '.$this->request->data["termino_puesta_marcha"][3];
				if(isset($this->request->data["termino_reproceso"]))
					$fechas["termino_reproceso"] = $this->request->data["termino_reproceso"][0].' '.$this->request->data["termino_reproceso"][1].':'.$this->request->data["termino_reproceso"][2].' '.$this->request->data["termino_reproceso"][3];
				if(isset($this->request->data["fecha_inicio_global"]))
					$fechas["fecha_inicio_global"] = $this->request->data["fecha_inicio_global"];
				if(isset($this->request->data["fecha_termino_global"]))
					$fechas["fecha_termino_global"] = $this->request->data["fecha_termino_global"];
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
                                                
			// Revisión de cambio de tipo de intervención para fechas
			if(isset($this->request->data["tipointervencion"])){
				if($planificacion["Planificacion"]["tipointervencion"] != $this->request->data["tipointervencion"]){
					if ($planificacion["Planificacion"]["tipointervencion"] == "RP" && $this->request->data["tipointervencion"] == "RI"){
						try {
							$fechas["llamado"] = $fechas["inicio_intervencion"];
							$fechas["llegada"] = $fechas["inicio_intervencion"];
						} catch (Exception $e){
							$this->logger($this, $e->getMessage());
						}
					} elseif ($planificacion["Planificacion"]["tipointervencion"] == "RI" && $this->request->data["tipointervencion"] == "RP"){
						try {
							unset($fechas["llamado"],$fechas["llegada"]);
						} catch (Exception $e){
							$this->logger($this, $e->getMessage());
						}
					} elseif ($planificacion["Planificacion"]["tipointervencion"] == "RP" && $this->request->data["tipointervencion"] == "EX"){
						try {
							$fechas["llamado"] = $fechas["inicio_intervencion"];
							$fechas["llegada"] = $fechas["inicio_intervencion"];
						} catch (Exception $e){
							$this->logger($this, $e->getMessage());
						}
					} elseif ($planificacion["Planificacion"]["tipointervencion"] == "EX" && $this->request->data["tipointervencion"] == "RP"){
						try {
							unset($fechas["llamado"],$fechas["llegada"]);
						} catch (Exception $e){
							$this->logger($this, $e->getMessage());
						}
					} elseif ($this->request->data["tipointervencion"] == "EX"){
						try {
							unset($fechas["inicio_prueba_potencia"],$fechas["termino_prueba_potencia"],$fechas["termino_desconexion"],$fechas["inicio_desconexion"],$fechas["inicio_conexion"],$fechas["termino_conexion"],$fechas["inicio_puesta_marcha"],$fechas["termino_puesta_marcha"]);
						} catch (Exception $e){
							$this->logger($this, $e->getMessage());
						}
					}elseif ($this->request->data["tipointervencion"] == "MP"){
                        try {
							unset($fechas["llamado"],$fechas["llegada"],$fechas["inicio_prueba_potencia"],$fechas["termino_prueba_potencia"],$fechas["inicio_desconexion"],$fechas["termino_desconexion"],$fechas["inicio_conexion"],$fechas["termino_conexion"],$fechas["inicio_puesta_marcha"],$fechas["termino_puesta_marcha"], $fechas["termino_reproceso"], $fechas["fecha_inicio_delta"], $fechas["fecha_termino_turno"]);
						} catch (Exception $e){
							$this->logger($this, $e->getMessage());
						}
                    }
				}
			}
			
			try {
                                
				$this->IntervencionFechas->save($fechas);
				$this->intervencionLogger($planificacion, "intervencion_fecha", $fechas);
                                
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				$comentarios = array();
				$comentarios["id"] = $this->request->data["id_comentarios"];
				if(isset($this->request->data["comentario"])) 
					$comentarios["comentario"] = $this->request->data["comentario"];
				if(isset($this->request->data["codigokch"]))
					$comentarios["codigo_kch"] = $this->request->data["codigokch"];
				$this->IntervencionComentarios->save($comentarios);
				$this->intervencionLogger($planificacion, "intervencion_comentarios", $comentarios);
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			

			$json = json_decode($planificacion["Planificacion"]["json"], true);
			
			if(isset($this->request->data["AvanceHoraPotenciaTotal"]))
				$json["AvanceHoraPotenciaTotal"] = $this->request->data["AvanceHoraPotenciaTotal"];
			if(isset($this->request->data["AvanceHoraPotenciaTotalObservacion"]))
				$json["AvanceHoraPotenciaTotalObservacion"] = $this->request->data["AvanceHoraPotenciaTotalObservacion"];
			if(isset($this->request->data["AvanceHoraPotenciaOEM"]))
				$json["AvanceHoraPotenciaOEM"] = $this->request->data["AvanceHoraPotenciaOEM"];
			if(isset($this->request->data["AvanceHoraPotenciaOEMObservacion"]))
				$json["AvanceHoraPotenciaOEMObservacion"] = $this->request->data["AvanceHoraPotenciaOEMObservacion"];
			if(isset($this->request->data["AvanceHoraPotenciaMINA"]))
				$json["AvanceHoraPotenciaMINA"] = $this->request->data["AvanceHoraPotenciaMINA"];
			if(isset($this->request->data["AvanceHoraPotenciaMINAObservacion"]))
				$json["AvanceHoraPotenciaMINAObservacion"] = $this->request->data["AvanceHoraPotenciaMINAObservacion"];
			if(isset($this->request->data["AvanceHoraPotenciaDCC"]))
				$json["AvanceHoraPotenciaDCC"] = $this->request->data["AvanceHoraPotenciaDCC"];
			if(isset($this->request->data["AvanceHoraPotenciaDCCObservacion"]))
				$json["AvanceHoraPotenciaDCCObservacion"] = $this->request->data["AvanceHoraPotenciaDCCObservacion"];
			
			try {
				// Eliminar fluidos
				$this->IntervencionFluido->deleteAll(array('IntervencionFluido.intervencion_id' => $this->request->data["id"]), false);
				// Registro de fluidos
				foreach($this->request->data["fluido"] as $key => $value) {
					$data_fluido = array();
					$data_fluido["fluido_id"] = $key;
					$data_fluido["cantidad"] = $value;
					$data_fluido["intervencion_id"] = $this->request->data["id"];
					if(isset($this->request->data["tipo_fluido"][$key])){
						$data_fluido["tipo_ingreso_id"] = $this->request->data["tipo_fluido"][$key];
					}
					$this->IntervencionFluido->create();
					$this->IntervencionFluido->save($data_fluido);
					if($value > 0 ) {
						$this->intervencionLogger($planificacion, "intervencion_fluidos", $data_fluido);
					}
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}


			try {
				$deltasIds = $this->DeltaItem->find('all', array(
					'fields' => array('id'),
					'conditions' => array('DeltaItem.grupo' => 9),
					'recursive' => -1
				));

				$idsGrupo = [];
				foreach ($deltasIds as $deltaItem) {
					$idsGrupo[] = $deltaItem["DeltaItem"]["id"];
				}				
				//$this->DeltaDetalle->deleteAll(array('DeltaDetalle.folio' => $folio, 'DeltaDetalle.delta_item_id not in' => $idsGrupo ), false);
				/*for($i = 1; $i < 100; $i++){
                                    $this->DeltaDetalle->deleteAll(array('DeltaDetalle.folio' => $folio, 'DeltaDetalle.delta_item_id' => $i), false);
                                    if(isset($this->request->data["delta_r"][$i]) && $this->request->data["delta_r"][$i] != '0'){
                                            try {
                                                    $data = array();
                                                    $data["delta_responsable_id"] = $this->request->data["delta_r"][$i];
                                                    $data["delta_item_id"] = $i;
                                                    $data["folio"] = $folio;
                                                    $data["tiempo"] = intval($this->request->data["delta_m"][$i]) + intval($this->request->data["delta"][$i]) * 60;
                                                    $data["observacion"] = $this->request->data["delta_o"][$i];
                                                    $this->DeltaDetalle->create();
                                                    $this->DeltaDetalle->save($data);
                                            } catch(Exception $e){
                                                    $this->logger($this, $e->getMessage());
                                            }
                                    }
				}*/
                                
				if($this->request->data["delta"] != null){
					//foreach($this->request->data["delta"] as $key => $value) {
					foreach($this->request->data["delta_r"] as $key => $value) {
						$this->DeltaDetalle->deleteAll(array('DeltaDetalle.folio' => $folio,'DeltaDetalle.delta_item_id' => $key), false);
						if(isset($this->request->data["delta_r"][$key]) && $this->request->data["delta_r"][$key] != '0'){
							//$this->DeltaDetalle->deleteAll(array('DeltaDetalle.folio' => $folio, 'DeltaDetalle.delta_item_id' => $key), false);
							$data = array();
							$data["delta_responsable_id"] = $this->request->data["delta_r"][$key];
							$data["delta_item_id"] = $key;
							$data["folio"] = $folio;
							$data["tiempo"] = intval($this->request->data["delta_m"][$key]) + intval($this->request->data["delta"][$key]) * 60;
							$data["observacion"] = $this->request->data["delta_o"][$key];
							$this->DeltaDetalle->create();
							$this->DeltaDetalle->save($data);
						}
					}
				}
			} catch(Exception $e){
				$this->logger($this, $e->getMessage());
			}

			try {
				// Eliminar elementos
				//$this->IntervencionElementos->deleteAll(array('IntervencionElementos.folio' => $folio, 'IntervencionElementos.tipo_registro' => '0'), false);
                                
				if($this->request->data["sistema-id"] != null) {
					// OBTENGO IDS DE ELEMENTOS ANTERIORES.
					$idElemento = $this->IntervencionElementos->find('all', array(
						'fields' => array('IntervencionElementos.id'),
						'conditions' => array('IntervencionElementos.folio' => $folio, 'IntervencionElementos.tipo_registro' => '0')
					));

					// Registro de elementos
					foreach($this->request->data["sistema-id"] as $key => $value) {


						/**  OBTENGO todos los folios de las distintas intervenciones en el correlativo que correspondan a Falla **/
                        $folios_evento = $this->Planificacion->find('all', array(
                            'fields' => array('Planificacion.folio'),
                            'conditions' => array('Planificacion.correlativo_final' => $planificacion['Planificacion']['correlativo_final'])
                        ));



                        foreach ($folios_evento as $value) {
                            $a[] = $value['Planificacion']['folio'];
                        }

                        /**  OBTENGO todos los elementos (folio y tipo_id) para saber si existe mas de un tipo "falla" en el correlativo
                         *     solo puede existir un elemento "falla" en todo el evento **/
                        $elementos_ = $this->IntervencionElementos->find('all', array(
                            'fields' => array('IntervencionElementos.folio'),
                            'conditions' => array('IntervencionElementos.folio' => $a, 'IntervencionElementos.tipo_registro' => '0', 'IntervencionElementos.tipo_id' => 1)
                        ));

                        /** comparo si el elemento falla es de este correlativo, si no, envio mensaje de error con mensaje */
                        if ($this->request->data["tipo-id"][$key] == 1) {
                            foreach ($elementos_ as $elem) {
                                if ($elem['IntervencionElementos']['folio'] != $folio) {
                                    //$this->Session->setFlash('Ya existe un elemento tipo "falla" en este evento, no puede haber dos en el mismo correlativo.', 'guardar_error');
                                	
									$this->redirect('/Trabajo/Detalle2/' . $id . '?edt=' . $this->request->query['edt'] . '&err_el=1');

									//$this->set('error_rp_ri', 'Ya existe un elemento tipo "falla" en este evento, no puede haber dos en el mismo correlativo.');
									//return false;
									
                                }
                            }
                        }

						$data_elemento = array();
						$data_elemento["folio"] = $folio;
						$data_elemento["tipo_registro"] = "0";
						$data_elemento["faena_id"] = $this->request->data["faena_id"];
						$data_elemento["flota_id"] = $this->request->data["flota_id"];
						$data_elemento["equipo_id"] = $this->request->data["equipo_id"];
						$data_elemento["sistema_id"] = $this->request->data["sistema-id"][$key];
						$data_elemento["subsistema_id"] = $this->request->data["subsistema-id"][$key];
						$data_elemento["subsistema_posicion_id"] = $this->request->data["posicion-subsistema-id"][$key];
						$data_elemento["id_elemento"] = $this->request->data["id-elemento"][$key];
						$data_elemento["elemento_id"] = $this->request->data["elemento-id"][$key];
						$data_elemento["elemento_posicion_id"] = $this->request->data["posicion-elemento-id"][$key];
						$data_elemento["diagnostico_id"] = $this->request->data["diagnostico-id"][$key];
						$data_elemento["solucion_id"] = $this->request->data["solucion-id"][$key];
						$data_elemento["tipo_id"] = $this->request->data["tipo-id"][$key];
						$data_elemento["pn_saliente"] = $this->request->data["pn_saliente"][$key];
						$data_elemento["pn_entrante"] = $this->request->data["pn_entrante"][$key];
						$data_elemento["tiempo"] = $this->request->data["hora_elemento"][$key] * 60 + $this->request->data["minuto_elemento"][$key];
						$this->IntervencionElementos->create();
						$this->IntervencionElementos->save($data_elemento);
						$this->intervencionLogger($planificacion, "intervencion_elementos", $data_elemento);
					}
                                
					// ELIMINO ELEMENTOS ANTERIORES
					foreach($idElemento as $el){
						$this->IntervencionElementos->deleteAll(array('IntervencionElementos.id' => $el['IntervencionElementos']['id']), false);
					}

					/** Si es RP o RI y es la ultima intervención verifico si tiene al menos un elemento falla
					Es obligatorio que exista al menos un elemento falla en este tipo de intervenciones */

					if($this->request->data["intervencion_terminada"] == "S" && ($this->request->data["tipointervencion"] == "RP" || $this->request->data["tipointervencion"] == "RI")){

						/**  OBTENGO todos los folios de las distintas intervenciones en el correlativo que correspondan a Falla **/
						$folios_evento = $this->Planificacion->find('all', array(
							'fields' => array('Planificacion.folio'),
							'conditions' => array('Planificacion.correlativo_final' => $planificacion['Planificacion']['correlativo_final'])
						));

						foreach ($folios_evento as $value) {
							$a[] = $value['Planificacion']['folio'];
						}

						/**  OBTENGO todos los elementos (folio y tipo_id) para saber si existe mas de un tipo "falla" en el correlativo
						 *     solo puede existir un elemento "falla" en todo el evento **/
						$elementos_ = $this->IntervencionElementos->find('all', array(
							'fields' => array('IntervencionElementos.folio'),
							'conditions' => array('IntervencionElementos.folio' => $a, 'IntervencionElementos.tipo_registro' => '0', 'IntervencionElementos.tipo_id' => 1)
						));



						if(count($elementos_) < 1){
							//$this->Session->setFlash('Para RI y RP dentro del correlativo debe existir al menos un elemento tipo "falla".', 'guardar_error');
							//$this->set('error_rp_ri', 'Para RI y RP dentro del correlativo debe existir al menos un elemento tipo "falla".');
							//return false;
							$this->redirect('/Trabajo/Detalle2/' . $id . '?edt=' . $this->request->query['edt']. '&err_el=0');
						}
					}


				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				// Registro de técnicos
				if(isset($this->request->data["tecnico-id"][0])) {
					$json["UserID"] = $this->request->data["tecnico-id"][0];
					unset($this->request->data["tecnico-id"][0]);
				}
				foreach($this->request->data["tecnico-id"] as $key => $value) {
					$i = str_pad($key + 1,2,"0",STR_PAD_LEFT);
					$json["TecnicoApoyo".$i] = $value;
					
				}
				
				foreach($this->request->data["tipo-tecnico"] as $key => $value) {
					$i = str_pad($key + 1,2,"0",STR_PAD_LEFT);
					$json["TipoTecnicoApoyo".$i] = $value;
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				if(isset($this->request->data["motivo_llamado_id"]) && is_numeric($this->request->data["motivo_llamado_id"])) {
					$json["MotivoID"] = $this->request->data["motivo_llamado_id"];
				}
				if(isset($this->request->data["categoria_sintoma_id"]) && is_numeric($this->request->data["categoria_sintoma_id"])) {
					$json["CategoriaID"] = $this->request->data["categoria_sintoma_id"];
				}
				if(isset($this->request->data["sintoma_id"]) && is_numeric($this->request->data["sintoma_id"])) {
					$json["SintomaID"] = $this->request->data["sintoma_id"];
				}
				if(isset($this->request->data["tipomantencion"])) {
						$json["tipo_programado"] = $this->request->data["tipomantencion"];
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			
			$json = json_encode($json);
			$data = array();
			$data["json"] = $json;
			$data["id"] = $planificacion["Planificacion"]["id"];
			
			try {
				if(isset($this->request->data["Turno"])) {
					$data["turno_id"] = $this->request->data["Turno"];
				}
				
				if(isset($this->request->data["Periodo"])) {
					$data["periodo_id"] = $this->request->data["Periodo"];
				}
				
				if(isset($this->request->data["LugarReparacion"])) {
					$data["lugar_reparacion_id"] = $this->request->data["LugarReparacion"];
				}

				if(isset($this->request->data["tipomantencion"])) {
					$data["tipomantencion"] = $this->request->data["tipomantencion"];
				}
                                
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				if(isset($this->request->data["motivo_llamado_id"]) && is_numeric($this->request->data["motivo_llamado_id"])) {
					$data["motivo_llamado_id"] = $this->request->data["motivo_llamado_id"];
				}
				if(isset($this->request->data["categoria_sintoma_id"]) && is_numeric($this->request->data["categoria_sintoma_id"])) {
					$data["categoria_sintoma_id"] = $this->request->data["categoria_sintoma_id"];
				}
				if(isset($this->request->data["sintoma_id"]) && is_numeric($this->request->data["sintoma_id"])) {
					$data["sintoma_id"] = $this->request->data["sintoma_id"];
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			//UPDATE HOROMETROS
			try {
				if(isset($this->request->data["horometro_cabina"])){
					$hc = str_replace("," , "." , $this->request->data["horometro_cabina"]);
					if(is_numeric($hc)) {
						$data["horometro_cabina"] = $hc;
                        $this->request->data["horometro_cabina"] = $hc;
					}
                }else{
					$data["horometro_cabina"] = 0;
				}
                                
				if(isset($this->request->data["horometro_motor"])){
					$hm = str_replace("," , "." , $this->request->data["horometro_motor"]);
					if(is_numeric($hm)) {
						$data["horometro_motor"] = $hm;
						$this->request->data["horometro_motor"] = $hm;
					}
				}else{
					$data["horometro_motor"] = 0;
				}
                           
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
                        
                        
			//update tipomantencion para todo el correlativo
			try {
				if(isset($this->request->data["tipomantencion"]) && is_numeric($this->request->data["tipomantencion"])) {
					$this->Planificacion->updateAll(
						array("Planificacion.tipomantencion" => "'".$this->request->data["tipomantencion"]."'"),
						array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
					);
					$this->intervencionLogger($planificacion, "intervencion_tipoMantencion", array("Planificacion.tipomantencion" => $this->request->data["tipomantencion"]));
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
                        
                        
                        
			// Update data de sintoma para todo el correlativo
			try {
				if(isset($this->request->data["motivo_llamado_id"]) && is_numeric($this->request->data["motivo_llamado_id"])) {
					$this->Planificacion->updateAll(
						array("Planificacion.motivo_llamado_id" => "'".$this->request->data["motivo_llamado_id"]."'"),
						array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
					);
					$this->intervencionLogger($planificacion, "intervencion_motivo_llamado",array("Planificacion.motivo_llamado_id" => $this->request->data["motivo_llamado_id"]));
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				if(isset($this->request->data["categoria_sintoma_id"]) && is_numeric($this->request->data["categoria_sintoma_id"])) {
					$this->Planificacion->updateAll(
						array("Planificacion.categoria_sintoma_id" => "'".$this->request->data["categoria_sintoma_id"]."'"),
						array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
					);
					$this->intervencionLogger($planificacion, "intervencion_cat_sintoma",array("Planificacion.categoria_sintoma_id" => $this->request->data["categoria_sintoma_id"]));
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				if(isset($this->request->data["sintoma_id"]) && is_numeric($this->request->data["sintoma_id"])) {
					$this->Planificacion->updateAll(
						array("Planificacion.sintoma_id" => "'".$this->request->data["sintoma_id"]."'"),
						array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
					);
					$this->intervencionLogger($planificacion, "intervencion_sintoma",array("Planificacion.sintoma_id" => $this->request->data["sintoma_id"]));
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
                        
                        
			//cambia horometros de cabina y motor para todo el correlativo
			try {
				if(isset($this->request->data["horometro_cabina"]) && is_numeric($this->request->data["horometro_cabina"])) {
					$this->Planificacion->updateAll(
						array("Planificacion.horometro_cabina" => "'".$this->request->data["horometro_cabina"]."'"),
						array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
					);
					if($this->request->data["horometro_cabina"] > 0) {
						$this->intervencionLogger($planificacion, "intervencion_h_cabina", array("Planificacion.horometro_cabina" => $this->request->data["horometro_cabina"]));
					}
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
                        
			try {
				if(isset($this->request->data["horometro_motor"]) && is_numeric($this->request->data["horometro_motor"])) {
					$this->Planificacion->updateAll(
						array("Planificacion.horometro_motor" => "'".$this->request->data["horometro_motor"]."'"),
						array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
					);
					if($this->request->data["horometro_motor"] > 0) {
						$this->intervencionLogger($planificacion, "intervencion_h_motor", array("Planificacion.horometro_motor" => $this->request->data["horometro_motor"]));
					}
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
                        
                        
                        			
			// Validacion cambio de tipo de intervencion
			if(isset($this->request->data["tipointervencion"])){
				if($planificacion["Planificacion"]["tipointervencion"] != $this->request->data["tipointervencion"]){
					if($planificacion["Planificacion"]["tipointervencion"] != "MP" && $this->request->data["tipointervencion"] != "MP") {
						$data["tipointervencion"] = $this->request->data["tipointervencion"];
						$log = fopen($log_file, "a");
						fwrite($log, date("Y-m-d h:i:s A") . "\tID: " . $planificacion["Planificacion"]["id"] . "\tUID: $usuarioID\tCambio de tipo intervencion: ".$planificacion["Planificacion"]["tipointervencion"]." a {$this->request->data["tipointervencion"]}\n");
						fclose($log);
					}
				}
			}
		
			try {
				if(isset($this->request->data["fecha_inicio_global"])) {
					$fecha_inicio_global = strtotime($this->request->data["fecha_inicio_global"]);
					$data["fecha"] = date("Y-m-d", $fecha_inicio_global);
					$data["hora"] = date("h:i A", $fecha_inicio_global);
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				if(isset($this->request->data["fecha_termino_global"])) {
					$fecha_termino_global = strtotime($this->request->data["fecha_termino_global"]);
					$data["fecha_termino"] = date("Y-m-d", $fecha_termino_global);
					$data["hora_termino"] = date("h:i A", $fecha_termino_global);
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				if(isset($this->request->data["fecha_inicio_global"]) && isset($this->request->data["fecha_termino_global"])) {
					$tiempo_trabajo = ($fecha_termino_global - $fecha_inicio_global) / 60;
					$hours = floor($tiempo_trabajo / 60);
					$minutes = ($tiempo_trabajo % 60);
					$data["tiempo_trabajo"] = sprintf('%02d:%02d', $hours, $minutes);
				}
			} catch (Exception $e){
				$this->logger($this, $e->getMessage());
			}
			
			try {
				// Si hay cambio de tipo de intervencion, se cambia el tipo a todo el correlativo, si nueveo tipo es EX, se finalizan todas las intervenciones ya que no tiene continuacion ni planificacion.
				try {
					$this->Planificacion->updateAll(
						array("Planificacion.tipointervencion" => "'".$this->request->data["tipointervencion"]."'"),
						array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
					);
					$this->intervencionLogger($planificacion, "intervencion_tipo",array("Planificacion.tipointervencion" => $this->request->data["tipointervencion"]));

				} catch (Exception $e){
					$this->logger($this, $e->getMessage());
				}
				
				try {
					if($this->request->data["tipointervencion"] == "EX"){
						// Si RI pasa a EX se elimina la planificación.
						$this->Planificacion->deleteAll(
							array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"], 'Planificacion.estado' => 2),
							false
						);
						
						$this->Planificacion->updateAll(
							array("Planificacion.padre" => "''", 'Planificacion.terminado' => "'S'", 'Planificacion.estado' => 7),
							array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
						); 
						
						// cada uno con su propio correlativo, confirmado!
						$this->Planificacion->updateAll(
							array("Planificacion.padre" => "''", 'Planificacion.terminado' => "'S'","Planificacion.correlativo_final" => "Planificacion.id"),
							array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
						);
					}
				} catch (Exception $e){
					$this->logger($this, $e->getMessage());
				}
				
				$data["reporte_base"] = NULL;
				//print_r($data);
				$this->Planificacion->save($data);
                                 
				
				try {
					$this->ReporteBase->deleteAll(
						array("ReporteBase.correlativo" => $this->request->data["correlativo_final"]),
						false
					);
				} catch (Exception $e){
					$this->logger($this, $e->getMessage());
				}
				
				try {
					$this->Planificacion->updateAll(
						array("Planificacion.reporte_base" => "NULL"),
						array("Planificacion.correlativo_final" => $this->request->data["correlativo_final"])
					);
				} catch (Exception $e){
					$this->logger($this, $e->getMessage());
				}
				
				$log = fopen($log_file, "a");
				fwrite($log, date("Y-m-d h:i:s A") . "\tID: " . $planificacion["Planificacion"]["id"] . "\tUID: $usuarioID\t Intervencion guardada OK\n");
				fclose($log);
			} catch(Exception $e){
				$log = fopen($log_file, "a");
				fwrite($log, date("Y-m-d h:i:s A") . "\tID: " . $planificacion["Planificacion"]["id"] . "\tUID: $usuarioID\t Intervencion guardada NOK, ".$e->getMessage()."\n");
				fclose($log);
				$this->logger($this, $e->getMessage());
			}
			
			
			
			
			$this->set('terminada', $this->request->data["intervencion_terminada"]);


			$this->Session->setFlash('Intervención guardada con éxito.', 'guardar_exito');
			if ($guardar_detalle == "S") {
				$this->redirect('/Trabajo/Detalle/' . $id.'?edt='.$this->request->query['edt']);
			} else {
				$this->redirect('/Trabajo/Detalle2/' . $id.'?edt='.$this->request->query['edt']);
			}
		}
		try {
			$planificacion = $this->Planificacion->find('first', array(
				'fields' => array("Planificacion.*","Sintoma.nombre","Flota.nombre","Unidad.unidad","Faena.nombre","Unidad.motor_id"),
				'conditions' => array('Planificacion.id' => $id),
				'recursive' => 1
			));
                        
			if($planificacion["Planificacion"]["tipointervencion"]=='BL'){
				$planificacion["Planificacion"]["tipointervencion"]="OP";
			}
			$this->set('estado', $planificacion["Planificacion"]["estado"]);
			
			if(isset($this->request->query['edt'])) {
				$edt = $this->request->query['edt'];
				$edt = substr($edt, 0, 1);

			}else{
				$edt = "0";
			}
			$this->set('edt', $edt);


			if(isset($this->request->query['err_el'])) {
				$err_el = $this->request->query['err_el'];
				if($err_el == 0) $this->set('error_rp_ri', 'Para RI y RP dentro del correlativo debe existir al menos un elemento tipo "falla".');
				else if($err_el == 1) $this->set('error_rp_ri','Ya existe un elemento tipo "falla" en este evento, no puede haber dos en el mismo correlativo.');
			}

			$faena_id = $planificacion["Planificacion"]["faena_id"];
			$json = str_replace('\n', '', $planificacion["Planificacion"]["json"]);
			$json = json_decode($json, true);
			
			$backlogs = $this->Backlog->find('all', array(
				'conditions' => array('Backlog.folio' => $planificacion["Planificacion"]["folio"]),
				'recursive' => 1
			));
			
			$this->set('backlogs', $backlogs);
			
			$usuarios_id = "";
			$tecnicos = array();
			$tecnicos[] = "-1";
			$tecnicos[] = "-2";
			
			if (isset($json["UserID"]) && is_numeric($json["UserID"])) {
				$tecnicos[] = $json["UserID"];
			}
			
			if (isset($json["tecnico_principal"]) && is_numeric($json["tecnico_principal"])) {
				$usuarios_id .= $json["tecnico_principal"] . ",";
			}
			
			for ($i = 2; $i < 11; $i++) {
				if (isset($json["TecnicoApoyo".(str_pad($i,2,"0",STR_PAD_LEFT))]) && is_numeric($json["TecnicoApoyo".(str_pad($i,2,"0",STR_PAD_LEFT))])) {
					$tecnicos[] = $json["TecnicoApoyo".(str_pad($i,2,"0",STR_PAD_LEFT))];
				}
			}
			
			for ($i = 2; $i < 50; $i++) {
				if (isset($json["tecnico_".$i]) && is_numeric($json["tecnico_".$i])) {
					$usuarios_id .= $json["tecnico_".$i] . ",";
				} else {
					break;
				}
			}
			
			$usuarios = $this->PermisoUsuario->find('all', array(
				'conditions' => array(
				"OR" => array(
					"AND" => array("PermisoUsuario.faena_id" => $faena_id, "PermisoUsuario.cargo_id" => "1"),
					"Usuario.id" => $tecnicos
					)
				),
				'fields' => array("Usuario.apellidos", "Usuario.nombres", "Usuario.id"),
				'order' => array('Usuario.apellidos', 'Usuario.nombres'),
				'recursive' => 1
			));
			
			$this->set('usuarios', $usuarios);
			
			if ($faena_id != 0) {
				$flota = $this->Unidad->find('all', array(
					'fields' =>array('DISTINCT Unidad.flota_id', 'Unidad.faena_id', 'Flota.nombre') ,
					'conditions' => array('Unidad.faena_id' => $faena_id)
				));
				$unidad = $this->Unidad->find('all', array(
					'fields' =>array('Unidad.id', 'Unidad.flota_id', 'Unidad.faena_id', 'Unidad.unidad', 'Unidad.horometro', 'Unidad.esn') ,
					'conditions' => array("Unidad.faena_id = $faena_id AND Unidad.flota_id = {$planificacion["Planificacion"]["flota_id"]}")
				));
			} else {
				$flota = $this->Unidad->find('all', array(
					'fields' =>array('DISTINCT Unidad.flota_id', 'Unidad.faena_id', 'Flota.nombre')));
				$unidad = $this->Unidad->find('all', array(
					'fields' =>array('Unidad.id', 'Unidad.flota_id', 'Unidad.faena_id', 'Unidad.unidad', 'Unidad.horometro', 'Unidad.esn') ,
					'conditions' => array("Unidad.faena_id <> 0 AND Unidad.flota_id = {$planificacion["Planificacion"]["flota_id"]}")
				));
			}
			//die("16");
			$sintoma = $this->Sintoma->find('all', array(
				'order' => 'Sintoma.nombre',
				'recursive' => -1
			));
			
			
			$this->set('flota', $flota);
			$this->set('unidad', $unidad);
			$this->set('sintoma', $sintoma); 
			if(!isset($json["categoria_sintoma"])||@$json["categoria_sintoma"]==""){
				//$json["categoria_sintoma"] = $util->getCategoriaSintoma($json["SintomaID"]);
			}
						
			if($json["MotivoID"] == "OP"){ $json["MotivoID"] = "1"; }
			if($json["MotivoID"] == "FC"){ $json["MotivoID"] = "2"; }
			if($json["MotivoID"] == "OT"){ $json["MotivoID"] = "3"; }
			
			$this->set('categoria_id', $json["CategoriaID"]);
			$this->set('sintoma_id', $json["SintomaID"]);
			
			$this->set('json', $json);
			$this->set('intervencion', $planificacion);
			$this->set('id', $id);
			$this->set('titulo', 'Detalle de la Intervención');
			
			/* Fix backlogs editables */
			if(@$json["tecnico_principal"]==""){
				$json["tecnico_principal"]="0";
			}
			if(@$json["sync_inte"]==""){
				$json["sync_inte"]="0";
			}
			/*
			$backlogs = $this->Backlog->find('all', array(
				'fields' => array('Backlog.*','IntervencionElementos.*'),
				'conditions' => array('Backlog.folio' => $planificacion["Planificacion"]["folio"], 'Backlog.e'=>'1'),
				'recursive' => 1,
				'order'=>array('Backlog.id')
			));
			$this->set('backlogs', $backlogs);*/
			$this->loadModel('Sistema_Motor');
			$motor_id=@$json["motor_id"];
			if($motor_id==""){
				$motor_id="0";
			}
			
			/*
			$backlog_sistemas=$this->Sistema_Motor->find('all',array(
														'fields' => array('DISTINCT Sistema.id','Sistema.nombre','Sistema_Motor.id'),
														'conditions' => array("Sistema_Motor.motor_id=$motor_id"),
														'recursive'=>1,
														'order'=>array('Sistema.nombre')));
			//die("1858");
			$this->set('backlog_sistemas', $backlog_sistemas);*/
			/*
			$modificaciones = $this->LogIntervencion->find('all', array(
				'fields' => array('LogIntervencion.planificacion_id','LogIntervencion.fecha','Usuario.nombres','Usuario.apellidos'),
				'conditions' => array('LogIntervencion.planificacion_id' => $id),
				'recursive' => 1,
				'order' => 'LogIntervencion.planificacion_id DESC'
			));
			$this->set('modificaciones', $modificaciones);*/
			
			// Fechas
			$intervencion_fechas = $this->IntervencionFechas->find('first', array('conditions' => array("IntervencionFechas.folio" => $planificacion["Planificacion"]["folio"]), 'recursive' => -1));
			$this->set('fechas', $intervencion_fechas);
			//print_r($intervencion_fechas);
			$intervencion_comentarios = $this->IntervencionComentarios->find('first', array('conditions' => array("IntervencionComentarios.folio" => $planificacion["Planificacion"]["folio"]), 'recursive' => -1));
			$this->set('comentarios', $intervencion_comentarios["IntervencionComentarios"]);
			$intervencion_elementos = $this->IntervencionElementos->find('all',  array(
				'conditions' => array("IntervencionElementos.folio" => $planificacion["Planificacion"]["folio"], 'IntervencionElementos.tipo_registro' => '0'), 
				'recursive' => 1
			));
			//print_r($intervencion_elementos);
			$this->set('elementos', $intervencion_elementos);
			
			$intervencion_elementos_reproceso = $this->IntervencionElementos->find('all', array('conditions' => array("IntervencionElementos.folio" => $planificacion["Planificacion"]["folio"], 'IntervencionElementos.tipo_registro' => '1'), 'recursive' => 1));
			$this->set('elementos_reproceso', $intervencion_elementos_reproceso);
			
			
			$intervencion_decisiones = $this->IntervencionDecisiones->find('first', array('conditions' => array("IntervencionDecisiones.folio" => $planificacion["Planificacion"]["folio"]), 'recursive' => 1));
			$this->set('decisiones', $intervencion_decisiones["IntervencionDecisiones"]);
			
			$delta_detalles = $this->DeltaDetalle->find('all', array('conditions' => array("DeltaDetalle.folio" => $planificacion["Planificacion"]["folio"]), 'recursive' => -1));
			$deltas = array();
			//print_r($delta_detalles);

			foreach ($delta_detalles as $key => $value) {
				$deltas[$value["DeltaDetalle"]["delta_item_id"]] = $value["DeltaDetalle"];
			}
			
			$motivos = $this->MotivoLlamado->find('list', array('order' => 'nombre'));
			
			$fluidos = $this->Fluido->find('all', array('order' => array("Fluido.nombre" => "ASC"), 'recursive' => 1));
			$tmp = array();
			
			$ingreso_fluidos = $this->IntervencionFluido->find('all', array('conditions'=>array('intervencion_id'=>$planificacion["Planificacion"]["id"]),'recursive' => -1));
			$tmp = array();
			foreach ($ingreso_fluidos as $fluido) {
				$tmp[$fluido["IntervencionFluido"]["fluido_id"]] = array($fluido["IntervencionFluido"]["cantidad"],$fluido["IntervencionFluido"]["tipo_ingreso_id"]);
			}
			$ingreso_fluidos = $tmp;
			
			foreach ($fluidos as $fluido) {
				if(!isset($ingreso_fluidos[$fluido["Fluido"]["id"]])) {
					$ingreso_fluidos[$fluido["Fluido"]["id"]] = array(0,"");
				}
				if(isset($fluido["Fluido"]["tipo_ingreso"]) && $fluido["Fluido"]["tipo_ingreso"] != null && count(explode(",", $fluido["Fluido"]["tipo_ingreso"])) > 1) {
					$tipos_ingreso = $this->FluidoTipoIngreso->find('list', array('conditions'=>array('id IN' => explode(",", $fluido["Fluido"]["tipo_ingreso"])),'order' => 'nombre'));
				} elseif(isset($fluido["Fluido"]["tipo_ingreso"]) && $fluido["Fluido"]["tipo_ingreso"] != null && count(explode(",", $fluido["Fluido"]["tipo_ingreso"])) == 1) {
					$tipos_ingreso = $this->FluidoTipoIngreso->find('list', array('conditions'=>array('id' => $fluido["Fluido"]["tipo_ingreso"]),'order' => 'nombre'));
				} else {
					$tipos_ingreso = array();
				}
				$tmp[$fluido["Fluido"]["id"]] = array($fluido["Fluido"]["nombre"],$fluido["FluidoUnidad"]["nombre"],$tipos_ingreso,$ingreso_fluidos[$fluido["Fluido"]["id"]][0],$ingreso_fluidos[$fluido["Fluido"]["id"]][1]);
			}
			$fluidos = $tmp;
			
			$tipos_tecnicos = $this->TipoTecnico->find('all', array('order' => array("TipoTecnico.nombre" => "ASC"), 'recursive' => -1));
			$this->set(compact('tipos_tecnicos'));
			
			$tipos_registros = $this->TipoElemento->find('all', array('order' => array("TipoElemento.nombre" => "ASC"), 'recursive' => -1));
			$this->set(compact('tipos_registros'));
			
			$soluciones = $this->Solucion->find('all', array('order' => array("Solucion.nombre" => "ASC"), 'recursive' => -1));
			$this->set(compact('soluciones'));

			if(is_numeric($planificacion['Planificacion']['motivo_llamado_id'])){
				$this->set('motivo_id', $planificacion['Planificacion']['motivo_llamado_id']);
			}
			
			if(is_numeric($planificacion['Planificacion']['sintoma_id'])){
				$this->set('sintoma_id', $planificacion['Planificacion']['sintoma_id']);
			}
			
			if(is_numeric($planificacion['Planificacion']['categoria_sintoma_id'])){
				$this->set('categoria_id', $planificacion['Planificacion']['categoria_sintoma_id']);
			}
			
			$clientIp = $this->request->clientIp();
			$this->set(compact('clientIp'));
			
			$this->set(compact('ingreso_fluidos'));
			$this->set(compact('motivos'));
			$this->set(compact('fluidos'));
			
			$turnos = $this->Turno->find('list', array('order' => 'nombre'));
			$this->set(compact('turnos'));
			
			$periodos = $this->Periodo->find('list', array('order' => 'nombre'));
			$this->set(compact('periodos'));
			
			$lugares_reparacion = $this->LugarReparacion->find('list', array('order' => 'nombre'));
			$this->set(compact('lugares_reparacion'));
			
			//print_r($deltas);
			$this->set('deltas', $deltas);
			$this->set('despliegue', $despliegue);
			
			$deltas_ = $this->DeltaItem->find('all', array('order' => array('grupo' => 'asc','nombre' => 'asc'), 'recursive' => -1));
			$this->set('deltas_', $deltas_);
		} catch(Exception $e){
			$this->logger($this, $e->getMessage());
		}
	}
			
	public function historial() {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Planificacion');
		$this->loadModel('Unidad');
		$this->loadModel('Faena');
		$this->loadModel('PermisoUsuario');
		$this->loadModel('Flota');
		$this->loadModel('FaenaFlota');
                $this->loadModel('Replanificacion');
                $this->loadModel('Motivo_replanificacion');
		$intervenciones = array();
		$usuario = $this->Session->read('Usuario');
		$faena_id = $this->Session->read('faena_id');
                $cargos = $this->Session->read('PermisosCargos');
                $cargo_id = $cargos[$faena_id][0];
		$fecha_inicio = date('Y-m-d', time() - 365*24*60*60);
		$fecha_termino = date('Y-m-d');
		$query = "";
		$estado = "0";
                $edt = "0";
		$tipo_intervencion = "";
		$flota_id = "";
		$unidad_id = "";
		$tipo_evento = "";
		$codigo = "";
		$correlativo="";
		$limit = 25;
		$aprobador_id="";
		$supervisor_responsable = "";
		$url = "1";
		$conditions = array();
		if (isset($this->request->query) && count($this->request->query) > 0) {
			if (isset($this->request->query['correlativo']) && $this->request->query['correlativo'] != "") {
				$query = "CAST(Planificacion.correlativo_final AS TEXT) LIKE '" . intval($this->request->query['correlativo']) . "%' AND "; 
				$correlativo = $this->request->query['correlativo'];
				$url .= "&correlativo=$correlativo";
			}
			if (isset($this->request->query['codigo']) && $this->request->query['codigo'] != "" && $this->request->query['codigo'] != "undefined") {
				$query .= "CAST(Planificacion.id AS TEXT) LIKE '" . intval($this->request->query['codigo']) . "%' AND ";
				$codigo = $this->request->query['codigo'];
				$url .= "&codigo=$codigo";
			}
		}
		
		if ($this->request->is('get')){
			if(isset($this->request->query['limit']) && is_numeric($this->request->query['limit'])) {
				$limit = $this->request->query['limit'];
			}
                        
                        if(isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
				$conditions["Planificacion.faena_id"] = $this->request->query['faena_id'];
			} else {
				$conditions["Planificacion.faena_id IN"] = $this->Session->read("FaenasFiltro");
			}
			if(isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
				$flota_id = explode("_", $this->request->query['flota_id']);
				$flota_id = $flota_id[1];
				$conditions["Planificacion.flota_id"] = $flota_id;
			}
			if(isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
				$unidad_id = explode("_", $this->request->query['unidad_id']);
				$unidad_id = $unidad_id[2];
				$conditions["Planificacion.unidad_id"] = $unidad_id;
			}
			if(isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
				$resultado = $this->Planificacion->find('first', array('conditions'=>array("id" => $this->request->query['folio']), 'recursive' => -1));
				if (isset($resultado["Planificacion"])) {
					$conditions["Planificacion.correlativo_final"] = $resultado["Planificacion"]["correlativo_final"];
				} else {
					$conditions["Planificacion.correlativo_final"] = "-1";
				}
				//$conditions["Planificacion.id"] = $this->request->query['folio'];
				$folio = $this->request->query['folio'];
			}
			if(isset($this->request->query['correlativo']) && is_numeric($this->request->query['correlativo'])) {
				$conditions["Planificacion.correlativo_final"] = $this->request->query['correlativo'];
				$correlativo = $this->request->query['correlativo'];
			}
			if(isset($this->request->query['estado']) && is_numeric($this->request->query['estado'])) {
				$conditions["Planificacion.estado"] = $this->request->query['estado'];
				$estado = $this->request->query['estado'];
			}
                        
                        
			if(isset($this->request->query['supervisor_responsable']) && $this->request->query['supervisor_responsable'] != '') {
				$supervisor_responsable = explode("_", $this->request->query['supervisor_responsable']);
				$supervisor_responsable = $supervisor_responsable[1];
				$conditions["Planificacion.supervisor_responsable"] = $supervisor_responsable;
			}
			if(isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] == '') {
				if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] != '') {
					$conditions["Planificacion.tipointervencion"] = $this->request->query['tipointervencion'];
				}
			}
			if(isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] == 'PR') {
				if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] == '') {
					$conditions["UPPER(Planificacion.tipointervencion)"] = ['MP', 'RP', 'OP', 'BL'];
					//$conditions["Planificacion.backlog_id IS NOT"] = NULL;
				}
				if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] != '') {
					$conditions["Planificacion.tipointervencion"] =  $this->request->query['tipointervencion'];
					if($this->request->query['tipointervencion'] == 'OP' || $this->request->query['tipointervencion'] == 'BL') {
						//$conditions["Planificacion.backlog_id IS NOT"] = NULL;
					}
				}
			}
			if(isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] == 'NP') {
				if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] == '') {
					$conditions["UPPER(Planificacion.tipointervencion)"] = ['EX', 'RI', 'OP', 'BL'];
					$conditions["Planificacion.backlog_id IS"] = NULL;
				}
				if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] != '') {
					$conditions["Planificacion.tipointervencion"] =  $this->request->query['tipointervencion'];
					if($this->request->query['tipointervencion'] == 'OP' || $this->request->query['tipointervencion'] == 'BL') {
						$conditions["Planificacion.backlog_id IS"] = NULL;
					}
				}
			}
			if(isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != '') {
				$fecha_inicio = $this->request->query['fecha_inicio'];
			}
			if(isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != '') {
				$fecha_termino = $this->request->query['fecha_termino'];
			}
			foreach($this->request->query as $key => $value) {
				$this->set($key, $value);
			}
		}
		
		$conditions["Planificacion.fecha BETWEEN ? AND ?"] = [$fecha_inicio . ' 00:00:00', $fecha_termino . ' 23:59:59'];
		
                if(isset($this->request->query['edt'])) {
                    $edt = $this->request->query['edt'];
                    $edt = substr($edt, 0, 1);
                }
                                
		if ($faena_id != 0) {
			$faena = "faena_id = $faena_id";
		} else {
			$faena = 'faena_id <> 0';
		}
		
		if (isset($this->request->query["order"])) {
			$order2 = split(":", $this->request->query["order"]);
			$order = "Planificacion.".$order2[0] . " " . $order2[1];
			
			if ($order2[0] == "tipointervencion") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Planificacion.tipointervencion' => $order2[1],'Planificacion.padre' => 'asc');
			}
			if ($order2[0] == "unidad_id") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Unidad.unidad' => $order2[1]);
			}
			
			if ($order2[0] == "flota_id") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Flota.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "fecha") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = "Planificacion.fecha {$order2[1]}, Planificacion.hora {$order2[1]}";	
			}
			
			if ($order2[0] == "esn") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Planificacion.esn' => $order2[1]);
			}
			
			if ($order2[0] == "faena") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Faena.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "estado") {
				$order = array('Estado.nombre' => $order2[1]);
			}
			
			if ($order2[0] == "duracion") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = "(DATE_PART('day', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) * 24 * 60 + ";
				$order .= "(DATE_PART('hour', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) * 60 + ";
				$order .= "(DATE_PART('minute', (Planificacion.fecha_termino ||' ' ||Planificacion.hora_termino)::timestamp - (Planificacion.fecha ||' ' ||Planificacion.hora)::timestamp)) {$order2[1]}";
			}
			
			if ($order2[0] == "supervisor") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = array('Supervisor.nombres' => $order2[1], 'Supervisor.apellidos' => $order2[1]);
			}
			
			if ($order2[0] == "correlativo") {
				//$order = "Planificacion.".$order[0] . " " . $order[1];
				//$order .= ", Planificacion.padre NULLS FIRST";
				$order = "Planificacion.correlativo {$order2[1]}, Planificacion.id {$order2[1]}";	
			}
		} else {
			$order = "Planificacion.fecha ASC, Planificacion.hora ASC";	
		}
		
                
                if(isset($this->request->query["btn-descargar"])){
                    $this->redirect("/Trabajo/descargaReplanificados?faena_id=".$this->request->query['faena_id']."&flota_id=".$this->request->query['flota_id']."&unidad_id=".$this->request->query['unidad_id'].
                                                                    "&limit=".$this->request->query['limit']."&tipo_evento=".$this->request->query['tipo_evento']."&tipointervencion=".$this->request->query['tipointervencion'].
                                                                    "&supervisor_responsable=".$this->request->query['supervisor_responsable']."&estado=".$this->request->query['estado']."&correlativo=".$this->request->query['correlativo'].
                                                                    "&folio=".$this->request->query['folio']."&fecha_inicio=".$this->request->query['fecha_inicio']."&fecha_termino=".$this->request->query['fecha_termino']);
                }
                
                
                    
                               
                $this->paginate = array(
                        'limit' => $limit,
                        'fields' => array('Planificacion.*', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre', 'Estado.nombre', 'count(rep.id) replan', 'est_mot.esn_placa'),
                        'joins' => array(
                                        array(
                                            'table' => 'replanificacion',
                                            'alias' => 'rep',
                                            'type' => 'LEFT',
                                            'conditions' => array(
                                                'rep.id_intervencion = "Planificacion".id'
                                            )
                                        ),
                                        array(
                                            'table' => 'estado_motor',
                                            'alias' => 'est_mot',
                                            'type' => 'INNER',
                                            'conditions' => array(
                                                'est_mot.unidad_id = "Planificacion".unidad_id',
                                                'est_mot.fecha_ps = (SELECT max(fecha_ps) 
                                                                    FROM estado_motor WHERE unidad_id = "Planificacion".unidad_id AND fecha_ps <= "Planificacion".fecha)'
                                            )
                                        )
                                    ),
                        'conditions' => $conditions,
                        'order' => $order, 
                        'group' => array('Planificacion.id', 'Unidad.unidad', 'Flota.nombre', 'Sintoma.nombre', 'Faena.nombre', 'Estado.nombre', 'est_mot.esn_placa'),
                        'recursive' => 1
                );
                
                
			
		$intervenciones = $this->paginate('Planificacion');
		$this->loadModel('UnidadDetalle');
		$this->loadModel('UsuarioFaena');
		
		
		$this->set('unidad_id', $unidad_id);
		$this->set('flota_id', $flota_id);
		
		$this->set('estado', $estado);
                $this->set('edt', $edt);
                $this->set("cargo", $cargo_id);
                
		$this->set('url', $url);
		
		$this->set('fecha_inicio', $fecha_inicio);
		$this->set('fecha_termino', $fecha_termino);
		$this->set('tipo_evento', $tipo_evento);
		$this->set('codigo', $codigo);
		
		$this->set('registros', $intervenciones);
		$this->set('correlativo', $correlativo);
		$this->set('aprobador_id', $aprobador_id);
		$this->set('supervisor_responsable', $supervisor_responsable);		
		$this->set('limit', $limit);
		$this->set('titulo', 'Registro de Intervenciones');
		
		$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
		$this->set(compact('faenas'));
		
		$flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id","faena_id","Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
		$this->set(compact('flotas'));
		
		$supervisores = $this->PermisoUsuario->find('all', array('fields' => array("DISTINCT PermisoUsuario.usuario_id","PermisoUsuario.faena_id","Usuario.nombres","Usuario.apellidos"), 'conditions' => array('PermisoUsuario.faena_id IN' => $this->Session->read("FaenasFiltro"), 'PermisoUsuario.cargo_id' => 2), 'order' => array("PermisoUsuario.faena_id","Usuario.nombres","Usuario.apellidos"), 'recursive' => 1));
		$this->set(compact('supervisores'));
		
		$unidades = $this->Unidad->find('all', array('fields' => array("id","flota_id","faena_id","Unidad.unidad"), 'conditions' => array('Unidad.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Unidad.e' => '1'), 'order' => array("Unidad.unidad"),'recursive' => -1));
		$this->set(compact('unidades'));
                
                $motivos = $this->Motivo_replanificacion->find('all',array('conditions' => array('Motivo_replanificacion.e' => '1'), 'order' => array("Motivo_replanificacion.nombre")));
                $this->set(compact('motivos'));
	}
	
	public function planificar($tipo = "", $id = "") {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Backlog');
		$this->loadModel('Planificacion');
		$this->loadModel('Unidad');
		$this->loadModel('Faena');
		$this->loadModel('PermisoUsuario');
		$this->loadModel('Flota');
		$this->loadModel('FaenaFlota');
		$this->loadModel('MotivoCategoriaSintoma');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('Sintoma');
                
                $this->loadModel('Prebacklog');
                $this->loadModel('Prebacklog_comentario');
		if ($this->request->is('post') && isset($this->request->data)) {
			$data = $this->request->data;
			$tmp = explode("_", $this->request->data["unidad_id"]);
			$data["flota_id"] = $tmp[1];
			$data["unidad_id"] = $tmp[2];
			if (is_numeric($data["tiempo_estimado_hora"])){
				$data["tiempo_estimado_hora"] = intval($data["tiempo_estimado_hora"]);
			} else {
				$data["tiempo_estimado_hora"] = "0";
			}
			$data["tiempo_trabajo"] = str_pad($data["tiempo_estimado_hora"],2,"0",STR_PAD_LEFT).":".$data["tiempo_estimado_minuto"];
			$data["hora"] = $data["hora"].":".$data["minuto"].":00 " . $data["periodo"];
			$data["estado"] = "2";
			$data["fecha_planificacion"] = $data["fecha"];
			$data["hora_planificacion"] = $data["hora"];
			$data["usuario_id"] = $this->getUsuarioID();
			$data["fecha_termino"] = $data["fecha"];
			$data["hora_termino"] = $data["hora"];
                        
			$backlog_id = "";
                        if(isset($data["backlog_id"]) && count($data["backlog_id"]) > 0) {
                                $backlog_id = $data["backlog_id"][0];
                                $data["backlog_id"] = $data["backlog_id"][0];
                        }
                        
			$tipomantencion = "";
			if(isset($data["tipomantencion"]) && is_numeric($data["tipomantencion"])) {
				$tipomantencion = $data["tipomantencion"];
			}
			$sintoma_id = "";
			if(isset($data["sintoma_id"]) && is_numeric($data["sintoma_id"])) {
				$sintoma_id = $data["sintoma_id"];
			}
			$data["tiempo_estimado"] = $data["tiempo_trabajo"];
			$fecha_folio = date("YmdHi",strtotime($data["fecha"].' '.$data["hora"]));
			$folio = $data["tipointervencion"].$tipomantencion.$data["faena_id"].$data["flota_id"].$data["unidad_id"].$data["usuario_id"].$sintoma_id.$backlog_id.$fecha_folio;
			$data["folio"] = $folio;
			$data["correlativo"] = $folio;
			unset($data["periodo"]);
			
			try {
				if (isset($data["categoria_sintoma_id"]) && isset($data["sintoma_id"])) {
					$motivo_categoria_sintoma = $this->MotivoCategoriaSintoma->find('first', array('fields' => array("motivo_id"), 'conditions' => array('categoria_id' => $data["categoria_sintoma_id"], 'sintoma_id' => $data["sintoma_id"]), 'recursive' => -1));
					if (isset($motivo_categoria_sintoma["MotivoCategoriaSintoma"]["motivo_id"])){
						$data["motivo_llamado_id"] = $motivo_categoria_sintoma["MotivoCategoriaSintoma"]["motivo_id"];
					} else {
						$data["motivo_llamado_id"] = "1";
					}
				}
			} catch (Exception $e) { 
				$this->logger($this, $e->getMessage());
			}
			
                        /** Prueba sintoma **/
                        $data['json'] = '{ "SintomaID" : "'. $sintoma_id .'"}';
                        
                        
			try {
                            
                            
                            $existe = $this->Planificacion->query("select count(*) a from planificacion Where folio = '". $folio ."'");

                                                            
                            if($existe[0][0]['a'] == 0){
                            
				$this->Planificacion->create();
				$this->Planificacion->save($data);
				$planificacion_id = $this->Planificacion->id;
				
                                if(count($this->request->data('backlog_id')) >= 1){
                                    foreach($this->request->data('backlog_id') as $value) {
                                        if(is_numeric($value)) {
                                                $data_backlog = array();
                                                $data_backlog["id"] = $value;
                                                $data_backlog["realizado"] = "S";
                                                $data_backlog["estado_id"] = "2";
                                                $data_backlog["intervencion_id"] = $planificacion_id;
                                                
                                                $b = $this->Backlog->find('first', array("conditions" => array("id" => $value), "fields" => "Backlog.*", 'recursive' => -1));
                                                
                                                foreach($b as $blg){
                                                    if(isset($blg['Backlog']['prebacklog_id'])){
                                                    
                                                        //Cambia estado de prebacklog a planificado
                                                        $this->Prebacklog->updateAll(
                                                                array('Prebacklog.estado_id' => 2),
                                                                array('Prebacklog.id' => $blg['Backlog']['prebacklog_id']));

                                                        //inserta un comentario con la creacion del backlog
                                                        $comen['comentario'] = 'Prebacklog PLanificado intervencion N-'.$planificacion_id;
                                                        $comen['usuario_id'] = $this->getUsuarioID();
                                                        $comen['prebacklog_id'] = $blg['Backlog']['prebacklog_id'];
                                                        $comen['fecha'] = date("Y-m-d H:i:s");

                                                        $this->Prebacklog_comentario->create();
                                                        $this->Prebacklog_comentario->save($comen);
                                                    }
                                                }
                                                
                                                
                                                $this->Backlog->save($data_backlog);                                                
                                                
                                        }
                                    }
                                }
                                
				
				
				$data = array();
				$data["id"] = $planificacion_id;
				$data["correlativo_final"] = $planificacion_id;
				$this->Planificacion->save($data);
				$this->Session->setFlash('Trabajo planificado con éxito.' , 'guardar_exito');
                            }else{
                                $this->Session->setFlash('La planificacion ya existe, por favor verifica los datos en el historial.'. $existe[0][0]['a'], 'guardar_error'); 
                            }
			} catch (Exception $e) { 
				$this->Session->setFlash('Ocurrió un error al intentar planificar el trabajo, por favor intente nuevamente.'.$existe[0][0]['a'], 'guardar_error');
				$this->logger($this, $e->getMessage());
			}
		
			$this->redirect('/Trabajo/Planificar');
		}
		
		if($tipo != '' && $id != '' && is_numeric($id)) {
			if(strtolower($tipo) == "backlog") {
				$backlog_id = intval($id);
				$registro = $this->Backlog->find('first', array(
					'conditions' => array("Backlog.id" => $id),
					'recursive' => -1
				));
				if(isset($registro["Backlog"]) && isset($registro["Backlog"]["id"])) {
					$faena_id = $registro["Backlog"]["faena_id"];
					$this->set(compact('faena_id'));
					$flota_id = $registro["Backlog"]["flota_id"];
					$this->set(compact('flota_id'));
					$unidad_id = $registro["Backlog"]["equipo_id"];
					$this->set(compact('unidad_id'));
					$this->set('backlog_id',$registro["Backlog"]["id"]);
				}
				$this->set('planificacion','backlog');
			}
		}
		
		$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("FaenasFiltro"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
		$this->set(compact('faenas')); 
		
		$flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id","faena_id","Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("FaenasFiltro"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
		$this->set(compact('flotas'));
		
		$unidades = $this->Unidad->find('all', array('fields' => array("id","flota_id","faena_id","Unidad.unidad", "Unidad.motor_id"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("FaenasFiltro"), "Unidad.e" => '1'), 'order' => array("Unidad.unidad"),'recursive' => -1));
		$this->set(compact('unidades'));
		
		$categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));		
		$this->set(compact('categoria_sintoma'));
		
		$sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id","Sintoma.id","Sintoma.nombre","Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
		$this->set(compact('sintomas'));
	}
        
    public function planifica_masivo($tipo = "", $id = "") {
		$this->layout = 'metronic_principal';
		$this->check_permissions($this);
		$this->loadModel('Backlog');
		$this->loadModel('Planificacion');
		$this->loadModel('Unidad');
		$this->loadModel('Faena');
		$this->loadModel('PermisoUsuario');
		$this->loadModel('Flota');
		$this->loadModel('FaenaFlota');
		$this->loadModel('MotivoCategoriaSintoma');
		$this->loadModel('SintomaCategoria');
		$this->loadModel('Sintoma');                
		$this->loadModel('Prebacklog');
		$this->loadModel('Prebacklog_comentario');

		$registros = "";
		$registros_error = "";
		
		$faenas = $this->Faena->find('list', array('conditions' => array('Faena.id IN' => $this->Session->read("PermisosFaenas"), 'Faena.e' => '1'), 'order' => array("Faena.nombre"), 'recursive' => -1));
		$this->set(compact('faenas')); 
                               
		$flotas = $this->FaenaFlota->find('all', array('fields' => array("flota_id","faena_id","Flota.nombre"), 'conditions' => array('FaenaFlota.faena_id IN' => $this->Session->read("PermisosFaenas"), 'Flota.nombre NOT' => null, 'FaenaFlota.e' => '1'), 'order' => array("Flota.nombre"), 'recursive' => 1));
		$this->set(compact('flotas'));
		
		$unidades = $this->Unidad->find('all', array('fields' => array("id","flota_id","faena_id","Unidad.unidad", "Unidad.motor_id"), 'conditions' => array('Unidad.faena_id' => $this->Session->read("PermisosFaenas"), "Unidad.e" => '1'), 'order' => array("Unidad.unidad"),'recursive' => -1));
		$this->set(compact('unidades'));
		
		$categoria_sintoma = $this->SintomaCategoria->find('list', array('order' => array("SintomaCategoria.nombre"), 'recursive' => -1));		
		$this->set(compact('categoria_sintoma'));
		
		$sintomas = $this->Sintoma->find('all', array('fields' => array("Sintoma.sintoma_categoria_id","Sintoma.id","Sintoma.nombre","Sintoma.codigo"), 'conditions' => array('Sintoma.e' => '1'), 'order' => array("Sintoma.nombre"), 'recursive' => -1));
		$this->set(compact('sintomas'));
                
		if ($this->request->is('post') && isset($this->request->data)) {
                    $data = $this->request->data;
                    
                    foreach($this->request->data["faena_id_"] as $key => $value) {
                        $data_plan = array();
                        $backlog_id = array();
                        
                        $tmp = explode("_", $this->request->data["equipo_id_"][$key]);
                        $data_plan["faena_id"] = $tmp[0];
                        $data_plan["flota_id"] = $tmp[1];
                        $data_plan["unidad_id"] = $tmp[2];
                        $data_plan['esn'] = $this->request->data["esn_"][$key];

                        $tipointervencion = $this->request->data["tipo_int_"][$key];
                        $data_plan["tipointervencion"] = ($tipointervencion == "" ? "NULL" : $tipointervencion);

						if($tipointervencion != 'MP'){
                            $sintoma_id = $this->request->data["sintoma_id_"][$key];
                            $data_plan["sintoma_id"] = $sintoma_id == "" ? "NULL" : $sintoma_id;

                            if ($this->request->data["backlog_id_"][$key] != ""){
                                $backlog_id = explode("," ,$this->request->data["backlog_id_"][$key]);
                                $data_plan["backlog_id"] = $backlog_id[0];
                            }else{
                                $backlog_id = "";
                                $data_plan["backlog_id"] = "";
                            }
                            
                        }else{
                            $sintoma_id = "";
                            $data_plan["sintoma_id"] = NULL;

                            $backlog_id = array();
                            $data_plan["backlog_id"] = "";
                        }

                        
						$data_plan["os_sap"] = $this->request->data["os_sap_"][$key];
                        $data_plan["observacion"] = $this->request->data["obs_"][$key];
                        $data_plan["usuario_id"] = $this->getUsuarioID();
                        $data_plan["fecha"] = $this->request->data["fec_prog_"][$key];
                        $data_plan["hora"] = $this->request->data["hor_prog_"][$key];
                        $data_plan["estado"] = "2";
                        $data_plan["fecha_termino"] = $data_plan["fecha"];
                        $data_plan["hora_termino"] = $data_plan["hora"];
                        $data_plan["cliente"] = "1";
                        $tipomantencion = $this->request->data["tipo_mant_"][$key];
                        $data_plan["tipomantencion"] = ($tipomantencion == "" ? "NULL" : $tipomantencion);
                        $data_plan["tiempo_trabajo"] = $this->request->data["t_estimado_"][$key];
                        $data_plan["tiempo_estimado"] = $data_plan["tiempo_trabajo"];
                        $data_plan["fecha_planificacion"] = $data_plan["fecha"];
                        $data_plan["hora_planificacion"] = $data_plan["hora"];

                        $fecha_folio = date("YmdHi",strtotime($data_plan["fecha"].' '.$data_plan["hora"]));
                        $folio = $tipointervencion.$tipomantencion.$data_plan["faena_id"].$data_plan["flota_id"].$data_plan["unidad_id"].$data_plan["usuario_id"].$sintoma_id.$data_plan["backlog_id"].$fecha_folio;
                        $data_plan["folio"] = $folio;
                        $data_plan["correlativo"] = $folio;
                        unset($data_plan["periodo"]);
                        

                        $cat_sintoma = "";

                        try {
                            if (isset($this->request->data["cat_sintoma_"][$key]) && isset($this->request->data["sintoma_id_"][$key]) &&
                                    $this->request->data["cat_sintoma_"][$key] != "" && $this->request->data["sintoma_id_"][$key] != "") {

                                $cat_sintoma = $this->request->data["cat_sintoma_"][$key];
                                $sint = $this->request->data["sintoma_id_"][$key];

                                $motivo_categoria_sintoma = $this->MotivoCategoriaSintoma->find('first', 
                                        array('fields' => array("motivo_id"), 
                                            'conditions' => array('categoria_id' => $cat_sintoma, 'sintoma_id' => $sint), 'recursive' => -1));

                                if (isset($motivo_categoria_sintoma["MotivoCategoriaSintoma"]["motivo_id"])){
                                        $data_plan["motivo_llamado_id"] = $motivo_categoria_sintoma["MotivoCategoriaSintoma"]["motivo_id"];
                                } else {
                                        $data_plan["motivo_llamado_id"] = "1";
                                }
                            }
                        } catch (Exception $e) { 
                                $this->logger($this, $e->getMessage());
                        }

                        /** Prueba sintoma **/
                        $data_plan['json'] = '{ "SintomaID" : "'. $sintoma_id .'", "categoria_sintoma" : "'. $cat_sintoma .'"}';

                        try {
                            $existe = $this->Planificacion->query("select count(*) a from planificacion Where folio = '". $folio ."'");

                            if($existe[0][0]['a'] == 0){

                                $this->Planificacion->create();
                                $this->Planificacion->save($data_plan);
                                $planificacion_id = $this->Planificacion->id;
                                
                                if(count($backlog_id) >= 1){
                                    foreach($backlog_id as $value) {
                                        if(is_numeric($value)) {
                                            $data_backlog = array();
                                            $data_backlog["id"] = $value;
                                            $data_backlog["realizado"] = "S";
                                            $data_backlog["estado_id"] = "2";
                                            $data_backlog["intervencion_id"] = $planificacion_id;

                                            $b = $this->Backlog->find('first', array("conditions" => array("id" => $value), "fields" => "Backlog.*", 'recursive' => -1));

                                            if(isset($b['Backlog']['prebacklog_id'])){

                                                //Cambia estado de prebacklog a planificado
                                                $this->Prebacklog->updateAll(
                                                        array('Prebacklog.estado_id' => 2),
                                                        array('Prebacklog.id' => $b['Backlog']['prebacklog_id']));

                                                //inserta un comentario con la creacion del backlog
                                                $comen['comentario'] = 'Prebacklog planificado, correlativo intervencion N°'.$planificacion_id;
                                                $comen['usuario_id'] = $this->getUsuarioID();
                                                $comen['prebacklog_id'] = $b['Backlog']['prebacklog_id'];
                                                $comen['fecha'] = date("Y-m-d H:i:s");

                                                $this->Prebacklog_comentario->create();
                                                $this->Prebacklog_comentario->save($comen);
                                            }

                                            $this->Backlog->save($data_backlog);                                                

                                        }
                                    }
                                }
                                $data = array();
                                $data["id"] = $planificacion_id;
                                $data["correlativo_final"] = $planificacion_id;
                                $this->Planificacion->save($data);
                                
                                $registros .= "F-".$planificacion_id.", ";
                                
                                
                                
                            }else{
                                $registros_error .= $this->request->data["faena_str_"][$key] . " - ". $this->request->data["flota_str_"][$key] . " - " . $this->request->data["equipo_str_"][$key] . " - ". $folio . " </br>";
                                
                                
                            }
                        } catch (Exception $e) { 
                            $registros_error .= $this->request->data["faena_str_"][$key] . " - ". $this->request->data["flota_str_"][$key] . " - " . $this->request->data["equipo_str_"][$key]. " - ". $folio . $e->getMessage() . " </br>";
                            
                            $this->Session->setFlash('Ocurrió un error al intentar planificar el trabajo, por favor intente nuevamente.'.$registros_error , 'guardar_error');
                            $this->logger($this, $e->getMessage());
                        }
                    }  
                    
		}
                
                if(strlen($registros) >= 3){
                    $this->set('exitos',$registros);
                }else{
                    $this->set('exitos','');
                }
                if(strlen($registros_error) >= 5){
                    $this->set('errores',$registros_error);
                }else{
                    $this->set('errores','');
                } 
                
		
	}
        
    public function getmp($faena_id, $flota_id, $unidad_id) {
		$this->layout = null;
		$this->loadModel('Planificacion');
		$inter = $this->Planificacion->find('all', array(
					'conditions' => array('Planificacion.faena_id' => $faena_id,'Planificacion.flota_id' => $flota_id,
											'Planificacion.unidad_id' => $unidad_id , 'Planificacion.tipointervencion' => 'MP', 
											'Planificacion.correlativo_final = Planificacion.id'),
					'order' => 'Planificacion.fecha DESC',
					'limit' => 15,
					'recursive' => -1));

		$this->set('intervencion', $inter);
	}
        
	public function replanificar($id_intervencion){
		$util = new UtilidadesController();
		$intervencion_anterior = array();
		$this->loadModel('Replanificacion');
		$this->loadModel('Planificacion');
		

		
			try {
				if (isset($this->request->data)) {
					$data = $this->request->data;
					$newdate = strtotime("-5 year",  date("Y-m-d",$data['fecha_new'.$id_intervencion]));
					//print_r($data);
					$replanifica["id_intervencion"] = $data['id_intervencion'.$id_intervencion];
					$replanifica["fecha_anterior"] = $data['fecha_a'.$id_intervencion];
					$replanifica["hora_anterior"] = $data['hora_a'.$id_intervencion];
					$replanifica["tiempo_estimado_anterior"] = $data['tiempo_estimado_a'.$id_intervencion];
					$replanifica["fecha_replanificacion"] = date('Y-m-d H:i:s', time());;
					$replanifica["motivo_id"] = $data['motivo_new'.$id_intervencion];
					$replanifica["comentario"] = $data['observacion_new'.$id_intervencion];
					$replanifica["usuario_id"] = $this->Session->read('usuario_id');

					$this->Replanificacion->create();
					$this->Replanificacion->save($replanifica);

					//$planificacion["id"] = $data['id_intervencion'];

					//$this->Planificacion->updateAll(
					// 			array('Planificacion.fecha' => "'".$data['fecha_new'.$id_intervencion]."'",
					//                        'Planificacion.hora' => "'".$data['hora_new'.$id_intervencion].":".$data['minuto_new'.$id_intervencion].":00 ".$data['periodo_new'.$id_intervencion]."'",
					//                        'Planificacion.fecha_termino' => "'".$data['fecha_new'.$id_intervencion]."'",
					//                        'Planificacion.hora_termino' => "'".$data['hora_new'.$id_intervencion].":".$data['minuto_new'.$id_intervencion].":00 ".$data['periodo_new'.$id_intervencion]."'",
					//                        'Planificacion.tiempo_estimado' => str_pad($data['tiempo_estimado_hora_new'.$id_intervencion],2,"0",STR_PAD_LEFT).":".$data['tiempo_estimado_minuto_new'.$id_intervencion]),
					//			array('Planificacion.id' => $id_intervencion));

					$query = "UPDATE Planificacion SET 
							fecha = '".$data['fecha_new'.$id_intervencion]."',
							hora = '".$data['hora_new'.$id_intervencion].":".$data['minuto_new'.$id_intervencion].":00 ".$data['periodo_new'.$id_intervencion]."',
							fecha_termino = '".$data['fecha_new'.$id_intervencion]."',
							hora_termino = '".$data['hora_new'.$id_intervencion].":".$data['minuto_new'.$id_intervencion].":00 ".$data['periodo_new'.$id_intervencion]."',
							tiempo_estimado = '".str_pad($data['tiempo_estimado_hora_new'.$id_intervencion],2,"0",STR_PAD_LEFT).":".$data['tiempo_estimado_minuto_new'.$id_intervencion]. "',
							updated = '". date("Y-m-d H:i:s") ."'
							Where id = ".$id_intervencion;
					//print_r($query);

					$this->Planificacion->query($query);



					$this->Session->setFlash('Intervención replanificada con éxito.', 'guardar_exito');

					$this->redirect(array(
							'controller' => 'Trabajo',
							'action' => 'Historial',
							'?' => array(
									'folio' => $id_intervencion,
									'fecha_termino' => $data['fecha_new'.$id_intervencion],
									'fecha_inicio' => date("Y-m-d", $newdate)
							)
					));
				}else{
					
					$newdate = strtotime("-5 year",  date("Y-m-d", date()));
					
					$this->Session->setFlash('Ocurrió un error al intentar replanificar la bitácora, por favor intente nuevamente. '.print_r($data) , 'guardar_error');
					$this->redirect(array(
							'controller' => 'Trabajo',
							'action' => 'Historial',
							'?' => array(
									'folio' => $id_intervencion,
									'fecha_termino' => $data['fecha_new'.$id_intervencion],
									'fecha_inicio' => date("Y-m-d", $newdate)
							)
					));
				}
				
			} catch (Exception $e) {
				$this->Session->setFlash('Ocurrió un error al intentar replanificar la bitácora, por favor intente nuevamente.' , 'guardar_error');
				$this->logger($this, $e->getMessage());
				
				$this->redirect(array(
						'controller' => 'Trabajo',
						'action' => 'Historial',
						'?' => array(
								'folio' => $id_intervencion,
								'fecha_termino' => $data['fecha_new'.$id_intervencion],
								'fecha_inicio' => date("Y-m-d", $newdate)
						)
				));
			}
		
	}
        
        
	public function descargaReplanificados(){
		$this->loadModel('Planificacion');
		$condition = " ";
		$order = array();
		if ($this->request->is('get')){
			if(isset($this->request->query['faena_id']) && is_numeric($this->request->query['faena_id'])) {
					$faena_id = $this->request->query['faena_id'];
			} else {
					$faena_id = str_replace("[","", json_encode($this->Session->read("FaenasFiltro")));
					$faena_id = str_replace("]","", $faena_id);
					//print_r(json_encode($this->Session->read("FaenasFiltro")));
			}
			
			$condition .= " AND planificacion.faena_id IN (".$faena_id.") ";
			
			if(isset($this->request->query['flota_id']) && $this->request->query['flota_id'] != '') {
					$flota_id = explode("_", $this->request->query['flota_id']);
					$flota_id = $flota_id[1];
					
					$condition .= " AND planificacion.flota_id = ".$flota_id;
			}
			if(isset($this->request->query['unidad_id']) && $this->request->query['unidad_id'] != '') {
					$unidad_id = explode("_", $this->request->query['unidad_id']);
					$unidad_id = $unidad_id[2];
					$condition .= " AND planificacion.unidad_id = ".$unidad_id;
			}
			if(isset($this->request->query['folio']) && is_numeric($this->request->query['folio'])) {
					$folio = $this->request->query['folio'];
					$condition .= " AND planificacion.id = ".$folio;
			}
			
			if(isset($this->request->query['correlativo']) && is_numeric($this->request->query['correlativo'])){
				$condition .= " AND planificacion.correlativo_final = ".$this->request->query['correlativo'];
			}
			
			if(isset($this->request->query['estado']) && is_numeric($this->request->query['estado'])) {
					//$conditions["Planificacion.estado"] = $this->request->query['estado'];
					$estado = $this->request->query['estado'];
					$condition .= " AND planificacion.estado = ".$estado;
			}


			if(isset($this->request->query['supervisor_responsable']) && $this->request->query['supervisor_responsable'] != '') {
					$supervisor_responsable = explode("_", $this->request->query['supervisor_responsable']);
					$supervisor_responsable = $supervisor_responsable[1];
					$condition .= " AND planificacion.supervisor_responsable = ".$supervisor_responsable;
			}
			if(isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] == '') {
					if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] != '') {
							$tipointervencion = $this->request->query['tipointervencion'];
					}
			}
			if(isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] == 'PR') {
					if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] == '') {
							$tipointervencion = "'MP', 'RP', 'OP', 'BL'";
							//$conditions["Planificacion.backlog_id IS NOT"] = NULL;
					}
					if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] != '') {
							$tipointervencion =  $this->request->query['tipointervencion'];
							if($this->request->query['tipointervencion'] == 'OP' || $this->request->query['tipointervencion'] == 'BL') {
									//$conditions["Planificacion.backlog_id IS NOT"] = NULL;
							}
					}
			}
			
			if(isset($this->request->query['tipo_evento']) && $this->request->query['tipo_evento'] == 'NP') {
					if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] == '') {
							$tipointervencion = "'EX', 'RI', 'OP', 'BL'";
					}
					if(isset($this->request->query['tipointervencion']) && $this->request->query['tipointervencion'] != '') {
							$tipointervencion =  $this->request->query['tipointervencion'];
							if($this->request->query['tipointervencion'] == 'OP' || $this->request->query['tipointervencion'] == 'BL') {
									//$conditions["Planificacion.backlog_id IS"] = NULL;
							}
					}
			}
			
			if(isset($tipointervencion)){
				$condition .= " AND planificacion.tipointervencion in (".$tipointervencion.") ";
			}
			
			if(isset($this->request->query['fecha_inicio']) && $this->request->query['fecha_inicio'] != '') {
					$fecha_inicio = $this->request->query['fecha_inicio'];
			}
			if(isset($this->request->query['fecha_termino']) && $this->request->query['fecha_termino'] != '') {
					$fecha_termino = $this->request->query['fecha_termino'];
			}
			foreach($this->request->query as $key => $value) {
					$this->set($key, $value);
			}
		}
		$query = "select replanificacion.id ids, Faena.nombre faena, flota.nombre flota, unidad.unidad, motor.nombre motor, planificacion.correlativo_final, planificacion.id,
					replanificacion.fecha_anterior, replanificacion.hora_anterior, planificacion.fecha, planificacion.hora, estado_motor.esn_placa esn, 
					planificacion.tipointervencion, planificacion.padre , sintoma.nombre sintoma, replanificacion.tiempo_estimado_anterior, 'Replanificado' estado,
					replanificacion.fecha_replanificacion, motivo.nombre motivo, replanificacion.comentario, concat(usuario.nombres, ' ', usuario.apellidos) usuario
					,planificacion.updated, planificacion.tiempo_estimado, planificacion.tipomantencion
					from replanificacion
					inner join Planificacion on planificacion.id = replanificacion.id_intervencion
					Inner join Faena on Faena.id = planificacion.faena_id
					inner join Flota on Flota.id = planificacion.flota_id
					inner join unidad on unidad.id = planificacion.unidad_id
					inner join motor on motor.id = unidad.motor_id
					inner join motivo_replanificacion motivo on motivo.id = replanificacion.motivo_id
					left join sintoma on sintoma.id = planificacion.sintoma_id
					inner join estados on estados.id = planificacion.estado
					inner join usuario on usuario.id = replanificacion.usuario_id
					INNER JOIN estado_motor ON estado_motor.unidad_id = planificacion.unidad_id  
					AND estado_motor.fecha_ps = (SELECT max(fecha_ps) 
						FROM estado_motor WHERE unidad_id = planificacion.unidad_id AND fecha_ps <= planificacion.fecha)
					Where replanificacion.fecha_anterior BETWEEN '".$fecha_inicio." 00:00:00' AND '".$fecha_termino." 23:59:59' " .$condition;
		
		$query .= " union

					select '10' ids , Faena.nombre faena, flota.nombre flota, unidad.unidad, motor.nombre motor, planificacion.correlativo_final, planificacion.id,
					planificacion.fecha, planificacion.hora, planificacion.fecha, planificacion.hora, estado_motor.esn_placa esn, 
					planificacion.tipointervencion, planificacion.padre , sintoma.nombre sintoma, planificacion.tiempo_estimado, estados.nombre estado,
					planificacion.updated, '' motivo, '' comentario, concat(usuario.nombres, ' ', usuario.apellidos) usuario
					,planificacion.updated, planificacion.tiempo_estimado, planificacion.tipomantencion
					from Planificacion
					Inner join Faena on Faena.id = planificacion.faena_id
					inner join Flota on Flota.id = planificacion.flota_id
					inner join unidad on unidad.id = planificacion.unidad_id
					inner join motor on motor.id = unidad.motor_id
					left join sintoma on sintoma.id = planificacion.sintoma_id
					inner join estados on estados.id = planificacion.estado
					left join usuario on usuario.id = planificacion.usuario_id
					INNER JOIN estado_motor ON estado_motor.unidad_id = planificacion.unidad_id  
					AND estado_motor.fecha_ps = (SELECT max(fecha_ps) 
						FROM estado_motor WHERE unidad_id = planificacion.unidad_id AND fecha_ps <= planificacion.fecha)
					Where Planificacion.fecha BETWEEN '".$fecha_inicio." 00:00:00' AND '".$fecha_termino." 23:59:59' " .$condition;
		$query .= " order by faena, flota , unidad, correlativo_final, estado desc, ids";
		
		
		$registros = $this->Planificacion->query($query);
		
		$dataArray[] =  array();
		
		$utilReporte = new UtilidadesReporteController();
		$util = new UtilidadesController();

		PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
		$objPHPExcel = new PHPExcel();
		header ('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header ('Cache-Control: max-age=0');
		header ('Content-Disposition: attachment;filename="Replanificaciones-'.date("Y-m-d").'".xlsx');
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		$objPHPExcel-> getProperties()
		->setCreator("DBM")
		->setLastModifiedBy("DBM")
		->setTitle("Replanificaciones");
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Replanificados');
			
		$i = 0;
		$filaInicial = 2;
		$linea = 1;
		$enc = array();
		
		$flinea = 0;
		$folio = 0;
		$corr = 0;
		$faena = "";
		$flota = "";
		$unidad = "";
		$fecha = "";
		$fecha_ant = "";
		$esn = "";
		$tipo = "";
		$descrip = "";
		$tpo_est = "";
		$estado = "";
		$f_rep = "";
		$motivo = "";
		$comentario = "";
		$usuario = "";
		
		
		foreach($registros as $value){
			$dataTemp = array();
			
			if($folio == $value[0]['id']){
				$flinea += 1;
			}else{
				$flinea = 0;
			}
			
			if($value[0]['estado'] != 'Replanificado'){
				$utilReporte->cellColor("A".($linea + 1).":R".($linea + 1 ), "DDEBF7","000000",$objPHPExcel);
			}elseif($value[0]['estado'] == 'Replanificado' && $flinea == 0){
				$utilReporte->cellColor("A".($linea + 1).":R".($linea + 1 ), "808080","000000",$objPHPExcel);
			}
			
			
			$folio = $value[0]['id'];
			$dataTemp[] = $value[0]['faena'];
			$dataTemp[] = $value[0]['flota'];
			$dataTemp[] = $value[0]['unidad'];
			$dataTemp[] = $value[0]['correlativo_final'];
			$dataTemp[] = $value[0]['id'];
			$dataTemp[] = $value[0]['fecha_anterior']. " " .$value[0]['hora_anterior'];
			$dataTemp[] = $value[0]['esn'];
			$dataTemp[] = ($value[0]['padre'] != "" ? "c".$value[0]['tipointervencion'] : $value[0]['tipointervencion']);
			
			$dataTemp[] = ($value[0]['tipointervencion'] == 'MP' ? $value[0]['tipomantencion'] : $value[0]['sintoma']);
			
			$dataTemp[] = $value[0]['motor'];
			
			if($value[0]['estado'] != 'Replanificado'){
				$dataTemp[] = ($value[0]['padre'] != "" ? "Continuación" : "Inicial");
			}elseif($value[0]['estado'] == 'Replanificado' && $flinea == 0){
				$dataTemp[] = "";
			}else{
				$dataTemp[] = "";
			}
							
			
			$dataTemp[] = $value[0]['tiempo_estimado_anterior'];
			$dataTemp[] = $value[0]['estado'];
			$dataTemp[] = ($value[0]['estado'] == 'Replanificado' ? 'SI':'NO');
			
			$dataTemp[] = date_format(date_create($value[0]['fecha_replanificacion']), 'd/m/Y H:i:s');
			$dataTemp[] = $value[0]['motivo'];
			$dataTemp[] = $value[0]['comentario'];
			$dataTemp[] = $value[0]['usuario'];
			//$dataTemp[] = $linea;
			
			$dataArray[] = $dataTemp;
			
			$linea++;
		}
		

		
		//Si no encuentra nada, dejo la linea inicial como 0 para que imprima un archivo vacio
		if(count($registros) <= 0 && $linea <= 0){
			$linea = 1;
		}
		
		
		// Encabezados
		#$encabezados = array("Id","Faena","Flota","Unidad","ESN","Medicion","U. Medida","Fecha","Tipo","Componente","Posición", "usuario", "valor PS", "Cambio", "Motivo Cambio", "Atrb. Especiales");
		$encabezados = array("Faena","Flota","Unidad","Correlativo","Folio","Inicio","ESN","Tipo", "Descripción","Modelo Motor", "Categoría", "Duración", "Estado","Replanificado", "Fecha Planificado", "Motivo", "Comentario", "Usuario");
		//foreach($enc as $e){
		//    $encabezados[] = $e;
		//}
		$count = count($encabezados);
		for($i=0;$i<$count;$i++){
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
		}
		//$colString = PHPExcel_Cell::stringFromColumnIndex($count);
		$utilReporte->cellColor("A1:R1","FF0000","FFFFFF",$objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A1');
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true); //->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true); //->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);//->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(8);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

		$style_cell = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),

			);

		$style_border = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN, 
					'color' => array('argb' => '000000'),)
			),
		);
		$objPHPExcel->getActiveSheet()->getStyle('A1:R'.($linea))->applyFromArray($style_cell);
		$objPHPExcel->getActiveSheet()->getStyle('A1:R'."1")->applyFromArray($style_border);
		//$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		//$objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
		$objWriter->save('php://output');
		exit;
		
		
		
	}
}
?>
