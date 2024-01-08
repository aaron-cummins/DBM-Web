<?php
	App::uses('ConnectionManager', 'Model');

	/* Esta clase defines funciones utilitarias para el uso en el sistema */
	class UtilidadesController extends AppController {

		public function getFaena($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Faena');
				$resultado = $this->Faena->find('first', array(
					'conditions' => array('id' => $id)
				));
				return $resultado['Faena']['nombre'];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getFlota($id) {
			if ($id != NULL && is_numeric($id) && $id != "" && $id != "undefined") {
				$this->loadModel('Flota');
				$resultado = $this->Flota->find('first', array(
					'fields' => array("Flota.id", "Flota.nombre"),
					'conditions' => array('Flota.id' => $id),
					'recursive' => -1
				));
				return $resultado['Flota']['nombre'];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getUnidad($id) {
			if ($id != NULL && is_numeric($id) && $id != "" && $id != "undefined") {
				$this->loadModel('Unidad');
				$resultado = $this->Unidad->find('first', array(
					'fields' => array("Unidad.id", "Unidad.unidad"),
					'conditions' => array('Unidad.id' => $id),
					'recursive' => -1
				));
				return @$resultado['Unidad']['unidad'];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getMotor($id) {
			if ($id != NULL && is_numeric($id) && $id != "" && $id != "undefined") {
				$this->loadModel('Unidad');
				$resultado = $this->Unidad->find('first', array(
					'fields' => array("Unidad.id", "Unidad.motor_id"),
					'conditions' => array('Unidad.id' => $id),
					'recursive' => -1
				));
				return @$resultado['Unidad']['motor_id'];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getFlotaUnidad($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Unidad');
				$resultado = $this->Unidad->find('first', array(
					'fields' => array("Unidad.id", "Unidad.flota_id"),
					'conditions' => array('Unidad.id' => $id)
				));
				return $resultado['Unidad']['flota_id'];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getDelta($val) {
			if ($val != null && $val != '') {
				switch ($val) {
					case 'd_traslado_dcc': 
					case 'ds1_traslado':
						return 'Traslado DCC';
						break;
					case 'd_traslado_oem': 
						return 'Traslado OEM';
						break;  
					case 'd_tronadura': 
					case 'd4_tronadura':
						return 'Tronadura';
						break; 
					case 'd_clima': 
					case 'd_clima_inter':
					case 'd4_clima_h':
					case 'd4_clima':
						return 'Clima';
						break; 
					case 'd_logistica_dcc': 
					case 'd2_logistica_dcc': 
					case 'd4_logistica_dcc':
						return 'Logistica DCC';
						break; 
					case 'd_logistica_oem': 
					case 'd2_logistica_oem':
						return 'Logistica OEM';
						break;
					case 'd_personal': 
						return 'Personal';
						break;
					case 'd2_cliente': 
					case 'd3_cliente':
					case 'ds3_cliente':
					case 'ds3_traslado':
						return 'Cliente';
						break;
					case 'd_oem': 
					case 'd3_oem':
					case 'd4_oem':
					case 'ds3_oem':
					case 'ds3_tronadura':
					case 'ds1_oem':
						return 'OEM';
						break;
					case 'd_zona_segura': 
						return 'Zona Segura';
						break;
					case 'd_charla': 
					case 'ds1_charla':
						return 'Charla';
						break;
					case 'd_tronadura_inter': 
					case 'd4_tronadura_h':
						return 'Tronadura';
						break;
					case 'd3_personal_dcc': 
					case 'ds1_persona_dcc':
						return 'Personal DCC';
						break;
					case 'd3_liquidos': 
						return 'Fluídos';
						break;
					case 'd3_repydiag': 
						return 'Reparación & Diagnóstico';
						break;
					case 'd3_repuestos': 
						return 'Repuestos';
						break;
					case 'd3_herramientas_dcc': 
					case 'ds1_herramientas':
						return 'Herramientas DCC';
						break;
					case 'd3_herramientas_panol': 
						return 'Herramientas Pañol';
						break;
					case 'd4_operador':
						return 'Operador';
						break;
					case 'd4_infraestructura':
					case 'ds1_infraestructura':
						return 'Infraestructura';
						break;
					default:
						return $val;
				}
			} else {
				return "-";
			}
		}
		
		public function getEstadoDCC($estado) {
			switch ($estado) {
				case 4:
				case 5:
				case 6:
					return "Aprobado";
					break;
				default:
					return "Sin revisar";
			}
		}
		
		public function getEstadoKCH($estado) {
			switch ($estado) {
				case 5:
					return "Aprobado";
					break;
				case 6:
					return "Rechazado";
					break;
				default:
					return "Sin revisar";
			}
		}

		public function getEstado($estado) {
			switch ($estado) {
				case 4: 
					return "Aprobado DCC";
					break;
				case 5:
					return "Aprobado Cliente";
					break;
				case 6:
					return "Rechazado Cliente";
					break;
				default:
					return "Sin revisar";
			}
		}
		
		public function getCausa($val) {
			switch ($val) {
				case 1:
					return "Falla";
					break;
				case 2:
					return "Consecuencia";
					break;
				case 3:
					return "Oportunidad";
					break;
				default:
					return "-";
					break;
			}
		}
		
		public function getSintoma($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Sintoma');
				$resultado = $this->Sintoma->find('first', array(
					'fields' => array('Sintoma.id', 'Sintoma.nombre', 'Sintoma.codigo'),
					'conditions' => array('Sintoma.id' => $id)
				));
				if ($resultado['Sintoma']['codigo'] == "0" || $resultado['Sintoma']['codigo'] == NULL) {
					return $resultado['Sintoma']['nombre'];
				} else {
					return 'FC ' . $resultado['Sintoma']['codigo'] . ' ' . $resultado['Sintoma']['nombre'];
				}
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getCategoriaSintoma($sintoma_id) {
			if ($sintoma_id != null && is_numeric($sintoma_id)) {
				$this->loadModel('Sintoma');
				$resultado = $this->Sintoma->find('first', array(
					'fields' => array("Sintoma.id", "Categoria.id"),
					'conditions' => array('Sintoma.id' => $sintoma_id),
					'recursive'=>1
				));
				return $resultado['Categoria']['id'];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getCategoria($sintoma_id) {
			if ($sintoma_id != null && is_numeric($sintoma_id)) {
				$this->loadModel('Sintoma');
				$resultado = $this->Sintoma->find('first', array(
					'fields' => array("Sintoma.id", "Categoria.nombre"),
					'conditions' => array('Sintoma.id' => $sintoma_id)
				));
				return $resultado['Categoria']['nombre'];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function checkFaena($nombre) {
			if ($nombre != null && $nombre != '') {
				$this->loadModel('Faena');
				$resultado = $this->Faena->find('first', array(
					'conditions' => array('nombre' => $nombre)
				));
				return $resultado['Faena']['id'];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getSupervisorDCC($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Usuario');
				$resultado = $this->Usuario->find('first', array(
					'conditions' => array('Usuario.id' => $id),
					'recursive' => -1
				));
				$apellidos = split(" ", trim($resultado['Usuario']['apellidos']));
				$nombres = trim($resultado['Usuario']['nombres']);
				return substr($nombres, 0, 1) . ". " . $apellidos[0];
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getSistema($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Sistema');
				$resultado = $this->Sistema->find('first', array(
					'fields' => array('Sistema.id', 'Sistema.nombre'),
					'conditions' => array('Sistema.id' => $id),
					'recursive' => -1
				));
				return ($resultado['Sistema']['nombre']);
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getSistemaMotor($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Sistema_Motor');
				$resultado = $this->Sistema_Motor->find('first', array(
					'fields' => array('Sistema_Motor.id', 'Sistema.nombre'),
					'conditions' => array('Sistema_Motor.id' => $id),
					'recursive' => 1
				));
				return (@$resultado['Sistema']['nombre']);
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getSubsistema($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Subsistema');
				$resultado = $this->Subsistema->find('first', array(
					'fields' => array('Subsistema.id', 'Subsistema.nombre'),
					'conditions' => array('Subsistema.id' => $id),
					'recursive' => -1
				));
				return ($resultado['Subsistema']['nombre']);
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getDiagnostico($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Diagnostico');
				$resultado = $this->Diagnostico->find('first', array(
					'conditions' => array('Diagnostico.id' => $id)
				));
				if (isset($resultado['Diagnostico']['nombre']))
					return ($resultado['Diagnostico']['nombre']);
				else
					return '-';
			} else {
				return "-";
			}
		}
		
		public function getSolucion($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Solucion');
				$resultado = $this->Solucion->find('first', array(
					'conditions' => array('Solucion.id' => $id)
				));
				return ($resultado['Solucion']['nombre']);
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getComentario($folio){
			$this->loadModel('IntervencionComentarios');
			$resultado = $this->IntervencionComentarios->find('first', array(
				'conditions' => array('IntervencionComentarios.folio' => $folio)
			));
			return (@$resultado['IntervencionComentarios']['comentario']);
		}
		
		public function getSubsistemaPosicion($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Posiciones_Subsistema');
				$resultado = $this->Posiciones_Subsistema->find('first', array(
					'conditions' => array('Posiciones_Subsistema.id' => $id)
				));
				return ($resultado['Posiciones_Subsistema']['nombre']);
			
				/*$this->loadModel('SubsistemaPosicionAsign');
				$resultado = $this->SubsistemaPosicionAsign->find('all', array(
					'fields' =>array('SubsistemaPosicionAsign.id', 'Posicion.nombre'),
					'conditions' => array('SubsistemaPosicionAsign.id' => $id)
				));
				return strtoupper($resultado[0]["Posicion"]["nombre"]);*/
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getElemento($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Elemento');
				$resultado = $this->Elemento->find('first', array(
					'conditions' => array('Elemento.id' => $id)
				));
				return ($resultado['Elemento']['nombre']);
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getBacklogInfo($backlog_id) {
			$this->loadModel('Backlog');
			$resultado = $this->Backlog->find('first', array(
				'fields' => array('Backlog.id', 'Sistema.nombre'),
				'conditions' => array('Backlog.id' => $backlog_id),
				'recursive' => 1
			));
			return @$resultado['Sistema']['nombre'];
		}
		
		public function getBacklogDescripcion($folio) {
			return $this->getBacklogInfo($folio);
		}
		
		public function getBacklog($backlog_id) {
			$this->loadModel('Backlog');
			$resultado = $this->Backlog->find('first', array(
				'fields' => array('Backlog.id', 'Backlog.criticidad', 'Sistema_Motor.*'),
				'conditions' => array('Backlog.id' => $backlog_id),
				'recursive' => 2
			));
			$return = "";
			switch($resultado['Backlog']['criticidad']) {
				case '1':
					$return = "Alto";
					break;
				case '2':
					$return = "Medio";
					break;
				case '3':
					$return = "Bajo";
					break;
			}
			$return .= " / " . $resultado['Sistema_Motor']['Sistema']['nombre'];
			return $return;
		}
		
		public function getElementoPosicion($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Posiciones_Elemento');
				$resultado = $this->Posiciones_Elemento->find('first', array(
					'conditions' => array('Posiciones_Elemento.id' => $id)
				));
				return ($resultado['Posiciones_Elemento']['nombre']);
			} else {
				return "SIN DATOS";
			}
		}
		
		public function getUsuarioInfo($usuario_id) {
			if (is_numeric($usuario_id)) {
				$this->loadModel('Usuario');
				$resultado = $this->Usuario->find('first', array(
					'fields' => array('Usuario.id', 'Usuario.apellidos', 'Usuario.nombres'),
					'conditions' => array('Usuario.id' => $usuario_id),
					'recursive' => -1
				));
				if (isset($resultado["Usuario"]))
					return ($resultado["Usuario"]["apellidos"] . ' ' . $resultado["Usuario"]["nombres"]);	
			}
			return "";
		}
                
                
                public function getUsuarioInfoRut($usuario) {
			if (is_numeric($usuario)) {
				$this->loadModel('Usuario');
				$resultado = $this->Usuario->find('first', array(
					'fields' => array('Usuario.id', 'Usuario.apellidos', 'Usuario.nombres'),
					'conditions' => array('Usuario.usuario' => $usuario),
					'recursive' => -1
				));
				if (isset($resultado["Usuario"]))
					return strtoupper($resultado["Usuario"]["apellidos"] . ' ' . $resultado["Usuario"]["nombres"]);	
			}
			return "";
		}
		
		public function getTecnicoLider($json) {
			$usuario_id = "";
			if(@$json["TipoTecnicoApoyo01"]=='0'){$usuario_id = $json["UserID"];}
			if(@$json["TipoTecnicoApoyo02"]=='0'){$usuario_id = $json["TecnicoApoyo02"];}
			if(@$json["TipoTecnicoApoyo03"]=='0'){$usuario_id = $json["TecnicoApoyo03"];}
			if(@$json["TipoTecnicoApoyo04"]=='0'){$usuario_id = $json["TecnicoApoyo04"];}
			if(@$json["TipoTecnicoApoyo05"]=='0'){$usuario_id = $json["TecnicoApoyo05"];}
			if(@$json["TipoTecnicoApoyo06"]=='0'){$usuario_id = $json["TecnicoApoyo06"];}
			if(@$json["TipoTecnicoApoyo07"]=='0'){$usuario_id = $json["TecnicoApoyo07"];}
			if(@$json["TipoTecnicoApoyo08"]=='0'){$usuario_id = $json["TecnicoApoyo08"];}
			if(@$json["TipoTecnicoApoyo09"]=='0'){$usuario_id = $json["TecnicoApoyo09"];}
			if(@$json["TipoTecnicoApoyo10"]=='0'){$usuario_id = $json["TecnicoApoyo10"];}
			if (is_numeric($usuario_id)) {
				$this->loadModel('Usuario');
				$resultado = $this->Usuario->find('first', array(
					'fields' => array('Usuario.id', 'Usuario.apellidos', 'Usuario.nombres'),
					'conditions' => array('Usuario.id' => $usuario_id),
					'recursive' => -1
				));
				if (isset($resultado["Usuario"]))
					return ($resultado["Usuario"]["apellidos"] . ' ' . $resultado["Usuario"]["nombres"]);	
			}
			return "";
		}
		
		public function getTecnicoRut($usuario_id) {
			if (is_numeric($usuario_id)) {
				$this->loadModel('Usuario');
				$resultado = $this->Usuario->find('first', array(
					'fields' => array('Usuario.usuario', 'Usuario.id'),
					'conditions' => array('Usuario.id' => $usuario_id),
					'recursive' => -1
				));
				
				return @$resultado["Usuario"]["usuario"];	
			}
			return '';
		}
		
		public function getTecnico($usuario_id) {
			if (is_numeric($usuario_id)) {
				$this->loadModel('Usuario');
				$resultado = $this->Usuario->find('first', array(
					'fields' => array('Usuario.nombres', 'Usuario.apellidos', 'Usuario.id'),
					'conditions' => array('Usuario.id' => $usuario_id),
					'recursive' => -1
				));
				
				$apellidos = split(' ', trim(@$resultado["Usuario"]["apellidos"]));
				return substr(trim(@$resultado["Usuario"]["nombres"]), 0, 1) . ". " . ucfirst(strtolower(@$apellidos[0]));	
			}
			return '';
		}
		
		public function getTecnicoFull($usuario_id) {
			$this->loadModel('Usuario');
			if($usuario_id!=""&&is_numeric($usuario_id)){
				$resultado = $this->Usuario->find('first', array(
					'fields' => array('Usuario.nombres', 'Usuario.apellidos', 'Usuario.id'),
					'conditions' => array('Usuario.id' => $usuario_id),
					'recursive' => -1
				));
				if (isset($resultado["Usuario"])) {
					//return $resultado["Usuario"]["apellidos"] .', '.$resultado["Usuario"]["nombres"];	
					return trim($resultado["Usuario"]["nombres"]).' '.trim($resultado["Usuario"]["apellidos"]);	
				} else {
					return "";
				}
			}else{
				return "";
			}
		}
		
		/*public function getNumHijos($id) {
			if ($id != null && is_numeric($id)) {
				$this->loadModel('Planificacion');
				$resultado = $this->Planificacion->find('first', array(
					'conditions' => array('Planificacion.id' => $id)
				));
				if ($resultado['Planificacion']['hijo'] != null && $resultado['Planificacion']['hijo'] != '') {
					return 1;
				}
			} else {
				return "0";
			}
		}*/
	
	
		public function desplegar_hijo($faena_id = 0, $codigo_hijo = "", $i, $j = 1) {
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array( 
													'conditions' => array(
													'padre' => $codigo_hijo,
													'estado' => array(3, 6, 7)),
													'recursive' => 1));
			if (count($intervencion) > 0) {
				$json = json_decode($intervencion["Planificacion"]["json"], true);
				$return = "<tr>";
				$return .= "<td>$i"."."."$j</td>";
				
				if ($faena_id == 0) {
					$return .= "<td nowrap>{$intervencion['Faena']['nombre']}</td>";
				}
				
				$return .= "<td nowrap>{$intervencion['Planificacion']['id']}</td>";
				$return .= "<td nowrap>{$this->getFlota($intervencion['Planificacion']['flota_id'])}</td>";
				$return .= "<td nowrap>{$this->getUnidad($intervencion['Planificacion']['unidad_id'])}</td>";
				$esn = "";
				if (@$json["esn_nuevo"] != "") {
					$esn = @$json["esn_nuevo"];
				} elseif (@$json["esn"] != "") {
					$esn = @$json["esn"];
				} else {
					$esn = $intervencion['Planificacion']['esn'];
				}
				
				if (is_numeric($esn)) {
					$return .= "<td align=\"center\">$esn</td>";
				} else {
					$return .= "<td align=\"center\">".$this->esnPadre($intervencion['Planificacion']['padre'])."</td>";
				}
				
				$return .= "<td align=\"center\">c".strtoupper($intervencion['Planificacion']['tipointervencion'])."</td>";
				$return .= "<td align=\"left\">-</td>";
				// Fecha inicio
				$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
				
				try {
					if ($date != '') {
						$return .= "<td align=\"left\">".$date->format('d-m-Y h:i A')."</td>";
					} else {
						$return .= "<td align=\"center\"></td>";
					}
				} catch (Exception $e) {
					$return .= "<td align=\"center\"></td>";
				}
				
				// Fecha termino
				$fecha_termino = '';
				
				if (isset($json["fecha_termino_g"]) && $json["fecha_termino_g"] != null && $json["fecha_termino_g"] != "") {
					$fecha_termino = new DateTime($json["fecha_termino_g"]);
				} elseif ($intervencion['Planificacion']['fecha_termino'] != '') {
					$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
				}
				
				try {
					if ($fecha_termino != '') {
						$return .= "<td align=\"left\">".$fecha_termino->format('d-m-Y h:i A')."</td>";
					} else {
						$return .= "<td align=\"center\"></td>";
					}
				} catch (Exception $e) {
					$return .= "<td align=\"center\"></td>";
				}
				
				$comentario = "";
				
				if (@$json["comentario"] != "") {
					$comentario = @$json["comentario"];
				} else {
					$comentario = @$json["comentarios"];
				}
				
				/*if (strlen($comentario) > 0) {
					//$return .= "<td align=\"center\"><span title=\"$comentario\">Ver</span></td>";
				} else {
					$return .= "<td align=\"center\"></td>";
				}*/
				
				$return .= "<td align=\"center\">";
				
				if (strlen($comentario) > 0) {
					$return .= "<div id=\"comentario_{$intervencion['Planificacion']['id']}\" class=\"box_comentario\">
						<h4>Comentario</h4>
						<p align=\"justify\">$comentario</p>
						<p align=\"right\"><span class=\"cerrar_comentario\" style=\"cursor: pointer;\">Cerrar</span></p>
					</div>";
					$return .= "<img src=\"/images/icon-comentario.png\" class=\"mostrar_comentario\" comentario=\"comentario_{$intervencion['Planificacion']['id']}\" style=\"width: 16px;cursor: pointer;\" title=\"Ver comentarios\" />";
				}
				
				$return .= "</td>";
				
				if ($intervencion['Planificacion']['estado'] == 6) {
					$return .= "<td align=\"center\">Rechazado Cliente</td>";
				} elseif ($intervencion['Planificacion']['estado'] == 3)  {
					$return .= "<td align=\"center\">Revisado</td>";
				} else {
					$return .= "<td align=\"center\">Sin Revisar</td>";
				}
				
				$url = strtolower($intervencion['Planificacion']['tipointervencion']);
				$url = $url == "rp" ? "bl" : $url;
				$return .= '<td align="center">
					<form action="/Trabajo/'.$url.'/'.$intervencion['Planificacion']['id'].'" method="GET">
						<input type="submit" name="detalle" value="Detalle" class="blueB"  />
					</form>
				</td>';
						
				$return .= "</tr>";
				if ($intervencion["Planificacion"]["hijo"] == "") {
					return $return;
				} else {
					return $return . $this->desplegar_hijo($faena_id, $intervencion["Planificacion"]["hijo"], $i, $j + 1);
				}
			} else {
				return "";
			}
		}
		
		public function esnPadre($padre) {
			if($padre!=""){
				$this->loadModel('Planificacion');
				$intervencion = $this->Planificacion->find('first', array( 
														'fields' => array('Planificacion.esn', 'Planificacion.hijo', 'Planificacion.json', 'Planificacion.padre'),
														'conditions' => array(
														'hijo' => $padre),
														'recursive' => -1));
				
				if (!isset($intervencion["Planificacion"])) {
					return "S/I";
				}
				
				$json = json_decode($intervencion["Planificacion"]["json"], true);										
				$esn = "";
				if (@$json["esn_nuevo"] != "") {
					$esn = @$json["esn_nuevo"];
				} elseif (@$json["esn"] != "") {
					$esn = @$json["esn"];
				} else {
					$esn = $intervencion['Planificacion']['esn'];
				}
				
				if (is_numeric($esn)) {
					return $esn;
				} else {
					return $this->esnPadre($intervencion['Planificacion']['padre']);
				}
			} else {
				return "";
			}
		}
		
		public function getCorrelativo($padre) {
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.id', 'Planificacion.hijo', 'Planificacion.padre'),
													'conditions' => array(
													'Planificacion.hijo' => $padre,
													'Planificacion.estado<>10'),
													'recursive' => -1));
			
			// Si tiene padre, llama 
			if (@$intervencion["Planificacion"]["padre"] != "" && @$intervencion["Planificacion"]["padre"] != null && @$intervencion["Planificacion"]["padre"] != "NULL") {
				return @$this->getCorrelativo($intervencion["Planificacion"]["padre"]);
			} else {
				return @$intervencion["Planificacion"]["id"];
			}
			// Si no tiene padre, retorna
			
		}
		
		public function getEstadoPadre($padre) {
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.id', 'Planificacion.hijo', 'Planificacion.estado'),
													'conditions' => array(
													'estado <> 4',
													'Planificacion.hijo' => $padre),
													'recursive' => -1));
			
			if(@$intervencion["Planificacion"]["estado"]==10){
				return $this->getEstadoPadre(@$intervencion["Planificacion"]["id"]);
			}else{
				return @$intervencion["Planificacion"]["id"];
			}
		}
		
		public function getJsonPadre($padre, $var) {
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.id', 'Planificacion.hijo', 'Planificacion.padre', 'Planificacion.json'),
													'conditions' => array(
													'Planificacion.hijo' => $padre),
													'recursive' => -1));
			
			// Si tiene padre, llama 
			if (@$intervencion["Planificacion"]["padre"] != "" && @$intervencion["Planificacion"]["padre"] != null && @$intervencion["Planificacion"]["padre"] != "NULL") {
				return $this->getJsonPadre($intervencion["Planificacion"]["padre"], $var);
			} else {
				$json = @json_decode(@$intervencion["Planificacion"]["json"], true); 
				return @$json[$var];
			}
		}
		
		public function getESNPadre($padre) {
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.id', 'Planificacion.hijo', 'Planificacion.padre', 'Planificacion.esn'),
													'conditions' => array(
													'Planificacion.hijo' => $padre),
													'recursive' => -1));
			
			// Si tiene padre, llama 
			if (@$intervencion["Planificacion"]["padre"] != "" && @$intervencion["Planificacion"]["padre"] != null && @$intervencion["Planificacion"]["padre"] != "NULL") {
				return $this->getESNPadre($intervencion["Planificacion"]["padre"]);
			} else {
				return @$intervencion["Planificacion"]["esn"];
			}
		}
		
		public function getESN($faena_id,$flota_id,$motor_id,$unidad,$fecha) {
			try {
				if($motor_id==''){$motor_id=0;}
				if($flota_id==''){$flota_id=0;}
				if($faena_id==''){$faena_id=0;}
				$this->loadModel('Esn');
				$esn = $this->Esn->find('all', array( 
                                    'fields' => array('Esn.esn', 'Esn.id'),
                                    'conditions' => array("flota_id='$flota_id' AND faena_id='$faena_id' AND motor_id='$motor_id' AND unidad='$unidad' AND (('$fecha'>=fecha_inicio AND '$fecha'<=fecha_termino) OR ('$fecha'>=fecha_inicio AND fecha_termino IS NULL))"),
                                    'order by id',
                                    //'limit' => 1,
                                    'recursive' => -1));
				//print_r($esn);
				$max = count($esn);
				if($max==1){
					return @$esn[0]["Esn"]["esn"];
				}else{
					$max_id = 0;
					$j=0;
					for($i=0;$i<$max;$i++){
						if($max_id<$esn[$i]["Esn"]["id"]){
							$max_id=$esn[$i]["Esn"]["id"];
							$j=$i;
						}
					}
					return @$esn[$j]["Esn"]["esn"];
				}
			} catch(Exception $e){
				return "";
			}
		}

		public function getESNFromEstadoMotor($faena_id, $flota_id, $unidad_id, $fecha) {
			$this->loadModel('EstadosMotores');
			$esn = $this->EstadosMotores->find('first', [
				'fields' => ['esn_placa'],
				'order' => 'fecha_ps DESC',
				'conditions' => [
					'AND' => [
						'faena_id' => $faena_id,
						'flota_id' => $flota_id,
						'unidad_id' => $unidad_id,
						'OR' => [
							[
								'AND' => [
									'fecha_ps <=' => $fecha,
									'fecha_falla IS NULL'
								]
							],
							[
								'AND' => [
									'fecha_ps <=' => $fecha,
									'fecha_falla >=' => $fecha
								]
							],
							'fecha_falla <=' => $fecha
						]					
					]
				],
				'recursive' => -1
			]);

			return !empty($esn) ? $esn['EstadosMotores']['esn_placa'] : null;
		}
		
		public function desplegar_hijo_base_report($codigo_padre = "", $time_anterior = 0, $codigo_zero = 0) {
			$this->loadModel('Planificacion');
			$string = "";
			$intervencion = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.*', 'Unidad.unidad'),
													'conditions' => array(
													'Planificacion.padre' => $codigo_padre,
													'estado IN (4, 5, 6)'),
													'recursive' => 1));
			$intervencion_zero = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre'),
													'conditions' => array(
													'Planificacion.id' => $codigo_zero),
													'recursive' => 1));
			if (count($intervencion) > 0) {
				//$supervisor = $this->getTecnico($intervencion['Planificacion']["aprobador_id"]);
				/*$status = "";
				switch($intervencion['Planificacion']['estado']) {
					case '4':
						$status = "Aprobado DCC";
						break;
					case '5':
						$status = "Aprobado KCH";
						break;
					case '6':
						$status = "Rechazado KCH";
						break;
				}*/
				
				
				
				// Delta Supervisor 1 y 2
				$json = json_decode($intervencion["Planificacion"]["json"], true);
				$esn = "";
				if (@$json["esn_nuevo"] != "") {
					$esn = @$json["esn_nuevo"];
				} elseif (@$json["esn"] != "") {
					$esn = @$json["esn"];
				} else {
					$esn = $intervencion['Planificacion']['esn'];
				}
				if (!is_numeric($esn)) {
					$esn = $intervencion_zero['Planificacion']['esn'];
				} 
				
				$esn_ = $this->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$this->getMotor($intervencion['Planificacion']['unidad_id']),$this->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
				$esn = $esn_;
				if($esn_==''){
					if (@$json["esn_conexion"] != "") {
						$esn =  @$json["esn_conexion"];
					} elseif (@$json["esn_nuevo"] != "") {
						$esn = @$json["esn_nuevo"];
					} elseif (@$json["esn"] != "") {
						$esn =  @$json["esn"];
					} else {
						$esn =  $intervencion['Planificacion']['esn'];
					}
					
					if (is_numeric($esn)) {
						$esn = $esn;
					} else {
						$esn  = $this->esnPadre($intervencion['Planificacion']['padre']);
					}
				}
				
				$sintoma ="";
				if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
					if (isset($json["tipo_programado"])) {
						if ($json["tipo_programado"] == "1500") {
							//$return .= "<td>Overhaul</td>";
							$sintoma = "Overhaul";
						} else {
							//$return .= "<td>{$json["tipo_programado"]}</td>";
							$sintoma = $json["tipo_programado"];
						}	
					} else {					
						if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
							//$return .= "<td>Overhaul</td>";
							$sintoma = "Overhaul";
						} else {
							//$return .= "<td>{$intervencion['Planificacion']['tipomantencion']}</td>";
							$sintoma = $intervencion['Planificacion']['tipomantencion'];
						}
					}
				} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
					//$sintoma = $this->getBacklogDescripcion($intervencion['Planificacion']['id']);
				} else {
					$sintoma = $this->getSintoma($intervencion['Planificacion']['sintoma_id']);
				}
				
				if (strlen($sintoma) > 30) {
					$sintoma = trim(substr($sintoma, 0, 28)) . '...';	
				}
				
				$string = "<td>{$intervencion_zero['Planificacion']['id']}</td>
							<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>";
				
				if (isset($intervencion_zero['Planificacion']['fecha_termino'])) {
					$fecha = strtotime($intervencion_zero['Planificacion']['fecha_termino']);
					foreach($json as $key => $value) {
						if (substr($key, 0, 4) == "ds1_" && substr($key, -2) == "_r" || substr($key, 0, 4) == "ds2_" && substr($key, -2) == "_r") {
							$newkey = substr($key, 0, -2);
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if ($hora == 0 && $minuto == 0) {
								continue;
							}
							$return = "<tr style=\"background-color: #DDEBF7; color: black;\">";
							$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
							$time_termino = $fecha + $hora * 60 * 60 + $minuto * 60;
							$fecha_termino = $time_termino;
							$return .= "<tr style=\"background-color:#DDEBF7;\">";
							$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
							<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>$esn</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							$string .= "<td>Intervención</td>";
							if ($value == 1) {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}elseif ($value == 2) {
								$return .=  "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							}elseif ($value == 3) {
								$return .=  "<td>T_Mina</td>";
								$return .= "<td>Mina</td>";
							}
							if (substr($key, 0, 4) == "ds1_") {
								$return .=  "<td>Tiempo</td>";
							} else {
								$return .=  "<td>Traslado</td>";
							}
							$return .=  "<td>".date("d-m-Y", $fecha)."</td>";
							$return .=  "<td>".date("h:i A", $fecha)."</td>";
							$return .=  "<td>".date("d-m-Y", $fecha_termino)."</td>";
							$return .=  "<td>".date("h:i A", $fecha_termino)."</td>";
							$return .=  "<td>$duracion</td>";
							$return .=  "<td nowrap>$sintoma</td>";
							$return .=  "</tr>";
							$fecha = $time_termino;
						}
					}					
				}
			
				$fecha = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
				$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);
				$diff = $fecha_termino->diff($fecha);
				$tiempo_trabajo = $diff->h + $diff->i / 60.0;
				$tiempo_trabajo = $tiempo_trabajo + ($diff->days*24);
				$tiempo_trabajo = number_format($tiempo_trabajo, 2, ",",".");
				
				
				
				$return = "<tr style=\"background-color: #808080; color: white;\">";
				$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
							<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>";
				
						
							
				$return .= "<td>$esn</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
							<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
				$return .= "<td>-</td>";
				$return .= "
				<td>Continuacion</td>
				<td>{$fecha->format('d-m-Y')}</td>
				<td>{$fecha->format('h:i A')}</td>
				<td>{$fecha_termino->format('d-m-Y')}</td>
				<td>{$fecha_termino->format('h:i A')}</td>
				<td>{$tiempo_trabajo}</td>";
				$return .=  "<td nowrap>$sintoma</td>";
				/*$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>$supervisor</td>";
				$return .= "<td>$tecnico</td>";
				$return .= "<td>$status</td>";*/
				$return .= "</tr>";
				
				/* Deltas */
				$time = strtotime($fecha->format('d-m-Y h:i A'));
				if (isset($json["llamado_fecha"]) && isset($json["llegada_fecha"])) {
					$inicio = strtotime($json["llamado_fecha"] . ' ' . $json["llamado_hora"] . ':'.$json["llamado_min"]. ' ' .$json["llamado_periodo"]);
					$termino = strtotime($json["llegada_fecha"] . ' ' . $json["llegada_hora"] . ':'.$json["llegada_min"]. ' ' .$json["llegada_periodo"]);
					$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
					if (($termino - $inicio) / 60 == 15) {
						$time = $termino;
						$return .= '<tr style="background-color:#F2F2F2;">';
						$return .= "
							<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
						if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'EX') {
							$return .= "<td>T_OEM</td>";
							$return .= "<td>OEM</td>";
						} else {
							$return .= "<td>T_DCC</td>";
							$return .= "<td>DCC</td>";
						}
						$return .= "<td>Tiempo</td>";
						$return .= "<td nowrap>".date('d-m-Y', $inicio)."</td>";
						$return .= "<td nowrap>".date('h:i A', $inicio)."</td>";
						$return .= "<td nowrap>".date('d-m-Y', $termino)."</td>";
						$return .= "<td nowrap>".date('h:i A', $termino)."</td>";
						$return .= "<td>$duracion</td>";
						$return .= "<td nowrap>$sintoma</td>";
						/*$return .= "<td>Tiempo</td>";
						$return .= "<td nowrap>Llamado-Lugar Fisico</td>";
						$return .= "<td>Delta1</td>";
						$return .= "<td>Espera Intervención</td>";
						$return .= "<td nowrap>$supervisor</td>";
						$return .= "<td nowrap>$tecnico_principal</td>";
						$return .= "<td nowrap>$status</td>";*/
						$return .= "</tr>";
					}
				}
				
				$delta_1 = array('d_traslado_dcc_h','d_traslado_oem_h','d_tronadura_h','d_clima_h','d_logistica_dcc_h','d_logistica_oem_h','d_personal_h');
				$delta_2 = array('d2_cliente_h','d_oem_h','d_zona_segura_h','d_clima_inter_h','d_charla_h','d_tronadura_inter_h');
				// Despliegue deltas
				if (is_array($json) && count($json) > 0) {
					foreach($json as $key => $value) {
						if (substr($key, 0, 2) == "d_" && substr($key, -2) == "_r") {
							if ($value == "" || intval($value) == 0) {
								continue;
							}
							$newkey = substr($key, 0, -2);
							if (!in_array($newkey."_h", $delta_1)) {
								continue;
							}
							
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if ($hora == 0 && $minuto == 0) {
								continue;
							}
							$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
							$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
							$return .= "<tr style=\"background-color:#F2F2F2;\">";
							$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							if ($value == 1) {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}elseif ($value == 2) {
								$return .= "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							}elseif ($value == 3) {
								$return .= "<td>T_Mina</td>";
								$return .= "<td>Mina</td>";
							}
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time)."</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time_termino)."</td>";
							$return .= "<td>$duracion</td>";
							$return .= "<td nowrap>$sintoma</td>";
							/*$return .= "<td>Tiempo</td>";
							if (in_array($newkey."_h", $delta_1)) {
								$return .= "<td nowrap>Llamado-Lugar Fisico</td>";
								$return .= "<td>Delta1</td>";
							}
							$return .= "<td nowrap>".$this->getDelta($newkey)."</td>";
							$return .= "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$tecnico_principal</td>";
							$return .= "<td nowrap>$status</td>";*/
							$return .= "</tr>";
							$time = $time_termino;
						}
					}
					
					// Cuando tiempo entre incio de intervencion y llegada es igual a 15 m.
					if (isset($json["llegada_fecha"]) && isset($json["i_i_f"])) {
						$inicio = strtotime($json["llegada_fecha"] . ' ' . $json["llegada_hora"] . ':'.$json["llegada_min"]. ' ' .$json["llegada_periodo"]);
						$termino = strtotime($json["i_i_f"] . ' ' . $json["i_i_h"] . ':'.$json["i_i_m"]. ' ' .$json["i_i_p"]);
						$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
						if (($termino - $inicio) / 60 == 15) {
							$time = $termino;
							$return .= '<tr style="background-color:#F2F2F2;">';
							$return .= "
								<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
								<td>{$intervencion_zero['Faena']['nombre']}</td>
								<td>{$intervencion_zero['Flota']['nombre']}</td>
								<td>{$intervencion['Unidad']['unidad']}</td>
								<td>{$intervencion['Planificacion']['esn']}</td>
								<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'EX') {
								$return .= "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							} else {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>".date('d-m-Y', $inicio)."</td>";
							$return .= "<td nowrap>".date('h:i A', $inicio)."</td>";
							$return .= "<td nowrap>".date('d-m-Y', $termino)."</td>";
							$return .= "<td nowrap>".date('h:i A', $termino)."</td>";
							$return .= "<td>$duracion</td>";
							$return .= "<td nowrap>$sintoma</td>";
							/*$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>Llamado-Lugar Fisico</td>";
							$return .= "<td>Delta1</td>";
							$return .= "<td>Espera Intervención</td>";
							$return .= "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$tecnico_principal</td>";
							$return .= "<td nowrap>$status</td>";*/
							$return .= "</tr>";
						}
					}
					
					foreach($json as $key => $value) {
						if (substr($key, 0, 2) == "d_" && substr($key, -2) == "_r" || substr($key, 0, 3) == "d2_" && substr($key, -2) == "_r") {
							if ($value == "" || intval($value) == 0) {
								continue;
							}
							$newkey = substr($key, 0, -2);
							if (!in_array($newkey."_h", $delta_2)) {
								continue;
							}
							
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if ($hora == 0 && $minuto == 0) {
								continue;
							}
							$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
							$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
							$return .= "<tr style=\"background-color:#F2F2F2;\">";
							$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							if ($value == 1) {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}elseif ($value == 2) {
								$return .= "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							}elseif ($value == 3) {
								$return .= "<td>T_Mina</td>";
								$return .= "<td>Mina</td>";
							}
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time)."</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time_termino)."</td>";
							$return .= "<td>$duracion</td>";
							$return .= "<td nowrap>$sintoma</td>";
							/*$return .= "<td>Tiempo</td>";
							if (in_array($newkey."_h", $delta_2)) {
								$return .= "<td nowrap>Lugar Fisico-Inicio Interv.</td>";
								$return .= "<td>Delta2</td>";
							}
							$return .= "<td nowrap>".$this->getDelta($newkey)."</td>";
							$return .= "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$tecnico_principal</td>";
							$return .= "<td nowrap>$status</td>";*/
							$return .= "</tr>";
							$time = $time_termino;
						}
					}
					
					foreach($json as $key => $value) {
						if (substr($key, 0, 2) == "d_" && substr($key, -2) == "_r" || substr($key, 0, 3) == "d2_" && substr($key, -2) == "_r" || substr($key, 0, 3) == "d3_" && substr($key, -2) == "_r"/* || substr($key, 0, 3) == "d4_" && substr($key, -2) == "_r"*/) {
							if ($value == "" || intval($value) == 0) {
								continue;
							}
							$newkey = substr($key, 0, -2);
							if (in_array($newkey."_h", $delta_1) || in_array($newkey."_h", $delta_2))  {
								continue;
							}
							
							if ($newkey != "d3_repydiag") {
								$hora = intval($json[$newkey."_h"]);
								$minuto = intval($json[$newkey."_m"]);
								if ($hora == 0 && $minuto == 0) {
									continue;
								}
								$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
								$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
								$return .= "<tr style=\"background-color:#F2F2F2;\">";
								$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
								<td>{$intervencion_zero['Faena']['nombre']}</td>
								<td>{$intervencion_zero['Flota']['nombre']}</td>
								<td>{$intervencion['Unidad']['unidad']}</td>
								<td>{$intervencion['Planificacion']['esn']}</td>
								<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
								if ($value == 1) {
									$return .= "<td>T_DCC</td>";
									$return .= "<td>DCC</td>";
								}elseif ($value == 2) {
									$return .= "<td>T_OEM</td>";
									$return .= "<td>OEM</td>";
								}elseif ($value == 3) {
									$return .= "<td>T_Mina</td>";
									$return .= "<td>Mins</td>";
								}
								$return .= "<td>Tiempo</td>";
								$return .= "<td nowrap>".date('d-m-Y', $time)."</td>";
								$return .= "<td nowrap>".date('h:i A', $time)."</td>";
								$return .= "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
								$return .= "<td nowrap>".date('h:i A', $time_termino)."</td>";
								$return .= "<td>$duracion</td>";
								$return .= "<td nowrap>$sintoma</td>";
								/*$return .= "<td>Tiempo</td>";
								if (substr($newkey, 0, 3) == "d3_") {
									$return .= "<td nowrap>Inicio Interv-Termino Interv.</td>";
									$return .= "<td>Delta3</td>";
								}
								if (substr($newkey, 0, 3) == "d4_") {
									$return .= "<td nowrap>Término Interv-Inicio P.P.</td>";
									$return .= "<td>Delta4</td>";
								}
								$return .= "<td nowrap>".$this->getDelta($newkey)."</td>";
								$return .= "<td nowrap>$supervisor</td>";
								$return .= "<td nowrap>$tecnico_principal</td>";
								$return .= "<td nowrap>$status</td>";*/
								$return .= "</tr>";	
							}
				
							//$time = $time_termino;
							
							$time_elementos = $time;
							if ($newkey == "d3_repydiag") {
								if (isset($json["ele_cantidad"]) && is_numeric($json["ele_cantidad"])) {
									// Despliegue elementos
									for ($i = 1; $i <= intval($json["ele_cantidad"]); $i++) {
										if (isset($json["elemento_$i"])) {
											if (!isset($json["d3_elemento_h_$i"]) && !isset($json["d3_elemento_m_$i"]))
												continue;
											/*	$json["d3_elemento_h_$i"] = "0";
											if (!isset($json["d3_elemento_m_$i"]))
												$json["d3_elemento_m_$i"] = "0";*/
											$elemento = split(",", $json["elemento_$i"]);
											$hora = intval($json["d3_elemento_h_$i"]);
											$minuto = intval($json["d3_elemento_m_$i"]);
											if ($hora == 0 && $minuto == 0) {
												//continue;
											}
											$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
											$time_elementos_termino = $time_elementos + $hora * 60 * 60 + $minuto * 60;
											$return .= "<tr style=\"background-color:#FFFFFF;\">";
											$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
												<td>{$intervencion_zero['Faena']['nombre']}</td>
												<td>{$intervencion_zero['Flota']['nombre']}</td>
												<td>{$intervencion['Unidad']['unidad']}</td>
												<td>{$intervencion['Planificacion']['esn']}</td>
												<td>{$intervencion['Planificacion']['tipointervencion']}</td>
												<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
											$return .= "<td>DCC</td>";
											$return .= "<td>Intervención</td>";
											$return .= "<td nowrap>".date('d-m-Y', $time_elementos)."</td>";
											$return .= "<td nowrap>".date('h:i A', $time_elementos)."</td>";
											$return .= "<td nowrap>".date('d-m-Y', $time_elementos_termino)."</td>";
											$return .= "<td nowrap>".date('h:i A', $time_elementos_termino)."</td>";
											$return .= "<td>$duracion</td>";
											$return .= "<td nowrap>$sintoma</td>";
											$return .= "</tr>";
											$time_elementos = $time_elementos_termino;
										}
									}
								}
							}
							$time = $time_elementos;
						}
					}
				}
				
				// Cuando delta 4 es igual a 15m.
				
				// 
				if ((strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'EX' || strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'MP') && isset($json["i_i_f"]) && isset($json["i_t_f"])) {
					$inicio = strtotime($json["i_i_f"] . ' ' . $json["i_i_h"] . ':'.$json["i_i_m"]. ' ' .$json["i_i_p"]);
					$termino = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
					$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
					$return .= '<tr style="background-color:#F2F2F2;">';
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
						<td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion_zero['Faena']['nombre']}</td>
						<td>{$intervencion_zero['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'EX') {
						$return .= "<td>T_OEM</td>";
						$return .= "<td>OEM</td>";
					} else {
						$return .= "<td>T_DCC</td>";
						$return .= "<td>DCC</td>";
					}
					$return .= "<td>Tiempo</td>";
					$return .= "<td nowrap>".date('d-m-Y', $inicio)."</td>";
					$return .= "<td nowrap>".date('h:i A', $inicio)."</td>";
					$return .= "<td nowrap>".date('d-m-Y', $termino)."</td>";
					$return .= "<td nowrap>".date('h:i A', $termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>Tiempo</td>";
					$return .= "<td nowrap>Inicio Interv-Termino Interv.</td>";
					$return .= "<td>-</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				
				if (isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"]) && isset($json["solicitud_f"]) && isset($json["solicitud_h"]) && isset($json["solicitud_m"]) && isset($json["solicitud_p"])) {
					$fecha = strtotime($json["i_t_f"] . " " . $json["i_t_h"].":".$json["i_t_m"]." ".$json["i_t_p"]);
					$fecha_termino = strtotime($json["solicitud_f"] . " " . $json["solicitud_h"].":".$json["solicitud_m"]." ".$json["solicitud_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
						<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Solicitud Traslado</td>";
					$return .= "<td>-</td>";
					$return .= "<td>Espera Traslado</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				
				// Espera de Desconexión
				if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"])) {
					$fecha = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
					$fecha_termino = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Cambio de Modulo</td>";
					$return .= "<td>-</td>";
					$return .= "<td>Espera Desconexion</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				
				// Registro de Desconexion
				if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
					$fecha = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
					$fecha_termino = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
						<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Cambio de Modulo</td>";
					$return .= "<td>-</td>";
					$return .= "<td>Desconexion</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				// Espera de conexion
				if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
					$fecha = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
					$fecha_termino = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Cambio de Modulo</td>";
					$return .= "<td>-</td>";
					$return .= "<td>Espera Conexion</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				
				// Registro de Conexion
				if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
					$fecha = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
					$fecha_termino = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Cambio de Modulo</td>";
					$return .= "<td>-</td>";
					$return .= "<td>Conexion</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				// Espera de Puesta en Marcha
				if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
					$fecha = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
					$fecha_termino = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Puesta en Marcha</td>";
					$return .= "<td>-</td>";
					$return .= "<td>Espera Puesta en Marcha</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				
				// Registro de Puesta en Marcha
				if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["pm_t_f"]) && isset($json["pm_t_h"]) && isset($json["pm_t_m"]) && isset($json["pm_t_p"])) {
					$fecha = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
					$fecha_termino = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"].":".$json["pm_t_m"]." ".$json["pm_t_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Puesta en Marcha</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				
				// Delta4
				if (isset($json["i_t_f"])) {
					$time = strtotime($json["i_t_f"] . " " . $json["i_t_h"].":".$json["i_t_m"]." ".$json["i_t_p"]);
					foreach($json as $key => $value) {
						if (substr($key, 0, 3) == "d4_" && substr($key, -2) == "_r") {
							if ($value == "" || intval($value) == 0) {
								continue;
							}
							$newkey = substr($key, 0, -2);
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if ($hora == 0 && $minuto == 0) {
								continue;
							}
							$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
							$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
							$return .= "<tr style=\"background-color:#F2F2F2;\">";
							$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							if ($value == 1) {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}elseif ($value == 2) {
								$return .= "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							}elseif ($value == 3) {
								$return .= "<td>T_Mina</td>";
								$return .= "<td>Mina</td>";
							}
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time)."</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time_termino)."</td>";
							$return .= "<td>$duracion</td>";
							$return .= "<td nowrap>$sintoma</td>";
							$return .= "</tr>";	
						}
					}
				}
				
				// Registro de Prueba de Potencia si es menor a 15m
				if (isset($json["pp_i_f"]) && isset($json["pp_i_h"]) && isset($json["pp_i_m"]) && isset($json["pp_i_p"]) && isset($json["pp_t_f"]) && isset($json["pp_t_h"]) && isset($json["pp_t_m"]) && isset($json["pp_t_p"])) {
					$fecha = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"].":".$json["pp_i_m"]." ".$json["pp_i_p"]);
					$fecha_termino = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"].":".$json["pp_t_m"]." ".$json["pp_t_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .= "<td nowrap>$sintoma</td>";
					/*$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Prueba Potencia</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>$supervisor</td>";
					$return .= "<td nowrap>$tecnico_principal</td>";
					$return .= "<td nowrap>$status</td>";*/
					$return .= "</tr>";
				}
				
				if ($intervencion["Planificacion"]["hijo"] == "") {
					// Registro de Fecha Operación
					if (isset($intervencion['Planificacion']['fecha_operacion'])) {
						$fecha = strtotime($json['fecha_termino_g']);
						foreach($json as $key => $value) {
							if (substr($key, 0, 4) == "ds3_" && substr($key, -2) == "_r") {
								$newkey = substr($key, 0, -2);
								$hora = intval($json[$newkey."_h"]);
								$minuto = intval($json[$newkey."_m"]);
								if ($hora == 0 && $minuto == 0) {
									continue;
								}
								
								$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
								$time_termino = $fecha + $hora * 60 * 60 + $minuto * 60;
								$fecha_termino = $time_termino;
								$return .= "<tr style=\"background-color:#DDEBF7;\">";
								$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
									<td>{$intervencion['Planificacion']['id']}</td>
									<td>{$intervencion_zero['Faena']['nombre']}</td>
									<td>{$intervencion_zero['Flota']['nombre']}</td>
									<td>{$intervencion['Unidad']['unidad']}</td>
									<td>{$intervencion['Planificacion']['esn']}</td>
									<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
								if ($value == 1) {
									$return .= "<td>T_DCC</td>";
									$return .= "<td>DCC</td>";
								}elseif ($value == 2) {
									$return .= "<td>T_OEM</td>";
									$return .= "<td>OEM</td>";
								}elseif ($value == 3) {
									$return .= "<td>T_Mina</td>";
									$return .= "<td>Mina</td>";
								}
								$return .= "<td>Final</td>";
								$return .= "<td>".date("d-m-Y", $fecha)."</td>";
								$return .= "<td>".date("h:i A", $fecha)."</td>";
								$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
								$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
								$return .= "<td>$duracion</td>";
								$return .= "<td nowrap>$sintoma</td>";
								/*$return .= "<td>Tiempo</td>";
								$return .= "<td>Termino Interv.-Inicio Operación</td>";
								$return .= "<td>DeltaS3</td>";
								$return .= "<td>".$this->getDelta($newkey)."</td>";
								$return .= "<td>$supervisor</td>";
								$return .= "<td nowrap>$tecnico_principal</td>";
								$return .= "<td nowrap>$status</td>";*/
								$return .= "</tr>";
								$fecha = $time_termino;
							}
						}					
					}
					return $return;
				} else {
					$time_anterior = strtotime($json['fecha_termino_g']);
					return $return . $this->desplegar_hijo_base_report($intervencion["Planificacion"]["hijo"], $time_anterior, $codigo_zero);
				}
			} else {
				return "";
			}
		}
		
		public function desplegar_hijo_base_report_xls($codigo_hijo = "", $time_anterior = 0, $codigo_zero = 0, $cambio_modulo = "-", $evento_finalizado = "-") {
			$this->loadModel('Planificacion');
			$string = "";
			$intervencion = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.*', 'Unidad.unidad'),
													'conditions' => array(
													'Planificacion.padre' => $codigo_hijo,
													'estado IN (4, 5, 6,10)'),
													'recursive' => 1));
			$intervencion_zero = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.*', 'Faena.nombre', 'Flota.nombre'),
													'conditions' => array(
													'Planificacion.id' => $codigo_zero),
													'recursive' => 1));
													
			
													
			$motivo_llamado = "-";
			$lugar_reparacion = "-";
			$json_zero = json_decode($intervencion_zero["Planificacion"]["json"], true);
			
			switch(strtoupper(@$json_zero["motivo_llamado"])) {
			case 'OP':
				$motivo_llamado = 'Operador';
				break;
			case 'FC':
				$motivo_llamado = 'Alarma';
				break;
				default:
				$motivo_llamado = '-';
				break;
			}
			
			switch(strtoupper(@$json_zero["lugar_reparacion"])) {
				case 'TA':
					$lugar_reparacion = 'Taller';
					break;
				case 'TE':
					$lugar_reparacion = 'Terreno';
					break;
					default:
				$lugar_reparacion = '-';
				break;
			}
			
			$return = "";
			
			if (count($intervencion) > 0) {
				$json = json_decode($intervencion["Planificacion"]["json"], true);
				if($intervencion["Planificacion"]["estado"]==10){
					$time_anterior = strtotime($json['fecha_inicio_g']);
					return $return . $this->desplegar_hijo_base_report_xls($intervencion["Planificacion"]["hijo"], $time_anterior, $codigo_zero, $cambio_modulo, $evento_finalizado);
				}
			
				//$json = json_decode($intervencion["Planificacion"]["json"], true);
				
				$horometro_inicial = @$json["horometro_cabina"];
				$horometro_final = 0;
				if (isset($json["horometro_pm"]))
					$horometro_final = $json["horometro_pm"];
				elseif (isset($json["horometro_final"]))
					$horometro_final = $json["horometro_final"];
				elseif(isset($json["horometro_cabina"]))
					$horometro_final = $json["horometro_cabina"];
					
				
				if ($horometro_inicial=='' && isset($json_zero["horometro_pm"]))
					$horometro_inicial = $json_zero["horometro_pm"];
				elseif ($horometro_inicial=='' && isset($json_zero["horometro_final"]))
					$horometro_inicial = $json_zero["horometro_final"];
				elseif($horometro_inicial=='')
					$horometro_inicial = $json_zero["horometro_cabina"];
					
				$turno = strtoupper(@$json["turno"]);
				$periodo = "";
				
				switch(strtoupper(@$json["periodo"])) {
					case 'D':
						$periodo = 'Día';
						break;
					case 'N':
						$periodo = 'Noche';
						break;
					default:
						$periodo = '-';
						break;
				}
				
					
				$tecnico_principal = is_numeric(@$json["tecnico_principal"]) ? $this->getTecnicoRut($json["tecnico_principal"]) : "";
				$tecnico_2 =  is_numeric(@$json["tecnico_2"])?$this->getTecnicoRut(@$json["tecnico_2"]):"";
				$tecnico_3 =  is_numeric(@$json["tecnico_3"])?$this->getTecnicoRut(@$json["tecnico_3"]):"";
				$tecnico_4 =  is_numeric(@$json["tecnico_4"])?$this->getTecnicoRut(@$json["tecnico_4"]):"";
				$tecnico_5 =  is_numeric(@$json["tecnico_5"])?$this->getTecnicoRut(@$json["tecnico_5"]):"";
				$tecnico_6 =  is_numeric(@$json["tecnico_6"])?$this->getTecnicoRut(@$json["tecnico_6"]):"";
				//$supervisor = $this->getTecnico($intervencion['Planificacion']['aprobador_id']);
				$aprobador = $this->getTecnico($intervencion['Planificacion']['aprobador_id']);
				$supervisor = $this->getTecnico(@$json['supervisor_d']);
				//$supervisor = $this->getTecnico($intervencion['Planificacion']["aprobador_id"]);
				/*$status = "";
				switch($intervencion['Planificacion']['estado']) {
					case '4':
						$status = "Aprobado DCC";
						break;
					case '5':
						$status = "Aprobado KCH";
						break;
					case '6':
						$status = "Rechazado KCH";
						break;
				}*/
				
				
				
				// Delta Supervisor 1 y 2
				
				$esn = "";
				if (@$json["esn_nuevo"] != "") {
					$esn = @$json["esn_nuevo"];
				} elseif (@$json["esn"] != "") {
					$esn = @$json["esn"];
				} else {
					$esn = $intervencion['Planificacion']['esn'];
				}
				if (!is_numeric($esn)) {
					$esn = $intervencion_zero['Planificacion']['esn'];
				}
				
				$esn_ = $this->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$this->getMotor($intervencion['Planificacion']['unidad_id']),$this->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
				$esn = $esn_;
				if($esn_==''){
					if (@$json["esn_conexion"] != "") {
						$esn =  @$json["esn_conexion"];
					} elseif (@$json["esn_nuevo"] != "") {
						$esn = @$json["esn_nuevo"];
					} elseif (@$json["esn"] != "") {
						$esn =  @$json["esn"];
					} else {
						$esn =  $intervencion['Planificacion']['esn'];
					}
					
					if (is_numeric($esn)) {
						$esn = $esn;
					} else {
						$esn  = $this->esnPadre($intervencion['Planificacion']['padre']);
					}
				}
				
				$sintoma ="-";
				$categoria_sintoma = "-";
				if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
					if (isset($json["tipo_programado"])) {
						if ($json["tipo_programado"] == "1500") {
							//$return .= "<td>Overhaul</td>";
							$sintoma = "Overhaul";
						} else {
							//$return .= "<td>{$json["tipo_programado"]}</td>";
							$sintoma = $json["tipo_programado"];
						}	
					} else {					
						if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
							//$return .= "<td>Overhaul</td>";
							$sintoma = "Overhaul";
						} else {
							//$return .= "<td>{$intervencion['Planificacion']['tipomantencion']}</td>";
							$sintoma = $intervencion['Planificacion']['tipomantencion'];
						}
					}
				} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
					if (@$intervencion['Planificacion']['backlog_id'] != null) {
						//$return .= "<td>".$this->getBacklog($intervencion['Planificacion']['backlog_id'])."</td>";
						$sintoma = $this->getBacklog($intervencion['Planificacion']['backlog_id']);
					}
				} else {
					//$return .= "<td>".$this->getSintoma($intervencion['Planificacion']['sintoma_id'])."</td>";
					$sintoma = $this->getSintoma($intervencion['Planificacion']['sintoma_id']);
					$categoria_sintoma = $this->getCategoria($intervencion['Planificacion']['sintoma_id']);
				}
				
				/*if (strlen($sintoma) > 30) {
					$sintoma = trim(substr($sintoma, 0, 28)) . '...';	
				}*/
				
				//$cambio_modulo = "-";
				//$evento_finalizado = "-";
				
				$string = "<td>{$intervencion_zero['Planificacion']['id']}</td>
							<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>";
				
				if (isset($intervencion_zero['Planificacion']['fecha_termino'])) {
					$fecha = strtotime($intervencion_zero['Planificacion']['fecha_termino']);
					foreach($json as $key => $value) {
						if (substr($key, 0, 4) == "ds1_" && substr($key, -2) == "_r" || substr($key, 0, 4) == "ds2_" && substr($key, -2) == "_r") {
							$newkey = substr($key, 0, -2);
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if ($hora == 0 && $minuto == 0) {
								continue;
							}
							$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
							$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
							$time_termino = $fecha + $hora * 60 * 60 + $minuto * 60;
							$fecha_termino = $time_termino;
							$return .= "<tr style=\"background-color:#DDEBF7;\">";
							$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
							<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>$esn</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							$string .= "<td>Intervención</td>";
							if ($value == 1) {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}elseif ($value == 2) {
								$return .=  "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							}elseif ($value == 3) {
								$return .=  "<td>T_Mina</td>";
								$return .= "<td>Mina</td>";
							}
							if (substr($key, 0, 4) == "ds1_") {
								$return .=  "<td>Tiempo</td>";
							} else {
								$return .=  "<td>Traslado</td>";
							}
							$return .=  "<td>".date("d-m-Y", $fecha)."</td>";
							$return .=  "<td>".date("h:i A", $fecha)."</td>";
							$return .=  "<td>".date("d-m-Y", $fecha_termino)."</td>";
							$return .=  "<td>".date("h:i A", $fecha_termino)."</td>";
							$return .=  "<td>$duracion</td>";
							$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
							$return .=  "<td>Tiempo</td>";
							
							if (substr($key, 0, 4) == "ds1_") {
								$return .=  "<td>Termino Interv.-Inicio Interv.</td>";
								$return .=  "<td>DeltaS1</td>";
							} else {
								$return .=  "<td>Sol. Traslado-Inicio Interv.</td>";
								$return .=  "<td>DeltaS2</td>";
							}
							$return .=  "<td>-</td>";
							$return .=  "<td nowrap>".$this->getDelta($newkey)."</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
							$return .=  "<td nowrap>$lugar_reparacion</td>";
							
							$return .=  "<td nowrap>$tecnico_principal</td>";
							$return .=  "<td nowrap>$tecnico_2</td>";
							$return .=  "<td nowrap>$tecnico_3</td>";
							$return .=  "<td nowrap>$tecnico_4</td>";
							$return .=  "<td nowrap>$tecnico_5</td>";
							$return .=  "<td nowrap>$tecnico_6</td>";
							
							$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
							$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
							$return .= "<td>".(@$json[$newkey."_o"])."</td>";
							$return .=  "</tr>";
							$fecha = $time_termino;
						}
					}					
				}
			
				$fecha = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
				$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);
				$diff = $fecha_termino->diff($fecha);
				$tiempo_trabajo = $diff->h + $diff->i / 60.0;
				$tiempo_trabajo = $tiempo_trabajo + ($diff->days*24);
				$tiempo_trabajo = number_format($tiempo_trabajo, 2, ",",".");
				
				
				
				$return .= "<tr style=\"background-color: #808080; color: white;\">";
				$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
							<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>";
				
						
							
				$return .= "<td>$esn</td>
				<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
							<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
				$return .= "<td>-</td>";
				$return .= "
				<td>Continuacion</td>
				<td>{$fecha->format('d-m-Y')}</td>
				<td>{$fecha->format('h:i A')}</td>
				<td>{$fecha_termino->format('d-m-Y')}</td>
				<td>{$fecha_termino->format('h:i A')}</td>
				<td>{$tiempo_trabajo}</td>";
				$return .=  "<td nowrap>$motivo_llamado</td>";
				$return .=  "<td nowrap>$categoria_sintoma</td>";
				$return .=  "<td nowrap>$sintoma</td>";
				$return .=  "";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .= "<td>-</td>";
				$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
				$return .=  "<td nowrap>$lugar_reparacion</td>";
				$return .=  "<td nowrap>$tecnico_principal</td>";
				$return .=  "<td nowrap>$tecnico_2</td>";
				$return .=  "<td nowrap>$tecnico_3</td>";
				$return .=  "<td nowrap>$tecnico_4</td>";
				$return .=  "<td nowrap>$tecnico_5</td>";
				$return .=  "<td nowrap>$tecnico_6</td>";
				$return .=  "<td nowrap>$supervisor</td>";
				$return .= "<td nowrap>$turno</td>";
				$return .= "<td nowrap>$periodo</td>";
				$return .=  "<td>{$aprobador}</td>";
				$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				if(@$json["comentario"]!=""){
					$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
				}elseif(@$json["comentarios"]!=""){
					$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
				}
				
				
				$return .= "</tr>";
				
				/* Deltas */
				$time = strtotime($fecha->format('d-m-Y h:i A'));
				/*if (isset($json["llamado_fecha"]) && isset($json["llegada_fecha"])) {
					$inicio = strtotime($json["llamado_fecha"] . ' ' . $json["llamado_hora"] . ':'.$json["llamado_min"]. ' ' .$json["llamado_periodo"]);
					$termino = strtotime($json["llegada_fecha"] . ' ' . $json["llegada_hora"] . ':'.$json["llegada_min"]. ' ' .$json["llegada_periodo"]);
					$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
					if (($termino - $inicio) / 60 == 15) {
						$time = $termino;
						$return .= '<tr style="background-color:#F2F2F2;">';
						$return .= "
							<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
						if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'EX') {
							$return .= "<td>T_OEM</td>";
							$return .= "<td>OEM</td>";
						} else {
							$return .= "<td>T_DCC</td>";
							$return .= "<td>DCC</td>";
						}
						$return .=  "<td nowrap>$motivo_llamado</td>";
						$return .= "<td>Tiempo</td>";	
						$return .= "<td nowrap>$sintoma</td>";
						$return .= "<td nowrap>".date('d-m-Y', $inicio)."</td>";
						$return .= "<td nowrap>".date('h:i A', $inicio)."</td>";
						$return .= "<td nowrap>".date('d-m-Y', $termino)."</td>";
						$return .= "<td nowrap>".date('h:i A', $termino)."</td>";
						$return .= "<td>$duracion</td>";
					
						$return .= "<td>Tiempo</td>";
						$return .= "<td nowrap>Llamado-Lugar Fisico</td>";
						$return .= "<td>Delta1</td>";
						$return .= "<td>Espera Intervención</td>";
						
						$return .=  "<td nowrap>$lugar_reparacion</td>";
						$return .=  "<td nowrap>$supervisor</td>";
						$return .=  "<td nowrap>$tecnico_principal</td>";
						$return .=  "<td nowrap>$tecnico_2</td>";
						$return .=  "<td nowrap>$tecnico_3</td>";
						$return .=  "<td nowrap>$tecnico_4</td>";
						$return .=  "<td nowrap>$tecnico_5</td>";
						$return .=  "<td nowrap>$tecnico_6</td>";
						$return .=  "<td>$horometro_inicial</td>";
							$return .=  "<td>$horometro_final</td>";
						$return .=  "<td>{$aprobador}</td>";
						$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
						$return .= "</tr>";
					}
				}*/
				
				$delta_1 = array('d_traslado_dcc_h','d_traslado_oem_h','d_tronadura_h','d_clima_h','d_logistica_dcc_h','d_logistica_oem_h','d_personal_h');
				$delta_2 = array('d2_cliente_h','d_oem_h','d_zona_segura_h','d_clima_inter_h','d_charla_h','d_tronadura_inter_h');
				// Despliegue deltas
				if (is_array($json) && count($json) > 0) {
					foreach($json as $key => $value) {
						if (substr($key, 0, 2) == "d_" && substr($key, -2) == "_r") {
							if ($value == "" || intval($value) == 0) {
								continue;
							}
							$newkey = substr($key, 0, -2);
							if (!in_array($newkey."_h", $delta_1)) {
								continue;
							}
							
							if (in_array($newkey."_h", $delta_1) && $intervencion['Planificacion']['tipointervencion'] != 'EX' && $intervencion['Planificacion']['tipointervencion'] != 'RI') {
								continue;
							}
							
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if ($hora == 0 && $minuto == 0) {
								continue;
							}
							$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
							$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
							$return .= "<tr style=\"background-color:#F2F2F2;\">";
							$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							if ($value == 1) {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}elseif ($value == 2) {
								$return .= "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							}elseif ($value == 3) {
								$return .= "<td>T_Mina</td>";
								$return .= "<td>Mina</td>";
							}
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time)."</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time_termino)."</td>";
							$return .= "<td>$duracion</td>";
							$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
							$return .= "<td>Tiempo</td>";
							if (in_array($newkey."_h", $delta_1)) {
								$return .= "<td nowrap>Llamado-Lugar Fisico</td>";
								$return .= "<td>Delta1</td>";
							}
							$return .=  "<td>-</td>";
							$return .= "<td nowrap>".$this->getDelta($newkey)."</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
							$return .=  "<td nowrap>$lugar_reparacion</td>";
							
							$return .=  "<td nowrap>$tecnico_principal</td>";
							$return .=  "<td nowrap>$tecnico_2</td>";
							$return .=  "<td nowrap>$tecnico_3</td>";
							$return .=  "<td nowrap>$tecnico_4</td>";
							$return .=  "<td nowrap>$tecnico_5</td>";
							$return .=  "<td nowrap>$tecnico_6</td>";
							$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
							$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
							$return .= "<td>".(@$json[$newkey."_o"])."</td>";
							$return .= "</tr>";
							$time = $time_termino;
						}
					}
					
					// Cuando tiempo entre incio de intervencion y llegada es igual a 15 m.
					/*if (isset($json["llegada_fecha"]) && isset($json["i_i_f"])) {
						$inicio = strtotime($json["llegada_fecha"] . ' ' . $json["llegada_hora"] . ':'.$json["llegada_min"]. ' ' .$json["llegada_periodo"]);
						$termino = strtotime($json["i_i_f"] . ' ' . $json["i_i_h"] . ':'.$json["i_i_m"]. ' ' .$json["i_i_p"]);
						$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
						if (($termino - $inicio) / 60 == 15) {
							$time = $termino;
							$return .= '<tr style="background-color:#F2F2F2;">';
							$return .= "
								<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
								<td>{$intervencion_zero['Faena']['nombre']}</td>
								<td>{$intervencion_zero['Flota']['nombre']}</td>
								<td>{$intervencion['Unidad']['unidad']}</td>
								<td>{$intervencion['Planificacion']['esn']}</td>
								<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'EX') {
								$return .= "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							} else {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}
							$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>$sintoma</td>";
							$return .= "<td nowrap>".date('d-m-Y', $inicio)."</td>";
							$return .= "<td nowrap>".date('h:i A', $inicio)."</td>";
							$return .= "<td nowrap>".date('d-m-Y', $termino)."</td>";
							$return .= "<td nowrap>".date('h:i A', $termino)."</td>";
							$return .= "<td>$duracion</td>";
							
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>Llamado-Lugar Fisico</td>";
							$return .= "<td>Delta1</td>";
							$return .= "<td>Espera Intervención</td>";
							
							$return .=  "<td nowrap>$lugar_reparacion</td>";
							$return .=  "<td nowrap>$supervisor</td>";
							$return .=  "<td nowrap>$tecnico_principal</td>";
							$return .=  "<td nowrap>$tecnico_2</td>";
							$return .=  "<td nowrap>$tecnico_3</td>";
							$return .=  "<td nowrap>$tecnico_4</td>";
							$return .=  "<td nowrap>$tecnico_5</td>";
							$return .=  "<td nowrap>$tecnico_6</td>";
							$return .=  "<td>$horometro_inicial</td>";
							$return .=  "<td>$horometro_final</td>";
							$return .=  "<td>{$aprobador}</td>";
							$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
							$return .= "</tr>";
						}
					}*/
					
					foreach($json as $key => $value) {
						if (substr($key, 0, 2) == "d_" && substr($key, -2) == "_r" || substr($key, 0, 3) == "d2_" && substr($key, -2) == "_r") {
							if ($value == "" || intval($value) == 0) {
								continue;
							}
							$newkey = substr($key, 0, -2);
							if (!in_array($newkey."_h", $delta_2)) {
								continue;
							}
							
							if (in_array($newkey."_h", $delta_2) && $intervencion['Planificacion']['tipointervencion'] != 'EX' && $intervencion['Planificacion']['tipointervencion'] != 'RI') {
							continue;
						}
							
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if ($hora == 0 && $minuto == 0) {
								continue;
							}
							$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
							$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
							$return .= "<tr style=\"background-color:#F2F2F2;\">";
							$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							if ($value == 1) {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}elseif ($value == 2) {
								$return .= "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							}elseif ($value == 3) {
								$return .= "<td>T_Mina</td>";
								$return .= "<td>Mina</td>";
							}
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time)."</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time_termino)."</td>";
							$return .= "<td>$duracion</td>";
							$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
							$return .= "<td>Tiempo</td>";
							if (in_array($newkey."_h", $delta_2)) {
								$return .= "<td nowrap>Lugar Fisico-Inicio Interv.</td>";
								$return .= "<td>Delta2</td>";
							}
							$return .=  "<td>-</td>";
							$return .= "<td nowrap>".$this->getDelta($newkey)."</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							$return .=  "<td>-</td>";
							//$return .= "<td nowrap>$turno</td>";
							//$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
							$return .=  "<td nowrap>$lugar_reparacion</td>";
							//$return .=  "<td nowrap>$supervisor</td>";
							$return .=  "<td nowrap>$tecnico_principal</td>";
							$return .=  "<td nowrap>$tecnico_2</td>";
							$return .=  "<td nowrap>$tecnico_3</td>";
							$return .=  "<td nowrap>$tecnico_4</td>";
							$return .=  "<td nowrap>$tecnico_5</td>";
							$return .=  "<td nowrap>$tecnico_6</td>";
							
							$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
							$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
							$return .= "<td>".(@$json[$newkey."_o"])."</td>";
							$return .= "</tr>";
							$time = $time_termino;
						}
					}
					
					foreach($json as $key => $value) {
						if (substr($key, 0, 2) == "d_" && substr($key, -2) == "_r" || substr($key, 0, 3) == "d2_" && substr($key, -2) == "_r" || substr($key, 0, 3) == "d3_" && substr($key, -2) == "_r"/* || substr($key, 0, 3) == "d4_" && substr($key, -2) == "_r"*/) {
							if ($value == "" || intval($value) == 0) {
								continue;
							}
							$newkey = substr($key, 0, -2);
							if (in_array($newkey."_h", $delta_1) || in_array($newkey."_h", $delta_2))  {
								continue;
							}
							
							if (substr($newkey, 0, 3) == "d3_" && $intervencion['Planificacion']['tipointervencion']=='EX') {
								continue;
							}
							
							if (substr($newkey, 0, 3) == "d4_" && $intervencion['Planificacion']['tipointervencion']=='EX') {
								continue;
							}
							
							if ($newkey != "d3_repydiag") {
								$hora = intval($json[$newkey."_h"]);
								$minuto = intval($json[$newkey."_m"]);
								if ($hora == 0 && $minuto == 0) {
									continue;
								}
								$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
								$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
								$return .= "<tr style=\"background-color:#F2F2F2;\">";
								$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
								<td>{$intervencion_zero['Faena']['nombre']}</td>
								<td>{$intervencion_zero['Flota']['nombre']}</td>
								<td>{$intervencion['Unidad']['unidad']}</td>
								<td>{$intervencion['Planificacion']['esn']}</td>
								<td>$horometro_inicial</td>
							<td>$horometro_final</td>
								<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
								if ($value == 1) {
									$return .= "<td>T_DCC</td>";
									$return .= "<td>DCC</td>";
								}elseif ($value == 2) {
									$return .= "<td>T_OEM</td>";
									$return .= "<td>OEM</td>";
								}elseif ($value == 3) {
									$return .= "<td>T_Mina</td>";
									$return .= "<td>Mina</td>";
								}
								$return .= "<td>Tiempo</td>";
								$return .= "<td nowrap>".date('d-m-Y', $time)."</td>";
								$return .= "<td nowrap>".date('h:i A', $time)."</td>";
								$return .= "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
								$return .= "<td nowrap>".date('h:i A', $time_termino)."</td>";
								$return .= "<td>$duracion</td>";
								$return .=  "<td nowrap>$motivo_llamado</td>";
								$return .=  "<td nowrap>$categoria_sintoma</td>";
								$return .=  "<td nowrap>$sintoma</td>";
								$return .= "<td>Tiempo</td>";
								if (substr($newkey, 0, 3) == "d3_") {
									$return .= "<td nowrap>Inicio Interv-Termino Interv.</td>";
									$return .= "<td>Delta3</td>";
								}
								if (substr($newkey, 0, 3) == "d4_") {
									$return .= "<td nowrap>Término Interv-Inicio P.P.</td>";
									$return .= "<td>Delta4</td>";
								}
								$return .=  "<td>-</td>";
								$return .= "<td nowrap>".$this->getDelta($newkey)."</td>";
								$return .=  "<td>-</td>";
								$return .=  "<td>-</td>";
								$return .=  "<td>-</td>";
								$return .=  "<td>-</td>";
								//$return .= "<td nowrap>$turno</td>";
							//$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
								$return .=  "<td nowrap>$lugar_reparacion</td>";
							//$return .=  "<td nowrap>$supervisor</td>";
								$return .=  "<td nowrap>$tecnico_principal</td>";
								$return .=  "<td nowrap>$tecnico_2</td>";
								$return .=  "<td nowrap>$tecnico_3</td>";
								$return .=  "<td nowrap>$tecnico_4</td>";
								$return .=  "<td nowrap>$tecnico_5</td>";
								$return .=  "<td nowrap>$tecnico_6</td>";
								$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
								$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
								$return .= "<td>".(@$json[$newkey."_o"])."</td>";
								$return .= "</tr>";	
							}
				
							//$time = $time_termino;
							
							$time_elementos = $time;
							if ($newkey == "d3_repydiag") {
								if (isset($json["ele_cantidad"]) && is_numeric($json["ele_cantidad"])) {
									// Despliegue elementos
									for ($i = 1; $i <= intval($json["ele_cantidad"]); $i++) {
										if (isset($json["elemento_$i"])) {
											if (!isset($json["d3_elemento_h_$i"]) && !isset($json["d3_elemento_m_$i"]))
												continue;
											/*	$json["d3_elemento_h_$i"] = "0";
											if (!isset($json["d3_elemento_m_$i"]))
												$json["d3_elemento_m_$i"] = "0";*/
											$elemento = split(",", $json["elemento_$i"]);
											$hora = intval($json["d3_elemento_h_$i"]);
											$minuto = intval($json["d3_elemento_m_$i"]);
											if ($hora == 0 && $minuto == 0) {
												//continue;
											}
											$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
											$time_elementos_termino = $time_elementos + $hora * 60 * 60 + $minuto * 60;
											$return .= "<tr style=\"background-color:#FFFFFF;\">";
											$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
												<td>{$intervencion_zero['Faena']['nombre']}</td>
												<td>{$intervencion_zero['Flota']['nombre']}</td>
												<td>{$intervencion['Unidad']['unidad']}</td>
												<td>{$intervencion['Planificacion']['esn']}</td>
												<td>$horometro_inicial</td>
							<td>$horometro_final</td>
												<td>{$intervencion['Planificacion']['tipointervencion']}</td>
												<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
											$return .= "<td>DCC</td>";
											$return .= "<td>Intervención</td>";
											$return .= "<td nowrap>".date('d-m-Y', $time_elementos)."</td>";
											$return .= "<td nowrap>".date('h:i A', $time_elementos)."</td>";
											$return .= "<td nowrap>".date('d-m-Y', $time_elementos_termino)."</td>";
											$return .= "<td nowrap>".date('h:i A', $time_elementos_termino)."</td>";
											$return .= "<td>$duracion</td>";
											$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
											$return .= "<td nowrap>{$this->getSistema($elemento[0])}</td>";
											$return .= "<td nowrap>{$this->getSubsistema($elemento[1])}</td>";
											$return .= "<td nowrap>{$this->getSubsistemaPosicion($elemento[2])}</td>";
											$return .= "<td nowrap>{$elemento[9]} </td>";
											$return .= "<td nowrap>{$this->getElemento($elemento[3])}</td>";
											$return .= "<td nowrap>{$this->getElementoPosicion($elemento[4])}</td>";
											//if ($elemento[8] == "1") {
												$return .= "<td nowrap>{$this->getDiagnostico($elemento[6])}</td>";
											//} else {
											//	$return .= "<td nowrap>-</td>";
											//}
											$return .= "<td nowrap>{$this->getSolucion($elemento[7])}</td>";
											$return .= "<td nowrap>{$this->getCausa($elemento[8])}</td>";
											//$return .= "<td nowrap>$turno</td>";
							//$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
											$return .=  "<td nowrap>$lugar_reparacion</td>";
								//			$return .=  "<td nowrap>$supervisor</td>";
											$return .=  "<td nowrap>$tecnico_principal</td>";
											$return .=  "<td nowrap>$tecnico_2</td>";
											$return .=  "<td nowrap>$tecnico_3</td>";
											$return .=  "<td nowrap>$tecnico_4</td>";
											$return .=  "<td nowrap>$tecnico_5</td>";
											$return .=  "<td nowrap>$tecnico_6</td>";
											$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
											$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
											if(@$json["comentario"]!=""){
												$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
											}elseif(@$json["comentarios"]!=""){
												$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
											}
											$return .= "</tr>";
											$time_elementos = $time_elementos_termino;
										}
									}
								}
							}
							$time = $time_elementos;
						}
					}
				}
				
				// Cuando delta 4 es igual a 15m.
				
				// 
				if ((strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'EX' || strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'MP') && isset($json["i_i_f"]) && isset($json["i_t_f"])) {
					$inicio = strtotime($json["i_i_f"] . ' ' . $json["i_i_h"] . ':'.$json["i_i_m"]. ' ' .$json["i_i_p"]);
					$termino = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
					$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
					$return .= '<tr style="background-color:#F2F2F2;">';
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
						<td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion_zero['Faena']['nombre']}</td>
						<td>{$intervencion_zero['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
							<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'EX') {
						$return .= "<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
						$return .= "<td>OEM</td>";
					} else {
						$return .= "<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
						$return .= "<td>DCC</td>";
					}
					$return .= "<td>Intervención</td>";
					$return .= "<td nowrap>".date('d-m-Y', $inicio)."</td>";
					$return .= "<td nowrap>".date('h:i A', $inicio)."</td>";
					$return .= "<td nowrap>".date('d-m-Y', $termino)."</td>";
					$return .= "<td nowrap>".date('h:i A', $termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td nowrap>Inicio Interv-Termino Interv.</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
				//	$return .= "<td nowrap>$turno</td>";
				//			$return .= "<td nowrap>$periodo</td>";
				$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
				//	$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
					$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
					$return .= "</tr>";
				}
				
				if (isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"]) && isset($json["solicitud_f"]) && isset($json["solicitud_h"]) && isset($json["solicitud_m"]) && isset($json["solicitud_p"])) {
					$fecha = strtotime($json["i_t_f"] . " " . $json["i_t_h"].":".$json["i_t_m"]." ".$json["i_t_p"]);
					$fecha_termino = strtotime($json["solicitud_f"] . " " . $json["solicitud_h"].":".$json["solicitud_m"]." ".$json["solicitud_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
						<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
					$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Solicitud Traslado</td>";
					$return .= "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .= "<td>Espera Traslado</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
				//	$return .= "<td nowrap>$turno</td>";
					//		$return .= "<td nowrap>$periodo</td>";
					$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
				//	$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
					$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
					$return .= "</tr>";
				}
				
				// Espera de Desconexión
				if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"])) {
					$fecha = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
					$fecha_termino = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
					$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Cambio de Modulo</td>";
					$return .= "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .= "<td>Espera Desconexion</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					//$return .= "<td nowrap>$turno</td>";
				//			$return .= "<td nowrap>$periodo</td>";
				$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
				//	$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
				$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
					$return .= "</tr>";
				}
				
				// Registro de Desconexion
				if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
					$fecha = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
					$fecha_termino = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td>
						<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";					
					$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Cambio de Modulo</td>";
					$return .= "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .= "<td>Desconexion</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
				//	$return .= "<td nowrap>$turno</td>";
					//		$return .= "<td nowrap>$periodo</td>";
					$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
				//	$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
					$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
					$return .= "</tr>";
				}
				// Espera de conexion
				if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
					$fecha = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
					$fecha_termino = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";

					$return .= "<td>Tiempo</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";					
					$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Cambio de Modulo</td>";
					$return .= "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .= "<td>Espera Conexion</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
				//	$return .= "<td nowrap>$turno</td>";
				//			$return .= "<td nowrap>$periodo</td>";
				$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
				//	$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
					$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
					$return .= "</tr>";
				}
				
				// Registro de Conexion
				if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
					$fecha = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
					$fecha_termino = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";					
					$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Cambio de Modulo</td>";
					$return .= "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .= "<td>Conexion</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					//$return .= "<td nowrap>$turno</td>";
						//	$return .= "<td nowrap>$periodo</td>";
						$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
					//$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
					$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
					$return .= "</tr>";
				}
				// Espera de Puesta en Marcha
				if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
					$fecha = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
					$fecha_termino = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";					
					$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Puesta en Marcha</td>";
					$return .= "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .= "<td>Espera Puesta en Marcha</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
				//	$return .= "<td nowrap>$turno</td>";
				//			$return .= "<td nowrap>$periodo</td>";
				$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
				//	$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
					$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
					$return .= "</tr>";
				}
				
				// Registro de Puesta en Marcha
				if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["pm_t_f"]) && isset($json["pm_t_h"]) && isset($json["pm_t_m"]) && isset($json["pm_t_p"])) {
					$fecha = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
					$fecha_termino = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"].":".$json["pm_t_m"]." ".$json["pm_t_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .= "<td>$duracion</td>";
					$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
					$return .= "<td>00_Motor completo</td>";
					$return .= "<td>Puesta en Marcha</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
					$return .= "<td>-</td>";
				//	$return .= "<td nowrap>$turno</td>";
				//			$return .= "<td nowrap>$periodo</td>";
				$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
				//	$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
					$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
					$return .= "</tr>";
				}
				
				// Delta4
				if (isset($json["i_t_f"])) {
					$time = strtotime($json["i_t_f"] . " " . $json["i_t_h"].":".$json["i_t_m"]." ".$json["i_t_p"]);
					foreach($json as $key => $value) {
						if (substr($key, 0, 3) == "d4_" && substr($key, -2) == "_r") {
							if ($value == "" || intval($value) == 0) {
								continue;
							}
							$newkey = substr($key, 0, -2);
							
							if (substr($newkey, 0, 3) == "d4_" && $intervencion['Planificacion']['tipointervencion']=='EX') {
								continue;
							}
							
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if ($hora == 0 && $minuto == 0) {
								continue;
							}
							$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
							$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
							$return .= "<tr style=\"background-color:#F2F2F2;\">";
							$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
							if ($value == 1) {
								$return .= "<td>T_DCC</td>";
								$return .= "<td>DCC</td>";
							}elseif ($value == 2) {
								$return .= "<td>T_OEM</td>";
								$return .= "<td>OEM</td>";
							}elseif ($value == 3) {
								$return .= "<td>T_Mina</td>";
								$return .= "<td>Mina</td>";
							}
							$return .= "<td>Tiempo</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time)."</td>";
							$return .= "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
							$return .= "<td nowrap>".date('h:i A', $time_termino)."</td>";
							$return .= "<td>$duracion</td>";
$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";
							$return .= "<td>-</td>";
							$return .= "<td>-</td>";
							$return .= "<td>-</td>";
							$return .= "<td>-</td>";
							$return .= "<td>-</td>";
							$return .= "<td>-</td>";
							$return .= "<td>-</td>";
							$return .= "<td>-</td>";
							$return .= "<td>-</td>";
							//$return .= "<td nowrap>$turno</td>";
							//$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
							$return .=  "<td nowrap>$lugar_reparacion</td>";
						//	$return .=  "<td nowrap>$supervisor</td>";
							$return .=  "<td nowrap>$tecnico_principal</td>";
							$return .=  "<td nowrap>$tecnico_2</td>";
							$return .=  "<td nowrap>$tecnico_3</td>";
							$return .=  "<td nowrap>$tecnico_4</td>";
							$return .=  "<td nowrap>$tecnico_5</td>";
							$return .=  "<td nowrap>$tecnico_6</td>";
							$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
							$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
							if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
					}
							$return .= "</tr>";	
						}
					}
				}
				
				// Registro de Prueba de Potencia si es menor a 15m
				if (isset($json["pp_i_f"]) && isset($json["pp_i_h"]) && isset($json["pp_i_m"]) && isset($json["pp_i_p"]) && isset($json["pp_t_f"]) && isset($json["pp_t_h"]) && isset($json["pp_t_m"]) && isset($json["pp_t_p"])) {
					$fecha = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"].":".$json["pp_i_m"]." ".$json["pp_i_p"]);
					$fecha_termino = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"].":".$json["pp_t_m"]." ".$json["pp_t_p"]);
					$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion_zero['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion_zero['Faena']['nombre']}</td>
							<td>{$intervencion_zero['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
							<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>
						<td>C{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>DCC</td>";
					$return .= "<td>Intervención</td>";
					$return .= "<td>".date("d-m-Y", $fecha)."</td>";
					$return .= "<td>".date("h:i A", $fecha)."</td>";
					$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .=  "<td>$duracion</td>";
$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";					
					$return .=  "<td>00_Motor completo</td>";
					$return .=  "<td>Prueba Potencia</td>";
					$return .=  "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .=  "<td>Prueba Potencia</td>";
					$return .=  "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .=  "<td>-</td>";
					$return .=  "<td>-</td>";
				//	$return .= "<td nowrap>$turno</td>";
				//			$return .= "<td nowrap>$periodo</td>";
				$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
					$return .=  "<td nowrap>$lugar_reparacion</td>";
				//	$return .=  "<td nowrap>$supervisor</td>";
					$return .=  "<td nowrap>$tecnico_principal</td>";
					$return .=  "<td nowrap>$tecnico_2</td>";
					$return .=  "<td nowrap>$tecnico_3</td>";
					$return .=  "<td nowrap>$tecnico_4</td>";
					$return .=  "<td nowrap>$tecnico_5</td>";
					$return .=  "<td nowrap>$tecnico_6</td>";
					$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
					$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					$comentarios = "-";
					if(@$json["comentario"]!=""){
						$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
						$comentarios = @$json["comentario"];
					}elseif(@$json["comentarios"]!=""){
						$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
						$comentarios = @$json["comentarios"];
					}
					$return .= "</tr>";
				}
				
				$comentarios = "-";
				if(@$json["comentario"]!=""){
					$comentarios = @$json["comentario"];
				}elseif(@$json["comentarios"]!=""){
					$comentarios = @$json["comentarios"];
				}
				
				if (isset($json["f_a_motor"])&&is_numeric(@$json["f_a_motor"])&&@$json["f_a_motor"]!='0'&&intval(@$json["f_a_motor"])>0){
				$actividad = "-";
				if (@$json["f_a_motor_s"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["f_a_motor_s"]=="R"){
					$actividad = "Relleno";
				}
				
				$return .= "
				<tr>
					<td> {$intervencion_zero["Planificacion"]["id"]}</td>
					<td> {$intervencion["Planificacion"]["id"]}</td>
					<td nowrap> {$intervencion_zero["Faena"]["nombre"]}</td>
					<td nowrap> {$intervencion_zero["Flota"]["nombre"]}</td>
					<td> {$intervencion["Unidad"]["unidad"]}</td>
					<td> {$intervencion["Planificacion"]["esn"]}</td>
					<td nowrap> $horometro_inicial</td>
					<td nowrap> $horometro_final</td>
					<td> {$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td> C{$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td>-</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td nowrap> $motivo_llamado</td>
					<td nowrap> $categoria_sintoma</td>
					<td nowrap> $sintoma</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>Aceite Motor</td>
					<td> {$json["f_a_motor"]}</td>
					<td>litros</td>
					<td> $actividad</td>
					<td>-</td>
					<td> $cambio_modulo</td>
					<td> $evento_finalizado</td>
					<td nowrap> $lugar_reparacion</td>
					<td nowrap> $tecnico_principal</td>
					<td nowrap> $tecnico_2</td>
					<td nowrap> $tecnico_3</td>
					<td nowrap> $tecnico_4</td>
					<td nowrap> $tecnico_5</td>
					<td nowrap> $tecnico_6</td>
					<td nowrap> $supervisor</td>
					<td nowrap> $turno</td>
					<td nowrap> $periodo</td>
					<td> $aprobador</td>
					<td> {$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>
					<td nowrap> $comentarios</td>
				</tr>
				";
				}
				
				if (isset($json["f_a_reserva"])&&is_numeric(@$json["f_a_reserva"])&&@$json["f_a_reserva"]!='0'&&intval(@$json["f_a_reserva"])>0){
				$actividad = "-";
				if (@$json["f_a_reserva_s"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["f_a_reserva_s"]=="R"){
					$actividad = "Relleno";
				}
					$return .= "
				<tr>
					<td> {$intervencion_zero["Planificacion"]["id"]}</td>
					<td> {$intervencion["Planificacion"]["id"]}</td>
					<td nowrap> {$intervencion_zero["Faena"]["nombre"]}</td>
					<td nowrap> {$intervencion_zero["Flota"]["nombre"]}</td>
					<td> {$intervencion["Unidad"]["unidad"]}</td>
					<td> {$intervencion["Planificacion"]["esn"]}</td>
					<td nowrap> $horometro_inicial</td>
					<td nowrap> $horometro_final</td>
					<td> {$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td> C{$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td>-</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td nowrap> $motivo_llamado</td>
					<td nowrap> $categoria_sintoma</td>
					<td nowrap> $sintoma</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>Aceite Reserva</td>
					<td> {$json["f_a_reserva"]}</td>
					<td>litros</td>
					<td> $actividad</td>
					<td>-</td>
					<td> $cambio_modulo</td>
					<td> $evento_finalizado</td>
					<td nowrap> $lugar_reparacion</td>
					<td nowrap> $tecnico_principal</td>
					<td nowrap> $tecnico_2</td>
					<td nowrap> $tecnico_3</td>
					<td nowrap> $tecnico_4</td>
					<td nowrap> $tecnico_5</td>
					<td nowrap> $tecnico_6</td>
					<td nowrap> $supervisor</td>
					<td nowrap> $turno</td>
					<td nowrap> $periodo</td>
					<td> $aprobador</td>
					<td> {$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>
					<td nowrap> $comentarios</td>
				</tr>
				";
				
				}
				
				if (isset($json["f_refrigerante"])&&is_numeric(@$json["f_refrigerante"])&&@$json["f_refrigerante"]!='0'&&intval(@$json["f_refrigerante"])>0){
					$actividad = "-";
					if (@$json["f_refrigerante_s"]=="C"){
						$actividad = "Cambio";
					}elseif(@$json["f_refrigerante_s"]=="R"){
						$actividad = "Relleno";
					}
				$return .= "
				<tr>
					<td> {$intervencion_zero["Planificacion"]["id"]}</td>
					<td> {$intervencion["Planificacion"]["id"]}</td>
					<td nowrap> {$intervencion_zero["Faena"]["nombre"]}</td>
					<td nowrap> {$intervencion_zero["Flota"]["nombre"]}</td>
					<td> {$intervencion["Unidad"]["unidad"]}</td>
					<td> {$intervencion["Planificacion"]["esn"]}</td>
					<td nowrap> $horometro_inicial</td>
					<td nowrap> $horometro_final</td>
					<td> {$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td> C{$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td>-</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td nowrap> $motivo_llamado</td>
					<td nowrap> $categoria_sintoma</td>
					<td nowrap> $sintoma</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>Refrigerante</td>
					<td> {$json["f_refrigerante"]}</td>
					<td>litros</td>
					<td> $actividad</td>
					<td>-</td>
					<td> $cambio_modulo</td>
					<td> $evento_finalizado</td>
					<td nowrap> $lugar_reparacion</td>
					<td nowrap> $tecnico_principal</td>
					<td nowrap> $tecnico_2</td>
					<td nowrap> $tecnico_3</td>
					<td nowrap> $tecnico_4</td>
					<td nowrap> $tecnico_5</td>
					<td nowrap> $tecnico_6</td>
					<td nowrap> $supervisor</td>
					<td nowrap> $turno</td>
					<td nowrap> $periodo</td>
					<td> $aprobador</td>
					<td> {$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>
					<td nowrap> $comentarios</td>
				</tr>
				";
				}
				
				if (isset($json["f_combustible"])&&is_numeric(@$json["f_combustible"])&&@$json["f_combustible"]!='0'&&intval(@$json["f_combustible"])>0){
					$actividad = "-";
					if (@$json["f_combustible_s"]=="C"){
						$actividad = "Cambio";
					}elseif(@$json["f_combustible_s"]=="R"){
						$actividad = "Relleno";
					}
				$return .= "
				<tr>
					<td> {$intervencion_zero["Planificacion"]["id"]}</td>
					<td> {$intervencion["Planificacion"]["id"]}</td>
					<td nowrap> {$intervencion_zero["Faena"]["nombre"]}</td>
					<td nowrap> {$intervencion_zero["Flota"]["nombre"]}</td>
					<td> {$intervencion["Unidad"]["unidad"]}</td>
					<td> {$intervencion["Planificacion"]["esn"]}</td>
					<td nowrap> $horometro_inicial</td>
					<td nowrap> $horometro_final</td>
					<td> {$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td> C{$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td>-</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td nowrap> $motivo_llamado</td>
					<td nowrap> $categoria_sintoma</td>
					<td nowrap> $sintoma</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>Combustible</td>
					<td> {$json["f_combustible"]}</td>
					<td>litros</td>
					<td> $actividad</td>
					<td>-</td>
					<td> $cambio_modulo</td>
					<td> $evento_finalizado</td>
					<td nowrap> $lugar_reparacion</td>
					<td nowrap> $tecnico_principal</td>
					<td nowrap> $tecnico_2</td>
					<td nowrap> $tecnico_3</td>
					<td nowrap> $tecnico_4</td>
					<td nowrap> $tecnico_5</td>
					<td nowrap> $tecnico_6</td>
					<td nowrap> $supervisor</td>
					<td nowrap> $turno</td>
					<td nowrap> $periodo</td>
					<td> $aprobador</td>
					<td> {$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>
					<td nowrap> $comentarios</td>
				</tr>
				";
				}
				
				if (isset($json["f_zerex"])&&is_numeric(@$json["f_zerex"])&&@$json["f_zerex"]!='0'&&intval(@$json["f_zerex"])>0){
				$return .= "
				<tr>
					<td> {$intervencion_zero["Planificacion"]["id"]}</td>
					<td> {$intervencion["Planificacion"]["id"]}</td>
					<td nowrap> {$intervencion_zero["Faena"]["nombre"]}</td>
					<td nowrap> {$intervencion_zero["Flota"]["nombre"]}</td>
					<td> {$intervencion["Unidad"]["unidad"]}</td>
					<td> {$intervencion["Planificacion"]["esn"]}</td>
					<td nowrap> $horometro_inicial</td>
					<td nowrap> $horometro_final</td>
					<td> {$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td> C{$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td>-</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td nowrap> $motivo_llamado</td>
					<td nowrap> $categoria_sintoma</td>
					<td nowrap> $sintoma</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>Zerex</td>
					<td> {$json["f_zerex"]}</td>
					<td>litros</td>
					<td></td>
					<td>-</td>
					<td> $cambio_modulo</td>
					<td> $evento_finalizado</td>
					<td nowrap> $lugar_reparacion</td>
					<td nowrap> $tecnico_principal</td>
					<td nowrap> $tecnico_2</td>
					<td nowrap> $tecnico_3</td>
					<td nowrap> $tecnico_4</td>
					<td nowrap> $tecnico_5</td>
					<td nowrap> $tecnico_6</td>
					<td nowrap> $supervisor</td>
					<td nowrap> $turno</td>
					<td nowrap> $periodo</td>
					<td> $aprobador</td>
					<td> {$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>
					<td nowrap> $comentarios</td>
				</tr>
				";
				}
				
				
				if (isset($json["f_resurs"])&&is_numeric(@$json["f_resurs"])&&@$json["f_resurs"]!='0'&&intval(@$json["f_resurs"])>0){
				$return .= "
				<tr>
					<td> {$intervencion_zero["Planificacion"]["id"]}</td>
					<td> {$intervencion["Planificacion"]["id"]}</td>
					<td nowrap> {$intervencion_zero["Faena"]["nombre"]}</td>
					<td nowrap> {$intervencion_zero["Flota"]["nombre"]}</td>
					<td> {$intervencion["Unidad"]["unidad"]}</td>
					<td> {$intervencion["Planificacion"]["esn"]}</td>
					<td nowrap> $horometro_inicial</td>
					<td nowrap> $horometro_final</td>
					<td> {$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td> C{$intervencion["Planificacion"]["tipointervencion"]}</td>
					<td>-</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td nowrap> $motivo_llamado</td>
					<td nowrap> $categoria_sintoma</td>
					<td nowrap> $sintoma</td>
					<td>Fluido</td>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td>Resurs</td>
					<td> {$json["f_resurs"]}</td>
					<td>tarros</td>
					<td></td>
					<td>-</td>
					<td> $cambio_modulo</td>
					<td> $evento_finalizado</td>
					<td nowrap> $lugar_reparacion</td>
					<td nowrap> $tecnico_principal</td>
					<td nowrap> $tecnico_2</td>
					<td nowrap> $tecnico_3</td>
					<td nowrap> $tecnico_4</td>
					<td nowrap> $tecnico_5</td>
					<td nowrap> $tecnico_6</td>
					<td nowrap> $supervisor</td>
					<td nowrap> $turno</td>
					<td nowrap> $periodo</td>
					<td> $aprobador</td>
					<td> {$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>
					<td nowrap> $comentarios</td>
				</tr>
				";
				}
				
				if ($intervencion["Planificacion"]["hijo"] == "") {
					// Registro de Fecha Operación
					if (isset($intervencion['Planificacion']['fecha_operacion'])) {
						$fecha = strtotime($json['fecha_termino_g']);
						foreach($json as $key => $value) {
							if (substr($key, 0, 4) == "ds3_" && substr($key, -2) == "_r") {
								$newkey = substr($key, 0, -2);
								$hora = intval($json[$newkey."_h"]);
								$minuto = intval($json[$newkey."_m"]);
								if ($hora == 0 && $minuto == 0) {
									continue;
								}
								
								$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
								$time_termino = $fecha + $hora * 60 * 60 + $minuto * 60;
								$fecha_termino = $time_termino;
								$return .= "<tr style=\"background-color:#DDEBF7;\">";
								$return .= "<td>{$intervencion['Planificacion']['id']}</td>
									<td>{$intervencion['Planificacion']['id']}</td>
									<td>{$intervencion_zero['Faena']['nombre']}</td>
									<td>{$intervencion_zero['Flota']['nombre']}</td>
									<td>{$intervencion['Unidad']['unidad']}</td>
									<td>{$intervencion['Planificacion']['esn']}</td>
									<td>$horometro_inicial</td>
							<td>$horometro_final</td>
									<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
								if ($value == 1) {
									$return .= "<td>T_DCC</td>";
									$return .= "<td>DCC</td>";
								}elseif ($value == 2) {
									$return .= "<td>T_OEM</td>";
									$return .= "<td>OEM</td>";
								}elseif ($value == 3) {
									$return .= "<td>T_Mina</td>";
									$return .= "<td>Mina</td>";
								}
								$return .= "<td>Final</td>";
								$return .= "<td>".date("d-m-Y", $fecha)."</td>";
								$return .= "<td>".date("h:i A", $fecha)."</td>";
								$return .= "<td>".date("d-m-Y", $fecha_termino)."</td>";
								$return .= "<td>".date("h:i A", $fecha_termino)."</td>";
								$return .=  "<td>$duracion</td>";
$return .=  "<td nowrap>$motivo_llamado</td>";
							$return .=  "<td nowrap>$categoria_sintoma</td>";
							$return .=  "<td nowrap>$sintoma</td>";								
								$return .=  "<td>Tiempo</td>";
								$return .=  "<td>Termino Interv.-Inicio Operación</td>";
								$return .=  "<td>DeltaS3</td>";
								$return .=  "<td>-</td>";
								$return .=  "<td>".$this->getDelta($newkey)."</td>";
								$return .=  "<td>-</td>";
								$return .=  "<td>-</td>";
								$return .=  "<td>-</td>";
								$return .=  "<td>-</td>";
							//	$return .= "<td nowrap>$turno</td>";
							//$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td nowrap>$cambio_modulo</td>";
							$return .=  "<td nowrap>$evento_finalizado</td>";
								$return .=  "<td nowrap>$lugar_reparacion</td>";
							//	$return .=  "<td nowrap>$supervisor</td>";
								$return .=  "<td nowrap>$tecnico_principal</td>";
								$return .=  "<td nowrap>$tecnico_2</td>";
								$return .=  "<td nowrap>$tecnico_3</td>";
								$return .=  "<td nowrap>$tecnico_4</td>";
								$return .=  "<td nowrap>$tecnico_5</td>";
								$return .=  "<td nowrap>$tecnico_6</td>";
							$return .=  "<td nowrap>$supervisor</td>";
							$return .= "<td nowrap>$turno</td>";
							$return .= "<td nowrap>$periodo</td>";
							$return .=  "<td>{$aprobador}</td>";
								$return .=  "<td>{$this->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
								if(@$json["comentario"]!=""){
									$return .=  "<td nowrap>".(@$json["comentario"])."</td>";
								}elseif(@$json["comentarios"]!=""){
									$return .=  "<td nowrap>".(@$json["comentarios"])."</td>";
								}
								$return .= "</tr>";
								$fecha = $time_termino;
							}
						}					
					}
					return $return;
				} else {
					$time_anterior = strtotime($json['fecha_termino_g']);
					return $return . $this->desplegar_hijo_base_report_xls($intervencion["Planificacion"]["hijo"], $time_anterior, $codigo_zero, $cambio_modulo, $evento_finalizado);
				}
			} else {
				return "";
			}
		}
		
		public function sel_subsistema($motor_id, $sistema_id) {
			$this->layout = null;
			$this->loadModel('MotorSistemaSubsistemaPosicion');
			$resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
				'fields' =>array('Subsistema.id', 'Subsistema.nombre'),
				'group' =>array('Subsistema.id', 'Subsistema.nombre'),
				'conditions' => array("motor_id" => $motor_id, "sistema_id" => $sistema_id),//, 'MotorSistemaSubsistemaPosicion.e' => '1'),
				'order' => array('Subsistema.nombre')
			));
			$this->set('resultados', $resultados);
		}
		
		public function sel_sistema($motor_id) {
			$this->layout = null;
			$this->loadModel('MotorSistemaSubsistemaPosicion');
			$resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
				'fields' =>array('Sistema.id', 'Sistema.nombre'),
				'group' =>array('Sistema.id', 'Sistema.nombre'),
				'conditions' => array("motor_id" => $motor_id),//, 'MotorSistemaSubsistemaPosicion.e' => '1'),
				'order' => array('Sistema.nombre')
			));
			$this->set('resultados', $resultados);
		}
		
		public function sel_posicion($motor_id, $sistema_id, $subsistema_id) {
			$this->layout = null;
			$this->loadModel('MotorSistemaSubsistemaPosicion');
			$resultados = $this->MotorSistemaSubsistemaPosicion->find('all', array(
				'fields' =>array('Posicion.id', 'Posicion.nombre'),
				'group' =>array('Posicion.id', 'Posicion.nombre'),
				'conditions' => array("motor_id" => $motor_id, "sistema_id" => $sistema_id, "subsistema_id" => $subsistema_id),//, 'MotorSistemaSubsistemaPosicion.e' => '1'),
				'order' => array('Posicion.nombre')
			));
			$this->set('resultados', $resultados);
		}
		
		public function sel_elemento($motor_id, $sistema_id, $subsistema_id) {
			$this->layout = null;
			$this->loadModel('Sistema_Subsistema_Motor_Elemento');
			$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
				'fields' =>array('Sistema_Subsistema_Motor_Elemento.codigo', 'Elemento.id', 'Elemento.nombre'),
				'group' =>array('Sistema_Subsistema_Motor_Elemento.codigo', 'Elemento.id', 'Elemento.nombre'),
				'conditions' => array("motor_id" => $motor_id, "sistema_id" => $sistema_id, "subsistema_id" => $subsistema_id, 'Sistema_Subsistema_Motor_Elemento.e' => '1'),
				'order' => array('length(Sistema_Subsistema_Motor_Elemento.codigo), Sistema_Subsistema_Motor_Elemento.codigo')
			));
			$this->set('resultados', $resultados);
		}
		
		public function sel_elemento_codigo($motor_id, $sistema_id, $subsistema_id, $codigo) {
			$this->layout = null;
			$this->loadModel('Sistema_Subsistema_Motor_Elemento');
			$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
				'fields' =>array('Sistema_Subsistema_Motor_Elemento.codigo', 'Elemento.id', 'Elemento.nombre'),
				'group' =>array('Sistema_Subsistema_Motor_Elemento.codigo', 'Elemento.id', 'Elemento.nombre'),
				'conditions' => array("motor_id" => $motor_id, "sistema_id" => $sistema_id, "subsistema_id" => $subsistema_id,'UPPER(codigo)'=>strtoupper($codigo), 'Sistema_Subsistema_Motor_Elemento.e' => '1'),
				'order' => array('length(Sistema_Subsistema_Motor_Elemento.codigo), Sistema_Subsistema_Motor_Elemento.codigo')
			));
			$this->set('resultados', $resultados);
		}
		
		public function sel_posicion_elemento($motor_id, $sistema_id, $subsistema_id, $codigo, $elemento_id) {
			$this->layout = null;
			$this->loadModel('Sistema_Subsistema_Motor_Elemento');
			$resultados = $this->Sistema_Subsistema_Motor_Elemento->find('all', array(
				'fields' =>array('Posicion.id', 'Posicion.nombre'),
				'group' =>array('Posicion.id', 'Posicion.nombre'),
				'conditions' => array("motor_id" => $motor_id, "sistema_id" => $sistema_id, "subsistema_id" => $subsistema_id, "codigo" => $codigo, "elemento_id" => $elemento_id, 'Sistema_Subsistema_Motor_Elemento.e' => '1'),
				'order' => array('Posicion.nombre')
			));
			$this->set('resultados', $resultados);
		}
		
		public function sel_diagnostico($motor_id, $sistema_id, $subsistema_id, $elemento_id) {
			$this->layout = null;
			$this->loadModel('MotorSistemaSubsistemaElementoDiagnostico');
			$resultados = $this->MotorSistemaSubsistemaElementoDiagnostico->find('all', array(
				'fields' =>array('Diagnostico.id', 'Diagnostico.nombre'),
				'group' =>array('Diagnostico.id', 'Diagnostico.nombre'),
				'conditions' => array("motor_id" => $motor_id, "sistema_id" => $sistema_id, "subsistema_id" => $subsistema_id, "elemento_id" => $elemento_id, 'MotorSistemaSubsistemaElementoDiagnostico.e' => '1'),
				'order' => array('Diagnostico.nombre')
			));
			$this->set('resultados', $resultados);
		}
		public function folioHijos($h) {
			if ($h == null || $h == '') {
				return "0";
			}
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array('fields' => array("Planificacion.id", "Planificacion.hijo", "Planificacion.padre"), 'conditions' => array("padre = '$h' AND estado <> 10"), 'recursive' => -1));
			if (isset($intervencion) && isset($intervencion["Planificacion"]) && isset($intervencion["Planificacion"]["id"])) {
				if ($intervencion["Planificacion"]["hijo"] != null && $intervencion["Planificacion"]["hijo"] != '') {
					return $intervencion["Planificacion"]["id"].", ".($this->folioHijos($intervencion["Planificacion"]["hijo"]));
				} else {
					return $intervencion["Planificacion"]["id"];
				}
			} else {
				return "0";
			}
		}
		
		public function sel_solucion() {
			$this->layout = null;
			$this->loadModel('Solucion');
			$resultados = $this->Solucion->find('all', array(
				'fields' =>array('Solucion.id', 'Solucion.nombre','Solucion.e'),
				//'conditions'=>array('Solucion.e'=>'1'),
				'order' => array('Solucion.nombre')
			));
			$this->set('resultados', $resultados);
		}
		
		public function getClaveTemporal() {
			return "58467";
		}
		
		public function existeDuplicado($folio) {
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.*'),
													'conditions' => array(
													'id' => $folio),
													'recursive' => -1));
			
			if (isset($intervencion["Planificacion"])) {
				$intervencion_2 = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.*'),
													'conditions' => array(
														"Planificacion.id <> {$intervencion["Planificacion"]["id"]}",
														'Planificacion.faena_id' => $intervencion["Planificacion"]["faena_id"],
														'Planificacion.flota_id' => $intervencion["Planificacion"]["flota_id"],
														'Planificacion.unidad_id' => $intervencion["Planificacion"]["unidad_id"],
														//'Planificacion.estado' => $intervencion["Planificacion"],
														'Planificacion.sintoma_id' => $intervencion["Planificacion"]["sintoma_id"],
														'Planificacion.tipointervencion' => $intervencion["Planificacion"]["tipointervencion"],
														'Planificacion.fecha' => $intervencion["Planificacion"]["fecha"],
														'Planificacion.hora' => $intervencion["Planificacion"]["hora"],
														'Planificacion.fecha_termino' => $intervencion["Planificacion"]["fecha_termino"],
														'Planificacion.hora_termino' => $intervencion["Planificacion"]["hora_termino"]),
													'order' => 'Planificacion.id',
													'recursive' => -1));
				if (isset($intervencion_2["Planificacion"])) {
					return $intervencion_2["Planificacion"]["id"];
				} else {
					return "0";
				}
			}
		}
		
		public function listarFaenasUsuario($uid){
			$this->loadModel('UsuarioFaena');
			$resultados = $this->UsuarioFaena->find('all', array( 
													'fields' => array('Faena.nombre', 'Faena.id'),
													'conditions' => array(
														'usuario_id' => $uid),
													'recursive' => 1));
			$return = array();
			foreach ($resultados as $r) { 
				//print_r($r);
				$return[] = $r["Faena"];
			}
			//return implode(", ", $return);
			return $return;
		}
		
		public function getNumeroTecnicos($fid){
			$this->loadModel('UsuarioFaena');
			$resultados = $this->UsuarioFaena->find('all', array( 
													'fields' => array('Usuario.id'),
													'conditions' => array(
														'nivelusuario_id' => 1,'faena_id'=>$fid),
													'recursive' => 1));
			return  count($resultados);
		}
		
		public function getNumeroSupervisorDCC($fid){
			$q="select usuario_faena.faena_id, usuario_nivel.nivel_id, count(*)
			from usuario_faena, usuario_nivel where usuario_nivel.usuario_id=usuario_faena.usuario_id
			and usuario_faena.faena_id=$fid AND usuario_nivel.nivel_id=2
			group by usuario_faena.faena_id, usuario_nivel.nivel_id;";
			$db=ConnectionManager::getDataSource('default');
			$r=$db->query($q);
			if(isset($r[0][0]["count"])){
				return $r[0][0]["count"];
			}else{
				return "0";
			}
		}
		
		public function getNumeroSupervisorCliente($fid){
			$q="select usuario_faena.faena_id, usuario_nivel.nivel_id, count(*)
			from usuario_faena, usuario_nivel where usuario_nivel.usuario_id=usuario_faena.usuario_id
			and usuario_faena.faena_id=$fid AND usuario_nivel.nivel_id=3
			group by usuario_faena.faena_id, usuario_nivel.nivel_id;";
			$db=ConnectionManager::getDataSource('default');
			$r=$db->query($q);
			if(isset($r[0][0]["count"])){
				return $r[0][0]["count"];
			}else{
				return "0";
			}
		}
		
		public function getNumeroSupervisorGestion($fid){
			$q="select usuario_faena.faena_id, usuario_nivel.nivel_id, count(*)
			from usuario_faena, usuario_nivel where usuario_nivel.usuario_id=usuario_faena.usuario_id
			and usuario_faena.faena_id=$fid AND usuario_nivel.nivel_id=5
			group by usuario_faena.faena_id, usuario_nivel.nivel_id;";
			$db=ConnectionManager::getDataSource('default');
			$r=$db->query($q);
			if(isset($r[0][0]["count"])){
				return $r[0][0]["count"];
			}else{
				return "0";
			}
		}
		
		public function getNumeroFlotas($fid){
			$q="select count(*) from (select flota_id from unidad where faena_id=$fid group by flota_id) as r;";
			$db=ConnectionManager::getDataSource('default');
			$r=$db->query($q);
			if(isset($r[0][0]["count"])){
				return $r[0][0]["count"];
			}else{
				return "0";
			}
		}
		
		public function getNumeroEquipos($fid){
			$q="select count(*) from unidad where faena_id=$fid;";
			$db=ConnectionManager::getDataSource('default');
			$r=$db->query($q);
			if(isset($r[0][0]["count"])){
				return $r[0][0]["count"];
			}else{
				return "0";
			}
		}
		
		public function getNumeroIntervenciones($fid){
			$q="select count(*) from planificacion where faena_id=$fid and estado<>10;";
			$db=ConnectionManager::getDataSource('default');
			$r=$db->query($q);
			if(isset($r[0][0]["count"])){
				return $r[0][0]["count"];
			}else{
				return "0";
			}
		}
		
		public function actualizar_correlativos(){
			$this->loadModel('Planificacion');
			$resultados = $this->Planificacion->find('all', array(
				'fields' => array('id','correlativo','tipointervencion','padre','estado'),
				'conditions' => array("correlativo IS NULL AND estado<>10"),
				'recursive' => -1
			));
			
			foreach($resultados as $r){
				if(isset($r['Planificacion'])){
					if(!is_numeric($r['Planificacion']['correlativo'])){
						if($r['Planificacion']['padre']==null||$r['Planificacion']['padre']==''||strtoupper($r['Planificacion']['tipointervencion'])=='EX'){
							$d=array(
								'id'=>$r['Planificacion']['id'],
								'correlativo'=>$r['Planificacion']['id']
							);
							$this->Planificacion->save($d);
						}else{
							$c=$this->getCorrelativo($r['Planificacion']['padre']);
							if($c!=''&&is_numeric($c)){
								$d=array(
									'id'=>$r['Planificacion']['id'],
									'correlativo'=>$c
								);
								$this->Planificacion->save($d);
							}
						}
					}
				}
			}
		}
		
		public function actualizar_tiempo_dcc(){
			$this->loadModel('Planificacion');
			$resultados = $this->Planificacion->find('all', array(
				'fields' => array('id','tipointervencion','tipomantencion','json','estado','fecha','hora','fecha_termino','hora_termino','tiempo_dcc'),
				'conditions' => array("tiempo_dcc IS NULL AND estado IN (4,5,6)"),
				'recursive' => -1
			));
			foreach($resultados as $r){
				if(isset($r['Planificacion'])){
					$d=0;
					$json=json_decode($r["Planificacion"]["json"],true);
					if(strtolower($r["Planificacion"]["tipointervencion"])=='mp'){
						if($r["Planificacion"]["tipomantencion"]=='250'||$r["Planificacion"]["tipomantencion"]=='500'||$r["Planificacion"]["tipomantencion"]=='1000'){
							$i=strtotime($r["Planificacion"]["fecha"].' '.$r["Planificacion"]["hora"]);
							$f=strtotime($r["Planificacion"]["fecha_termino"].' '.$r["Planificacion"]["hora_termino"]);
							$d=($f-$i)/60;
							$data=array('id'=>$r["Planificacion"]["id"],'tiempo_dcc'=>$d);
							$this->Planificacion->save($data);
							continue;
						}
					}
					
					/*if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"])) {
						$i = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
						$f = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
						$d+=($f-$i)/60;
					}*/
						
					if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
						$i = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
						$f = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
						$d+=($f-$i)/60;
					}	
						
					/*if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
						$i = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
						$f = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
						$d+=($f-$i)/60;
					}*/	
						
					if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
						$i = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
						$f = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
						$d+=($f-$i)/60;
					}	
						
					/*if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
						$i = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
						$f = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
						$d+=($f-$i)/60;
					}*/	
						
					if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["pm_t_f"]) && isset($json["pm_t_h"]) && isset($json["pm_t_m"]) && isset($json["pm_t_p"])) {
						$i = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
						$f = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"].":".$json["pm_t_m"]." ".$json["pm_t_p"]);
						$d+=($f-$i)/60;
					}
					
					if (isset($json["pp_i_f"]) && isset($json["pp_i_h"]) && isset($json["pp_i_m"]) && isset($json["pp_i_p"]) && isset($json["pp_t_f"]) && isset($json["pp_t_h"]) && isset($json["pp_t_m"]) && isset($json["pp_t_p"])) {
						$i = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"].":".$json["pp_i_m"]." ".$json["pp_i_p"]);
						$f = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"].":".$json["pp_t_m"]." ".$json["pp_t_p"]);
						$d+=($f-$i)/60;
					}
					
					// Recorremos deltas
					foreach($json as $key=>$value) {
						if (substr($key, -2) == "_r"&&(substr($key, 0, 1) == "d" || substr($key, 0, 3) != "ds1")) {
							if($value!='1'){
								continue;
							}
							$newkey = substr($key, 0, -2);
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if(intval($hora)>0||intval($minuto)>0){
								$d+=intval($hora)*60+intval($minuto);
							}
						}
					}
					
					$data=array('id'=>$r["Planificacion"]["id"],'tiempo_dcc'=>$d);
					$this->Planificacion->save($data);
				}
			}
		}
		 
		public function actualizar_tiempo_oem(){
			$this->loadModel('Planificacion');
			$resultados = $this->Planificacion->find('all', array(
				'fields' => array('id','tipointervencion','tipomantencion','json','estado','fecha','hora','fecha_termino','hora_termino','tiempo_oem'),
				'conditions' => array("tiempo_oem IS NULL AND estado IN (4,5,6)"),
				'recursive' => -1
			));
			foreach($resultados as $r){
				if(isset($r['Planificacion'])){
					$d=0;
					$json=json_decode($r["Planificacion"]["json"],true);
					//if(strtolower($r["Planificacion"]["tipointervencion"])=='ex'){
						/*if (isset($json["llamado_fecha"]) && isset($json["llegada_fecha"])) {
							$i= strtotime($json["llamado_fecha"] . ' ' . $json["llamado_hora"] . ':'.$json["llamado_min"]. ' ' .$json["llamado_periodo"]);
							$f= strtotime($json["llegada_fecha"] . ' ' . $json["llegada_hora"] . ':'.$json["llegada_min"]. ' ' .$json["llegada_periodo"]);
							if(($f-$i)/60==15){
								if(strtolower($r["Planificacion"]["tipointervencion"])=='ex'){
									$d+=15;
								}
							}
						}*/
						/*if (isset($json["i_i_f"]) && isset($json["llegada_fecha"])) {
							$i= strtotime($json["llegada_fecha"] . ' ' . $json["llegada_hora"] . ':'.$json["llegada_min"]. ' ' .$json["llegada_periodo"]);
							$f= strtotime($json["i_i_f"] . ' ' . $json["i_i_h"] . ':'.$json["i_i_m"]. ' ' .$json["i_i_p"]);
							if(($f-$i)/60==15){
								if(strtolower($r["Planificacion"]["tipointervencion"])=='ex'){
									$d+=15;
								}
							}
						}*/
						
						
						// Las esperas son tiempo OEM
						if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"])) {
							$i = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
							$f = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
							$d+=($f-$i)/60;
						}	
							
						/*if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
							$i = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
							$f = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
							$d+=($f-$i)/60;
						}	*/
							
						if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
							$i = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
							$f = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
							$d+=($f-$i)/60;
						}	
							
						/*if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
							$i = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
							$f = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
							$d+=($f-$i)/60;
						}*/	
							
						if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
							$i = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
							$f = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
							$d+=($f-$i)/60;
						}	
							
						/*if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["pm_t_f"]) && isset($json["pm_t_h"]) && isset($json["pm_t_m"]) && isset($json["pm_t_p"])) {
							$i = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
							$f = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"].":".$json["pm_t_m"]." ".$json["pm_t_p"]);
							$d+=($f-$i)/60;
						}*/
						
						/*if (isset($json["pp_i_f"]) && isset($json["pp_i_h"]) && isset($json["pp_i_m"]) && isset($json["pp_i_p"]) && isset($json["pp_t_f"]) && isset($json["pp_t_h"]) && isset($json["pp_t_m"]) && isset($json["pp_t_p"])) {
							$i = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"].":".$json["pp_i_m"]." ".$json["pp_i_p"]);
							$f = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"].":".$json["pp_t_m"]." ".$json["pp_t_p"]);
							$d+=($f-$i)/60;
						}*/
						
						if (isset($json["i_i_f"]) && isset($json["i_t_f"])) {
							$i= strtotime($json["i_i_f"] . ' ' . $json["i_i_h"] . ':'.$json["i_i_m"]. ' ' .$json["i_i_p"]);
							$f= strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
							if(strtolower($r["Planificacion"]["tipointervencion"])=='ex'){
								$d+=($f-$i)/60;
							}
						}
						
						// Recorremos deltas
						foreach($json as $key=>$value) {
							if (substr($key, -2) == "_r"&&(substr($key, 0, 1) == "d" || substr($key, 0, 3) != "ds1")) {
								if($value!='2'){
									continue;
								}
								$newkey = substr($key, 0, -2);
								$hora = intval($json[$newkey."_h"]);
								$minuto = intval($json[$newkey."_m"]);
								if(intval($hora)>0||intval($minuto)>0){
									$d+=intval($hora)*60+intval($minuto);
								}
							}
						}
						
					//}
					$data=array('id'=>$r["Planificacion"]["id"],'tiempo_oem'=>$d);
					$this->Planificacion->save($data);
				}
			}
		}
		
		public function actualizar_tiempo_mina(){
			$this->loadModel('Planificacion');
			$resultados = $this->Planificacion->find('all', array(
				'fields' => array('id','tipointervencion','tipomantencion','json','estado','fecha','hora','fecha_termino','hora_termino','tiempo_mina'),
				'conditions' => array("tiempo_mina IS NULL AND estado IN (4,5,6)"),
				'recursive' => -1
			));
			foreach($resultados as $r){
				if(isset($r['Planificacion'])){
					$d=0;
					$json=json_decode($r["Planificacion"]["json"],true);
					// Recorremos deltas
					foreach($json as $key=>$value) {
						if (substr($key, -2) == "_r"&&(substr($key, 0, 1) == "d" || substr($key, 0, 3) != "ds1")) {
							if($value!='3'){
								continue;
							}
							$newkey = substr($key, 0, -2);
							$hora = intval($json[$newkey."_h"]);
							$minuto = intval($json[$newkey."_m"]);
							if(intval($hora)>0||intval($minuto)>0){
								$d+=intval($hora)*60+intval($minuto);
							}
						}
					}
					$data=array('id'=>$r["Planificacion"]["id"],'tiempo_mina'=>$d);
					$this->Planificacion->save($data);
				}
			}
		}
		
		public function img_name($sistema_id, $subsistema_id, $motor_id){
			$this->loadModel('Sistema');
			$this->loadModel('Subsistema');
			$this->loadModel('Motor');
			$this->layout=null;
			$r0=array("Ó","Ú","Í","Á","Ü","Ñ"," ");
			$r1=array("O","U","I","A","U","N","_");
			$return="";
			$r = $this->Motor->find('first', array( 'fields' => array('Motor.nombre'),'conditions' => array('id' => $motor_id),'recursive' => -1));
			if(isset($r["Motor"])){
				$return.=$r["Motor"]["nombre"];
			}
			$r = $this->Sistema->find('first', array( 'fields' => array('Sistema.nombre'),'conditions' => array('id' => $sistema_id),'recursive' => -1));
			if(isset($r["Sistema"])){
				$t=explode("_",$r["Sistema"]["nombre"]);
				$return.="_".$t[1];
			}
			$r = $this->Subsistema->find('first', array( 'fields' => array('Subsistema.nombre'),'conditions' => array('id' => $subsistema_id),'recursive' => -1));
			if(isset($r["Subsistema"])){
				$return.="_".$r["Subsistema"]["nombre"];
			}
			$return = str_replace($r0,$r1,$return);
			$return = strtoupper($return);
			$this->set('return', $return);
		}
		
		public function existenDuplicados($folio) {
			$this->loadModel('Planificacion');
			$intervencion = $this->Planificacion->find('first', array( 
													'fields' => array('Planificacion.*'),
													'conditions' => array(
													'id' => $folio),
													'recursive' => -1));
			
			if (isset($intervencion["Planificacion"])) {
				$intervencion_2 = $this->Planificacion->find('all', array( 
													'fields' => array('Planificacion.*'),
													'conditions' => array(
														"Planificacion.id <> {$intervencion["Planificacion"]["id"]}",
														'Planificacion.faena_id' => $intervencion["Planificacion"]["faena_id"],
														'Planificacion.flota_id' => $intervencion["Planificacion"]["flota_id"],
														'Planificacion.unidad_id' => $intervencion["Planificacion"]["unidad_id"],
														'Planificacion.estado NOT IN (10)',
														'Planificacion.sintoma_id' => $intervencion["Planificacion"]["sintoma_id"],
														'Planificacion.tipointervencion' => $intervencion["Planificacion"]["tipointervencion"],
														'Planificacion.fecha' => $intervencion["Planificacion"]["fecha"],
														'Planificacion.hora' => $intervencion["Planificacion"]["hora"],
														'Planificacion.fecha_termino' => $intervencion["Planificacion"]["fecha_termino"],
														'Planificacion.hora_termino' => $intervencion["Planificacion"]["hora_termino"]),
													'order' => 'Planificacion.id',
													'recursive' => -1));
													
												// sistemas y subsistemas
													
				$return = array();
				foreach ($intervencion_2 as $int) { 
					$return[] = $int["Planificacion"]["id"];
				}
				return $return;
				/*
				if (isset($intervencion_2["Planificacion"])) {
					return $intervencion_2["Planificacion"]["id"];
				} else {
					return "0";
				}*/
			}
		}
		
		
		public function getIntervencionBacklog($ts){
			if($ts==null||trim($ts)==""){
				return "";
			}
			$this->loadModel('Planificacion');
			$p=$this->Planificacion->find('first', array(
				'fields' => array('Planificacion.id','Planificacion.json'),
				'conditions' => array("Planificacion.json LIKE"=>'%,"sync_inte":"'.$ts.'",%'),
				'recursive' => -1
			));
			//echo "Planificacion.json LIKE '%,\"sync_inte\":\"$ts\",%'";
			return @$p["Planificacion"]["id"];
		}
		
		public function getSistemaBacklog($folio){
			$this->loadModel('Sistema_Motor');
			$p=$this->Sistema_Motor->find('first', array(
				'fields' => array('Sistema.nombre'),
				'conditions' => array("Sistema_Motor.id"=>$folio),
				'recursive' => 1
			));
			return @$p["Sistema"]["nombre"];
		}
		
		public function getIntervencionBacklog2($id){
			if($id==null||trim($id)==""){
				return "";
			}
			$this->loadModel('Planificacion');
			$p=$this->Planificacion->find('first', array(
				'fields' => array('Planificacion.id','Planificacion.backlog_id'),
				'conditions' => array("Planificacion.backlog_id"=>$id),
				'recursive' => -1
			));
			return @$p["Planificacion"]["id"];
		}
		
		public function actualizarBacklogs(){
			$this->loadModel('Planificacion');
			$this->loadModel('Backlog');
			$this->loadModel('Sistema_Motor');
			
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
		}
		
		function existe_cambio_modulo($folio){
			$this->loadModel('Planificacion');
			$p = $this->Planificacion->find('first', array(
				'fields' => array('Planificacion.id','Planificacion.json','Planificacion.padre','Planificacion.hijo'),
				'conditions' => array("Planificacion.id=$folio"),
				'recursive' => -1
			));
			if(isset($p["Planificacion"])){
				if(strpos($p["Planificacion"]["json"],"desc_f") !== false){
					return true;
				}elseif($p["Planificacion"]["hijo"]!=null&&$p["Planificacion"]["hijo"]!=''){
					$p2 = $this->Planificacion->find('first', array(
						'fields' => array('Planificacion.id','Planificacion.padre','Planificacion.hijo'),
						'conditions' => array("Planificacion.padre='{$p["Planificacion"]["hijo"]}'"),
						'recursive' => -1
					));
					if(isset($p2["Planificacion"])){
						return $this->existe_cambio_modulo($p2["Planificacion"]["id"]);
					}else{
						return false;
					}
				}else{
					return false;
				}
			} else {
				return false;
			}
		}
		
		function getEstadoBacklog($folio){
			$this->loadModel('Planificacion');
			$p = $this->Planificacion->find('first', array(
				'fields' => array('Planificacion.id','Planificacion.backlog_id','Planificacion.estado'),
				'conditions' => array("Planificacion.backlog_id=$folio"),
				'recursive' => -1
			));
			if(isset($p["Planificacion"])&&isset($p["Planificacion"]["estado"])){
				if($p["Planificacion"]["estado"]==2){
					echo "Planificado";
				}
				if($p["Planificacion"]["estado"]==7){
					echo "Sin Revisar";
				}
				if($p["Planificacion"]["estado"]==4){
					echo "Aprobado DCC";
				}
			}else{
				echo "Sin Planificar";
			}
		}
		
		function correlativo_terminado($folio){
			$this->loadModel('Planificacion');
			$p = $this->Planificacion->find('first', array(
				'fields' => array('Planificacion.id','Planificacion.json','Planificacion.padre','Planificacion.hijo'),
				'conditions' => array("Planificacion.id=$folio"),
				'recursive' => -1
			));
			if(isset($p["Planificacion"])){
				if($p["Planificacion"]["hijo"]==null||$p["Planificacion"]["hijo"]==''){
					return true;
				}elseif($p["Planificacion"]["hijo"]!=null&&$p["Planificacion"]["hijo"]!=''){
					$p2 = $this->Planificacion->find('first', array(
						'fields' => array('Planificacion.id','Planificacion.padre','Planificacion.hijo','Planificacion.estado'),
						'conditions' => array("Planificacion.padre='{$p["Planificacion"]["hijo"]}' and Planificacion.estado not in (1,2)"),
						'recursive' => -1
					));
					if(isset($p2["Planificacion"])){
						return $this->correlativo_terminado($p2["Planificacion"]["id"]);
					}else{
						return false;
					}
				}else{
					return false;
				}
			} else {
				return false;
			}
		}
		
		function revisarContinuaciones(){
			$this->loadModel('Planificacion');
			$this->layout = null;
			$p = $this->Planificacion->find('all', array(
				'fields' => array('Planificacion.id','Planificacion.tipointervencion','Planificacion.padre','Planificacion.hijo', 'Planificacion.tipointervencion_original', 'Planificacion.correlativo'),
				'conditions' => array(" Planificacion.id = Planificacion.correlativo AND Planificacion.tipointervencion_original IS NOT NULL AND Planificacion.tipointervencion_original <> '' AND Planificacion.tipointervencion_original <> Planificacion.tipointervencion AND Planificacion.hijo IS NOT NULL AND Planificacion.hijo <> '' "),
				'recursive' => -1,
				'limit' => 20
			));
			foreach($p as $planificacion){
				$query = "UPDATE tipointervencion='{$planificacion["Planificacion"]["tipointervencion"]}' FROM Planificacion WHERE correlativo = '{$planificacion["Planificacion"]["correlativo"]}'";
				print_r($planificacion);
				print_r("<br />");
				print_r($query);
				print_r("<hr />");
			}
			//print_r($p);
			exit;
		}
		
		function padreAprobado($folio) {
			//return true;
			$this->loadModel('Planificacion');
			$this->layout = null;
			$p = $this->Planificacion->find('first', array(
				'fields' => array('Planificacion.id','Planificacion.estado','Planificacion.padre','Planificacion.folio'),
				'conditions' => array("Planificacion.folio = '$folio'"),
				'recursive' => -1
			));
			//print_r($p["Planificacion"]);
			if(!isset($p["Planificacion"])){
				return true;
			}
			if(isset($p["Planificacion"]) && ($p["Planificacion"]["estado"] == '4' || $p["Planificacion"]["estado"] == '5' || $p["Planificacion"]["estado"] == '6')) {
				return true;
			}else{
				return false;
			}
		}
		
		
		public function check_permissions_menu_item($controller = "", $action = "", $faena_session, $usuario_id, $cargos) {
			$this->loadModel('Vista');
			$this->loadModel('Usuario');
			$this->loadModel('PermisoGlobal');
			$this->loadModel('PermisoUsuario'); 
			$this->loadModel('PermisoPersonalizado');
	
						
			//debug("faena: ".$faena_id);
			//debug("usuario: ".$usuario_id);
			//debug($cargos);
			
			$vista_todos = null;
			$vista_todos = $this->Vista->find('first', array(
				'fields' => array('Vista.id'),
				'conditions' => array("LOWER(Vista.controller)" => "*", 'LOWER(Vista.action)' => "*"),
				'recursive' => -1
			));
			if(isset($vista_todos) && isset($vista_todos["Vista"]["id"])) {
				$vista_todos = $vista_todos["Vista"]["id"];
				//debug("La vista $controller/$action está definida en los permisos.");
				//print_r($vista_todos);
			}else{
				//$this->render('/Permiso/Vista');
				//debug("La vista $controller/$action no está definida en los permisos.");
				return false;
			}
			
			$vista_id = null;
			$vista = $this->Vista->find('first', array(
				'fields' => array('Vista.id'),
				'conditions' => array("LOWER(Vista.controller)" => strtolower($controller), 'LOWER(Vista.action) LIKE' => "%".strtolower($action)."%"),
				'recursive' => -1
			));
			 
			if(isset($vista) && isset($vista["Vista"]["id"])) {
				$vista_id = $vista["Vista"]["id"];
				//debug("La vista $controller/$action está definida en los permisos.");
			}else{
				$vista_id = -1;
				//$this->render('/Permiso/Vista');
				//debug("La vista $controller/$action no está definida en los permisos.");
				//return;
			}
			
			if(!isset($cargos[$faena_session]) || !isset($cargos[$faena_session][0]) || !is_numeric($cargos[$faena_session][0])) {
				//$this->render('/Permiso/Cargo');
				//die;
				//debug("El usuario: $usuario_id no tiene cargo definido para la faena: $faena_id");
				return false;
			}
			
			
			
			$cargos[$faena_session][] = -1;
			$acceso_a_todo = false;
			if($vista_todos != null) {
				$permisos = $this->PermisoPersonalizado->find('all', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session),
					'recursive' => -1
				));
				//debug($permisos);
				if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
					
					$permiso = $this->PermisoPersonalizado->find('first', array(
						'fields' => array('PermisoPersonalizado.id'),
						'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.vista_id" => $vista_todos, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session),
						'recursive' => -1
					));
					
					if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
						$acceso_a_todo = true;
					} else {				
						$acceso_a_todo = false;
					}
				} else {
					$permiso = $this->PermisoGlobal->find('first', array(
						'fields' => array('PermisoGlobal.id'),
						'conditions' => array("PermisoGlobal.cargo_id IN" => $cargos[$faena_session], "PermisoGlobal.vista_id" => $vista_todos, "PermisoGlobal.e" => '1'),
						'recursive' => -1
					));
					if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
						$acceso_a_todo = true;
					} else {				
						$acceso_a_todo = false;
					}
				}
			}
			
			if($vista_id != null && $acceso_a_todo == false) {
				$permisos = $this->PermisoPersonalizado->find('all', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session),
					'recursive' => -1
				));
				//debug($permisos); 
				if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
					$permiso = $this->PermisoPersonalizado->find('first', array(
						'fields' => array('PermisoPersonalizado.id'),
						'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.vista_id" => $vista_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session),
						'recursive' => -1
					));
					if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
						//debug("El usuario: $usuario_id tiene permisos personalizados en faena: $faena_id para acceder a $controller/$action.");
						//return;
					} else {				 
						//$this->render('/Permiso/Acceso');
						return false;
					}
				} else {
					$permiso = $this->PermisoGlobal->find('first', array(
						'fields' => array('PermisoGlobal.id'),
						'conditions' => array("PermisoGlobal.cargo_id IN" => $cargos[$faena_session], "PermisoGlobal.vista_id" => $vista_id, "PermisoGlobal.e" => '1'),
						'recursive' => -1
					));
					if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
						//debug("El usuario: $usuario_id tiene permisos globales en faena: $faena_id para acceder a $controller/$action.");
						//return;
					} else {				
						//$this->render('/Permiso/Acceso');
						return false;
					}
				}
			}
			return true;
		}
		
		public function check_permissions_role($role_id, $faena_session, $usuario_id, $cargos) {
			$this->loadModel('Vista');
			$this->loadModel('Usuario');
			$this->loadModel('PermisoGlobal');
			$this->loadModel('PermisoUsuario'); 
			$this->loadModel('PermisoPersonalizado');
			if(!isset($cargos[$faena_session]) || !isset($cargos[$faena_session][0]) || !is_numeric($cargos[$faena_session][0])) {
				return false;
			}
			$cargos[$faena_session][] = -1;
			if($role_id != null) {
				$permisos = $this->PermisoPersonalizado->find('all', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session),
					'recursive' => -1
				));
				if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
					$permiso = $this->PermisoPersonalizado->find('first', array(
						'fields' => array('PermisoPersonalizado.id'),
						'conditions' => array("PermisoPersonalizado.cargo_id IN" => $cargos[$faena_session], "PermisoPersonalizado.rol_id" => $role_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_session),
						'recursive' => -1
					));
					if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
					} else {
						return false;
					}
				} else {
					$permiso = $this->PermisoGlobal->find('first', array(
						'fields' => array('PermisoGlobal.id'),
						'conditions' => array("PermisoGlobal.cargo_id IN" => $cargos[$faena_session], "PermisoGlobal.rol_id" => $role_id, "PermisoGlobal.e" => '1'),
						'recursive' => -1
					));
					if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
					} else {
						return false;
					}
				}
			}
			return true;
		}
		
		public function check_permissions_notifications($role_id, $faena_id, $usuario_id) {
			$this->loadModel('Vista');
			$this->loadModel('Usuario');
			$this->loadModel('PermisoGlobal');
			$this->loadModel('PermisoUsuario'); 
			$this->loadModel('PermisoPersonalizado');
			
			$permisos_usuarios = $this->PermisoUsuario->find('all', array(
				'fields' => array('PermisoUsuario.id','PermisoUsuario.cargo_id'),
				'conditions' => array("PermisoUsuario.usuario_id" => $usuario_id, "PermisoUsuario.faena_id" => $faena_id, "PermisoUsuario.e" => '1'),
				'recursive' => -1

			));
			
			foreach($permisos_usuarios as $permiso_usuario){
				$cargo_id = $permiso_usuario["PermisoUsuario"]["cargo_id"];
				$permisos = $this->PermisoPersonalizado->find('all', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id" => $cargo_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_id),
					'recursive' => -1
				));
				
				if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
					$permiso = $this->PermisoPersonalizado->find('first', array(
						'fields' => array('PermisoPersonalizado.id'),
						'conditions' => array("PermisoPersonalizado.cargo_id" => $cargo_id, "PermisoPersonalizado.rol_id" => $role_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_id),
						'recursive' => -1
					));
					if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
					} else {
						return false;
					}
				} else {
					$permiso = $this->PermisoGlobal->find('first', array(
						'fields' => array('PermisoGlobal.id'),
						'conditions' => array("PermisoGlobal.cargo_id" => $cargo_id, "PermisoGlobal.rol_id" => $role_id, "PermisoGlobal.e" => '1'),
						'recursive' => -1
					));
					
					if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
					} else {
						return false;
					}
				}
			} 
			
			return true; 
		}
		
		public function check_permissions_mails($correo_id, $faena_id, $usuario_id) {
			$this->loadModel('Vista');
			$this->loadModel('Usuario');
			$this->loadModel('PermisoGlobal');
			$this->loadModel('PermisoUsuario'); 
			$this->loadModel('PermisoPersonalizado');
			
			$permisos_usuarios = $this->PermisoUsuario->find('all', array(
				'fields' => array('PermisoUsuario.id','PermisoUsuario.cargo_id'),
				'conditions' => array("PermisoUsuario.usuario_id" => $usuario_id, "PermisoUsuario.faena_id" => $faena_id, "PermisoUsuario.e" => '1', "Cargo.e" => '1'),
				'recursive' => 1

			));
			//echo "PermisoUsuario<br />";
			//print_r($permisos_usuarios);
			foreach($permisos_usuarios as $permiso_usuario){
				$cargo_id = $permiso_usuario["PermisoUsuario"]["cargo_id"];
				$permisos = $this->PermisoPersonalizado->find('all', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id" => $cargo_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_id, "Cargo.e" => '1'),
					'recursive' => 1
				));
				
				if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
					$permiso = $this->PermisoPersonalizado->find('first', array(
						'fields' => array('PermisoPersonalizado.id'),
						'conditions' => array("PermisoPersonalizado.cargo_id" => $cargo_id, "PermisoPersonalizado.correo_id" => $correo_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_id, "Cargo.e" => '1'),
						'recursive' => 1
					));
					if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
					} else {
						return false;
					}
				} else {
					$permiso = $this->PermisoGlobal->find('first', array(
						'fields' => array('PermisoGlobal.id'),
						'conditions' => array("PermisoGlobal.cargo_id" => $cargo_id, "PermisoGlobal.correo_id" => $correo_id, "PermisoGlobal.e" => '1', "Cargo.e" => '1'),
						'recursive' => 1
					));
					//echo "PermisoGlobal<br />";
					//print_r($permiso);
					if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
					} else {
						return false;
					}
				}
			} 
			
			return true; 
		}
		
		public function get_users_with_permissions($role_id, $faena_id)  {
			$this->layout = NULL;
			$this->loadModel('Vista');
			$this->loadModel('Usuario');
			$this->loadModel('PermisoGlobal');
			$this->loadModel('PermisoUsuario'); 
			$this->loadModel('PermisoPersonalizado');
			//echo "<pre>";
			$permisos_usuarios = $this->PermisoUsuario->find('all', array(
				'fields' => array('PermisoUsuario.id','PermisoUsuario.cargo_id', "PermisoUsuario.usuario_id"),
				'conditions' => array("PermisoUsuario.faena_id" => $faena_id, "PermisoUsuario.e" => '1', "Cargo.e" => '1'),
				'recursive' => 1

			));
			
			if ($role_id == 1) {
				$permisos_usuarios = $this->PermisoUsuario->find('all', array(
					'fields' => array('PermisoUsuario.id','PermisoUsuario.cargo_id', "PermisoUsuario.usuario_id"),
					'conditions' => array("PermisoUsuario.faena_id" => $faena_id, "PermisoUsuario.cargo_id NOT IN" => array(1,2), "PermisoUsuario.e" => '1', "Cargo.e" => '1'),
					'recursive' => 1
				));
			}
			
			$usuarios = array();
			foreach($permisos_usuarios as $permiso_usuario){
				//print_r($permiso_usuario);
				$cargo_id = $permiso_usuario["PermisoUsuario"]["cargo_id"];
				$usuario_id = $permiso_usuario["PermisoUsuario"]["usuario_id"];
				$permisos = $this->PermisoPersonalizado->find('all', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id" => $cargo_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_id, "Cargo.e" => '1'),
					'recursive' => 1
				));
				//print_r($permisos);
				if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
					//echo "Tiene permiso personalizado";
					$permiso = $this->PermisoPersonalizado->find('first', array(
						'fields' => array('PermisoPersonalizado.id'),
						'conditions' => array("PermisoPersonalizado.cargo_id" => $cargo_id, "PermisoPersonalizado.correo_id" => $role_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_id, "Cargo.e" => '1'),
						'recursive' => 1
					));
					if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
						$usuarios[] = $usuario_id;
						//break;
					//	echo "usuario: $usuario_id | cargo_id: $cargo_id | role_id = $role_id | faena_id: $faena_id => Tiene permiso personalizado. <br />";
					} else {
					//	echo "usuario: $usuario_id | cargo_id: $cargo_id | role_id = $role_id | faena_id: $faena_id => No tiene permiso personalizado. <br />";
					}
				} else {
					//echo "No tiene permiso personalizado, tiene permiso global";
					$permiso = $this->PermisoGlobal->find('first', array(
						'fields' => array('PermisoGlobal.id'),
						'conditions' => array("PermisoGlobal.cargo_id" => $cargo_id, "PermisoGlobal.correo_id" => $role_id, "PermisoGlobal.e" => '1', "Cargo.e" => '1'),
						'recursive' => 1
					));
					if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
						$usuarios[] = $usuario_id;
						//break;
					//	echo "usuario: $usuario_id | cargo_id: $cargo_id | role_id = $role_id | faena_id: $faena_id => Tiene permiso global. <br />";
					} else {
					//	echo "usuario: $usuario_id | cargo_id: $cargo_id | role_id = $role_id | faena_id: $faena_id => No tiene permiso global. <br />";
					}
				}
			}
			return array_unique($usuarios);
			exit;
		}
		
		public function get_users_with_permissions_mail($correo_id, $faena_id)  {
			$this->layout = NULL;
			$this->loadModel('Vista');
			$this->loadModel('Usuario');
			$this->loadModel('PermisoGlobal');
			$this->loadModel('PermisoUsuario'); 
			$this->loadModel('PermisoPersonalizado');
			//echo "<pre>";
			$permisos_usuarios = $this->PermisoUsuario->find('all', array(
				'fields' => array('PermisoUsuario.id','PermisoUsuario.cargo_id', "PermisoUsuario.usuario_id"),
				'conditions' => array("PermisoUsuario.faena_id" => $faena_id, "Cargo.e" => '1', 'PermisoUsuario.e' => '1'),
				'recursive' => 1

			));
			
			//if ($role_id == 1) {
				$permisos_usuarios = $this->PermisoUsuario->find('all', array(
					'fields' => array('PermisoUsuario.id','PermisoUsuario.cargo_id', "PermisoUsuario.usuario_id"),
					'conditions' => array("PermisoUsuario.faena_id" => $faena_id, "PermisoUsuario.cargo_id NOT IN" => array(1,2), "PermisoUsuario.e" => '1', "Cargo.e" => '1'),
					'recursive' => 1
				)); 
			//}
			
			$usuarios = array();
			foreach($permisos_usuarios as $permiso_usuario){
				//print_r($permiso_usuario);
				$cargo_id = $permiso_usuario["PermisoUsuario"]["cargo_id"];
				$usuario_id = $permiso_usuario["PermisoUsuario"]["usuario_id"];
				$permisos = $this->PermisoPersonalizado->find('all', array(
					'fields' => array('PermisoPersonalizado.id'),
					'conditions' => array("PermisoPersonalizado.cargo_id" => $cargo_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_id, "Cargo.e" => '1'),
					'recursive' => 1
				));
				//print_r($permisos);
				//echo "usuario: $usuario_id<br />";
				if(isset($permisos) && isset($permisos[0]["PermisoPersonalizado"])) {
					$permiso = $this->PermisoPersonalizado->find('first', array(
						'fields' => array('PermisoPersonalizado.id'),
						'conditions' => array("PermisoPersonalizado.cargo_id" => $cargo_id, "PermisoPersonalizado.correo_id" => $correo_id, "PermisoPersonalizado.usuario_id" => $usuario_id, "PermisoPersonalizado.faena_id" => $faena_id, "Cargo.e" => '1'),
						'recursive' => 1
					));
					if(isset($permiso) && isset($permiso["PermisoPersonalizado"]["id"])) {
						//echo "Tiene permiso personalizado <br />";
						$usuarios[] = $usuario_id;
						//break;
					//	echo "usuario: $usuario_id | cargo_id: $cargo_id | role_id = $role_id | faena_id: $faena_id => Tiene permiso personalizado. <br />";
					} else {
					//	echo "usuario: $usuario_id | cargo_id: $cargo_id | role_id = $role_id | faena_id: $faena_id => No tiene permiso personalizado. <br />";
					}
				} else {
					//echo "No tiene permiso personalizado <br />";
					$permiso = $this->PermisoGlobal->find('first', array(
						'fields' => array('PermisoGlobal.id'),
						'conditions' => array("PermisoGlobal.cargo_id" => $cargo_id, "PermisoGlobal.correo_id" => $correo_id, "PermisoGlobal.e" => '1', "Cargo.e" => '1'),
						'recursive' => 1
					));
					if(isset($permiso) && isset($permiso["PermisoGlobal"]["id"])) {
						//echo "Tiene permiso global <br />";
						$usuarios[] = $usuario_id;
						//break;
					//	echo "usuario: $usuario_id | cargo_id: $cargo_id | role_id = $role_id | faena_id: $faena_id => Tiene permiso global. <br />";
					} else {
					//	echo "usuario: $usuario_id | cargo_id: $cargo_id | role_id = $role_id | faena_id: $faena_id => No tiene permiso global. <br />";
					}
				}
			}
			//print_r (array_unique($usuarios));
			return array_unique($usuarios);
			exit;
		}
                
                public function validaFecha($fecha, $formato = 'Y-m-d H:i:s')
                {
                    $d = DateTime::createFromFormat($formato, $fecha);
                    return $d && $d->format($formato) == $fecha;
                }

	}
?>