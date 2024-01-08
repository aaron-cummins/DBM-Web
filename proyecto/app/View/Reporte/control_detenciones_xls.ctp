<?php
	ini_set('memory_limit','512M');
	$faenas = $this->Session->read('faenas'); 
	App::import('Controller', 'UtilidadesReporte');
	App::import('Vendor', 'Classes/PHPExcel');
	PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
	$objPHPExcel = new PHPExcel();
	$utilReporte = new UtilidadesReporteController();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Cache-Control: max-age=0');
	header('Content-Disposition: attachment;filename="Control-Detenciones-'.date("Y-m-d").'".xlsx');
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.date('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	$objPHPExcel->
    getProperties()
        ->setCreator("SISRAI")
        ->setLastModifiedBy("SISRAI")
        ->setTitle("Control Detenciones");
		
	$objPHPExcel->setActiveSheetIndex(0)->setTitle('Control Detenciones');
	
	// Encabezados
	$encabezados = array("Unidad","ESN");
	for ($i = 1; $i <= $dias; $i++) {
		$encabezados[] = $i;
	}
	$encabezados[] = "MP";
	$encabezados[] = "RI";
	$encabezados[] = "EX";
	$encabezados[] = "RP";
	$encabezados[] = "BL";
	$count = count($encabezados);
	$fin = "";
	for($i=0;$i<$count;$i++){
		$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($i,1,$encabezados[$i]);
	}
	$utilReporte->cellColor("A1:B1","FF0000","FFFFFF",$objPHPExcel);
	$dataArray = array();
	$detencion_diaria = array();
	$filas = 0;
	foreach ($unidades as $unidad) {
		if (!isset($resultados[$unidad["Unidad"]["id"]])) {
			continue;
		}
		$filas++;
		$data = array();
		$data[] = $unidad["Unidad"]["unidad"];
		$data[] = $unidad["Unidad"]["esn"];
		$resumen["MP"] = 0;
		$resumen["RI"] = 0;
		$resumen["EX"] = 0;
		$resumen["RP"] = 0;
		$resumen["BL"] = 0;
		for ($i = 1; $i <= $dias; $i++) {
			$data[$i+1] = "";
			//echo "<td style=\"padding: 0;vertical-align: top;\">";
			if (isset($resultados[$unidad["Unidad"]["id"]][$i])) {
				$cantidad = count($resultados[$unidad["Unidad"]["id"]][$i]);
				$int = array();
				for ($j = 0; $j < $cantidad; $j++) {
					//print_r($resultados[$unidad["Unidad"]["id"]][$i][$j][0]);
					$tipo_intervencion = @$resultados[$unidad["Unidad"]["id"]][$i][$j][0];
					if ($tipo_intervencion == "MP" || $tipo_intervencion == "RI" || $tipo_intervencion == "EX" || $tipo_intervencion == "RP" || $tipo_intervencion == "BL") {
						$resumen[$tipo_intervencion]++;
						//$resumen_total[$tipo_intervencion]++;
					}
					
					if (!isset($detencion_diaria[$i][$tipo_intervencion])) {
						$detencion_diaria[$i][$tipo_intervencion] = 1;
					} else {
						$detencion_diaria[$i][$tipo_intervencion]++;
					}
				
					//$folio = $resultados[$unidad["Unidad"]["id"]][$i][$j][3];
					//$title = 'Folio: '.$resultados[$unidad["Unidad"]["id"]][$i][$j][3]."\nDescripción: ".$resultados[$unidad["Unidad"]["id"]][$i][$j][1]."\nComentario: ".$resultados[$unidad["Unidad"]["id"]][$i][$j][2];
					
					/*if ($j < $cantidad - 1) {
						//echo "<div class=\"detalle\" id=\"detalle_$folio\" style=\"cursor:pointer; padding: 6px; border-bottom: 1px solid #e4e4e4; text-align: center; $color\" data-toggle=\"tooltip\" data-placement=\"right\" data-html=\"true\" title=\"$title\">".$resultados[$unidad["Unidad"]["id"]][$i][$j][0]."";
						//echo "<div class=\"detalles detalle_$folio\" style=\"position: relative; height:50px; width:100px;\">Comentarios</div>";
						//echo "</div>";
					} else {
						// Ultimo sin border-bottom
						//echo "<div class=\"detalle\" id=\"detalle_$folio\" style=\"cursor:pointer; padding: 6px; text-align: center; $color\" data-toggle=\"tooltip\" data-placement=\"right\" data-html=\"true\" title=\"$title\">".$resultados[$unidad["Unidad"]["id"]][$i][$j][0]."";
						//echo "<div class=\"detalles detalle_$folio\" style=\"position: relative; height:50px; width:100px;\">Comentarios</div>";
						//echo "</div>";
					}*/
					$int[] = $resultados[$unidad["Unidad"]["id"]][$i][$j][0];
				}
				$data[$i+1] = implode(" / ", $int);
			}
			//echo "</td>";
		}
		foreach ($resumen as $key => $value) {
			$data[] = $value;
		}
		$dataArray[] = $data;
	}
	$filas++;
	if($dias==30){
		$utilReporte->cellColor("C1:AF1","CCCCCC","000000",$objPHPExcel);
		$utilReporte->cellColor("AG1:AK1","FF0000","FFFFFF",$objPHPExcel);
		$utilReporte->cellColor("AG2:AK$filas","EEEEEE","000000",$objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':AF'.($filas+2))->getFont()->setBold(true);
		$fin = "AK";
		$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':AF'.($filas+8))->applyFromArray(
			array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				)
			)
		);
	}
	if($dias==31){
		$utilReporte->cellColor("C1:AG1","CCCCCC","000000",$objPHPExcel);
		$utilReporte->cellColor("AH1:AL1","FF0000","FFFFFF",$objPHPExcel);
		$utilReporte->cellColor("AH2:AL$filas","EEEEEE","000000",$objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':AG'.($filas+2))->getFont()->setBold(true);
		$fin = "AL";
		$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':AG'.($filas+8))->applyFromArray(
			array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				)
			)
		);
	}
	if($dias==28){
		$utilReporte->cellColor("C1:AD1","CCCCCC","000000",$objPHPExcel);
		$utilReporte->cellColor("AE1:AI1","FF0000","FFFFFF",$objPHPExcel);
		$utilReporte->cellColor("AE2:AI$filas","EEEEEE","000000",$objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':AD'.($filas+2))->getFont()->setBold(true);
		$fin = "AI";
		$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':AD'.($filas+8))->applyFromArray(
			array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				)
			)
		);
	}
	if($dias==29){
		$utilReporte->cellColor("C1:AE1","CCCCCC","000000",$objPHPExcel);
		$utilReporte->cellColor("AF1:AJ1","FF0000","FFFFFF",$objPHPExcel);
		$fin = "AJ";
		$utilReporte->cellColor("AF2:AJ$filas","EEEEEE","000000",$objPHPExcel);
		$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':AE'.($filas+2))->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':AE'.($filas+8))->applyFromArray(
			array(
				'borders' => array(
					'allborders' => array(
						'style' => PHPExcel_Style_Border::BORDER_THIN,
						'color' => array('rgb' => '000000')
					)
				)
			)
		);
	}
	$objPHPExcel->getActiveSheet()->getStyle('B'.($filas+2).':B'.($filas+9))->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:'.$fin.'1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle("A1:$fin"."$filas")->applyFromArray(
		array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
					'color' => array('rgb' => '000000')
				)
			)
		)
	);

	$dataArray[] = array();
	
	$data = array();
	$data[] = "";
	$data[] = "Tipo";
	for ($i = 1; $i <= $dias; $i++) {
		$data[] = $i;
	}
	$dataArray[] = $data;
	$data = array();
	$data[] = "";
	$data[] = "MP";
	for ($i = 1; $i <= $dias; $i++) {
		$data[] = @$detencion_diaria[$i]["MP"];
	}
	$dataArray[] = $data;
	$data = array();
	$data[] = "";
	$data[] = "RI";
	for ($i = 1; $i <= $dias; $i++) {
		$data[] = @$detencion_diaria[$i]["RI"];
	}
	$dataArray[] = $data;
	$data = array();
	$data[] = "";
	$data[] = "EX";
	for ($i = 1; $i <= $dias; $i++) {
		$data[] = @$detencion_diaria[$i]["EX"];
	}
	$dataArray[] = $data;
	$data = array();
	$data[] = "";
	$data[] = "RP";
	for ($i = 1; $i <= $dias; $i++) {
		$data[] = @$detencion_diaria[$i]["RP"];
	}
	$dataArray[] = $data;
	$data = array();
	$data[] = "";
	$data[] = "BL";
	for ($i = 1; $i <= $dias; $i++) {
		$data[] = @$detencion_diaria[$i]["BL"];
	}
	$dataArray[] = $data;
	$data = array();
	$data[] = "";
	$data[] = "Total";
	for ($i = 1; $i <= $dias; $i++) {
		$data[] = @$detencion_diaria[$i]["BL"]+@$detencion_diaria[$i]["RP"]+@$detencion_diaria[$i]["EX"]+@$detencion_diaria[$i]["RI"]+@$detencion_diaria[$i]["MP"];
	}
	$dataArray[] = $data;
	
	$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
	$objPHPExcel->setActiveSheetIndex(0);
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	$objWriter->save('php://output');
	exit;
	
