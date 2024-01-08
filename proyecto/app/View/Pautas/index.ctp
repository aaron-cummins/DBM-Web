<?php
	ini_set('memory_limit','1024M');
	set_time_limit(100000);
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	//App::import('Controller', 'UtilidadesReporte');
	App::import('Vendor', 'Classes/PHPExcel');
	PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
	//$objPHPExcel = new PHPExcel();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Cache-Control: max-age=0');
	$motor = str_replace(" ", "_", $motor);
	header('Content-Disposition: attachment;filename="PM_'.$motor."_".$intervencion["tipomantencion"]."_".date("Y-m-d").'".xlsx');
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	
	$objPHPExcel = $objReader->load('/var/www/html/app/webroot/pautas/PM_'.$motor.'.xlsx');
	switch($intervencion["tipomantencion"]){
		case '250':
			$objPHPExcel->getSheetByName('500')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
			$objPHPExcel->getSheetByName('1000')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
			$objPHPExcel->setActiveSheetIndex(0);
			break;
		case '500':
			$objPHPExcel->getSheetByName('250')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
			$objPHPExcel->getSheetByName('1000')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
			$objPHPExcel->setActiveSheetIndex(1);
			break;
		case '1000':
			$objPHPExcel->getSheetByName('250')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
			$objPHPExcel->getSheetByName('500')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
			$objPHPExcel->setActiveSheetIndex(2);
			break;
	}
	
	$objPHPExcel->getActiveSheet();
	
	for($i=1;$i<100;$i++){
		$number = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
		if(isset($number)&&is_numeric($number)){
			if(isset($json["ra_$number"])&&$json["ra_$number"]!=''){
				$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i,'X');
				//print_r($json["ra_$number"]);
				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($style);
			}
			
			if(isset($json["ra_".$number."_".$number])&&$json["ra_".$number."_".$number]!=''){
				//print_r($json["ra_".$number."_".$number]);
				$objPHPExcel->getActiveSheet()->SetCellValue('D'.$i,'X');
				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$i)->applyFromArray($style);
			}
			if(isset($json["me_$number"])&&$json["me_$number"]!=''){
				//print_r($json["me_$number"]);
				$objPHPExcel->getActiveSheet()->SetCellValue('E'.$i,$json["me_$number"]);
			}
			if(isset($json["na_$number"])&&$json["na_$number"]!=''){
				//print_r($json["na_$number"]);		
				$objPHPExcel->getActiveSheet()->SetCellValue('G'.$i,'X');
				$style = array(
					'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->applyFromArray($style);
			}
			if(isset($json["te_$number"])&&$json["te_$number"]!=''&&$json["te_$number"]!='0'){
				//print_r($json["te_$number"]);
				$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i,strtoupper($util->getTecnico($json["te_$number"])));
			}
			if(isset($json["ob_$number"])&&$json["ob_$number"]!=''){
				//print_r($json["ob_$number"]);		
				$objPHPExcel->getActiveSheet()->SetCellValue('H'.$i,strtoupper($json["ob_$number"]));
			}
		}
	}
	
	//print_r($intervencion);
	//print_r($json);
	//print_r($motor);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(35);
	$objPHPExcel->getSecurity()->setLockWindows(true);
	$objPHPExcel->getSecurity()->setLockStructure(true);
	$objPHPExcel->getSecurity()->setWorkbookPassword("PHPExcel");
	$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
	$objPHPExcel->getActiveSheet()->getProtection()->setPassword("PHPExcel");
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
?>