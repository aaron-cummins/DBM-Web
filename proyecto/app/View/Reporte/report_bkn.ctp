<?php
	ini_set('memory_limit','512M');
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
	App::import('Vendor', 'Classes/PHPExcel');
	$objPHPExcel = new PHPExcel();
	//header("Content-Type: application/vnd.ms-excel");
	//header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, max-age=0");
	//header("content-disposition: attachment; filename=Flash-Report-".date("Y-m-d").".xlsx");
	
	$objPHPExcel->
    getProperties()
        ->setCreator("SISRAI")
        ->setLastModifiedBy("SISRAI")
        ->setTitle("Flash Report");
		
	$objPHPExcel->setActiveSheetIndex(0)->setTitle('Flash Report');
	
?> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Flash Report</title>
</head>
<style>
	* {
		font-family: Arial;
		font-size: 12px;
	}
	
	td {
		white-space: nowrap;
	}
</style>
<body>
<?php if (isset($intervenciones)&&count($intervenciones) > 0) { ?>
<table cellpadding="5" cellspacing="0" width="100%" border="1">
  <thead>
	<tr style="background-color: red; color: white;font-weight: bold;">
	<td>Correlativo</td>
    <td>Folio</td>
    <td>Faena</td>
	<td>Flota</td>
	<td>Unidad</td>
	<td>Esn</td>
	<td nowrap>Horometro Motor Inicial</td>
	<td nowrap>Horometro motor Final</td>
	<td>Tipo</td>
	<td>Actividad</td>
	<td>Responsable</td>
	<td>Categoría</td>
	<td nowrap>Fecha Inicio</td>
	<td nowrap>Hora Inicio</td>
	<td nowrap>Fecha Término</td>
	<td nowrap>Hora Término</td>
	<td>Tiempo</td>
	<td nowrap>Motivo Llamado</td>
	<td nowrap>Categoría Sintoma</td>
	<td>Sintoma</td>
	<td>Sistema</td>
	<td>Subsistema</td>
	<td nowrap>Pos. Subsistema</td>
	<td>ID</td>
	<td>Elemento</td>
	<td>Pos.Elemento</td>
	<td>Diagnostico</td>
	<td>Solución</td>
	<td>Causa</td>
	<td nowrap>Cambio Módulo</td>
	<td nowrap>Evento Finalizado</td>
	<td nowrap>Lugar Reparación</td>
	<td nowrap>Tecnico Principal</td>
	<td nowrap>Tecnico N°2</td>
	<td nowrap>Tecnico N°3	</td>
	<td nowrap>Tecnico N°4	</td>
	<td nowrap>Tecnico N°5	</td>
	<td nowrap>Tecnico N°6	</td>
	
	<td nowrap>Supervisor Responsable</td>
	<td>Turno</td>
	<td>Período</td>
	<td nowrap>Supervisor Aprobador</td>
	<td nowrap>Status KCH</td>
	<td nowrap>Comentario Técnico</td>
	</tr>
</thead>
<tbody>
<?php 
	$i = 1;
	foreach ($intervenciones as $intervencion) {
		if ($intervencion["Planificacion"]["estado"]==10){
			continue;
		}
		$fecha = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
		$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);
		$diff = $fecha_termino->diff($fecha);
		$tiempo_trabajo = $diff->h + $diff->i / 60.0;
		$tiempo_trabajo = $tiempo_trabajo + ($diff->days*24);
		if ($tiempo_trabajo == 0) {
			continue;
		}
		if ($tiempo_trabajo < $duracion_filtro) {
			continue;
		}
		$motivo_llamado = "-";
		$lugar_reparacion = "-";
		$tiempo_trabajo = number_format($tiempo_trabajo, 2, ",",".");
		$json = json_decode($intervencion["Planificacion"]["json"], true);
		$tecnico_principal = is_numeric(@$json["tecnico_principal"]) ? $util->getTecnico($json["tecnico_principal"]) : "";
		$tecnico_2 =  is_numeric(@$json["tecnico_2"])?$util->getTecnico(@$json["tecnico_2"]):"";
		$tecnico_3 =  is_numeric(@$json["tecnico_3"])?$util->getTecnico(@$json["tecnico_3"]):"";
		$tecnico_4 =  is_numeric(@$json["tecnico_4"])?$util->getTecnico(@$json["tecnico_4"]):"";
		$tecnico_5 =  is_numeric(@$json["tecnico_5"])?$util->getTecnico(@$json["tecnico_5"]):"";
		$tecnico_6 =  is_numeric(@$json["tecnico_6"])?$util->getTecnico(@$json["tecnico_6"]):"";
		$cambio_modulo = "-";
		$evento_finalizado = "-";
		$aprobador = $util->getTecnico($intervencion['Planificacion']['aprobador_id']);
		$supervisor = $util->getTecnico(@$json['supervisor_d']);
		$sintoma = "-";
		$categoria_sintoma = "-";
		if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'MP') {
			if (isset($json["tipo_programado"])) {
				if ($json["tipo_programado"] == "1500") {
					$sintoma =  "Overhaul";
				} else {
					$sintoma =  $json["tipo_programado"];
				}	
			} else {					
				if ($intervencion['Planificacion']['tipomantencion'] == "1500") {
					$sintoma =  "Overhaul";
				} else {
					$sintoma =  $intervencion['Planificacion']['tipomantencion'];
				}
			}
		} elseif (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'BL') {
			if (@$intervencion['Planificacion']['backlog_id'] != null) {
				$sintoma = $util->getBacklog($intervencion['Planificacion']['backlog_id']);
			}
		} else { 
			$sintoma = $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
			$categoria_sintoma = $util->getCategoria($intervencion['Planificacion']['sintoma_id']);
		}
		
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
		
		$horometro_inicial =  $json["horometro_cabina"];
		$horometro_final = 0;
		if (isset($json["horometro_pm"]))
			$horometro_final = $json["horometro_pm"];
		elseif (isset($json["horometro_final"]))
			$horometro_final = $json["horometro_final"];
		else 
			$horometro_final = $json["horometro_cabina"];
			
			
		$cambio_modulo = $util->existe_cambio_modulo($intervencion["Planificacion"]["id"]) == true ? "SI" : "NO";
		$evento_finalizado = $util->correlativo_terminado($intervencion["Planificacion"]["id"]) == true ? "SI" : "NO";
		
	?>
	<tr style="<?php
		if ($intervencion["Planificacion"]["padre"] != "") {
			echo "background-color: grey; color: white;";
		} else {
			echo "background-color: black; color: white;";
		}	
	?>">
    <td><?php echo $intervencion["Planificacion"]["id"];?></td>
    <td><?php echo $intervencion["Planificacion"]["id"];?></td>
	<td nowrap><?php echo $intervencion["Faena"]["nombre"];?></td>
	<td nowrap><?php echo $intervencion["Flota"]["nombre"];?></td>
	<td><?php echo $intervencion["Unidad"]["unidad"];?></td>
	<td><?php echo $intervencion["Planificacion"]["esn"];?></td>
	<td nowrap><?php echo $horometro_inicial;?></td>
	<td nowrap><?php echo $horometro_final;?></td>
	<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
	<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
	<td>-</td>
	<td>Inicial</td>
	<td nowrap><?php echo $fecha->format('d-m-Y');?></td>
	<td nowrap><?php echo $fecha->format('h:i A');?></td>
	<td nowrap><?php echo $fecha_termino->format('d-m-Y');?></td>
	<td nowrap><?php echo $fecha_termino->format('h:i A');?></td>
	<td><?php echo $tiempo_trabajo;?></td>
	<td nowrap><?php echo $motivo_llamado; ?></td>
	<td nowrap><?php echo $categoria_sintoma;?></td>
	<td nowrap><?php echo $sintoma;?></td>
	<?php
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
		echo "<td>-</td>";
	?>
	<!--<td>Cód.KCH</td>
	<td>	Horas  Elemento</td>
	-->
	
	<!--
	<td>Turno</td>
	<td>Dia/Noche	</td>
	-->
	<td><?php echo $cambio_modulo;?></td>
	<td><?php echo $evento_finalizado;?></td>
	<td nowrap><?php echo $lugar_reparacion; ?></td>
	
	<td nowrap><?php echo $tecnico_principal;?></td>
	<td nowrap><?php echo $tecnico_2;?></td>
	<td nowrap><?php echo $tecnico_3;?></td>
	<td nowrap><?php echo $tecnico_4;?></td>
	<td nowrap><?php echo $tecnico_5;?></td>
	<td nowrap><?php echo $tecnico_6;?></td>
	
	<!--<td>Comentario_Acti</td>
	<td>Cambio modulo</td>-->
	<td nowrap><?php echo $supervisor;?></td>
	<td nowrap><?php echo $turno; ?></td>
	<td nowrap><?php echo $periodo; ?></td>
	<td><?php echo $aprobador; ?></td>
	<td><?php echo $util->getEstadoKCH($intervencion["Planificacion"]["estado"]); ?></td>
	<td nowrap><?php 
		$comentarios = "";
		if(@$json["comentario"]!=""){
			echo @$json["comentario"];
			$comentarios = @$json["comentario"];
		}elseif(@$json["comentarios"]!=""){
			echo @$json["comentarios"];
			$comentarios = @$json["comentarios"];
		}
		?></td>
	</tr>
	<?php
		/*if (isset($json["llamado_fecha"]) && isset($json["llegada_fecha"]) && ($intervencion['Planificacion']['tipointervencion']=='EX' || $intervencion['Planificacion']['tipointervencion']=='RI')) {
			$inicio = strtotime($json["llamado_fecha"] . ' ' . $json["llamado_hora"] . ':'.$json["llamado_min"]. ' ' .$json["llamado_periodo"]);
			$termino = strtotime($json["llegada_fecha"] . ' ' . $json["llegada_hora"] . ':'.$json["llegada_min"]. ' ' .$json["llegada_periodo"]);
			$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
			if (($termino - $inicio) / 60 == 15) {
				$time = $termino;
				echo '<tr style="background-color:#F2F2F2;">';
				echo "
					<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>{$intervencion['Planificacion']['esn']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'EX') {
					echo "<td>T_OEM</td>";
					echo "<td>OEM</td>";
				} else {
					echo "<td>T_DCC</td>";
					echo "<td>DCC</td>";
				}
				echo "<td nowrap>$motivo_llamado</td>";
				echo "<td>Tiempo</td>";
				echo "<td nowrap>$sintoma</td>";
				echo "<td nowrap>".date('d-m-Y', $inicio)."</td>";
				echo "<td nowrap>".date('h:i A', $inicio)."</td>";
				echo "<td nowrap>".date('d-m-Y', $termino)."</td>";
				echo "<td nowrap>".date('h:i A', $termino)."</td>";
				echo "<td>$duracion</td>";
				
				echo "<td>Tiempo</td>";
				echo "<td nowrap>Llamado-Lugar Fisico</td>";
				echo "<td>Delta1</td>";
				echo "<td>Espera Intervención</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				
				echo "<td nowrap>$lugar_reparacion</td>";
				echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td>$horometro_inicial</td>";
				echo "<td>$horometro_final</td>";
				echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "</tr>";
			}
		}
		*/
	
		$time = strtotime($fecha->format('d-m-Y h:i A'));	
		$delta_1 = array('d_traslado_dcc_h','d_traslado_oem_h','d_tronadura_h','d_clima_h','d_logistica_dcc_h','d_logistica_oem_h','d_personal_h');
		$delta_2 = array('d2_cliente_h','d_oem_h','d_zona_segura_h','d_clima_inter_h','d_charla_h','d_tronadura_inter_h','d2_logistica_dcc_h','d2_logistica_oem_h');
		// Despliegue deltas
		if (is_array($json) && count($json) > 0) {
			foreach($json as $key => $value) {
				if (substr($key, 0, 2) == "d_" && substr($key, -2) == "_r") {
					if ($value == "" || intval($value) == 0) {
						continue;
					}
					
					$newkey = substr($key, 0, -2);
					
					if (in_array($newkey."_h", $delta_1) && $intervencion['Planificacion']['tipointervencion'] != 'EX' && $intervencion['Planificacion']['tipointervencion'] != 'RI') {
						continue;
					}
					
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
					echo "<tr style=\"background-color:#F2F2F2;\">";
					echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>{$intervencion['Planificacion']['esn']}</td>
					<td>$horometro_inicial</td>
					<td>$horometro_final</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if ($value == 1) {
						echo "<td>T_DCC</td>";
						echo "<td>DCC</td>";
					}elseif ($value == 2) {
						echo "<td>T_OEM</td>";
						echo "<td>OEM</td>";
					}elseif ($value == 3) {
						echo "<td>T_Mina</td>";
						echo "<td>Mina</td>";
					}
					echo "<td>Tiempo</td>";					
					echo "<td nowrap>".date('d-m-Y', $time)."</td>";
					echo "<td nowrap>".date('h:i A', $time)."</td>";
					echo "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
					echo "<td nowrap>".date('h:i A', $time_termino)."</td>";
					echo "<td>$duracion</td>";
					echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
					echo "<td>Tiempo</td>";
					if (in_array($newkey."_h", $delta_1)) {
						echo "<td nowrap>Llamado-Lugar Fisico</td>";
						echo "<td>Delta1</td>";
					}
					echo "<td>-</td>";
					echo "<td nowrap>".$util->getDelta($newkey)."</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
					echo "<td nowrap>$lugar_reparacion</td>";
					
					echo "<td nowrap>$tecnico_principal</td>";
					echo "<td nowrap>$tecnico_2</td>";
					echo "<td nowrap>$tecnico_3</td>";
					echo "<td nowrap>$tecnico_4</td>";
					echo "<td nowrap>$tecnico_5</td>";
					echo "<td nowrap>$tecnico_6</td>";
					
					echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
					echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					echo "<td>".(@$json[$newkey."_o"])."</td>";
					echo "</tr>";
					$time = $time_termino;
				}
			}
			
			// Cuando tiempo entre incio de intervencion y llegada es igual a 15 m.
			/*if (isset($json["llegada_fecha"]) && isset($json["i_i_f"]) && ($intervencion['Planificacion']['tipointervencion']=='EX'||$intervencion['Planificacion']['tipointervencion']=='RI')) {
				$inicio = strtotime($json["llegada_fecha"] . ' ' . $json["llegada_hora"] . ':'.$json["llegada_min"]. ' ' .$json["llegada_periodo"]);
				$termino = strtotime($json["i_i_f"] . ' ' . $json["i_i_h"] . ':'.$json["i_i_m"]. ' ' .$json["i_i_p"]);
				$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
				if (($termino - $inicio) / 60 == 15) {
					$time = $termino;
					echo '<tr style="background-color:#F2F2F2;">';
					echo "
						<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if (strtoupper($intervencion['Planificacion']['tipointervencion']) == 'EX') {
						echo "<td>T_OEM</td>";
						echo "<td>OEM</td>";
					} else {
						echo "<td>T_DCC</td>";
						echo "<td>DCC</td>";
					}
					echo "<td nowrap>$motivo_llamado</td>";
					echo "<td>Tiempo</td>";
					echo "<td nowrap>$sintoma</td>";
					echo "<td nowrap>".date('d-m-Y', $inicio)."</td>";
					echo "<td nowrap>".date('h:i A', $inicio)."</td>";
					echo "<td nowrap>".date('d-m-Y', $termino)."</td>";
					echo "<td nowrap>".date('h:i A', $termino)."</td>";
					echo "<td>$duracion</td>";
					
					echo "<td>Tiempo</td>";
					echo "<td nowrap>Llamado-Lugar Fisico</td>";
					echo "<td>Delta1</td>";
					echo "<td>Espera Intervención</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					
					echo "<td nowrap>$lugar_reparacion</td>";
					echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$tecnico_principal</td>";
					echo "<td nowrap>$tecnico_2</td>";
					echo "<td nowrap>$tecnico_3</td>";
					echo "<td nowrap>$tecnico_4</td>";
					echo "<td nowrap>$tecnico_5</td>";
					echo "<td nowrap>$tecnico_6</td>";
					echo "<td>$horometro_inicial</td>";
				echo "<td>$horometro_final</td>";
					echo "<td>{$aprobador}</td>";
					echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					echo "</tr>";
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
					echo "<tr style=\"background-color:#F2F2F2;\">";
					echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>{$intervencion['Planificacion']['esn']}</td>
					<td>$horometro_inicial</td>
					<td>$horometro_final</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if ($value == 1) {
						echo "<td>T_DCC</td>";
						echo "<td>DCC</td>";
					}elseif ($value == 2) {
						echo "<td>T_OEM</td>";
						echo "<td>OEM</td>";
					}elseif ($value == 3) {
						echo "<td>T_Mina</td>";
						echo "<td>Mina</td>";
					}
					echo "<td>Tiempo</td>";
					echo "<td nowrap>".date('d-m-Y', $time)."</td>";
					echo "<td nowrap>".date('h:i A', $time)."</td>";
					echo "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
					echo "<td nowrap>".date('h:i A', $time_termino)."</td>";
					echo "<td>$duracion</td>";
					echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
					echo "<td>Tiempo</td>";
					if (in_array($newkey."_h", $delta_2)) {
						echo "<td nowrap>Lugar Fisico-Inicio Interv.</td>";
						echo "<td>Delta2</td>";
					}
					echo "<td>-</td>";
					echo "<td nowrap>".$util->getDelta($newkey)."</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					//echo "<td nowrap>$turno</td>";
					//echo "<td nowrap>$periodo</td>";
					echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
					echo "<td nowrap>$lugar_reparacion</td>";
					//echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$tecnico_principal</td>";
					echo "<td nowrap>$tecnico_2</td>";
					echo "<td nowrap>$tecnico_3</td>";
					echo "<td nowrap>$tecnico_4</td>";
					echo "<td nowrap>$tecnico_5</td>";
					echo "<td nowrap>$tecnico_6</td>";
					echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
					echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					echo "<td>".(@$json[$newkey."_o"])."</td>";
					echo "</tr>";
					$time = $time_termino;
				}
			}
			
			foreach($json as $key => $value) {
				if (substr($key, 0, 2) == "d_" && substr($key, -2) == "_r" || substr($key, 0, 3) == "d2_" && substr($key, -2) == "_r" || substr($key, 0, 3) == "d3_" && substr($key, -2) == "_r") {
				
					
				
					if ($value == "" || intval($value) == 0) {
						continue;
					}
					$newkey = substr($key, 0, -2);
					
					if (substr($newkey, 0, 3) == "d3_" && $intervencion['Planificacion']['tipointervencion']=='EX') {
						continue;
					}
					
					if (substr($newkey, 0, 3) == "d4_" && $intervencion['Planificacion']['tipointervencion']=='EX') {
						continue;
					}
					
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
						echo "<tr style=\"background-color:#F2F2F2;\">";
						echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
						if ($value == 1) {
							echo "<td>T_DCC</td>";
							echo "<td>DCC</td>";
						}elseif ($value == 2) {
							echo "<td>T_OEM</td>";
							echo "<td>OEM</td>";
						}elseif ($value == 3) {
							echo "<td>T_Mina</td>";
							echo "<td>Mina</td>";
						}
						echo "<td>Tiempo</td>";
						echo "<td nowrap>".date('d-m-Y', $time)."</td>";
						echo "<td nowrap>".date('h:i A', $time)."</td>";
						echo "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
						echo "<td nowrap>".date('h:i A', $time_termino)."</td>";
						echo "<td>$duracion</td>";
						echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
						echo "<td>Tiempo</td>";
						if (substr($newkey, 0, 3) == "d3_") {
							echo "<td nowrap>Inicio Interv-Termino Interv.</td>";
							echo "<td>Delta3</td>";
						}
						if (substr($newkey, 0, 3) == "d4_") {
							echo "<td nowrap>Término Interv-Inicio P.P.</td>";
							echo "<td>Delta4</td>";
						}
						echo "<td>-</td>";
						echo "<td nowrap>".$util->getDelta($newkey)."</td>";
						echo "<td>-</td>";
						echo "<td>-</td>";
						echo "<td>-</td>";
						echo "<td>-</td>";
						//echo "<td nowrap>$turno</td>";
						//echo "<td nowrap>$periodo</td>";
						echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
						echo "<td nowrap>$lugar_reparacion</td>";
						//echo "<td nowrap>$supervisor</td>";
						echo "<td nowrap>$tecnico_principal</td>";
						echo "<td nowrap>$tecnico_2</td>";
						echo "<td nowrap>$tecnico_3</td>";
						echo "<td nowrap>$tecnico_4</td>";
						echo "<td nowrap>$tecnico_5</td>";
						echo "<td nowrap>$tecnico_6</td>";
						echo "<td nowrap>$supervisor</td>";
						echo "<td nowrap>$turno</td>";
						echo "<td nowrap>$periodo</td>";
						echo "<td>{$aprobador}</td>";
						echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
						echo "<td>".(@$json[$newkey."_o"])."</td>";

						echo "</tr>";	
						$time = $time_termino;
					}
		
					//$time = $time_termino;
					
					$time_elementos = $time;
					if ($newkey == "d3_repydiag") {
						if (isset($json["ele_cantidad"]) && is_numeric($json["ele_cantidad"])&&$intervencion['Planificacion']['tipointervencion']!='EX') {
							// Despliegue elementos
							for ($i = 1; $i <= intval($json["ele_cantidad"]); $i++) {
								if (isset($json["elemento_$i"])) {
									if (!isset($json["d3_elemento_h_$i"]) && !isset($json["d3_elemento_m_$i"]))
										continue;
									/*	$json["d3_elemento_h_$i"] = "0";
									if (!isset($json["d3_elemento_m_$i"]))
										$json["d3_elemento_m_$i"] = "0";*/
									$elemento = split(",", $json["elemento_$i"]);
									$hora = intval(@$json["d3_elemento_h_$i"]);
									$minuto = intval(@$json["d3_elemento_m_$i"]);
									if ($hora == 0 && $minuto == 0) {
										//continue;
									}
									$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
									$time_elementos_termino = $time_elementos + $hora * 60 * 60 + $minuto * 60;
									echo "<tr style=\"background-color:#FFFFFF;\">";
									echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
										<td>{$intervencion['Faena']['nombre']}</td>
										<td>{$intervencion['Flota']['nombre']}</td>
										<td>{$intervencion['Unidad']['unidad']}</td>
										<td>{$intervencion['Planificacion']['esn']}</td>
										<td>$horometro_inicial</td>
					<td>$horometro_final</td>
										<td>{$intervencion['Planificacion']['tipointervencion']}</td>
										<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
									echo "<td>DCC</td>";
									echo "<td>Intervención</td>";
									echo "<td nowrap>".date('d-m-Y', $time_elementos)."</td>";
									echo "<td nowrap>".date('h:i A', $time_elementos)."</td>";
									echo "<td nowrap>".date('d-m-Y', $time_elementos_termino)."</td>";
									echo "<td nowrap>".date('h:i A', $time_elementos_termino)."</td>";
									echo "<td>$duracion</td>";	
									echo "<td nowrap>$motivo_llamado</td>";
									echo "<td nowrap>$categoria_sintoma</td>";
									echo "<td nowrap>$sintoma</td>";
									/*
									var sistema_id = elemento[0];
									var subsistema_id = elemento[1];
									var sposicion_id = elemento[2];
									var elemento_id = elemento[3];
									var posicion_id = elemento[4];
									var categoria_id = elemento[5];
									var diagnostico_id = elemento[6];
									var solucion_id = elemento[7];
									var tipo_inter = "N/A";
									
									if (elemento[8] != undefined) {
										tipo_inter = elemento[8];
										if (tipo_inter == "1") {
											tipo_inter = "FALLA";
										} else if (tipo_inter == "2") {
											tipo_inter = "CONSECUENCIA";
										} else if (tipo_inter == "3") {
											tipo_inter = "OPORTUNIDAD";
										}
									}
									*/
									echo "<td nowrap>{$util->getSistema($elemento[0])}</td>";
									echo "<td nowrap>{$util->getSubsistema($elemento[1])}</td>";
									echo "<td nowrap>{$util->getSubsistemaPosicion($elemento[2])}</td>";
									echo "<td nowrap>{$elemento[9]} </td>";
									echo "<td nowrap>{$util->getElemento($elemento[3])}</td>";
									echo "<td nowrap>{$util->getElementoPosicion($elemento[4])}</td>";
									//if ($elemento[8] == "1") {
										echo "<td nowrap>{$util->getDiagnostico($elemento[6])}</td>";
									//} else {
									//	echo "<td nowrap>-</td>";
									//}
									echo "<td nowrap>{$util->getSolucion($elemento[7])}</td>";
									echo "<td nowrap>{$util->getCausa($elemento[8])}</td>";
									//echo "<td nowrap>$turno</td>";
									//echo "<td nowrap>$periodo</td>";
									echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
									echo "<td nowrap>$lugar_reparacion</td>";
									//echo "<td nowrap>$supervisor</td>";
									echo "<td nowrap>$tecnico_principal</td>";
									echo "<td nowrap>$tecnico_2</td>";
									echo "<td nowrap>$tecnico_3</td>";
									echo "<td nowrap>$tecnico_4</td>";
									echo "<td nowrap>$tecnico_5</td>";
									echo "<td nowrap>$tecnico_6</td>";
									echo "<td nowrap>$supervisor</td>";
									echo "<td nowrap>$turno</td>";
									echo "<td nowrap>$periodo</td>";
									echo "<td>{$aprobador}</td>";
									echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
									echo "<td>$comentarios</td>";
									echo "</tr>";
									$time_elementos = $time_elementos_termino;
									$time = $time_elementos;
								}
							}
						}
					}
					//$time = $time_termino;
				}
			}
		}
	
		if ((strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'EX' || strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'MP') && isset($json["i_i_f"]) && isset($json["i_t_f"])) {
			$inicio = strtotime($json["i_i_f"] . ' ' . $json["i_i_h"] . ':'.$json["i_i_m"]. ' ' .$json["i_i_p"]);
			$termino = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
			$duracion = number_format(($termino - $inicio)/(60*60), 2, ",",".");
			echo '<tr style="background-color:#F2F2F2;">';
			echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
				<td>{$intervencion['Faena']['nombre']}</td>
				<td>{$intervencion['Flota']['nombre']}</td>
				<td>{$intervencion['Unidad']['unidad']}</td>
				<td>{$intervencion['Planificacion']['esn']}</td>
				<td>$horometro_inicial</td>
					<td>$horometro_final</td>
				<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
			if (strtoupper($intervencion["Planificacion"]["tipointervencion"]) == 'EX') {
				echo "<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>OEM</td>";
			} else {
				echo "<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
			}
			//echo "<td>Tiempo</td>";
			echo "<td>Intervención</td>";
			
			echo "<td nowrap>".date('d-m-Y', $inicio)."</td>";
			echo "<td nowrap>".date('h:i A', $inicio)."</td>";
			echo "<td nowrap>".date('d-m-Y', $termino)."</td>";
			echo "<td nowrap>".date('h:i A', $termino)."</td>";
			echo "<td>$duracion</td>";
			echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
			echo "<td>Intervención</td>";
			echo "<td nowrap>Inicio Interv-Termino Interv.</td>";
			echo "<td>-</td>";
			echo "<td>-</td>";
			echo "<td>Intervención</td>";
			echo "<td>-</td>";
			echo "<td>-</td>";
			echo "<td>-</td>";
			echo "<td>-</td>";
			//echo "<td nowrap>$turno</td>";
			//		echo "<td nowrap>$periodo</td>";
			echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
			echo "<td nowrap>$lugar_reparacion</td>";
			//echo "<td nowrap>$supervisor</td>";
			echo "<td nowrap>$tecnico_principal</td>";
			echo "<td nowrap>$tecnico_2</td>";
			echo "<td nowrap>$tecnico_3</td>";
			echo "<td nowrap>$tecnico_4</td>";
			echo "<td nowrap>$tecnico_5</td>";
			echo "<td nowrap>$tecnico_6</td>";
			echo "<td nowrap>$supervisor</td>";
			echo "<td nowrap>$turno</td>";
			echo "<td nowrap>$periodo</td>";
			echo "<td>{$aprobador}</td>";
			echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
			echo "<td>$comentarios</td>";
			echo "</tr>";
		}
				
			if (isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"]) && isset($json["solicitud_f"]) && isset($json["solicitud_h"]) && isset($json["solicitud_m"]) && isset($json["solicitud_p"])) {
				$fecha = strtotime($json["i_t_f"] . " " . $json["i_t_h"].":".$json["i_t_m"]." ".$json["i_t_p"]);
				$fecha_termino = strtotime($json["solicitud_f"] . " " . $json["solicitud_h"].":".$json["solicitud_m"]." ".$json["solicitud_p"]);
				$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
				echo "<tr style=\"background-color:#DDEBF7;\">";
				echo "<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
				echo "<td>Intervención</td>";
				
				echo "<td>".date("d-m-Y", $fecha)."</td>";
				echo "<td>".date("h:i A", $fecha)."</td>";
				echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
				echo "<td>".date("h:i A", $fecha_termino)."</td>";
				echo "<td>$duracion</td>";
				echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
				echo "<td>00_Motor completo</td>";
				echo "<td>Solicitud Traslado</td>";
				echo "<td>-</td>";
				echo "<td>Espera Traslado</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				//echo "<td nowrap>$turno</td>";
				//	echo "<td nowrap>$periodo</td>";
				echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
				echo "<td nowrap>$lugar_reparacion</td>";
				//echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "<td>$comentarios</td>";
				echo "</tr>";
			}
				
				// Espera de Desconexión
			if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["i_t_f"]) && isset($json["i_t_h"]) && isset($json["i_t_m"]) && isset($json["i_t_p"])) {
				$fecha = strtotime($json["i_t_f"] . ' ' . $json["i_t_h"] . ':'.$json["i_t_m"]. ' ' .$json["i_t_p"]);
				$fecha_termino = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
				$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
				echo "<tr style=\"background-color:#DDEBF7;\">";
				echo "<td>{$intervencion['Planificacion']['id']}</td>
				<td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
				echo "<td>Tiempo</td>";
				echo "<td>".date("d-m-Y", $fecha)."</td>";
				echo "<td>".date("h:i A", $fecha)."</td>";
				echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
				echo "<td>".date("h:i A", $fecha_termino)."</td>";
				echo "<td>$duracion</td>";
				echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
				echo "<td>00_Motor completo</td>";
				echo "<td>Cambio de Modulo</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>Espera Desconexion</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				//echo "<td nowrap>$turno</td>";
				//	echo "<td nowrap>$periodo</td>";
				echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
				echo "<td nowrap>$lugar_reparacion</td>";
				//echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "<td>$comentarios</td>";
				echo "</tr>";
			}
				
				// Registro de Desconexion
			if (isset($json["desc_h"]) && isset($json["desc_f"]) && isset($json["desc_m"]) && isset($json["desc_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
				$fecha = strtotime($json["desc_f"] . " " . $json["desc_h"].":".$json["desc_m"]." ".$json["desc_p"]);
				$fecha_termino = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
				$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
				echo "<tr style=\"background-color:#DDEBF7;\">";
				echo "<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
				
				echo "<td>Intervención</td>";
				echo "<td>".date("d-m-Y", $fecha)."</td>";
				echo "<td>".date("h:i A", $fecha)."</td>";
				echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
				echo "<td>".date("h:i A", $fecha_termino)."</td>";
				echo "<td>$duracion</td>";
				echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
				echo "<td>00_Motor completo</td>";
				echo "<td>Cambio de Modulo</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>Desconexion</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				//echo "<td nowrap>$turno</td>";
					//echo "<td nowrap>$periodo</td>";
					echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
				echo "<td nowrap>$lugar_reparacion</td>";
				//echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "<td>$comentarios</td>";
				echo "</tr>";
			}
				// Espera de conexion
			if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["desct_f"]) && isset($json["desct_h"]) && isset($json["desct_m"]) && isset($json["desct_p"])) {
				$fecha = strtotime($json["desct_f"] . " " . $json["desct_h"].":".$json["desct_m"]." ".$json["desct_p"]);
				$fecha_termino = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
				$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
				echo "<tr style=\"background-color:#DDEBF7;\">";
				echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
				echo "<td>Tiempo</td>";
				echo "<td>".date("d-m-Y", $fecha)."</td>";
				echo "<td>".date("h:i A", $fecha)."</td>";
				echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
				echo "<td>".date("h:i A", $fecha_termino)."</td>";
				echo "<td>$duracion</td>";
				echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
				echo "<td>00_Motor completo</td>";
				echo "<td>Cambio de Modulo</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>Espera Conexion</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				//echo "<td nowrap>$turno</td>";
			//		echo "<td nowrap>$periodo</td>";
			echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
				echo "<td nowrap>$lugar_reparacion</td>";
			//	echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "<td>$comentarios</td>";
				echo "</tr>";
			}
				
				// Registro de Conexion
			if (isset($json["con_h"]) && isset($json["con_f"]) && isset($json["con_m"]) && isset($json["con_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
				$fecha = strtotime($json["con_f"] . " " . $json["con_h"].":".$json["con_m"]." ".$json["con_p"]);
				$fecha_termino = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
				$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
				echo "<tr style=\"background-color:#DDEBF7;\">";
				echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
				echo "<td>Intervención</td>";
				echo "<td>".date("d-m-Y", $fecha)."</td>";
				echo "<td>".date("h:i A", $fecha)."</td>";
				echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
				echo "<td>".date("h:i A", $fecha_termino)."</td>";
				echo "<td>$duracion</td>";
				echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
				echo "<td>00_Motor completo</td>";
				echo "<td>Cambio de Modulo</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>Conexion</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				//echo "<td nowrap>$turno</td>";
				//	echo "<td nowrap>$periodo</td>";
				echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
				echo "<td nowrap>$lugar_reparacion</td>";
				//echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "<td>$comentarios</td>";
				echo "</tr>";
			}
			// Espera de Puesta en Marcha
			if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["cont_f"]) && isset($json["cont_h"]) && isset($json["cont_m"]) && isset($json["cont_p"])) {
				$fecha = strtotime($json["cont_f"] . " " . $json["cont_h"].":".$json["cont_m"]." ".$json["cont_p"]);
				$fecha_termino = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
				$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
				echo "<tr style=\"background-color:#DDEBF7;\">";
				echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
				echo "<td>Tiempo</td>";
				echo "<td>".date("d-m-Y", $fecha)."</td>";
				echo "<td>".date("h:i A", $fecha)."</td>";
				echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
				echo "<td>".date("h:i A", $fecha_termino)."</td>";
				echo "<td>$duracion</td>";
				echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
				echo "<td>00_Motor completo</td>";
				echo "<td>Puesta en Marcha</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>Espera Puesta en Marcha</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				//echo "<td nowrap>$turno</td>";
				//	echo "<td nowrap>$periodo</td>";
				echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
				echo "<td nowrap>$lugar_reparacion</td>";
				//echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "<td>$comentarios</td>";
				echo "</tr>";
			}
				
				// Registro de Puesta en Marcha
			if (isset($json["pm_i_f"]) && isset($json["pm_i_h"]) && isset($json["pm_i_m"]) && isset($json["pm_i_p"]) && isset($json["pm_t_f"]) && isset($json["pm_t_h"]) && isset($json["pm_t_m"]) && isset($json["pm_t_p"])) {
				$fecha = strtotime($json["pm_i_f"] . " " . $json["pm_i_h"].":".$json["pm_i_m"]." ".$json["pm_i_p"]);
				$fecha_termino = strtotime($json["pm_t_f"] . " " . $json["pm_t_h"].":".$json["pm_t_m"]." ".$json["pm_t_p"]);
				$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
				echo "<tr style=\"background-color:#DDEBF7;\">";
				echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
				echo "<td>Intervención</td>";
				echo "<td>".date("d-m-Y", $fecha)."</td>";
				echo "<td>".date("h:i A", $fecha)."</td>";
				echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
				echo "<td>".date("h:i A", $fecha_termino)."</td>";
				echo "<td>$duracion</td>";
				echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
				echo "<td>00_Motor completo</td>";
				echo "<td>Puesta en Marcha</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>Puesta en Marcha</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				//echo "<td nowrap>$turno</td>";
				//	echo "<td nowrap>$periodo</td>";
				echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
				echo "<td nowrap>$lugar_reparacion</td>";
				//echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "<td>$comentarios</td>";
				echo "</tr>";
			}
				
			// Delta4
			//$time = strtotime($json["i_t_f"] . " " . $json["i_t_h"].":".$json["i_t_m"]." ".$json["i_t_p"]);
			if (isset($json["i_t_f"])&&isset($json["i_t_h"])&&isset($json["i_t_m"])&&isset($json["i_t_p"])){
				$time = strtotime($json["i_t_f"] . " " . $json["i_t_h"].":".$json["i_t_m"]." ".$json["i_t_p"]);
			}else{
				$time = 0;
			}
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
					
					if (substr($newkey, 0, 3) == "d4_" && $intervencion['Planificacion']['tipointervencion']=='EX') {
						continue;
					}
					
					$duracion = number_format(($hora * 60 + $minuto) / 60, 2, ",",".");
					$time_termino = $time + $hora * 60 * 60 + $minuto * 60;
					echo "<tr style=\"background-color:#F2F2F2;\">";
					echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>{$intervencion['Planificacion']['esn']}</td>
					<td>$horometro_inicial</td>
					<td>$horometro_final</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					if ($value == 1) {
						echo "<td>T_DCC</td>";
						echo "<td>DCC</td>";
					}elseif ($value == 2) {
						echo "<td>T_OEM</td>";
						echo "<td>OEM</td>";
					}elseif ($value == 3) {
						echo "<td>T_Mina</td>";
						echo "<td>Mina</td>";
					}
					echo "<td>Tiempo</td>";
					echo "<td nowrap>".date('d-m-Y', $time)."</td>";
					echo "<td nowrap>".date('h:i A', $time)."</td>";
					echo "<td nowrap>".date('d-m-Y', $time_termino)."</td>";
					echo "<td nowrap>".date('h:i A', $time_termino)."</td>";
					echo "<td>$duracion</td>";
					echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
					echo "<td>Tiempo</td>";
					echo "<td nowrap>Término Interv-Inicio P.P.</td>";
					echo "<td>Delta4</td>";
					echo "<td>-</td>";
					echo "<td nowrap>".$util->getDelta($newkey)."</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					echo "<td>-</td>";
					//echo "<td nowrap>$turno</td>";
					//echo "<td nowrap>$periodo</td>";
					echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
					echo "<td nowrap>$lugar_reparacion</td>";
					//echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$tecnico_principal</td>";
					echo "<td nowrap>$tecnico_2</td>";
					echo "<td nowrap>$tecnico_3</td>";
					echo "<td nowrap>$tecnico_4</td>";
					echo "<td nowrap>$tecnico_5</td>";
					echo "<td nowrap>$tecnico_6</td>";
					echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
					echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
					echo "<td>".(@$json[$newkey."_o"])."</td>";
					echo "</tr>";	
				}
			}
				
				// Registro de Prueba de Potencia si es menor a 15m
			if (isset($json["pp_i_f"]) && isset($json["pp_i_h"]) && isset($json["pp_i_m"]) && isset($json["pp_i_p"]) && isset($json["pp_t_f"]) && isset($json["pp_t_h"]) && isset($json["pp_t_m"]) && isset($json["pp_t_p"])) {
				$fecha = strtotime($json["pp_i_f"] . " " . $json["pp_i_h"].":".$json["pp_i_m"]." ".$json["pp_i_p"]);
				$fecha_termino = strtotime($json["pp_t_f"] . " " . $json["pp_t_h"].":".$json["pp_t_m"]." ".$json["pp_t_p"]);
				$duracion = number_format(($fecha_termino - $fecha) / (60 * 60), 2, ",",".");
				echo "<tr style=\"background-color:#DDEBF7;\">";
				echo "<td>{$intervencion['Planificacion']['id']}</td><td>{$intervencion['Planificacion']['id']}</td>
						<td>{$intervencion['Faena']['nombre']}</td>
						<td>{$intervencion['Flota']['nombre']}</td>
						<td>{$intervencion['Unidad']['unidad']}</td>
						<td>{$intervencion['Planificacion']['esn']}</td>
						<td>$horometro_inicial</td>
					<td>$horometro_final</td>
						<td>{$intervencion['Planificacion']['tipointervencion']}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
				echo "<td>DCC</td>";
				echo "<td>Intervención</td>";
				echo "<td>".date("d-m-Y", $fecha)."</td>";
				echo "<td>".date("h:i A", $fecha)."</td>";
				echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
				echo "<td>".date("h:i A", $fecha_termino)."</td>";
				echo "<td>$duracion</td>";
				echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
				echo "<td>00_Motor completo</td>";
				echo "<td>Prueba Potencia</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>Prueba Potencia</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
				//echo "<td nowrap>$turno</td>";
				//	echo "<td nowrap>$periodo</td>";
				echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
				echo "<td nowrap>$lugar_reparacion</td>";
				//echo "<td nowrap>$supervisor</td>";
				echo "<td nowrap>$tecnico_principal</td>";
				echo "<td nowrap>$tecnico_2</td>";
				echo "<td nowrap>$tecnico_3</td>";
				echo "<td nowrap>$tecnico_4</td>";
				echo "<td nowrap>$tecnico_5</td>";
				echo "<td nowrap>$tecnico_6</td>";
				echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
				echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
				echo "<td>$comentarios</td>";
				echo "</tr>";
			}
			
			
			
			if (isset($json["f_a_motor"])&&is_numeric(@$json["f_a_motor"])&&@$json["f_a_motor"]!='0'&&intval(@$json["f_a_motor"])>0){
				$actividad = "-";
				if (@$json["f_a_motor_s"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["f_a_motor_s"]=="R"){
					$actividad = "Relleno";
				}
			?>
			<tr>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td nowrap><?php echo $intervencion["Faena"]["nombre"];?></td>
				<td nowrap><?php echo $intervencion["Flota"]["nombre"];?></td>
				<td><?php echo $intervencion["Unidad"]["unidad"];?></td>
				<td><?php echo $intervencion["Planificacion"]["esn"];?></td>
				<td nowrap><?php echo $horometro_inicial;?></td>
				<td nowrap><?php echo $horometro_final;?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td>-</td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td nowrap><?php echo $motivo_llamado; ?></td>
				<td nowrap><?php echo $categoria_sintoma;?></td>
				<td nowrap><?php echo $sintoma;?></td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>Aceite Motor</td>
				<td><?php echo $json["f_a_motor"];?></td>
				<td>litros</td>
				<td><?php echo $actividad;?></td>
				<td>-</td>
				<td><?php echo $cambio_modulo;?></td>
				<td><?php echo $evento_finalizado;?></td>
				<td nowrap><?php echo $lugar_reparacion; ?></td>
				<td nowrap><?php echo $tecnico_principal;?></td>
				<td nowrap><?php echo $tecnico_2;?></td>
				<td nowrap><?php echo $tecnico_3;?></td>
				<td nowrap><?php echo $tecnico_4;?></td>
				<td nowrap><?php echo $tecnico_5;?></td>
				<td nowrap><?php echo $tecnico_6;?></td>
				<td nowrap><?php echo $supervisor;?></td>
				<td nowrap><?php echo $turno; ?></td>
				<td nowrap><?php echo $periodo; ?></td>
				<td><?php echo $aprobador; ?></td>
				<td><?php echo $util->getEstadoKCH($intervencion["Planificacion"]["estado"]); ?></td>
				<td nowrap><?php echo $comentarios;?></td>
			</tr>
			<?php
			}
			if (isset($json["f_a_reserva"])&&is_numeric(@$json["f_a_reserva"])&&@$json["f_a_reserva"]!='0'&&intval(@$json["f_a_reserva"])>0){
				$actividad = "-";
				if (@$json["f_a_reserva_s"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["f_a_reserva_s"]=="R"){
					$actividad = "Relleno";
				}
			?>
			<tr>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td nowrap><?php echo $intervencion["Faena"]["nombre"];?></td>
				<td nowrap><?php echo $intervencion["Flota"]["nombre"];?></td>
				<td><?php echo $intervencion["Unidad"]["unidad"];?></td>
				<td><?php echo $intervencion["Planificacion"]["esn"];?></td>
				<td nowrap><?php echo $horometro_inicial;?></td>
				<td nowrap><?php echo $horometro_final;?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td>-</td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td nowrap><?php echo $motivo_llamado; ?></td>
				<td nowrap><?php echo $categoria_sintoma;?></td>
				<td nowrap><?php echo $sintoma;?></td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>Aceite Reserva</td>
				<td><?php echo $json["f_a_reserva"];?> </td>
				<td>litros</td>
				<td><?php echo $actividad;?></td>
				<td>-</td>
				<td><?php echo $cambio_modulo;?></td>
				<td><?php echo $evento_finalizado;?></td>
				<td nowrap><?php echo $lugar_reparacion; ?></td>
				<td nowrap><?php echo $tecnico_principal;?></td>
				<td nowrap><?php echo $tecnico_2;?></td>
				<td nowrap><?php echo $tecnico_3;?></td>
				<td nowrap><?php echo $tecnico_4;?></td>
				<td nowrap><?php echo $tecnico_5;?></td>
				<td nowrap><?php echo $tecnico_6;?></td>
				<td nowrap><?php echo $supervisor;?></td>
				<td nowrap><?php echo $turno; ?></td>
				<td nowrap><?php echo $periodo; ?></td>
				<td><?php echo $aprobador; ?></td>
				<td><?php echo $util->getEstadoKCH($intervencion["Planificacion"]["estado"]); ?></td>
				<td nowrap><?php echo $comentarios;?></td>
			</tr>
			<?php
			}
			if (isset($json["f_refrigerante"])&&is_numeric(@$json["f_refrigerante"])&&@$json["f_refrigerante"]!='0'&&intval(@$json["f_refrigerante"])>0){
				$actividad = "-";
				if (@$json["f_refrigerante_s"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["f_refrigerante_s"]=="R"){
					$actividad = "Relleno";
				}
			?>
			<tr>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td nowrap><?php echo $intervencion["Faena"]["nombre"];?></td>
				<td nowrap><?php echo $intervencion["Flota"]["nombre"];?></td>
				<td><?php echo $intervencion["Unidad"]["unidad"];?></td>
				<td><?php echo $intervencion["Planificacion"]["esn"];?></td>
				<td nowrap><?php echo $horometro_inicial;?></td>
				<td nowrap><?php echo $horometro_final;?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td>-</td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td nowrap><?php echo $motivo_llamado; ?></td>
				<td nowrap><?php echo $categoria_sintoma;?></td>
				<td nowrap><?php echo $sintoma;?></td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>Refrigerante</td>
				<td><?php echo $json["f_refrigerante"];?></td>
				<td>litros</td>
				<td><?php echo $actividad;?></td>
				<td>-</td>
				<td><?php echo $cambio_modulo;?></td>
				<td><?php echo $evento_finalizado;?></td>
				<td nowrap><?php echo $lugar_reparacion; ?></td>
				<td nowrap><?php echo $tecnico_principal;?></td>
				<td nowrap><?php echo $tecnico_2;?></td>
				<td nowrap><?php echo $tecnico_3;?></td>
				<td nowrap><?php echo $tecnico_4;?></td>
				<td nowrap><?php echo $tecnico_5;?></td>
				<td nowrap><?php echo $tecnico_6;?></td>
				<td nowrap><?php echo $supervisor;?></td>
				<td nowrap><?php echo $turno; ?></td>
				<td nowrap><?php echo $periodo; ?></td>
				<td><?php echo $aprobador; ?></td>
				<td><?php echo $util->getEstadoKCH($intervencion["Planificacion"]["estado"]); ?></td>
				<td nowrap><?php echo $comentarios;?></td>
			</tr>
			<?php
			}
			if (isset($json["f_combustible"])&&is_numeric(@$json["f_combustible"])&&@$json["f_combustible"]!='0'&&intval(@$json["f_combustible"])>0){
				$actividad = "-";
				if (@$json["f_combustible_s"]=="C"){
					$actividad = "Cambio";
				}elseif(@$json["f_combustible_s"]=="R"){
					$actividad = "Relleno";
				}
			?>
			<tr>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td nowrap><?php echo $intervencion["Faena"]["nombre"];?></td>
				<td nowrap><?php echo $intervencion["Flota"]["nombre"];?></td>
				<td><?php echo $intervencion["Unidad"]["unidad"];?></td>
				<td><?php echo $intervencion["Planificacion"]["esn"];?></td>
				<td nowrap><?php echo $horometro_inicial;?></td>
				<td nowrap><?php echo $horometro_final;?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td>-</td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td nowrap><?php echo $motivo_llamado; ?></td>
				<td nowrap><?php echo $categoria_sintoma;?></td>
				<td nowrap><?php echo $sintoma;?></td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>Combustible</td>
				<td><?php echo $json["f_combustible"];?></td>
				<td>litros</td>
				<td><?php echo $actividad;?></td>
				<td>-</td>
				<td><?php echo $cambio_modulo;?></td>
				<td><?php echo $evento_finalizado;?></td>
				<td nowrap><?php echo $lugar_reparacion; ?></td>
				<td nowrap><?php echo $tecnico_principal;?></td>
				<td nowrap><?php echo $tecnico_2;?></td>
				<td nowrap><?php echo $tecnico_3;?></td>
				<td nowrap><?php echo $tecnico_4;?></td>
				<td nowrap><?php echo $tecnico_5;?></td>
				<td nowrap><?php echo $tecnico_6;?></td>
				<td nowrap><?php echo $supervisor;?></td>
				<td nowrap><?php echo $turno; ?></td>
				<td nowrap><?php echo $periodo; ?></td>
				<td><?php echo $aprobador; ?></td>
				<td><?php echo $util->getEstadoKCH($intervencion["Planificacion"]["estado"]); ?></td>
				<td nowrap><?php echo $comentarios;?></td>
			</tr>
			<?php
			}
			if (isset($json["f_zerex"])&&is_numeric(@$json["f_zerex"])&&@$json["f_zerex"]!='0'&&intval(@$json["f_zerex"])>0){
			?>
			<tr>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td nowrap><?php echo $intervencion["Faena"]["nombre"];?></td>
				<td nowrap><?php echo $intervencion["Flota"]["nombre"];?></td>
				<td><?php echo $intervencion["Unidad"]["unidad"];?></td>
				<td><?php echo $intervencion["Planificacion"]["esn"];?></td>
				<td nowrap><?php echo $horometro_inicial;?></td>
				<td nowrap><?php echo $horometro_final;?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td>-</td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td nowrap><?php echo $motivo_llamado; ?></td>
				<td nowrap><?php echo $categoria_sintoma;?></td>
				<td nowrap><?php echo $sintoma;?></td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>Zerex</td>
				<td><?php echo $json["f_zerex"];?></td>
				<td>litros</td>
				<td>-</td>
				<td>-</td>
				<td><?php echo $cambio_modulo;?></td>
				<td><?php echo $evento_finalizado;?></td>
				<td nowrap><?php echo $lugar_reparacion; ?></td>
				<td nowrap><?php echo $tecnico_principal;?></td>
				<td nowrap><?php echo $tecnico_2;?></td>
				<td nowrap><?php echo $tecnico_3;?></td>
				<td nowrap><?php echo $tecnico_4;?></td>
				<td nowrap><?php echo $tecnico_5;?></td>
				<td nowrap><?php echo $tecnico_6;?></td>
				<td nowrap><?php echo $supervisor;?></td>
				<td nowrap><?php echo $turno; ?></td>
				<td nowrap><?php echo $periodo; ?></td>
				<td><?php echo $aprobador; ?></td>
				<td><?php echo $util->getEstadoKCH($intervencion["Planificacion"]["estado"]); ?></td>
				<td nowrap><?php echo $comentarios;?></td>
			</tr>
			<?php
			}if (isset($json["f_resurs"])&&is_numeric(@$json["f_resurs"])&&@$json["f_resurs"]!='0'&&intval(@$json["f_resurs"])>0){
			?>
			<tr>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td><?php echo $intervencion["Planificacion"]["id"];?></td>
				<td nowrap><?php echo $intervencion["Faena"]["nombre"];?></td>
				<td nowrap><?php echo $intervencion["Flota"]["nombre"];?></td>
				<td><?php echo $intervencion["Unidad"]["unidad"];?></td>
				<td><?php echo $intervencion["Planificacion"]["esn"];?></td>
				<td nowrap><?php echo $horometro_inicial;?></td>
				<td nowrap><?php echo $horometro_final;?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
				<td>-</td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td nowrap><?php echo $motivo_llamado; ?></td>
				<td nowrap><?php echo $categoria_sintoma;?></td>
				<td nowrap><?php echo $sintoma;?></td>
				<td>Fluido</td>
				<td>-</td>
				<td>-</td>
				<td>-</td>
				<td>Resus</td>
				<td><?php echo $json["f_resurs"];?></td>
				<td>tarros</td>
				<td></td>
				<td>-</td>
				<td><?php echo $cambio_modulo;?></td>
				<td><?php echo $evento_finalizado;?></td>
				<td nowrap><?php echo $lugar_reparacion; ?></td>
				<td nowrap><?php echo $tecnico_principal;?></td>
				<td nowrap><?php echo $tecnico_2;?></td>
				<td nowrap><?php echo $tecnico_3;?></td>
				<td nowrap><?php echo $tecnico_4;?></td>
				<td nowrap><?php echo $tecnico_5;?></td>
				<td nowrap><?php echo $tecnico_6;?></td>
				<td nowrap><?php echo $supervisor;?></td>
				<td nowrap><?php echo $turno; ?></td>
				<td nowrap><?php echo $periodo; ?></td>
				<td><?php echo $aprobador; ?></td>
				<td><?php echo $util->getEstadoKCH($intervencion["Planificacion"]["estado"]); ?></td>
				<td nowrap><?php echo $comentarios;?></td>
			</tr>
			<?php
			}
			
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
						echo "<tr style=\"background-color:#DDEBF7;\">";
						echo "<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion['Planificacion']['id']}</td>
							<td>{$intervencion['Faena']['nombre']}</td>
							<td>{$intervencion['Flota']['nombre']}</td>
							<td>{$intervencion['Unidad']['unidad']}</td>
							<td>{$intervencion['Planificacion']['esn']}</td>
							<td>$horometro_inicial</td>
					<td>$horometro_final</td>
							<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
						if ($value == 1) {
							echo "<td>T_DCC</td>";
							echo "<td>DCC</td>";
						}elseif ($value == 2) {
							echo "<td>T_OEM</td>";
							echo "<td>OEM</td>";
						}elseif ($value == 3) {
							echo "<td>T_Mina</td>";
							echo "<td>Mina</td>";
						}
						echo "<td>Final</td>";
						echo "<td>".date("d-m-Y", $fecha)."</td>";
						echo "<td>".date("h:i A", $fecha)."</td>";
						echo "<td>".date("d-m-Y", $fecha_termino)."</td>";
						echo "<td>".date("h:i A", $fecha_termino)."</td>";
						echo "<td>$duracion</td>";
						echo "<td nowrap>$motivo_llamado</td>";
					echo "<td nowrap>$categoria_sintoma</td>";
					echo "<td nowrap>$sintoma</td>";
						echo "<td>Tiempo</td>";
						echo "<td>Termino Interv.-Inicio Operación</td>";
						echo "<td>DeltaS3</td>";
						echo "<td>-</td>";
						echo "<td>".$util->getDelta($newkey)."</td>";
						echo "<td>-</td>";
						echo "<td>-</td>";
						echo "<td>-</td>";
						echo "<td>-</td>";
					//	echo "<td nowrap>$turno</td>";
					//echo "<td nowrap>$periodo</td>";
					echo "<td nowrap>$cambio_modulo</td>";
					echo "<td nowrap>$evento_finalizado</td>";
						echo "<td nowrap>$lugar_reparacion</td>";
					//	echo "<td nowrap>$supervisor</td>";
						echo "<td nowrap>$tecnico_principal</td>";
						echo "<td nowrap>$tecnico_2</td>";
						echo "<td nowrap>$tecnico_3</td>";
						echo "<td nowrap>$tecnico_4</td>";
						echo "<td nowrap>$tecnico_5</td>";
						echo "<td nowrap>$tecnico_6</td>";
						echo "<td nowrap>$supervisor</td>";
					echo "<td nowrap>$turno</td>";
					echo "<td nowrap>$periodo</td>";
					echo "<td>{$aprobador}</td>";
						echo "<td>{$util->getEstadoKCH($intervencion["Planificacion"]["estado"])}</td>";
						echo "<td>$comentarios</td>";
						echo "</tr>";
						$fecha = $time_termino;
					}
				}					
			}
			
			
			if ($intervencion["Planificacion"]["hijo"] != null && $intervencion["Planificacion"]["hijo"] != ""&&$intervencion['Planificacion']['tipointervencion']!='EX') { 
				$time_anterior = strtotime($json['fecha_termino_g']);
				echo $util->desplegar_hijo_base_report_xls($intervencion["Planificacion"]["hijo"], $time_anterior, $intervencion["Planificacion"]["id"],$cambio_modulo,$evento_finalizado);
			}
		}
	}
?>
	</tbody>
	</table>
</body>
</html>
<?php
	//$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	//$objWriter->save('php://output');
	//exit;
?>