?>
<div class="wrapper">
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Detenciones por Unidad</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
		  <thead>
			<tr>
				<td style="width: 40px !important; text-align: center;"><strong>Unidad</strong></td>
				<td style="width: 40px !important; text-align: center;"><strong>ESN</strong></td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td style=\"text-align: center;\">$i</td>";
					}
				?>
				<td><strong>MP</strong></td>
				<td><strong>RI</strong></td>
				<td><strong>EX</strong></td>
				<td><strong>RP</strong></td>
				<td><strong>BL</strong></td>
			</tr>
		  </thead>
		  <tbody>
			<?php
			
			$resumen_total["MP"] = 0;
			$resumen_total["RI"] = 0;
			$resumen_total["EX"] = 0;
			$resumen_total["RP"] = 0;
			$resumen_total["BL"] = 0;
			
			$detencion_diaria = array();
			
			foreach ($unidades as $unidad) {
				// Si Unidad no tiene intervenciones la saltamos para mejorar despliegue
				if (!isset($resultados[$unidad["Unidad"]["id"]])) {
					continue;
				}
				$resumen["MP"] = 0;
				$resumen["RI"] = 0;
				$resumen["EX"] = 0;
				$resumen["RP"] = 0;
				$resumen["BL"] = 0;
			?>
			  <tr>
				<td rowspan="<?php //echo $max_intervenciones;?>" style="text-align: center;vertical-align: top;" nowrap><?php echo $unidad["Unidad"]["unidad"];?></td>
				<td rowspan="<?php// echo $max_intervenciones;?>" style="text-align: center;vertical-align: top;" nowrap><?php echo $unidad["Unidad"]["esn"];?></td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td style=\"padding: 0;vertical-align: top;\">";
						if (isset($resultados[$unidad["Unidad"]["id"]][$i])) {
							$cantidad = count($resultados[$unidad["Unidad"]["id"]][$i]);
							for ($j = 0; $j < $cantidad; $j++) {
								//print_r($resultados[$unidad["Unidad"]["id"]][$i][$j][0]);
								$tipo_intervencion = @$resultados[$unidad["Unidad"]["id"]][$i][$j][0];
								if ($tipo_intervencion == "MP" || $tipo_intervencion == "RI" || $tipo_intervencion == "EX" || $tipo_intervencion == "RP" || $tipo_intervencion == "BL") {
									$resumen[$tipo_intervencion]++;
									$resumen_total[$tipo_intervencion]++;
									
									if (!isset($detencion_diaria[$i][$tipo_intervencion])) {
										$detencion_diaria[$i][$tipo_intervencion] = 1;
									} else {
										$detencion_diaria[$i][$tipo_intervencion]++;
									}
									
								}
							
								if ($resultados[$unidad["Unidad"]["id"]][$i][$j][0] == "RI") {
									$color = "background-color: yellow; color: red;";
								} else {
									$color = "";
								}
								
								$folio = $resultados[$unidad["Unidad"]["id"]][$i][$j][3];
								
								$title = 'Folio: '.$resultados[$unidad["Unidad"]["id"]][$i][$j][3]."\nDescripción: ".$resultados[$unidad["Unidad"]["id"]][$i][$j][1]."\nComentario: ".$resultados[$unidad["Unidad"]["id"]][$i][$j][2];
								
								if ($j < $cantidad - 1) {
									echo "<div class=\"detalle\" id=\"detalle_$folio\" style=\"cursor:pointer; padding: 6px; border-bottom: 1px solid #e4e4e4; text-align: center; $color\" data-toggle=\"tooltip\" data-placement=\"right\" data-html=\"true\" title=\"$title\">".$resultados[$unidad["Unidad"]["id"]][$i][$j][0]."";
									//echo "<div class=\"detalles detalle_$folio\" style=\"position: relative; height:50px; width:100px;\">Comentarios</div>";
									echo "</div>";
								} else {
									// Ultimo sin border-bottom
									echo "<div class=\"detalle\" id=\"detalle_$folio\" style=\"cursor:pointer; padding: 6px; text-align: center; $color\" data-toggle=\"tooltip\" data-placement=\"right\" data-html=\"true\" title=\"$title\">".$resultados[$unidad["Unidad"]["id"]][$i][$j][0]."";
									//echo "<div class=\"detalles detalle_$folio\" style=\"position: relative; height:50px; width:100px;\">Comentarios</div>";
									echo "</div>";
								}
							}
						}
						echo "</td>";
					}
				?>
				<?php
					foreach ($resumen as $key => $value) {
						echo "<td>$value</td>";
					}
				?>
			</tr>
			  <?php } ?>
			<tr>
				<td colspan="<?php echo ($dias + 2);?>"></td>
				<?php
					foreach ($resumen_total as $key => $value) {
						echo "<td>$value</td>";
					}
				?>
			</tr>
		  </tbody>
	  </table>
	</div>
	
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Detenciones Diarias</h6></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
		  <thead>
			<tr>
				<td style="width: 40px !important; text-align: center;"><strong>Intervención</strong></td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td style=\"text-align: center;\">$i</td>";
					}
				?>
			</tr>
		  </thead>
		  <tbody>
			<tr>
				<td>MP</td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td>".@$detencion_diaria[$i]["MP"]."</td>";
					}
				?>
			</tr>
			<tr>
				<td>RI</td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td>".@$detencion_diaria[$i]["RI"]."</td>";
					}
				?>
			</tr>
			<tr>
				<td>EX</td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td>".@$detencion_diaria[$i]["EX"]."</td>";
					}
				?>
			</tr>
			<tr>
				<td>RP</td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td>".@$detencion_diaria[$i]["RP"]."</td>";
					}
				?>
			</tr>
			<tr>
				<td>BL</td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td>".@$detencion_diaria[$i]["BL"]."</td>";
					}
				?>
			</tr>
			<tr>
				<td>Total</td>
				<?php 
					for ($i = 1; $i <= $dias; $i++) {
						echo "<td>".(@$detencion_diaria[$i]["BL"]+@$detencion_diaria[$i]["RP"]+@$detencion_diaria[$i]["EX"]+@$detencion_diaria[$i]["RI"]+@$detencion_diaria[$i]["MP"])."</td>";
					}
				?>
			</tr>
		  </tbody>
	  </table>
	</div>
	
	 <div class="widget">
        <div class="title"><img src="/images/icons/dark/chart.png" alt="" class="titleIcon"><h6>Imprevistos por Equipo</h6></div>
        <div id="chart-container"></div>
    </div>
</div>