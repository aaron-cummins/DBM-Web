<?php
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	$faenas = $this->Session->read('faenas'); 
	ini_set('memory_limit','512M');
	$faenas = $this->Session->read('faenas'); 
	App::import('Controller', 'UtilidadesReporte');
	App::import('Vendor', 'Classes/PHPExcel');
	PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
	$objPHPExcel = new PHPExcel();
	$utilReporte = new UtilidadesReporteController();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Cache-Control: max-age=0');
	header('Content-Disposition: attachment;filename="Registro-Backlogs-'.date("Y-m-d").'".xlsx');
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	$objPHPExcel->
    getProperties()
        ->setCreator("SISRAI")
        ->setLastModifiedBy("SISRAI")
        ->setTitle("Registro Backlogs");
		
	$objPHPExcel->setActiveSheetIndex(0)->setTitle('Registro Backlogs');
	$encabezados = array("Folio","Faena","Flota","Equipo","Sistema","Fecha","Criticidad","Folio Gen.","Folio Prog.","Comentario","Estado","Estado");
	$count = count($encabezados);
	$fin = "";
	for($i=0;$i<$count;$i++){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
	}
	//$utilReporte->cellColor("A1:B1","FF0000","FFFFFF",$objPHPExcel);
	$dataArray = array();
	$filas = 0;
	$i = 1;
	foreach ($resultado as $backlog) {
		$data = array();
		$data[] = $backlog["Backlog"]["id"];
		$data[] = $backlog["Faena"]["nombre"];
		$data[] = $backlog["Flota"]["nombre"];
		$data[] = $backlog["Unidad"]["unidad"];
		$data[] = $backlog["Sistema"]["nombre"];
		$data[] = date("d-m-Y",strtotime($backlog["Backlog"]["fecha"]));
		switch($backlog["Backlog"]["criticidad"]){
			case '1': $data[] =  "Alto"; break;
			case '2': $data[] =  "Medio"; break;
			case '3': $data[] =  "Bajo"; break;
		}
		;
		$data[] = $backlog["Backlog"]["folio_creador"];
		$data[] = $backlog["Backlog"]["folio_programador"];
		$data[] = $backlog["Backlog"]["comentario"];
		$data[] = $backlog["Estado"]["nombre"];
		if($backlog["Backlog"]["realizado"]!='S') {
			if($backlog["Backlog"]["e"]=='1'){
				$data[] = "Activo";
			}
			if($backlog["Backlog"]["e"]=='0'){
				$data[] = "Inactivo";
			}
		}else{
			$data[] = 'Activo';
		}
		$dataArray[] = $data;
	}
	
	$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	$objWriter->save('php://output');
	exit;
?>