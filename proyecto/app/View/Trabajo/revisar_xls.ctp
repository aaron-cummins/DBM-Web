<?php
	ini_set('memory_limit','1024M');
	App::import('Controller', 'Utilidades');
	App::import('Controller', 'UtilidadesReporte');
	App::import('Vendor', 'Classes/PHPExcel');
	$util = new UtilidadesController();
	$utilReporte = new UtilidadesReporteController();
	PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
	$objPHPExcel = new PHPExcel();
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Cache-Control: max-age=0');
	header('Content-Disposition: attachment;filename="Revision-DCC-'.date('Y-m-d').'.xlsx"');
	header('Expires: Mon, 07 Sep 1985 09:00:00 GMT');
	header('Last-Modified: '.date('D, d M Y H:i:s').' GMT');
	header('Cache-Control: cache, must-revalidate');
	header('Pragma: public');
	
	$objPHPExcel->getProperties()->setCreator("SISRAI")->setLastModifiedBy("SISRAI")->setTitle("Revision DCC");
	$objPHPExcel->setActiveSheetIndex(0)->setTitle('Revision DCC');
	$encabezados = array("Faena","Correlativo","Folio","Flota","Unidad","ESN","Tipo","Descripcióon","Inicio","Sincronizacion","Duracion","Tecnico Principal","Comentario","Estado","Unico");
	//print_r($encabezados);
	$count = count($encabezados);
	for($i=0;$i<$count;$i++){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
	}
	$utilReporte->cellColor("A1:AR1","FF0000","FFFFFF",$objPHPExcel);	
	$dataArray = array();
	if (count($intervenciones) > 0) {
		foreach ($intervenciones as $intervencion) { 
			$json = json_decode($intervencion["Planificacion"]["json"], true);
			$date = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
			$fecha_termino = '';
			$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' .$intervencion['Planificacion']['hora_termino']);
			$diff = date_diff($fecha_termino, $date);
			$fecha_registro = new DateTime($intervencion['Planificacion']['fecha_registro']);
			$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60) * 60;
			$h = floor($total / 60);
			$m = $total - $h * 60;
			$h = str_pad($h, 2, "0", STR_PAD_LEFT);
			$m = str_pad($m, 2, "0", STR_PAD_LEFT);
			$total = $h.":".$m;
			$esn = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
			if($esn==''){
				if (@$json["esn_conexion"] != "") {
					$esn = @$json["esn_conexion"];
				} elseif (@$json["esn_nuevo"] != "") {
					$esn = @$json["esn_nuevo"];
				} elseif (@$json["esn"] != "") {
					$esn = @$json["esn"];
				} else {
					$esn = $intervencion['Planificacion']['esn'];
				}	
				if (!is_numeric($esn)) {
					$esn = $util->esnPadre($intervencion['Planificacion']['padre']);
				}
			}
			$dataLocal = array();
			$dataLocal[] = $intervencion['Faena']['nombre'];//1
			$dataLocal[] = $intervencion['Planificacion']['correlativo'];//2
			$dataLocal[] = $intervencion['Planificacion']['id'];//3
			$dataLocal[] = $util->getFlota($intervencion['Planificacion']['flota_id']);//4
			$dataLocal[] = $util->getUnidad($intervencion['Planificacion']['unidad_id']);//5
			$dataLocal[] = $esn;//6
			if ($intervencion['Planificacion']['padre'] != null && $intervencion['Planificacion']['padre'] != '') {
				$dataLocal[] = "c".strtoupper($intervencion['Planificacion']['tipointervencion']);//7
			} else {
				$dataLocal[] = strtoupper($intervencion['Planificacion']['tipointervencion']);//7
			}
			if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
				if (isset($json["tipo_programado"])) {
					if ($json["tipo_programado"] == "1500") {
						$dataLocal[] = "Overhaul";//8
					} else {
						$dataLocal[] = $json["tipo_programado"];//8
					}	
				} else {					
					if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
						$dataLocal[] = "Overhaul";//8
					} else {
						$dataLocal[] = $intervencion['Planificacion']['tipomantencion'];//8
					}
				}
			} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
				$dataLocal[] = $util->getBacklogDescripcion($intervencion['Planificacion']['id']);//8
			} else {
				$dataLocal[] = $util->getSintoma($intervencion['Planificacion']['sintoma_id']);//8
			}
			try {
				if ($date != '')
					$dataLocal[] = $date->format('d-m-Y h:i A');//9
				else
					$dataLocal[] = "";//9
			} catch (Exception $e) {
				$dataLocal[] = "";//9
			}  
			try {
				if ($fecha_registro != '')
					$dataLocal[] = $fecha_registro->format('d-m-Y h:i A');//10
				else
					$dataLocal[] = "";//10
			} catch (Exception $e) {
				$dataLocal[] = "";//10
			}
			$dataLocal[] = $total;//11
			$dataLocal[] = "";//$util->getUsuarioInfo($json["tecnico_principal"]);//12
			$comentario = "";
			if (@$json["comentario"] != "") {
				$comentario = @$json["comentario"];
			} else {
				$comentario = @$json["comentarios"];
			}
			$dataLocal[] = "";//$comentario;//13
			$dataLocal[] = $intervencion['Estado']['nombre'];//14
			$fd = $util->existeDuplicado($intervencion['Planificacion']['id']);
			if ($fd == '0') {
				$dataLocal[] = 'SI';//15
			} else {
				$dataLocal[] = 'NO';//15
			}
			$dataArray[] = $dataLocal;
		}
	}
	//$total = count($dataArray) + 1;
	//print_r($dataArray);
	$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
	//$objPHPExcel->getActiveSheet()->getStyle('A1:AR1')->getFont()->setBold(true);
	/*
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(13);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(13);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(70);
	*/
	//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	$objWriter->save('php://output');
	exit;
?>