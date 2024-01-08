<?php
	ini_set('memory_limit','1024M');
	set_time_limit(100000);
	App::import('Controller', 'Utilidades');
	App::import('Controller', 'UtilidadesReporte');
	App::import('Vendor', 'Classes/PHPExcel');
	$util = new UtilidadesController();
	$utilReporte = new UtilidadesReporteController();
	PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
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
		
	$objPHPExcel->setActiveSheetIndex(0)->setTitle('Flash Report');
	
	// Encabezados
	$encabezados = array("Correlativo","Folio","Faena","Flota","Unidad","Esn","Horometro Inicial","Horometro Final","Tipo","Actividad","Responsable","Categoría","Fecha Inicio","Hora Inicio","Fecha Término","Hora Término","Tiempo","Motivo Llamado","Categoría Sintoma","Sintoma","Sistema","Subsistema","Pos. Subsistema","ID","Elemento","Pos.Elemento","Diagnostico","Solución","Causa","Cambio Módulo","Evento Finalizado","Lugar Reparación","Tecnico Principal","Tecnico N°2","Tecnico N°3","Tecnico N°4","Tecnico N°5","Tecnico N°6","Supervisor Responsable","Turno","Período","Supervisor Aprobador","Status KCH","Comentario Técnico");
	$count = count($encabezados);
	//echo "<pre>";
	//print_r($encabezados);
	//echo "</pre>";
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
		$json = json_decode($intervencion["json"], true);
		$lugar_reparacion = "";
		if(@$json["lugar_reparacion"] != '') {
			$json["LugarReparacion"] = @$json["lugar_reparacion"];
		}
		switch(strtoupper(@$json["LugarReparacion"])) {
			case 'TA':
				$lugar_reparacion = 'Taller';
				break;
			case 'TE':
				$lugar_reparacion = 'Terreno';
				break;
			default:
				$lugar_reparacion = '';
				break;
		}
		$cambio_modulo = $util->existe_cambio_modulo($intervencion["id"]) == true ? "SI" : "NO";
		$evento_finalizado = $util->correlativo_terminado($intervencion["id"]) == true ? "SI" : "NO";
		//$tiempo_trabajo = number_format($tiempo_trabajo, 2, ",",".");
		
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
		$motivo_llamado = "";
		if(@$json["motivo_llamado"] != '') {
			$json["MotivoID"] = @$json["motivo_llamado"];
		}
		switch(strtoupper(@$json["MotivoID"])) { 
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
				$motivo_llamado = '';
				break;
		}
		
		$sintoma = "";
		$categoria_sintoma = "";
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
			$sintoma = $util->getBacklogDescripcion($intervencion['id']);
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
		
		$esn_ = $util->getESN($intervencion['faena_id'],$intervencion['flota_id'],$util->getMotor($intervencion['unidad_id']),$util->getUnidad($intervencion['unidad_id']),$intervencion['fecha']);
		$esn = $esn_;
		if($esn_==''){
			if (@$json["esn_conexion"] != "") {
				$esn =  @$json["esn_conexion"];
			} elseif (@$json["esn_nuevo"] != "") {
				$esn = @$json["esn_nuevo"];
			} elseif (@$json["esn"] != "") {
				$esn =  @$json["esn"];
			} else {
				$esn =  $intervencion['esn'];
			}
			
			if (is_numeric($esn)) {
				$esn = $esn;
			} else {
				$esn  = $this->esnPadre($intervencion['padre']);
			}
		}
				
		$statuskcc = $util->getEstadoKCH($intervencion["estado"]);
		$dataLocal = array();
		$dataLocal[] = $intervencion["id"];
		$dataLocal[] = $intervencion["id"];
		$dataLocal[] = $faena;
		$dataLocal[] = $flota;
		$dataLocal[] = $unidad;
		$dataLocal[] = $esn;
		$dataLocal[] = $horometro_inicial;
		$dataLocal[] = $horometro_final;
		$dataLocal[] = $intervencion["tipointervencion"];
		$dataLocal[] = $intervencion["tipointervencion"];
		$dataLocal[] = "";
		$dataLocal[] = "Inicial";
		$dataLocal[] = $fecha->format('d-m-Y');
		$dataLocal[] = $fecha->format('H:i');
		$dataLocal[] = $fecha_termino->format('d-m-Y');
		$dataLocal[] = $fecha_termino->format('H:i');
		$dataLocal[] = $tiempo_trabajo; // Tiempo trabajo
		$dataLocal[] = $motivo_llamado;
		$dataLocal[] = $categoria_sintoma;
		$dataLocal[] = $sintoma;
		$dataLocal[] = "";
		$dataLocal[] = "";
		$dataLocal[] = "";
		$dataLocal[] = "";
		$dataLocal[] = "";
		$dataLocal[] = "";
		$dataLocal[] = "";
		$dataLocal[] = "";
		$dataLocal[] = "";
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
			$utilReporte->delta_1($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
			$utilReporte->delta_2($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
		}
		
		if($intervencion["tipointervencion"]!='MP'){
			$utilReporte->delta_3($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
		}
		
		if($intervencion["tipointervencion"]!='MP'){
			$utilReporte->get_elementos($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
		}
		
		if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
			$utilReporte->delta_4($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
		}
		
		if($intervencion["tipointervencion"]=='MP'&&$intervencion["tipomantencion"]!='1500'){
			$utilReporte->delta_mp($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
		}
		
		if($intervencion["tipointervencion"]!='EX'){
			$utilReporte->espera_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->registro_desconexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
			$utilReporte->espera_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->registro_conexion($time,$json,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
			$utilReporte->espera_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->registro_puesta_marcha($time,$json,$dataLocal,$dataArray,$objPHPExcel);
			$utilReporte->registro_prueba_potencia($time,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		if($intervencion["tipointervencion"]!='EX'&&$intervencion["tipointervencion"]!='MP'){
			$utilReporte->delta_5($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
			$utilReporte->get_elementos_reproceso($time,$dataLocal,$dataArray,$objPHPExcel,$intervencion["folio"]);
		}
		
		if($intervencion["tipointervencion"]=='EX' && !isset($json["Elemento_1"])){
			$tiempo_trabajo = ($time_termino - $time)/3600;
			$dataLocal[9] = "EX";
			$dataLocal[10] = "OEM";
			$dataLocal[11] = "Intervención";
			$dataLocal[12] = date('d-m-Y', $time);
			$dataLocal[13] = date('H:i', $time);
			$dataLocal[14] = date('d-m-Y', $time_termino);
			$dataLocal[15] = date('H:i', $time_termino);
			$dataLocal[16] = $tiempo_trabajo;
			$dataLocal[20] = "Tiempo";
			$dataLocal[21] = "Inicio Interv-Termino Interv.";
			$dataLocal[22] = "";
			$dataLocal[24] = "Intervención";
			$dataArray[] = $dataLocal;
		}
		
		$utilReporte->registro_fluidos($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel);
		
		if (isset($intervencion['fecha_operacion'])) {
			$utilReporte->delta_operacion($time_termino,$json,$dataLocal,$dataArray,$objPHPExcel);
		}
		
		// Si tiene hijo y no es EX, comenzamos el recorrido
		if($intervencion["tipointervencion"]!='EX'){
			$utilReporte->get_son_test($intervencion["folio"],$dataLocal,$dataArray,$objPHPExcel);
		}
	}
	
	$total = count($dataArray) + 1;
	
	//echo "<pre>";
	//print_r($dataArray);
	//echo "</pre>";
	
	$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
	$objPHPExcel->getActiveSheet()->getStyle('A1:AR1')->getFont()->setBold(true);
	for ($i=1;$i<=$total;$i++){
		$objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->getNumberFormat()->setFormatCode('#,##0.00');
	}
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(13);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(70);
	$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	$objWriter->save('php://output');
	exit;
?>