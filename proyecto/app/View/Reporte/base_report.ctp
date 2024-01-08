<?php
	ini_set('memory_limit','1024M');
	App::import('Model', 'IntervencionElementos');
	App::import('Model', 'IntervencionFechas');
	App::import('Model', 'DeltaDetalle');
	App::import('Controller', 'Utilidades');
	App::import('Controller', 'UtilidadesReporte');
	$util = new UtilidadesController();
	$utilReporte = new UtilidadesReporteController();
	
	App::import('Model', 'Planificacion');
	$var = new Planificacion();
	$var_fechas = new IntervencionFechas();
	$var_deltas = new DeltaDetalle();
?>
<script type="text/javascript">
$(document).ready(function(){
	/*
	$("#btnAceptar").click(function(){
		if($("#faena_id").val()=="0"){
			return false;
		}
		
		return true;
	});
	*/
	
	$("#faena_id").val('<?php echo @$faena_id;?>');
	$("#faena_id").change();
	$("#flota_id").val('<?php echo @$flota_id;?>');
	$("#flota_id").change();
	$("#unidad_id").val('<?php echo @$unidad_id;?>');
	$("#unidad_id").change();
});
</script>
<div class="wrapper">
       <form method="POST">
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
				<tr>
					<td>Faena</td>
					<td>
						<select name="faena_id" id="faena_id">
							<option value="0">Todas</option>
							<?php foreach ($faenas as $key => $value) { ?>
							<option value="<?php echo $key; ?>"<?php echo $faena_id == $key ? ' selected="selected"':'';?>><?php echo $value; ?></option>
							<?php } ?>
						</select>
					</td>
					<td>Flota</td>
					<td>
						<select name="flota_id" id="flota_id" flota_id="<?php echo @$flota_id; ?>" style="width: 250px; overflow: hidden;">
						</select>
					</td>
                    <td>Unidad</td>
                    <td>
						<select name="unidad_id" id="unidad_id" unidad_id="<?php echo @$unidad_id; ?>" style="width: 250px; overflow: hidden;">
						</select> 
					</td>
					
				</tr>
				<tr>
					<td>Período Fecha Inicio</td> 
					<td>
						<input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio;?>" max="<?php echo date("Y-m-d");?>" /> a 
						<input type="date" name="fecha_termino" id="fecha_termino" value="<?php echo $fecha_termino;?>" max="<?php echo date("Y-m-d");?>" />
					</td>
                    <td>Correlativo</td>
                    <td><input name="correlativo" id="correlativo" type="text" value="<?php echo @$correlativo; ?>" style="width: 40px;" /></td>
                    <td>Duración</td>
                    <td><input name="duracion" id="duracion" type="text" value="<?php echo @$duracion_filtro; ?>" style="width: 40px;" /></td>
					<td align="right" colspan="2">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Reporte/base_report'; return false;" />
						<input type="submit" name="filtro_aceptar" id="btnAceptar" value="Aceptar" class="greenB" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		</form>
	</div>
