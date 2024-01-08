<?php
	ini_set('memory_limit','512M');
	App::import('Controller', 'Utilidades');
	App::import('Controller', 'UtilidadesReporte');
	App::import('Vendor', 'Classes/PHPExcel');
	$util = new UtilidadesController();
	$utilReporte = new UtilidadesReporteController();
	$objPHPExcel = new PHPExcel();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Cache-Control: max-age=0');
	header('Content-Disposition: attachment;filename="Flash-Report-'.date("Y-m-d").'".xlsx');
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	$objPHPExcel->
    getProperties()
        ->setCreator("SISRAI")
        ->setLastModifiedBy("SISRAI")
        ->setTitle("Flash Report");
		
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->setActiveSheetIndex(0)->setTitle('Flash Report');
	
	// Encabezados
	$encabezados = array("Correlativo","Folio","Faena","Flota","Unidad","Esn","Horometro Inicial","Horometro Final","Tipo","Actividad","Responsable","Categoría","Fecha Inicio","Hora Inicio","Fecha Término","Hora Término","Tiempo","Motivo Llamado","Categoría Sintoma","Sintoma","Sistema","Subsistema","Pos. Subsistema","ID","Elemento","Pos.Elemento","Diagnostico","Solución","Causa","Cambio Módulo","Evento Finalizado","Lugar Reparación","Tecnico Principal","Tecnico N°2","Tecnico N°3","Tecnico N°4","Tecnico N°5","Tecnico N°6","Supervisor Responsable","Turno","Período","Supervisor Aprobador","Status KCH","Comentario Técnico");
	$count = count($encabezados);
	
	for($i=0;$i<$count;$i++){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
	}
	
	$utilReporte->cellColor("A1:AR1","FF0000","FFFFFF",$objPHPExcel);
	
	// Carga de Datos desde Array
	$dataArray = array();
	$i = 1;
	foreach ($intervenciones as $intervencion) {
		$faena = $intervencion["Faena"]["nombre"];
		$flota = $intervencion["Flota"]["nombre"];
		$unidad = $intervencion["Unidad"]["unidad"];
		$intervencion = $intervencion["Planificacion"];
		if ($intervencion["estado"]==10){
			continue;
		}
		$fecha = new DateTime($intervencion['fecha'] . ' ' . $intervencion['hora']);	
		$fecha_termino = new DateTime($intervencion['fecha_termino'] . ' ' . $intervencion['hora_termino']);
		$diff = $fecha_termino->diff($fecha);
		$tiempo_trabajo = $diff->h + $diff->i / 60.0;
		$tiempo_trabajo = $tiempo_trabajo + ($diff->days*24);
		if ($tiempo_trabajo == 0) {
			continue;
		}
		if ($tiempo_trabajo < $duracion_filtro) {
			continue;
		}
		$lugar_reparacion = "-";
		switch(strtoupper(@$json["lugar_reparacion"])) {
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
		$cambio_modulo = $util->existe_cambio_modulo($intervencion["id"]) == true ? "SI" : "NO";
		$evento_finalizado = $util->correlativo_terminado($intervencion["id"]) == true ? "SI" : "NO";
		$tiempo_trabajo = number_format($tiempo_trabajo, 2, ",",".");
		$json = json_decode($intervencion["json"], true);
		$tecnico = array();
		$tecnico[] = is_numeric(@$json["tecnico_principal"]) ? $util->getTecnico($json["tecnico_principal"]) : "-";
		$tecnico[] = is_numeric(@$json["tecnico_2"])?$util->getTecnico(@$json["tecnico_2"]):"-";
		$tecnico[] = is_numeric(@$json["tecnico_3"])?$util->getTecnico(@$json["tecnico_3"]):"-";
		$tecnico[] = is_numeric(@$json["tecnico_4"])?$util->getTecnico(@$json["tecnico_4"]):"-";
		$tecnico[] = is_numeric(@$json["tecnico_5"])?$util->getTecnico(@$json["tecnico_5"]):"-";
		$tecnico[] = is_numeric(@$json["tecnico_6"])?$util->getTecnico(@$json["tecnico_6"]):"-";
		$aprobador = $util->getTecnico(@$intervencion['aprobador_id']);
		$supervisor = $util->getTecnico(@$json['supervisor_d']);
		$turno = strtoupper(@$json["turno"]);
		$periodo = "-";
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
		$comentarios = "-";
		if(@$json["comentario"]!=""){
			$comentarios = @$json["comentario"];
		}elseif(@$json["comentarios"]!=""){
			$comentarios = @$json["comentarios"];
		}
		$motivo_llamado = "-";
		switch(strtoupper(@$json["motivo_llamado"])) { 
			case 'OP':
				$motivo_llamado = 'Operador';
				break;
			case 'FC':
				$motivo_llamado = 'Alarma';
				break;
			case 'OT':
				$motivo_llamado = 'Oportunidad';
				break;
			default:
				$motivo_llamado = '-';
				break;
		}
		
		$sintoma = "-";
		$categoria_sintoma = "-";
		if (strtoupper($intervencion['tipointervencion']) == 'MP') {
			if (isset($json["tipo_programado"])) {
				if ($json["tipo_programado"] == "1500") {
					$sintoma =  "Overhaul";
				} else {
					$sintoma =  $json["tipo_programado"];
				}	
			} else {					
				if ($intervencion['tipomantencion'] == "1500") {
					$sintoma =  "Overhaul";
				} else {
					$sintoma =  $intervencion['tipomantencion'];
				}
			}
		} elseif (strtoupper($intervencion['tipointervencion']) == 'BL') {
			if (@$intervencion['backlog_id'] != null) {
				$sintoma = $util->getBacklog($intervencion['backlog_id']);
			}
		} else { 
			$sintoma = $util->getSintoma($intervencion['sintoma_id']);
			$categoria_sintoma = $util->getCategoria($intervencion['sintoma_id']);
		}
		
		$horometro_inicial = @$json["horometro_cabina"];
		$horometro_final = 0;
		if (isset($json["horometro_pm"]))
			$horometro_final = @$json["horometro_pm"];
		elseif (isset($json["horometro_final"]))
			$horometro_final = @$json["horometro_final"];
		else 
			$horometro_final = @$json["horometro_cabina"];
		
		$statuskcc = $util->getEstadoKCH($intervencion["estado"]);
		$dataLocal = array();
		$dataLocal[] = $intervencion["id"];
		$dataLocal[] = $intervencion["id"];
		$dataLocal[] = $faena;
		$dataLocal[] = $flota;
		$dataLocal[] = $unidad;
		$dataLocal[] = $intervencion["esn"];
		$dataLocal[] = $horometro_inicial;
		$dataLocal[] = $horometro_final;
		$dataLocal[] = $intervencion["tipointervencion"];
		$dataLocal[] = $intervencion["tipointervencion"];
		$dataLocal[] = "-";
		$dataLocal[] = "Inicial";
		$dataLocal[] = $fecha->format('d-m-Y');
		$dataLocal[] = $fecha->format('h:i A');
		$dataLocal[] = $fecha_termino->format('d-m-Y');
		$dataLocal[] = $fecha_termino->format('h:i A');
		$dataLocal[] = $tiempo_trabajo; // Tiempo trabajo
		$dataLocal[] = $motivo_llamado;
		$dataLocal[] = $categoria_sintoma;
		$dataLocal[] = $sintoma;
		$dataLocal[] = "-";
		$dataLocal[] = "-";
		$dataLocal[] = "-";
		$dataLocal[] = "-";
		$dataLocal[] = "-";
		$dataLocal[] = "-";
		$dataLocal[] = "-";
		$dataLocal[] = "-";
		$dataLocal[] = "-";
		$dataLocal[] = $cambio_modulo;
		$dataLocal[] = $evento_finalizado;
		$dataLocal[] = $lugar_reparacion;
		foreach($tecnico as $k=>$v){
			$dataLocal[] = $v;	
		}
		$dataLocal[] = $supervisor;
		$dataLocal[] = $turno;
		$dataLocal[] = $periodo;
		$dataLocal[] = $aprobador;
		$dataLocal[] = $statuskcc;
		$dataLocal[] = $comentarios;
		$dataArray[] = $dataLocal;
		
		$linea = count($dataArray) + 1;
		// Formateo de linea principal
		$utilReporte->cellColor("A$linea:AR$linea","000000","FFFFFF",$objPHPExcel);
		
		$time = strtotime($fecha->format('d-m-Y h:i A'));
		$time_termino = strtotime($fecha_termino->format('d-m-Y h:i A'));
		// Delta 1 y Delta 2
		if($intervencion["tipointervencion"]=='EX'||$intervencion["tipointervencion"]=='RI'){
			$utilReporte->delta_1($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->delta_2($time,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
			$utilReporte->delta_3($time,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
			$utilReporte->get_elementos($time,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
			$utilReporte->delta_4($time,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		if($intervencion["tipointervencion"]!='EX'){
			$utilReporte->espera_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->registro_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->espera_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->registro_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->espera_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->registro_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->registro_prueba_potencia($time,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
			$utilReporte->delta_5($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->get_elementos_reproceso($time,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		if($intervencion["tipointervencion"]=='EX'){
			$tiempo_trabajo = ($time_termino - $time)/3600;
			$dataLocal[9] = "EX";
			$dataLocal[10] = "OEM";
			$dataLocal[11] = "Intervencion";
			$dataLocal[12] = date('d-m-Y', $time);
			$dataLocal[13] = date('h:i A', $time);
			$dataLocal[14] = date('d-m-Y', $time_termino);
			$dataLocal[15] = date('h:i A', $time_termino);
			$dataLocal[16] = $tiempo_trabajo;
			$dataLocal[20] = "Tiempo";
			$dataLocal[21] = "Inicio Interv-Termino Interv.";
			$dataLocal[22] = "-";
			$dataLocal[24] = "Intervencion";
			$dataArray[] = $dataLocal;
		}
		
		if($intervencion["tipointervencion"]=='MP'&&$intervencion["tipomantencion"]!='1500'){
			$tiempo_trabajo = ($time_termino - $time)/3600;
			$dataLocal[9] = "MP";
			$dataLocal[10] = "DCC";
			$dataLocal[11] = "Intervencion";
			$dataLocal[12] = date('d-m-Y', $time);
			$dataLocal[13] = date('h:i A', $time);
			$dataLocal[14] = date('d-m-Y', $time_termino);
			$dataLocal[15] = date('h:i A', $time_termino);
			$dataLocal[16] = $tiempo_trabajo;
			$dataLocal[20] = "Tiempo";
			$dataLocal[21] = "Inicio Interv-Termino Interv.";
			$dataLocal[22] = "-";
			$dataLocal[24] = "Intervencion";
			$dataArray[] = $dataLocal;
		}
		
		$utilReporte->registro_fluidos($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel);
		
		if (isset($intervencion['fecha_operacion'])) {
			$utilReporte->delta_operacion($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		// Si tiene hijo y no es EX, comenzamos el recorrido
		if($intervencion["tipointervencion"]!='EX'){
			if(isset($intervencion["hijo"])&&$intervencion["hijo"]!=null&&$intervencion["hijo"]!=''){
				$utilReporte->get_son($intervencion["hijo"],$dataLocal,$dataArray,$objPHPExcel);
			}
		}
	}
	
	//$total = count($dataArray) + 1;
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
	$objPHPExcel->getActiveSheet()->getStyle('A1:AR1')->getFont()->setBold(true);
	//$objPHPExcel->getActiveSheet()->freezePane('C2');
	//$objPHPExcel->getActiveSheet()->freezePaneByColumnAndRow(1, 2);
	$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	$objWriter->save('php://output');
	exit;
?>