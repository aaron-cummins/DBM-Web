<?php
	//set_time_limit(100000);
	App::uses('ConnectionManager', 'Model'); 
	App::import('Controller', 'Utilidades');
	/* Esta clase defines funciones utilitarias para el uso en el sistema */
	class UtilidadesReporteController extends AppController {
		function get_son($identificador = null, $original = null, &$dataArray, &$objPHPExcel){
			$this->loadModel('Planificacion');
			$util = new UtilidadesController();
			$Intervencion = $this->Planificacion->find('first', array( 
														'fields' => array('Planificacion.id','Planificacion.fecha','Planificacion.fecha_termino','Planificacion.hora','Planificacion.hora_termino','Planificacion.json','Planificacion.padre','Planificacion.hijo','Planificacion.estado','Planificacion.aprobador_id','Planificacion.tipointervencion','Planificacion.fecha_operacion','Planificacion.tipomantencion','Planificacion.folio'),
														'conditions' => array(
															'Planificacion.padre' => $identificador,
															'estado IN (4, 5, 6,10)'),
													'recursive' => -1));
			if(count($intervencion)>0&&isset($intervencion["Planificacion"])){
				$intervencion = $intervencion["Planificacion"];
				if($intervencion["estado"]!='10'){
					$fecha = new DateTime($intervencion['fecha'] . ' ' . $intervencion['hora']);
					$fecha_termino = new DateTime($intervencion['fecha_termino'] . ' ' . $intervencion['hora_termino']);
					$diff = $fecha_termino->diff($fecha);
					$tiempo_trabajo = $diff->h + $diff->i / 60.0;
					$tiempo_trabajo = $tiempo_trabajo + ($diff->days*24);
					//$tiempo_trabajo = number_format($tiempo_trabajo, 2, ",",".");
					$json = json_decode($intervencion["json"], true);
					$tecnico = array();
					$tecnico[] = is_numeric(@$json["UserID"])?$util->getTecnicoRut($json["UserID"]) : "";
					$tecnico[] = is_numeric(@$json["tecnico_2"])?$util->getTecnicoRut(@$json["tecnico_2"]):"";
					$tecnico[] = is_numeric(@$json["tecnico_3"])?$util->getTecnicoRut(@$json["tecnico_3"]):"";
					$tecnico[] = is_numeric(@$json["tecnico_4"])?$util->getTecnicoRut(@$json["tecnico_4"]):"";
					$tecnico[] = is_numeric(@$json["tecnico_5"])?$util->getTecnicoRut(@$json["tecnico_5"]):"";
					$tecnico[] = is_numeric(@$json["tecnico_6"])?$util->getTecnicoRut(@$json["tecnico_6"]):"";
					$aprobador = $util->getTecnico(@$intervencion['aprobador_id']);
					$supervisor = $util->getTecnico(@$json['supervisor_d']);
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
							$periodo = '';
							break;
					}
					$comentarios = "";
					if(@$json["comentario"]!=""){
						$comentarios = @$json["comentario"];
					}elseif(@$json["comentarios"]!=""){
						$comentarios = @$json["comentarios"];
					}
					$horometro_final = 0;
					if (isset($json["horometro_pm"]))
						$horometro_final = @$json["horometro_pm"];
					elseif (isset($json["horometro_final"]))
						$horometro_final = @$json["horometro_final"];
					elseif (isset($json["horometro_cabina"]))
						$horometro_final = @$json["horometro_cabina"];
					else
						$horometro_final = $original[7];
						
					$statuskcc = $util->getEstadoKCH($intervencion["estado"]);
					$dataLocal = array();
					$dataLocal[] = $original[0];
					$dataLocal[] = $intervencion["id"];
					$dataLocal[] = $original[2];
					$dataLocal[] = $original[3];
					$dataLocal[] = $original[4];
					$dataLocal[] = $original[5];
					$dataLocal[] = $original[7];
					$dataLocal[] = $horometro_final;
					$dataLocal[] = $original[8];
					$dataLocal[] = "C".$original[9];
					$dataLocal[] = "";
					$dataLocal[] = "Continuación";
					$dataLocal[] = $fecha->format('d-m-Y');
					$dataLocal[] = $fecha->format('H:i');
					$dataLocal[] = $fecha_termino->format('d-m-Y');
					$dataLocal[] = $fecha_termino->format('H:i');
					$dataLocal[] = $tiempo_trabajo; // Tiempo trabajo
					$dataLocal[] = $original[17];
					$dataLocal[] = $original[18];
					$dataLocal[] = $original[19];
					$dataLocal[] = "";
					$dataLocal[] = "";
					$dataLocal[] = "";
					$dataLocal[] = "";
					$dataLocal[] = "";
					$dataLocal[] = "";
					$dataLocal[] = "";
					$dataLocal[] = "";
					$dataLocal[] = "";
					$dataLocal[] = $original[29];//$cambio_modulo;
					$dataLocal[] = $original[30];//$evento_finalizado;
					$dataLocal[] = $original[31];
					foreach($tecnico as $k=>$v){
						$dataLocal[] = $v;	
					} 
					$dataLocal[] = $supervisor;
					$dataLocal[] = $turno;
					$dataLocal[] = $periodo;
					$dataLocal[] = $aprobador;
					$dataLocal[] = $statuskcc;
					$dataLocal[] = $comentarios;
					
					// Delta continuacion de trabajos
					//$time_anterior = strtotime($original[14]. " " .$original[15]);
					$time_anterior = strtotime($fecha->format('d-m-Y h:i A'));
					$this->delta_continuacion($time_anterior,$json,$dataLocal,$dataArray,$objPHPExcel);
					$dataArray[] = $dataLocal;
					
					$linea = count($dataArray) + 1;
					// Formateo de linea principal
					$this->cellColor("A$linea:AR$linea","808080","FFFFFF",$objPHPExcel);
					
					$time = strtotime($fecha->format('d-m-Y h:i A'));
					$time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));
					if($intervencion["tipointervencion"]=='RI'){
						$this->delta_1($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						$this->delta_2($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					}
					if($intervencion["tipointervencion"]!='MP'){
						$this->delta_3($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					}
					
					if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
						$this->get_elementos($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					}
					
					if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
						$this->delta_4($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					}
					
					if($intervencion["tipointervencion"]=='MP'&&$intervencion["tipomantencion"]!='1500'){
						$this->delta_mp($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						/*
						$tiempo_trabajo = ($time_termino - $time)/3600;
						$dataLocal[9] = "CMP";
						$dataLocal[10] = "DCC";
						$dataLocal[11] = "Intervención";
						$dataLocal[12] = date('d-m-Y', $time);
						$dataLocal[13] = date('H:i', $time);
						$dataLocal[14] = date('d-m-Y', $time_termino);
						$dataLocal[15] = date('H:i', $time_termino);
						$dataLocal[16] = $tiempo_trabajo;
						$dataLocal[20] = "Tiempo";
						$dataLocal[21] = "Inicio Mantención-Termino Mantención";
						$dataLocal[22] = "Delta3";
						$dataLocal[24] = "Mantención";
						$dataArray[] = $dataLocal;
						*/
					}else{
						$this->espera_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						$this->registro_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						$this->espera_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						$this->registro_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						$this->espera_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						$this->registro_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						$this->registro_prueba_potencia($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					}
					
					if($intervencion["tipointervencion"]!='MP'){
						$this->delta_5($time,$json,$dataLocal,$dataArray,$objPHPExcel);
						$this->get_elementos_reproceso($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					}
					
					$this->registro_fluidos($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel);
					$time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));
					if (isset($intervencion['fecha_operacion'])) {
						$this->delta_operacion($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel);
					}
				}
				if($intervencion["hijo"]!=null&&$intervencion["hijo"]!=''){
					$this->get_son($intervencion["hijo"],$original,$dataArray,$objPHPExcel);
				}
			}
		}
		
		function get_son_test($identificador = null, $original = null, &$dataArray, &$objPHPExcel){
			$this->loadModel('Planificacion');
			$util = new UtilidadesController();
			$intervencion = $this->Planificacion->find('first', array( 
														'fields' => array('Planificacion.id','Planificacion.fecha','Planificacion.fecha_termino','Planificacion.hora','Planificacion.hora_termino','Planificacion.json','Planificacion.padre','Planificacion.hijo','Planificacion.estado','Planificacion.aprobador_id','Planificacion.tipointervencion','Planificacion.fecha_operacion','Planificacion.tipomantencion','Planificacion.folio','Planificacion.turno','Planificacion.periodo','Planificacion.supervisor_responsable'),
														'conditions' => array(
															'Planificacion.padre' => $identificador,
															'estado IN (4, 5, 6)'),
													'recursive' => -1));
			if(count($intervencion)>0&&isset($intervencion["Planificacion"])){
				$intervencion = $intervencion["Planificacion"];
				$fecha = new DateTime($intervencion['fecha'] . ' ' . $intervencion['hora']);
				$fecha_termino = new DateTime($intervencion['fecha_termino'] . ' ' . $intervencion['hora_termino']);
				$diff = $fecha_termino->diff($fecha);
				$tiempo_trabajo = $diff->h + $diff->i / 60.0;
				$tiempo_trabajo = $tiempo_trabajo + ($diff->days*24);
				$json = json_decode($intervencion["json"], true);
				$tecnico = array();
				$tecnico[] = is_numeric(@$json["UserID"]) ? $util->getTecnicoRut($json["UserID"]) : "";
				$tecnico[] = is_numeric(@$json["TecnicoApoyo02"])?$util->getTecnicoRut(@$json["TecnicoApoyo02"]):"";
				$tecnico[] = is_numeric(@$json["TecnicoApoyo03"])?$util->getTecnicoRut(@$json["TecnicoApoyo03"]):"";
				$tecnico[] = is_numeric(@$json["TecnicoApoyo04"])?$util->getTecnicoRut(@$json["TecnicoApoyo04"]):"";
				$tecnico[] = is_numeric(@$json["TecnicoApoyo05"])?$util->getTecnicoRut(@$json["TecnicoApoyo05"]):"";
				$tecnico[] = is_numeric(@$json["TecnicoApoyo06"])?$util->getTecnicoRut(@$json["TecnicoApoyo06"]):"";
				$aprobador = $util->getTecnico(@$intervencion['aprobador_id']);
				$supervisor = $util->getTecnico(@$intervencion['supervisor_responsable']);
				$turno = strtoupper($intervencion["turno"]);
				$periodo = "";
				switch(strtoupper($intervencion["periodo"])) {
					case 'D':
						$periodo = 'Día';
						break;
					case 'N':
						$periodo = 'Noche';
						break;
					default:
						$periodo = '';
						break;
				}
				$comentarios = "";
				if(@$json["Comentarios"]!=""){
					$comentarios = @$json["Comentarios"];
				}elseif(@$json["Comentario"]!=""){
					$comentarios = @$json["Comentario"];
				}
				$horometro_final = 0;
				if (isset($json["horometro_pm"]))
					$horometro_final = @$json["horometro_pm"];
				elseif (isset($json["horometro_final"]))
					$horometro_final = @$json["horometro_final"];
				elseif (isset($json["horometro_cabina"]))
					$horometro_final = @$json["horometro_cabina"];
				else
					$horometro_final = $original[7];
					
				$statuskcc = $util->getEstadoKCH($intervencion["estado"]);
				$dataLocal = array();
				$dataLocal[] = $original[0];
				$dataLocal[] = $intervencion["id"];
				$dataLocal[] = $original[2];
				$dataLocal[] = $original[3];
				$dataLocal[] = $original[4];
				$dataLocal[] = $original[5];
				$dataLocal[] = $original[7];
				$dataLocal[] = $horometro_final;
				$dataLocal[] = $original[8];
				$dataLocal[] = "C".$original[9];
				$dataLocal[] = "";
				$dataLocal[] = "Continuación";
				$dataLocal[] = $fecha->format('d-m-Y');
				$dataLocal[] = $fecha->format('H:i');
				$dataLocal[] = $fecha_termino->format('d-m-Y');
				$dataLocal[] = $fecha_termino->format('H:i');
				$dataLocal[] = $tiempo_trabajo; // Tiempo trabajo
				$dataLocal[] = $original[17];
				$dataLocal[] = $original[18];
				$dataLocal[] = $original[19];
				$dataLocal[] = "";
				$dataLocal[] = "";
				$dataLocal[] = "";
				$dataLocal[] = "";
				$dataLocal[] = "";
				$dataLocal[] = "";
				$dataLocal[] = "";
				$dataLocal[] = "";
				$dataLocal[] = "";
				$dataLocal[] = $original[29];//$cambio_modulo;
				$dataLocal[] = $original[30];//$evento_finalizado;
				$dataLocal[] = $original[31];
				foreach($tecnico as $k=>$v){
					$dataLocal[] = $v;	
				}
				$dataLocal[] = $supervisor;
				$dataLocal[] = $turno;
				$dataLocal[] = $periodo;
				$dataLocal[] = $aprobador;
				$dataLocal[] = $statuskcc;
				$dataLocal[] = $comentarios;
				
				// Delta continuacion de trabajos
				//$time_anterior = strtotime($original[14]. " " .$original[15]);
				$time_anterior = strtotime($fecha->format('d-m-Y h:i A'));
				$this->delta_continuacion($time_anterior,$json,$dataLocal,$dataArray,$objPHPExcel);
				$dataArray[] = $dataLocal;
				
				$linea = count($dataArray) + 1;
				// Formateo de linea principal
				$this->cellColor("A$linea:AR$linea","808080","FFFFFF",$objPHPExcel);
				
				$time = strtotime($fecha->format('d-m-Y h:i A'));
				$time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));
				if($intervencion["tipointervencion"]=='EX'||$intervencion["tipointervencion"]=='RI'){
					$this->delta_1($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
					$this->delta_2($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
				}
				
				if($intervencion["tipointervencion"]!='MP'){
					$this->delta_3($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
				}
				
				if($intervencion["tipointervencion"]!='MP'){
					$this->get_elementos($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
				}
				
				if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
					$this->delta_4($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
				}
				
				if($intervencion["tipointervencion"]=='MP'&&$intervencion["tipomantencion"]!='1500'){
					$this->delta_mp($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
				}
				
				if($intervencion["tipointervencion"]!='EX'){
					$this->espera_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					$this->registro_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
					$this->espera_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					$this->registro_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
					$this->espera_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					$this->registro_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel);
					$this->registro_prueba_potencia($time,$json,$dataLocal,$dataArray,$objPHPExcel);
				}
				
				if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
					$this->delta_5($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
					$this->get_elementos_reproceso($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
				}
				
				$this->registro_fluidos($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel);
				$time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));
				if (isset($intervencion['fecha_operacion'])) {
					$this->delta_operacion($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel);
				}
				$this->get_son_test($intervencion["folio"],$original,$dataArray,$objPHPExcel);
			}
		}
		
		function cellColor($cells,$color,$fontcolor,$objPHPExcel){
			$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array(
					 'rgb' => $color
				)
			));
			$objPHPExcel->getActiveSheet()->getStyle($cells)->applyFromArray(array(
				'font'  => array(
					'color' => array('rgb' => $fontcolor)
				)
			));
		}
		
		function delta_1(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '1'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					$dataLocal[9] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal[10] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal[11] = "Tiempo";
					$dataLocal[12] = date('d-m-Y', $time);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $time_termino);
					$dataLocal[15] = date('H:i', $time_termino);
					$dataLocal[16] = $tiempo_trabajo;
					$dataLocal[43] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal[20] = "Tiempo";
					$dataLocal[21] = "Llamado-Lugar Físico";
					$dataLocal[22] = "Delta1";
					$dataLocal[24] = $delta["DeltaItem"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function generar_delta_1(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '1'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal["sistema"] = "Tiempo";
					$dataLocal["subsistema"] = "Llamado-Lugar Físico";
					$dataLocal["pos_subsistema"] = "Delta1";
					$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function delta_1_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '1'),
															 'recursive' => 1));
			$return = "";	
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + intval($delta["DeltaDetalle"]["tiempo"]) * 60;
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
					$return .= "<td>".$delta["DeltaResponsable"]["nombre"]."</td>";
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
			return $return;
		}
		
		function delta_2(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '2'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					$dataLocal[9] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal[10] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal[11] = "Tiempo";
					$dataLocal[12] = date('d-m-Y', $time);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $time_termino);
					$dataLocal[15] = date('H:i', $time_termino);
					$dataLocal[16] = $tiempo_trabajo;
					$dataLocal[43] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal[20] = "Tiempo";
					$dataLocal[21] = "Lugar Físico-Inicio Interv.";
					$dataLocal[22] = "Delta2";
					$dataLocal[24] = $delta["DeltaItem"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function generar_delta_2(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '2'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal["sistema"] = "Tiempo";
					$dataLocal["subsistema"] = "Lugar Físico-Inicio Interv.";
					$dataLocal["pos_subsistema"] = "Delta2";
					$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
					
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function delta_2_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '2'),
															 'recursive' => 1));
			$return = "";	
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + intval($delta["DeltaDetalle"]["tiempo"]) * 60;
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
					$return .= "<td>".$delta["DeltaResponsable"]["nombre"]."</td>";
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
			return $return;
		}
		
		function delta_3(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaItem.id','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '3'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"]) && $delta["DeltaItem"]["id"] != '16') {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					$dataLocal[9] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal[10] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal[11] = "Tiempo";
					$dataLocal[12] = date('d-m-Y', $time);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $time_termino);
					$dataLocal[15] = date('H:i', $time_termino);
					$dataLocal[16] = $tiempo_trabajo;
					$dataLocal[43] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal[20] = "Tiempo";
					$dataLocal[21] = "Inicio Interv-Término Interv.";
					$dataLocal[22] = "Delta3";
					$dataLocal[24] = $delta["DeltaItem"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function generar_delta_3(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaItem.id','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '3'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"]) && $delta["DeltaItem"]["id"] != '16') {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					
					
					$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal["sistema"] = "Tiempo";
					$dataLocal["subsistema"] = "Inicio Interv-Término Interv.";
					$dataLocal["pos_subsistema"] = "Delta3";
					$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
					
					
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function delta_3_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '3'),
															 'recursive' => 1));
			$return = "";	
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + intval($delta["DeltaDetalle"]["tiempo"]) * 60;
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if($delta["DeltaItem"]["id"] != '16'){
						$return .= "<td>Tiempo</td>";
						$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
						$return .= "<td>".$delta["DeltaResponsable"]["nombre"]."</td>";
					}else{
						$return .= "<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
						$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
						$return .= "<td>Intervención</td>";
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
			return $return;
		}
		
		function delta_4(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '4'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal["sistema"] = "Tiempo";
					$dataLocal["subsistema"] = "Término Interv-Inicio P.P.";
					$dataLocal["pos_subsistema"] = "Delta4";
					$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
					
					
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function generar_delta_4(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '4'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal["sistema"] = "Tiempo";
					$dataLocal["subsistema"] = "Término Interv-Inicio P.P.";
					$dataLocal["pos_subsistema"] = "Delta4";
					$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
					
					
					
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function delta_4_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '4'),
															 'recursive' => 1));
			$return = "";	
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + intval($delta["DeltaDetalle"]["tiempo"]) * 60;
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
					$return .= "<td>".$delta["DeltaResponsable"]["nombre"]."</td>";
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
			return $return;
		}
		
		function delta_5(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '5'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"]) && $delta["DeltaItem"]["id"] != '32') {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					$dataLocal[9] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal[10] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal[11] = "Tiempo";
					$dataLocal[12] = date('d-m-Y', $time);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $time_termino);
					$dataLocal[15] = date('H:i', $time_termino);
					$dataLocal[16] = $tiempo_trabajo;
					$dataLocal[43] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal[20] = "Tiempo";
					$dataLocal[21] = "Reproceso";
					$dataLocal[22] = "Delta5";
					$dataLocal[24] = $delta["DeltaItem"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function generar_delta_5(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '5'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"]) && $delta["DeltaItem"]["id"] != '32') {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					
					
					$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
					$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal["sistema"] = "Tiempo";
					$dataLocal["subsistema"] = "Reproceso";
					$dataLocal["pos_subsistema"] = "Delta5";
					$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
					
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function delta_5_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '5'),
															 'recursive' => 1));
			$return = "";	
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + intval($delta["DeltaDetalle"]["tiempo"]) * 60;
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if($delta["DeltaItem"]["id"] != '32'){
						$return .= "<td>Tiempo</td>";
						$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
						$return .= "<td>".$delta["DeltaResponsable"]["nombre"]."</td>";
					}else{
						$return .= "<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
						$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
						$return .= "<td>Intervencion</td>";
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
			return $return;
		}
		
		function delta_operacion(&$time,$json,$original,&$dataArray, &$objPHPExcel){
			if (is_array($json) && count($json) > 0) {
				foreach($json as $key => $value) {
					if (substr($key, 0, 4) == "ds3_" && substr($key, -2) == "_r") {
						$newkey = substr($key, 0, -2);
						$hora = intval(@$json[$newkey."_h"]);
						$minuto = intval(@$json[$newkey."_m"]);
						if ($hora!=0||$minuto!=0) {
							$dataLocal = $original;
							$comentario = @$json[$newkey."_o"];
							$responsable = @$json[$newkey."_r"];
							$tiempo_trabajo = ($hora * 60 + $minuto) / 60;
							$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
							if ($responsable == 1) {
								$dataLocal[9] = "T_DCC";
								$dataLocal[10] = "DCC";
							}elseif ($responsable == 2) {
								$dataLocal[9] = "T_OEM";
								$dataLocal[10] = "OEM";
							}elseif ($responsable == 3) {
								$dataLocal[9] = "T_MINA";
								$dataLocal[10] = "MINA";
							}else{
								$dataLocal[9] = "";
								$dataLocal[10] = "";
							}
							$dataLocal[11] = "Final";
							$dataLocal[12] = date('d-m-Y', $time);
							$dataLocal[13] = date('H:i', $time);
							$dataLocal[14] = date('d-m-Y', $time_termino);
							$dataLocal[15] = date('H:i', $time_termino);
							$dataLocal[16] = $tiempo_trabajo;
							$dataLocal[43] = $comentario;
							$dataLocal[20] = "Tiempo";
							$dataLocal[21] = "Término Interv.-Inicio Operación";
							$dataLocal[22] = "DeltaS3";
							$dataLocal[24] = $this->getDelta($newkey);
							$dataArray[] = $dataLocal;
							$linea = count($dataArray) + 1;
							$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
							$time = $time_termino;
						}
					}
				}
			}
		}
		
		function generar_delta_operacion(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			// Delta 8
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '8'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
				$tiempo_trabajo = $tiempo / 60;
				$time_termino = $time + $tiempo * 60;
				$dataLocal = $original;
				
				
				$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
				$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
				$dataLocal["categoria"] = "Final";
				$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
				$dataLocal["hora_inicio"] = date('H:i', $time);
				$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
				$dataLocal["hora_termino"] = date('H:i', $time_termino);
				$dataLocal["tiempo"] = $tiempo_trabajo;
				$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
				$dataLocal["sistema"] = "Tiempo";
				$dataLocal["subsistema"] = "Término Interv.-Inicio Operacion";
				$dataLocal["pos_subsistema"] = "DeltaS3";
				$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
				
				$dataArray[] = $dataLocal;
				$linea = count($dataArray) + 1;
				$time = $time_termino;
			}
		}
		
		function delta_continuacion($time,$json,$original,&$dataArray, &$objPHPExcel){
			if (is_array($json) && count($json) > 0) {
				foreach($json as $key => $value) {
					if (substr($key, 0, 4) == "ds1_" && substr($key, -2) == "_r") {
						$newkey = substr($key, 0, -2);
						$hora = intval(@$json[$newkey."_h"]);
						$minuto = intval(@$json[$newkey."_m"]);
						$time = $time - $hora * 60 * 60 - $minuto * 60;
					}
				}
			}
			if (is_array($json) && count($json) > 0) {
				foreach($json as $key => $value) {
					if (substr($key, 0, 4) == "ds1_" && substr($key, -2) == "_r") {
						$newkey = substr($key, 0, -2);
						$hora = intval(@$json[$newkey."_h"]);
						$minuto = intval(@$json[$newkey."_m"]);
						if ($hora!=0||$minuto!=0) {
							$dataLocal = $original;
							$comentario = @$json[$newkey."_o"];
							$responsable = @$json[$newkey."_r"];
							$tiempo_trabajo = ($hora * 60 + $minuto) / 60;
							$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
							if ($responsable == 1) {
								$dataLocal[9] = "T_DCC";
								$dataLocal[10] = "DCC";
							}elseif ($responsable == 2) {
								$dataLocal[9] = "T_OEM";
								$dataLocal[10] = "OEM";
							}elseif ($responsable == 3) {
								$dataLocal[9] = "T_MINA";
								$dataLocal[10] = "MINA";
							}else{
								$dataLocal[9] = "";
								$dataLocal[10] = "";
							}
							$dataLocal[11] = "Tiempo";
							$dataLocal[12] = date('d-m-Y', $time);
							$dataLocal[13] = date('H:i', $time);
							$dataLocal[14] = date('d-m-Y', $time_termino);
							$dataLocal[15] = date('H:i', $time_termino);
							$dataLocal[16] = $tiempo_trabajo;
							$dataLocal[43] = $comentario;
							$dataLocal[20] = "Tiempo";
							$dataLocal[21] = "Término Interv.-Inicio Interv.";
							$dataLocal[22] = "DeltaS1";
							$dataLocal[24] = $this->getDelta($newkey);
							$dataArray[] = $dataLocal;
							$linea = count($dataArray) + 1;
							$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
							$time = $time_termino;
						}
					}
				}
			}
		}
		
		function generar_delta_continuacion($time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			// Delta 7
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '7'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
				$tiempo_trabajo = $tiempo / 60;
				$time_termino = $time + $tiempo * 60;
				$dataLocal = $original;
				
				
				$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
				$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
				$dataLocal["categoria"] = "Tiempo";
				$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
				$dataLocal["hora_inicio"] = date('H:i', $time);
				$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
				$dataLocal["hora_termino"] = date('H:i', $time_termino);
				$dataLocal["tiempo"] = $tiempo_trabajo;
				$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
				$dataLocal["sistema"] = "Tiempo";
				$dataLocal["subsistema"] = "Término Interv.-Inicio Interv.";
				$dataLocal["pos_subsistema"] = "DeltaS1";
				$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
				
				$dataArray[] = $dataLocal;
				$linea = count($dataArray) + 1;
				$time = $time_termino;
			}
		}

		function delta_mp(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '6'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					if($delta["DeltaItem"]["id"] != '43'){
						$dataLocal[9] = "T_" . $delta["DeltaResponsable"]["nombre"];
					}else{
						//$dataLocal[9] = "MP";
					}
					$dataLocal[10] = $delta["DeltaResponsable"]["nombre"];
					if($delta["DeltaItem"]["id"] != '43'){
						$dataLocal[11] = "Tiempo";
					}else{
						$dataLocal[11] = "Intervención";
					}
					$dataLocal[12] = date('d-m-Y', $time);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $time_termino);
					$dataLocal[15] = date('H:i', $time_termino);
					$dataLocal[16] = $tiempo_trabajo;
					$dataLocal[43] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal[20] = "Tiempo";
					$dataLocal[21] = "Inicio Mantención-Termino Mantención";
					$dataLocal[22] = "Delta3";
					$dataLocal[24] = $delta["DeltaItem"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}	
		
		
		function generar_delta_mp(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '6'),
															 'recursive' => 1));
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$tiempo = intval($delta["DeltaDetalle"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					if($delta["DeltaItem"]["id"] != '43'){
						$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
					}else{
						$dataLocal["actividad"]  = "MP";
					}
					$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
					if($delta["DeltaItem"]["id"] != '43'){
						$dataLocal["categoria"] = "Tiempo";
					}else{
						$dataLocal["categoria"] = "Intervención";
					}
					
					
					//$dataLocal["actividad"] = "T_" . $delta["DeltaResponsable"]["nombre"];
					//$dataLocal["responsable"] = $delta["DeltaResponsable"]["nombre"];
					//$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["comentario_tecnico"] = $delta["DeltaDetalle"]["observacion"];
					$dataLocal["sistema"] = "Tiempo";
					$dataLocal["subsistema"] = "Inicio Mantención-Término Mantención";
					$dataLocal["pos_subsistema"] = "Delta3";
					$dataLocal["elemento"] = $delta["DeltaItem"]["nombre"];
					
					
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}

		function delta_mp_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('DeltaDetalle');
			$deltas = $this->DeltaDetalle->find('all', array('fields' => array('DeltaDetalle.tiempo','DeltaDetalle.observacion','DeltaItem.nombre','DeltaResponsable.nombre','DeltaItem.id'),
															 'conditions' => array('DeltaDetalle.folio' => $folio, 'DeltaItem.grupo' => '6'),
															 'recursive' => 1));
			$return = "";	
			foreach($deltas as $delta){
				if(is_numeric($delta["DeltaDetalle"]["tiempo"])) {
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + intval($delta["DeltaDetalle"]["tiempo"]) * 60;
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if($delta["DeltaItem"]["id"] != '43'){
						$return .= "<td>Tiempo</td>";
						$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
						$return .= "<td>".$delta["DeltaResponsable"]["nombre"]."</td>";
					}else{
						$return .= "<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
						$return .= "<td>T_" . $delta["DeltaResponsable"]["nombre"]."</td>";
						$return .= "<td>Intervencion</td>";
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
			return $return;
		}		

		function get_elementos(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('IntervencionElementos');
			$elementos = $this->IntervencionElementos->find('all', array('fields' => array('IntervencionElementos.tiempo','IntervencionElementos.id_elemento','Sistema.nombre','Subsistema.nombre','Elemento.nombre','Diagnostico.nombre','Solucion.nombre','Posiciones_Elemento.nombre','Posiciones_Subsistema.nombre','TipoElemento.nombre'),
															 'conditions' => array('IntervencionElementos.folio' => $folio, 'tipo_registro' => '0'),
															 'recursive' => 1));
			foreach($elementos as $elemento){
				if(is_numeric($elemento["IntervencionElementos"]["tiempo"])) {
					$tiempo = intval($elemento["IntervencionElementos"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Intervención";
					$dataLocal[12] = date('d-m-Y', $time);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $time_termino);
					$dataLocal[15] = date('H:i', $time_termino);
					$dataLocal[16] = $tiempo_trabajo;
					$dataLocal[20] = $elemento["Sistema"]["nombre"];
					$dataLocal[21] = $elemento["Subsistema"]["nombre"];
					$dataLocal[22] = $elemento["Posiciones_Subsistema"]["nombre"];					
					$dataLocal[23] = $elemento["IntervencionElementos"]["id_elemento"];
					$dataLocal[24] = $elemento["Elemento"]["nombre"];
					$dataLocal[25] = $elemento["Posiciones_Elemento"]["nombre"];
					$dataLocal[26] = $elemento["Diagnostico"]["nombre"];
					$dataLocal[27] = $elemento["Solucion"]["nombre"];
					$dataLocal[28] = $elemento["TipoElemento"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function generar_get_elementos(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('IntervencionElementos');
			$elementos = $this->IntervencionElementos->find('all', array('fields' => array('IntervencionElementos.tiempo','IntervencionElementos.id_elemento','Sistema.nombre','Subsistema.nombre','Elemento.nombre','Diagnostico.nombre','Solucion.nombre','Posiciones_Elemento.nombre','Posiciones_Subsistema.nombre','TipoElemento.nombre'),
															 'conditions' => array('IntervencionElementos.folio' => $folio, 'tipo_registro' => '0'),
															 'recursive' => 1));
			foreach($elementos as $elemento){
				if(is_numeric($elemento["IntervencionElementos"]["tiempo"])) {
					$tiempo = intval($elemento["IntervencionElementos"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;

					$dataLocal["responsable"]= "DCC";
					$dataLocal["categoria"] = "Intervención";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["sistema"] = $elemento["Sistema"]["nombre"];
					$dataLocal["subsistema"] = $elemento["Subsistema"]["nombre"];
					$dataLocal["pos_subsistema"] = $elemento["Posiciones_Subsistema"]["nombre"];					
					$dataLocal["id_code"] = $elemento["IntervencionElementos"]["id_elemento"];
					$dataLocal["elemento"] = $elemento["Elemento"]["nombre"];
					$dataLocal["pos_elemento"] = $elemento["Posiciones_Elemento"]["nombre"];
					$dataLocal["diagnostico"] = $elemento["Diagnostico"]["nombre"];
					$dataLocal["solucion"] = $elemento["Solucion"]["nombre"];
					$dataLocal["causa"] = $elemento["TipoElemento"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function get_elementos_reproceso(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('IntervencionElementos');
			$elementos = $this->IntervencionElementos->find('all', array('fields' => array('IntervencionElementos.tiempo','IntervencionElementos.id_elemento','Sistema.nombre','Subsistema.nombre','Elemento.nombre','Diagnostico.nombre','Solucion.nombre','Posiciones_Elemento.nombre','Posiciones_Subsistema.nombre','TipoElemento.nombre'),
															 'conditions' => array('IntervencionElementos.folio' => $folio, 'tipo_registro' => '1'),
															 'recursive' => 1));
			foreach($elementos as $elemento){
				if(is_numeric($elemento["IntervencionElementos"]["tiempo"])) {
					$tiempo = intval($elemento["IntervencionElementos"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Intervención";
					$dataLocal[12] = date('d-m-Y', $time);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $time_termino);
					$dataLocal[15] = date('H:i', $time_termino);
					$dataLocal[16] = $tiempo_trabajo;
					$dataLocal[20] = $elemento["Sistema"]["nombre"];
					$dataLocal[21] = $elemento["Subsistema"]["nombre"];
					$dataLocal[22] = $elemento["Posiciones_Subsistema"]["nombre"];					
					$dataLocal[23] = $elemento["IntervencionElementos"]["id_elemento"];
					$dataLocal[24] = $elemento["Elemento"]["nombre"];
					$dataLocal[25] = $elemento["Posiciones_Elemento"]["nombre"];
					$dataLocal[26] = $elemento["Diagnostico"]["nombre"];
					$dataLocal[27] = $elemento["Solucion"]["nombre"];
					$dataLocal[28] = $elemento["TipoElemento"]["nombre"];
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function generar_get_elementos_reproceso(&$time,$original,&$dataArray,&$objPHPExcel,$folio){
			$this->loadModel('IntervencionElementos');
			$elementos = $this->IntervencionElementos->find('all', array('fields' => array('IntervencionElementos.tiempo','IntervencionElementos.id_elemento','Sistema.nombre','Subsistema.nombre','Elemento.nombre','Diagnostico.nombre','Solucion.nombre','Posiciones_Elemento.nombre','Posiciones_Subsistema.nombre','TipoElemento.nombre'),
															 'conditions' => array('IntervencionElementos.folio' => $folio, 'tipo_registro' => '1'),
															 'recursive' => 1));
			foreach($elementos as $elemento){
				if(is_numeric($elemento["IntervencionElementos"]["tiempo"])) {
					$tiempo = intval($elemento["IntervencionElementos"]["tiempo"]);
					$tiempo_trabajo = $tiempo / 60;
					$time_termino = $time + $tiempo * 60;
					$dataLocal = $original;
					
					$dataLocal["responsable"]= "DCC";
					$dataLocal["categoria"] = "Intervención";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $time);
					$dataLocal["hora_inicio"] = date('H:i', $time);
					$dataLocal["fecha_termino"] = date('Y-m-d', $time_termino);
					$dataLocal["hora_termino"] = date('H:i', $time_termino);
					$dataLocal["tiempo"] = $tiempo_trabajo;
					$dataLocal["sistema"] = $elemento["Sistema"]["nombre"];
					$dataLocal["subsistema"] = $elemento["Subsistema"]["nombre"];
					$dataLocal["pos_subsistema"] = $elemento["Posiciones_Subsistema"]["nombre"];					
					$dataLocal["id_code"] = $elemento["IntervencionElementos"]["id_elemento"];
					$dataLocal["elemento"] = $elemento["Elemento"]["nombre"];
					$dataLocal["pos_elemento"] = $elemento["Posiciones_Elemento"]["nombre"];
					$dataLocal["diagnostico"] = $elemento["Diagnostico"]["nombre"];
					$dataLocal["solucion"] = $elemento["Solucion"]["nombre"];
					$dataLocal["causa"] = $elemento["TipoElemento"]["nombre"];
					
					
					$dataArray[] = $dataLocal;
					$linea = count($dataArray) + 1;
					$this->cellColor("A$linea:AR$linea","DDEBF7","000000",$objPHPExcel);
					$time = $time_termino;	
				}
			}
		}
		
		function espera_desconexion(&$time,$json,$original,&$dataArray, &$objPHPExcel){
			if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"])) {
				$fecha = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
				$fecha_termino = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					$dataLocal[9] = "T_DCC";
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Tiempo";
					$dataLocal[12] = date('d-m-Y', $fecha);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $fecha_termino);
					$dataLocal[15] = date('H:i', $fecha_termino);
					$dataLocal[16] = $duracion;
					$dataLocal[20] = "00_Motor completo";
					$dataLocal[21] = "Cambio de Modulo";
					$dataLocal[24] = "Espera Descnexión";
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function generar_espera_desconexion(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.termino_intervencion	','IntervencionFechas.inicio_desconexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.termino_intervencion IS NOT NULL', 'IntervencionFechas.inicio_desconexion IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["termino_intervencion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["inicio_desconexion"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					$dataLocal["actividad"] = "T_DCC";
					$dataLocal["responsable"] = "DCC";
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
					$dataLocal["hora_inicio"] = date('H:i', $fecha);
					$dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
					$dataLocal["hora_termino"] = date('H:i', $fecha_termino);
					$dataLocal["tiempo"] = $duracion;
					$dataLocal["sistema"] = "00_Motor completo";
					$dataLocal["subsistema"] = "Cambio de Módulo";
					$dataLocal["elemento"] = "Espera Desconexión";
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function espera_desconexion_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_desconexion','IntervencionFechas.termino_intervencion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_desconexion IS NOT NULL', 'IntervencionFechas.termino_intervencion IS NOT NULL'),
															 'recursive' => -1));
			
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["termino_intervencion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["inicio_desconexion"]);
				if($fecha_termino > $fecha){
					$return = "";	
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + ($fecha_termino - $fecha) / (60 * 60);
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>T_OEM</td>";
					$return .= "<td>OEM</td>";
					$return .=  "<td>".date("d-m-Y", $fecha)."</td>";
					$return .=  "<td>".date("h:i A", $fecha)."</td>";
					$return .=  "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .=  "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .=  "<td>$duracion</td>";
					$return .=  "<td nowrap>$sintoma</td>";
					$return .=  "</tr>";
					$fecha = $time_termino;
					return $return;
				}
			}
		}
		
		function registro_desconexion(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_desconexion','IntervencionFechas.termino_desconexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_desconexion IS NOT NULL', 'IntervencionFechas.termino_desconexion IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["inicio_desconexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_desconexion"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					$dataLocal[9] = "T_DCC";
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Intervención";
					$dataLocal[12] = date('d-m-Y', $fecha);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $fecha_termino);
					$dataLocal[15] = date('H:i', $fecha_termino);
					$dataLocal[16] = $duracion;
					$dataLocal[20] = "00_Motor completo";
					$dataLocal[21] = "Cambio de Modulo";
					$dataLocal[24] = "Desconexión";
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function generar_registro_desconexion(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_desconexion','IntervencionFechas.termino_desconexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_desconexion IS NOT NULL', 'IntervencionFechas.termino_desconexion IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["inicio_desconexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_desconexion"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_DCC";
					$dataLocal["responsable"] = "DCC";
					$dataLocal["categoria"] = "Intervención";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
					$dataLocal["hora_inicio"] = date('H:i', $fecha);
					$dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
					$dataLocal["hora_termino"] = date('H:i', $fecha_termino);
					$dataLocal["tiempo"] = $duracion;
					$dataLocal["sistema"] = "00_Motor completo";
					$dataLocal["subsistema"] = "Cambio de Módulo";
					$dataLocal["elemento"] = "Desconexión";
					
					
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function registro_desconexion_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_desconexion','IntervencionFechas.termino_desconexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_desconexion IS NOT NULL', 'IntervencionFechas.termino_desconexion IS NOT NULL'),
															 'recursive' => -1));
			
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["inicio_desconexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_desconexion"]);
				if($fecha_termino > $fecha){
					$return = "";	
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + ($fecha_termino - $fecha) / (60 * 60);
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>T_DCC</td>";
					$return .= "<td>DCC</td>";
					$return .=  "<td>".date("d-m-Y", $fecha)."</td>";
					$return .=  "<td>".date("h:i A", $fecha)."</td>";
					$return .=  "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .=  "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .=  "<td>$duracion</td>";
					$return .=  "<td nowrap>$sintoma</td>";
					$return .=  "</tr>";
					$fecha = $time_termino;
					return $return;
				}
			}
		}
		
		function espera_conexion(&$time,$json,$original,&$dataArray, &$objPHPExcel){
			if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
				$fecha = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
				$fecha_termino = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					$dataLocal[9] = "T_DCC";
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Tiempo";
					$dataLocal[12] = date('d-m-Y', $fecha);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $fecha_termino);
					$dataLocal[15] = date('H:i', $fecha_termino);
					$dataLocal[16] = $duracion;
					$dataLocal[20] = "00_Motor completo";
					$dataLocal[21] = "Cambio de Modulo";
					$dataLocal[24] = "Espera Conexión";
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function generar_espera_conexion(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.termino_desconexion','IntervencionFechas.inicio_conexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.termino_desconexion IS NOT NULL', 'IntervencionFechas.inicio_conexion IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["termino_desconexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["inicio_conexion"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_DCC";
					$dataLocal["responsable"] = "DCC";
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
					$dataLocal["hora_inicio"] = date('H:i', $fecha);
					$dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
					$dataLocal["hora_termino"] = date('H:i', $fecha_termino);
					$dataLocal["tiempo"] = $duracion;
					$dataLocal["sistema"] = "00_Motor completo";
					$dataLocal["subsistema"] = "Cambio de Módulo";
					$dataLocal["elemento"] = "Espera Conexión";
					
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function espera_conexion_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_conexion','IntervencionFechas.termino_desconexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_conexion IS NOT NULL', 'IntervencionFechas.termino_desconexion IS NOT NULL'),
															 'recursive' => -1));
			
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["termino_desconexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["inicio_conexion"]);
				if($fecha_termino > $fecha){
					$return = "";	
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + ($fecha_termino - $fecha) / (60 * 60);
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>Tiempo</td>";
					$return .= "<td>T_OEM</td>";
					$return .= "<td>OEM</td>";
					$return .=  "<td>".date("d-m-Y", $fecha)."</td>";
					$return .=  "<td>".date("h:i A", $fecha)."</td>";
					$return .=  "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .=  "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .=  "<td>$duracion</td>";
					$return .=  "<td nowrap>$sintoma</td>";
					$return .=  "</tr>";
					$fecha = $time_termino;
					return $return;
				}
			}
		}
		
		function registro_conexion(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_conexion','IntervencionFechas.termino_conexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_conexion IS NOT NULL', 'IntervencionFechas.termino_conexion IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["inicio_conexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_conexion"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					$dataLocal[9] = "T_DCC";
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Intervención";
					$dataLocal[12] = date('d-m-Y', $fecha);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $fecha_termino);
					$dataLocal[15] = date('H:i', $fecha_termino);
					$dataLocal[16] = $duracion;
					$dataLocal[20] = "00_Motor completo";
					$dataLocal[21] = "Cambio de Modulo";
					$dataLocal[24] = "Desconexión";
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function generar_registro_conexion(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_conexion','IntervencionFechas.termino_conexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_conexion IS NOT NULL', 'IntervencionFechas.termino_conexion IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["inicio_conexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_conexion"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_DCC";
					$dataLocal["responsable"] = "DCC";
					$dataLocal["categoria"] = "Intervención";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
					$dataLocal["hora_inicio"] = date('H:i', $fecha);
					$dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
					$dataLocal["hora_termino"] = date('H:i', $fecha_termino);
					$dataLocal["tiempo"] = $duracion;
					$dataLocal["sistema"] = "00_Motor completo";
					$dataLocal["subsistema"] = "Cambio de Módulo";
					$dataLocal["elemento"] = "Conexión";
					
					
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function registro_conexion_reporte(&$fecha,$folio,$intervencion,$esn,$sintoma){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_conexion','IntervencionFechas.termino_conexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_conexion IS NOT NULL', 'IntervencionFechas.termino_conexion IS NOT NULL'),
															 'recursive' => -1));
			
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["inicio_conexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_conexion"]);
				if($fecha_termino > $fecha){
					$return = "";	
					$return .= "<tr style=\"background-color: #DDEBF7; color: black;\">";
					$duracion = number_format((intval($delta["DeltaDetalle"]["tiempo"])) / 60, 2, ",",".");
					$time_termino = $fecha + ($fecha_termino - $fecha) / (60 * 60);
					$fecha_termino = $time_termino;
					$return .= "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>$esn</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>Tiempdo</td>";
					$return .= "<td>T_DCC</td>";
					$return .= "<td>DCC</td>";
					$return .=  "<td>".date("d-m-Y", $fecha)."</td>";
					$return .=  "<td>".date("h:i A", $fecha)."</td>";
					$return .=  "<td>".date("d-m-Y", $fecha_termino)."</td>";
					$return .=  "<td>".date("h:i A", $fecha_termino)."</td>";
					$return .=  "<td>$duracion</td>";
					$return .=  "<td nowrap>$sintoma</td>";
					$return .=  "</tr>";
					$fecha = $time_termino;
					return $return;
				}
			}
		}
		
		function espera_puesta_marcha(&$time,$json,$original,&$dataArray, &$objPHPExcel){
			if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
				$fecha = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
				$fecha_termino = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					$dataLocal[9] = "T_DCC";
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Tiempo";
					$dataLocal[12] = date('d-m-Y', $fecha);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $fecha_termino);
					$dataLocal[15] = date('H:i', $fecha_termino);
					$dataLocal[16] = $duracion;
					$dataLocal[20] = "00_Motor completo";
					$dataLocal[21] = "Puesta en Marcha";
					$dataLocal[24] = "Espera Puesta en Marcha";
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function generar_espera_puesta_marcha(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_puesta_marcha','IntervencionFechas.termino_conexion'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_puesta_marcha IS NOT NULL', 'IntervencionFechas.termino_conexion IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["termino_conexion"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["inicio_puesta_marcha"]);
				if($fecha_termino>$fecha){

					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_DCC";
					$dataLocal["responsable"] = "DCC";
					$dataLocal["categoria"] = "Tiempo";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
					$dataLocal["hora_inicio"] = date('H:i', $fecha);
					$dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
					$dataLocal["hora_termino"] = date('H:i', $fecha_termino);
					$dataLocal["tiempo"] = $duracion;
					$dataLocal["sistema"] = "00_Motor completo";
					$dataLocal["subsistema"] = "Puesta en Marcha";
					$dataLocal["elemento"] = "Espera Puesta en Marcha";
					
					
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function registro_puesta_marcha(&$time,$json,$original,&$dataArray, &$objPHPExcel){
			if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["pm_t_f"]) && isset($json["pm_t_h"]) && isset($json["pm_t_m"]) && isset($json["pm_t_p"])) {
				$fecha = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
				$fecha_termino = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"].":".$json["pm_t_m"]." ".$json["pm_t_p"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					$dataLocal[9] = "T_DCC";
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Intervención";
					$dataLocal[12] = date('d-m-Y', $fecha);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $fecha_termino);
					$dataLocal[15] = date('H:i', $fecha_termino);
					$dataLocal[16] = $duracion;
					$dataLocal[20] = "00_Motor completo";
					$dataLocal[21] = "Puesta en Marcha";
					$dataLocal[24] = "Puesta en Marcha";
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function generar_registro_puesta_marcha(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_puesta_marcha','IntervencionFechas.termino_puesta_marcha'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_puesta_marcha IS NOT NULL', 'IntervencionFechas.termino_puesta_marcha IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["inicio_puesta_marcha"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_puesta_marcha"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_DCC";
					$dataLocal["responsable"] = "DCC";
					$dataLocal["categoria"] = "Intervención";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
					$dataLocal["hora_inicio"] = date('H:i', $fecha);
					$dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
					$dataLocal["hora_termino"] = date('H:i', $fecha_termino);
					$dataLocal["tiempo"] = $duracion;
					$dataLocal["sistema"] = "00_Motor completo";
					$dataLocal["subsistema"] = "Puesta en Marcha";
					$dataLocal["elemento"] = "Puesta en Marcha";
					
					
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function registro_prueba_potencia(&$time,$json,$original,&$dataArray, &$objPHPExcel){
			if (isset($json["pp_i_f"]) && isset($json["pp_i_h"]) && isset($json["pp_i_m"]) && isset($json["pp_i_p"]) && isset($json["pp_t_f"]) && isset($json["pp_t_h"]) && isset($json["pp_t_m"]) && isset($json["pp_t_p"])) {
				$fecha = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"].":".$json["pp_i_m"]." ".$json["pp_i_p"]);
				$fecha_termino = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"].":".$json["pp_t_m"]." ".$json["pp_t_p"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					$dataLocal[9] = "T_DCC";
					$dataLocal[10] = "DCC";
					$dataLocal[11] = "Intervención";
					$dataLocal[12] = date('d-m-Y', $fecha);
					$dataLocal[13] = date('H:i', $time);
					$dataLocal[14] = date('d-m-Y', $fecha_termino);
					$dataLocal[15] = date('H:i', $fecha_termino);
					$dataLocal[16] = $duracion;
					$dataLocal[20] = "00_Motor completo";
					$dataLocal[21] = "Prueba Potencia";
					$dataLocal[24] = "Prueba Potencia";
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function generar_registro_prueba_potencia(&$time,$json,$original,&$dataArray, &$objPHPExcel,$folio){
			$this->loadModel('IntervencionFechas');
			$fechas = $this->IntervencionFechas->find('first', array('fields' => array('IntervencionFechas.inicio_prueba_potencia','IntervencionFechas.termino_prueba_potencia'),
															 'conditions' => array('IntervencionFechas.folio' => $folio, 'IntervencionFechas.inicio_prueba_potencia IS NOT NULL', 'IntervencionFechas.termino_prueba_potencia IS NOT NULL'),
															 'recursive' => -1));
			if(isset($fechas["IntervencionFechas"])) {
				$fecha = strtotime($fechas["IntervencionFechas"]["inicio_prueba_potencia"]);
				$fecha_termino = strtotime($fechas["IntervencionFechas"]["termino_prueba_potencia"]);
				if($fecha_termino>$fecha){
					$duracion = ($fecha_termino - $fecha) / (60 * 60);
					$dataLocal = $original;
					
					$dataLocal["actividad"] = "T_DCC";
					$dataLocal["responsable"] = "DCC";
					$dataLocal["categoria"] = "Intervención";
					$dataLocal["fecha_inicio"] = date('Y-m-d', $fecha);
					$dataLocal["hora_inicio"] = date('H:i', $fecha);
					$dataLocal["fecha_termino"] = date('Y-m-d', $fecha_termino);
					$dataLocal["hora_termino"] = date('H:i', $fecha_termino);
					$dataLocal["tiempo"] = $duracion;
					$dataLocal["sistema"] = "00_Motor completo";
					$dataLocal["subsistema"] = "Prueba Potencia";
					$dataLocal["elemento"] = "Prueba Potencia";
					
					$dataArray[] = $dataLocal;
					$time = $fecha_termino;
				}
			}
		}
		
		function registro_fluidos(&$time,$json,$original,&$dataArray, &$objPHPExcel){
			
			
			
			if (isset($json["AceiteMotor"])&&is_numeric(@$json["AceiteMotor"])&&@$json["AceiteMotor"]!='0'&&intval(@$json["AceiteMotor"])>0){
				$actividad = "";
				if (@$json["AceiteMotorTipo"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["AceiteMotorTipo"]=="R"){
					$actividad = "Relleno";
				}
				$dataLocal = $original;
				$dataLocal[10] = "";
				$dataLocal[11] = "Fluido";
				$dataLocal[16] = "";
				$dataLocal[20] = "Fluido";
				$dataLocal[21] = "";
				$dataLocal[24] = "Aceite Motor";
				$dataLocal[25] = $json["AceiteMotor"];
				$dataLocal[26] = "litros";
				$dataLocal[27] = $actividad;
				$dataArray[] = $dataLocal;
			}
			if (isset($json["AceiteReserva"])&&is_numeric(@$json["AceiteReserva"])&&@$json["AceiteReserva"]!='0'&&intval(@$json["AceiteReserva"])>0){
				$actividad = "";
				if (@$json["AceiteReservaTipo"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["AceiteReservaTipo"]=="R"){
					$actividad = "Relleno";
				}
				$dataLocal = $original;
				$dataLocal[10] = "";
				$dataLocal[11] = "Fluido";
				$dataLocal[16] = "";
				$dataLocal[20] = "Fluido";
				$dataLocal[21] = "";
				$dataLocal[24] = "Aceite Reserva";
				$dataLocal[25] = $json["AceiteReserva"];
				$dataLocal[26] = "litros";
				$dataLocal[27] = $actividad;
				$dataArray[] = $dataLocal;
			}
			if (isset($json["Refrigerante"])&&is_numeric(@$json["Refrigerante"])&&@$json["Refrigerante"]!='0'&&intval(@$json["Refrigerante"])>0){
				$actividad = "";
				if (@$json["RefrigeranteTipo"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["RefrigeranteTipo"]=="R"){
					$actividad = "Relleno";
				}
				$dataLocal = $original;
				$dataLocal[10] = "";
				$dataLocal[11] = "Fluido";
				$dataLocal[16] = "";
				$dataLocal[20] = "Fluido";
				$dataLocal[21] = "";
				$dataLocal[24] = "Refrigerante";
				$dataLocal[25] = $json["Refrigerante"];
				$dataLocal[26] = "litros";
				$dataLocal[27] = $actividad;
				$dataArray[] = $dataLocal;
			}
			if (isset($json["Combustible"])&&is_numeric(@$json["Combustible"])&&@$json["Combustible"]!='0'&&intval(@$json["Combustible"])>0){
				$actividad = "";
				if (@$json["CombustibleTipo"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["CombustibleTipo"]=="R"){
					$actividad = "Relleno";
				}
				$dataLocal = $original;
				$dataLocal[10] = "";
				$dataLocal[11] = "Fluido";
				$dataLocal[16] = "";
				$dataLocal[20] = "Fluido";
				$dataLocal[21] = "";
				$dataLocal[24] = "Combustible";
				$dataLocal[25] = $json["Combustible"];
				$dataLocal[26] = "litros";
				$dataLocal[27] = $actividad;
				$dataArray[] = $dataLocal;
			}
			if (isset($json["Zerex"])&&is_numeric(@$json["Zerex"])&&@$json["Zerex"]!='0'&&intval(@$json["Zerex"])>0){
				$dataLocal = $original;
				$dataLocal[10] = "";
				$dataLocal[11] = "Fluido";
				$dataLocal[16] = "";
				$dataLocal[20] = "Fluido";
				$dataLocal[21] = "";
				$dataLocal[24] = "Zerex";
				$dataLocal[25] = $json["Zerex"];
				$dataLocal[26] = "litros";
				$dataArray[] = $dataLocal;
			}
			if (isset($json["Resurs"])&&is_numeric(@$json["Resurs"])&&@$json["Resurs"]!='0'&&intval(@$json["Resurs"])>0){
				$dataLocal = $original;
				$dataLocal[10] = "";
				$dataLocal[11] = "Fluido";
				$dataLocal[16] = "";
				$dataLocal[20] = "Fluido";
				$dataLocal[21] = "";
				$dataLocal[24] = "Resurs";
				$dataLocal[25] = $json["Resurs"];
				$dataLocal[26] = "tarros";
				$dataArray[] = $dataLocal;
			}
		}
		
		function generar_registro_fluidos(&$time, $json, $original, &$dataArray, &$objPHPExcel, $folio){
			$this->loadModel('IntervencionFluido');
			$ingreso_fluidos = $this->IntervencionFluido->find('all', array('conditions'=>array('intervencion_id'=>$folio),'recursive' => 2));
			foreach ($ingreso_fluidos as $fluido) {
				if (floatval($fluido["IntervencionFluido"]["cantidad"]) > 0){
					$dataLocal = $original;
					$dataLocal["responsable"] = "";
					$dataLocal["categoria"] = "Fluido";
					$dataLocal["tiempo"] = "";
					$dataLocal["sistema"] = "Fluido";
					$dataLocal["subsistema"] = "Fluido";
					$dataLocal["elemento"] = $fluido["Fluido"]["nombre"];
					$dataLocal["pos_elemento"] = $fluido["IntervencionFluido"]["cantidad"];
					$dataLocal["diagnostico"] = $fluido["Fluido"]["FluidoUnidad"]["nombre"];
					$dataLocal["solucion"] = $fluido["TipoIngreso"]["nombre"];
					$dataArray[] = $dataLocal;
				}
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
					case 'd3_charla':
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
					case 'ds1_liquidos':
						return 'Fluidos';
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
					case 'd3_operador':
						return 'Operador';
						break;
					case 'd4_infraestructura':
					case 'd3_infraestructura':
					case 'ds1_infraestructura':
						return 'Infraestructura';
						break;
					case 'd3_mantencion':
						return 'Mantención';
						break;
					default:
						return $val;
				}
			} else {
				return "";
			}
		}

	}
?>