<?php if (count($intervenciones) > 0) { ?>
<div class="wrapper">
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Flash Report</h6>
		<a href="./base_report_xls?t=<?php echo time() . md5(time());?>&fecha_inicio=<?php echo $fecha_inicio;?>&fecha_termino=<?php echo $fecha_termino;?>&faena_id=<?php echo $faena_id;?>&correlativo=<?php echo $correlativo;?>&duracion=<?php echo $duracion_filtro;?>&flota_id=<?php echo $flota_id;?>&unidad_id=<?php echo $unidad_id;?>" target="_blank" style="float: right;margin-right: 8px;margin-top: 8px;">Exportar XLS</a>
		</div>
		<table cellpadding="0" cellspacing="0" width="100%" class="sTable">
		  <thead>
			<tr>
                <td>Correlativo</td>
                <td>Folio</td>
                <td>Faena</td>
                <td>Flota</td>
                <td>Unidad</td>
                <td>Esn</td>
                <td>Tipo</td>
                <td>Actividad</td>
                <td>Responsable</td>
                <td>Categoría</td>
                <td colspan="2">Inicio</td>
                <td colspan="2">Término</td>
                <td>Duración</td>
                <td>Sintoma</td>
			</tr>
		</thead>
		<tbody>
		<?php 
			$i = 1;
			foreach ($intervenciones as $intervencion) {
				$folio = $intervencion['Planificacion']['folio'];
				$fecha = new DateTime($intervencion['Planificacion']['fecha'] . ' ' . $intervencion['Planificacion']['hora']);
				$fecha_termino = new DateTime($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);
				$time_termino = strtotime($intervencion['Planificacion']['fecha_termino'] . ' ' . $intervencion['Planificacion']['hora_termino']);
				$diff = $fecha_termino->diff($fecha);
				$tiempo_trabajo = $diff->h + $diff->i / 60.0;
				$tiempo_trabajo = $tiempo_trabajo + ($diff->days*24);
				if ($tiempo_trabajo == 0) {
					continue;
				}
				if ($tiempo_trabajo < $duracion_filtro) {
					continue;
				}
				$tiempo_trabajo = number_format($tiempo_trabajo, 2, ",",".");
				$json = json_decode($intervencion["Planificacion"]["json"], true);
				$sintoma = "";
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
					$sintoma = $util->getBacklogDescripcion($intervencion['Planificacion']['backlog_id']);
				} else {
					$sintoma = $util->getSintoma($intervencion['Planificacion']['sintoma_id']);
				}
				
				if (strlen($sintoma) > 30) {
					$sintoma = trim(substr($sintoma, 0, 28)) . '...';	
				}
				
				$esn_ = $util->getESN($intervencion['Planificacion']['faena_id'],$intervencion['Planificacion']['flota_id'],$util->getMotor($intervencion['Planificacion']['unidad_id']),$util->getUnidad($intervencion['Planificacion']['unidad_id']),$intervencion['Planificacion']['fecha']);
				$intervencion["Planificacion"]["esn"] = $esn_;
				$esn = "";
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
						$intervencion["Planificacion"]["esn"] = $esn;
					} else {
						$intervencion["Planificacion"]["esn"] = $util->esnPadre($intervencion['Planificacion']['padre']);
					}
				}
			?>
			<tr style="background-color: black; color: white;">
                <td><?php echo $intervencion["Planificacion"]["correlativo_final"];?></td>
                <td><?php echo $intervencion["Planificacion"]["id"];?></td>
                <td nowrap><?php echo $intervencion["Faena"]["nombre"];?></td>
                <td nowrap><?php echo $intervencion["Flota"]["nombre"];?></td>
                <td><?php echo $intervencion["Unidad"]["unidad"];?></td>
                <td><?php echo $intervencion["Planificacion"]["esn"];?></td>
                <td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
                <td><?php echo $intervencion["Planificacion"]["tipointervencion"];?></td>
                <td>-</td>
                <td>Inicial</td>
                <td nowrap><?php echo $fecha->format('d-m-Y');?></td>
                <td nowrap><?php echo $fecha->format('h:i A');?></td>
                <td nowrap><?php echo $fecha_termino->format('d-m-Y');?></td>
                <td nowrap><?php echo $fecha_termino->format('h:i A');?></td>
                <td><?php echo $tiempo_trabajo;?></td>
                <td nowrap><?php echo $sintoma;?></td>
                <!--<td>-</td>
                <td>-</td>
                <td>-</td>
                <td>-</td>
                <?php //echo "<td nowrap>$supervisor</td>";?>
                <td nowrap><?php //echo $tecnico_principal;?></td>
                <?php //echo "<td nowrap>$status</td>";?>-->
			</tr>
			<?php
				$time = strtotime($fecha->format('d-m-Y h:i A'));
				echo $utilReporte->delta_1_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
				echo $utilReporte->delta_2_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
				if($intervencion["Planificacion"]["tipointervencion"]!='MP'){
					echo $utilReporte->delta_3_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
				}
				echo $utilReporte->delta_4_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
				echo $utilReporte->delta_5_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
				if($intervencion["Planificacion"]["tipointervencion"]=='MP'){
					echo $utilReporte->delta_mp_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
				}
				if($intervencion["Planificacion"]["tipointervencion"]!='EX'){
					//echo $utilReporte->espera_desconexion_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
					//echo $utilReporte->registro_desconexion_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
					//echo $utilReporte->espera_conexion_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
					//echo $utilReporte->registro_conexion_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
					//echo $utilReporte->espera_puesta_marcha_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
					//echo $utilReporte->registro_puesta_marcha_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
					//echo $utilReporte->registro_prueba_potencia_reporte($time, $folio, $intervencion, $intervencion["Planificacion"]["esn"], $sintoma);
				}
				
				if($intervencion["Planificacion"]["tipointervencion"]=='EX' && !isset($json["Elemento_1"])){
					$tiempo_trabajo = ($time_termino - $time) / 60;
					$duracion = number_format($tiempo_trabajo / 60, 2, ",",".");
					$return = "<tr style=\"background-color:#DDEBF7;\">";
					$return .= "<td>{$intervencion['Planificacion']['correlativo_final']}</td>
					<td>{$intervencion['Planificacion']['id']}</td>
					<td>{$intervencion['Faena']['nombre']}</td>
					<td>{$intervencion['Flota']['nombre']}</td>
					<td>{$intervencion['Unidad']['unidad']}</td>
					<td>{$intervencion["Planificacion"]["esn"]}</td>
					<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>{$intervencion['Planificacion']['tipointervencion']}</td>";
					$return .= "<td>T_OEM</td>";
					$return .= "<td>Intervención</td>";
					$return .=  "<td>".date("d-m-Y", $time)."</td>";
					$return .=  "<td>".date("h:i A", $time)."</td>";
					$return .=  "<td>".date("d-m-Y", $time_termino)."</td>";
					$return .=  "<td>".date("h:i A", $time_termino)."</td>";
					$return .=  "<td>$duracion</td>";
					$return .=  "<td nowrap>$sintoma</td>";
					$return .=  "</tr>";
					echo $return;
				}
			?>
			<?php
				//$time = strtotime($fecha->format('d-m-Y h:i A'));
				
				// Registro de Fecha Operación
				if (isset($intervencion['Planificacion']['fecha_operacion'])) {
					$fecha = strtotime($json['FechaTerminoGlobal']);
										
				}
				
				$time_anterior = strtotime($json['FechaTerminoGlobal']);
				//echo $util->desplegar_hijo_base_report($folio,  strtotime($json['FechaTerminoGlobal']), $intervencion["Planificacion"]["id"]);
			}
		?>
		</tbody>
		</table>
	</div>
</div>
<?php } ?>