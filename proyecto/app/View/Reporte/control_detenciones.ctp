<?php
	ini_set('memory_limit','512M');
	$faenas = $this->Session->read('faenas'); 
	//$faena_id = "";
	//print_r($faenas);
?>
<script type="text/javascript">
google.load('visualization', '1.0', {'packages':['corechart']});
	google.setOnLoadCallback(drawCharts);
	
	function drawCharts() {
		var jsonData = JSON.parse('<?php echo json_encode($grafico_data);?>');
		var data = new google.visualization.DataTable(jsonData);
		var options = {
			'title':"Imprevistos por Equipo\nEquipos con más de un imprevisto",
			height: 300,
			'legend':{'position': 'bottom'},
			'backgroundColor':'#f9f9f9',
			/*bar: { groupWidth: '75%' },*/
		};
		
		var chart = new google.visualization.ColumnChart(document.getElementById('chart-container'));
		chart.draw(data, options);
	}
	
	$(function() {
		$("#faenaid").change(function(){
			var val = $(this).val();
			$("#flotaid option").hide();
			if(val!=''){
				$("#flotaid option[value='0']").show();
				$("#flotaid option[faena_id='"+val+"']").show();
				$("#flotaid").change();
			}
		});
		
		$("#faenaid").change();
	});
</script>
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Reporte</h5>
			<span>Control de Detenciones</span>
		</div>
	  <div class="clear"></div>
	</div>
</div>

<div class="line"></div>
	<div class="wrapper">
 		<form method="POST">
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
               <tr>
					<td width="15%">Faena</td>
					<td>
						<select name="faenaid" id="faenaid">
							<?php if (is_array($faenas)){ ?>
							<option value="" <?php echo ($faena_id == "") ? 'selected="selected"' : ''; ?>>Todas</option>
							<?php foreach ($faenas as $key => $value) { ?> 
                            <option value="<?php echo $value["Faena"]["id"];?>" <?php echo ($faena_id == $value["Faena"]["id"]) ? 'selected="selected"' : ''; ?>><?php echo $value["Faena"]["nombre"];?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="15%">Flota</td>
					<td>
						<select name="flotaid" id="flotaid">
							<option value="0" <?php echo ($flota_id == "0") ? 'selected="selected"' : ''; ?>>Todas</option>
							<?php
								foreach ($flotas as $flota) { 
									echo "<option value=\"{$flota["UnidadDetalle"]["flota_id"]}\" ".(($flota_id == $flota["UnidadDetalle"]["flota_id"]) ? 'selected="selected"' : '')." faena_id=\"{$flota["UnidadDetalle"]["faena_id"]}\">{$flota["UnidadDetalle"]["flota"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td width="15%">Estado</td>
					<td>
						<select name="estado" id="estado">
							<option value="" <?php echo ($estado == "") ? 'selected="selected"' : ''; ?>>Todas</option>
							<option value="7" <?php echo ($estado == "7") ? 'selected="selected"' : ''; ?>>Sin Revisar</option>
							<option value="4" <?php echo ($estado == "4") ? 'selected="selected"' : ''; ?>>Aprobado DCC</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Mes</td>
					<td>
						<select name="mes" id="mes">
							<option value="01" <?php echo ($mes == "01") ? 'selected="selected"' : ''; ?>>Enero</option>
							<option value="02" <?php echo ($mes == "02") ? 'selected="selected"' : ''; ?>>Febrero</option>
							<option value="03" <?php echo ($mes == "03") ? 'selected="selected"' : ''; ?>>Marzo</option>
							<option value="04" <?php echo ($mes == "04") ? 'selected="selected"' : ''; ?>>Abril</option>
							<option value="05" <?php echo ($mes == "05") ? 'selected="selected"' : ''; ?>>Mayo</option>
							<option value="06" <?php echo ($mes == "06") ? 'selected="selected"' : ''; ?>>Junio</option>
							<option value="07" <?php echo ($mes == "07") ? 'selected="selected"' : ''; ?>>Julio</option>
							<option value="08" <?php echo ($mes == "08") ? 'selected="selected"' : ''; ?>>Agosto</option>
							<option value="09" <?php echo ($mes == "09") ? 'selected="selected"' : ''; ?>>Septiembre</option>
							<option value="10" <?php echo ($mes == "10") ? 'selected="selected"' : ''; ?>>Octubre</option>
							<option value="11" <?php echo ($mes == "11") ? 'selected="selected"' : ''; ?>>Noviembre</option>
							<option value="12" <?php echo ($mes == "12") ? 'selected="selected"' : ''; ?>>Diciembre</option>
						</select>
						<select name="anio" id="anio">
							<?php for($i = date("Y");$i >= date("Y") - 10; $i--) { ?>
							<option value="<?php echo $i;?>" <?php echo ($anio == $i) ? 'selected="selected"' : ''; ?>><?php echo $i;?></option>
							<?php } ?>
						</select>
					</td>
					<td align="right" colspan="2">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Reporte/control_detenciones'; return false;" />
						<input type="submit" name="filtro_aceptar" value="Aceptar" class="greenB" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		</form>
	</div>
<div class="wrapper">
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Detenciones por Unidad</h6>
			<div style="float: right;margin: 7px 9px;">
				<a href="/Reporte/control_detenciones_xls?anio=<?php echo $anio;?>&mes=<?php echo $mes;?>&estado=<?php echo $estado;?>&flotaid=<?php echo $flota_id;?>&faenaid=<?php echo $faena_id;?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar</a>
			</div>
		</div>
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