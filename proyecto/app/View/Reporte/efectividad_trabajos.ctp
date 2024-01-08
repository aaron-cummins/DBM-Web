 <?php
	ini_set('memory_limit','128M');
	App::import('Controller', 'Utilidades');
	$util = new UtilidadesController();
?>
<script type="text/javascript">
	google.load('visualization', '1.0', {'packages':['corechart', 'bar']});
	google.setOnLoadCallback(drawCharts);
	
	function drawCharts() {
		var jsonData = JSON.parse('<?php echo json_encode($grafico_data);?>');
		var data = new google.visualization.DataTable(jsonData);
		
		var options = {
			'title':"Eventos Planificados y Ejecutados por Faena",
			height: 300,
			'legend':{'position': 'bottom'},
			'backgroundColor':'#f9f9f9'
		};
		
		chart = new google.visualization.ColumnChart(document.getElementById('chart-container-1'));
		chart.draw(data, options);
	
		
 <?php if ($desplegar_detalle == "1") { ?>
 
	jsonData = JSON.parse('<?php echo json_encode($grafico_detalle_1);?>');
	data = new google.visualization.DataTable(jsonData);
	options = {'title':"Trabajos Planificados\nAgrupados por tipo de intervención",
				 'height':300,
				 'backgroundColor':'#f9f9f9',
				 'colors': ['#109618', '#ffd700','orange'],
				 'legend':{'position': 'bottom'}};

	chart = new google.visualization.PieChart(document.getElementById('chart-container-detalle'));
	chart.draw(data, options);
		
		
	jsonData = JSON.parse('<?php echo json_encode($grafico_detalle_2);?>');
	data = new google.visualization.DataTable(jsonData);
	options = {'title':"Trabajos Ejecutados\nAgrupados por tipo de intervención",
				 'height':300,
				 'backgroundColor':'#f9f9f9',
				 'colors': ['#109618', '#ffd700','orange'],
				 'legend':{'position': 'bottom'}};

	chart = new google.visualization.PieChart(document.getElementById('chart-container-detalle2'));
	chart.draw(data, options);


 <?php } ?>
 }
</script>
<!-- Title area -->
<div class="titleArea">
  <div class="wrapper">
		<div class="pageTitle">
			<h5>Reporte</h5>
			<span>Efectividad de Trabajos</span>
		</div>
	  <div class="clear"></div>
	</div>
</div>


<!-- Page statistics area -->


<div class="line"></div>
	<div class="wrapper">
 		<form method="POST" action="/Reporte/efectividad_trabajos/">
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
                <!--<tr>
					<td width="15%">Faena</td>
					<td>
						<select name="faena_id" id="faena_id">
							<option value="" <?php echo ($faena_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
						</select>
					</td>
					<td width="15%">Flota</td>
					<td>
						<select name="flota_id_h" id="flota_id_h">
							<option value="" <?php echo ($flota_id == "") ? 'selected="selected"' : ''; ?>>Todas</option>
							<?php
								foreach ($flotas as $flota) { 
									echo "<option value=\"{$flota["UnidadDetalle"]["flota_id"]}\" ".(($flota_id == $flota["UnidadDetalle"]["flota_id"]) ? 'selected="selected"' : '').">{$flota["UnidadDetalle"]["flota"]}</option>"."\n"; 
								}
							?>
						</select>
					</td>
					<td width="15%">Equipo</td>
					<td>
						<select name="unidad_id_h" id="unidad_id_h" unidad_id="<?php echo $unidad_id;?>">
							<option value="" <?php echo ($unidad_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Arreglo Motor</td>
					<td>
                    	<select name="motor_id" id="motor_id">
							<option value="" <?php echo ($motor_id == "") ? 'selected="selected"' : ''; ?>>Todos</option>
						</select>
                    </td>
					<td width="15%">Tipo Evento</td>
					<td>
						<select name="tipo_evento" id="tipo_evento">
							<option value="" <?php echo ($tipo_evento == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="PR" <?php echo ($tipo_evento == "PR") ? 'selected="selected"' : ''; ?>>Programado</option>
							<option value="NP" <?php echo ($tipo_evento == "NP") ? 'selected="selected"' : ''; ?>>No Programado</option>
						</select>
					</td>
					<td width="15%">Tipo Intervención</td>
					<td>
						<select name="tipo_intervencion" id="tipo_intervencion" tipo_intervencion="<?php echo $tipo_intervencion;?>">
							<option value="" <?php echo ($tipo_intervencion == "") ? 'selected="selected"' : ''; ?>>Todos</option>
							<option value="MP" <?php echo ($tipo_intervencion == "MP") ? 'selected="selected"' : ''; ?>>MP</option>
							<option value="RP" <?php echo ($tipo_intervencion == "RP") ? 'selected="selected"' : ''; ?>>RP</option>
							<option value="BL" <?php echo ($tipo_intervencion == "BL") ? 'selected="selected"' : ''; ?>>BL</option>
							<option value="EX" <?php echo ($tipo_intervencion == "EX") ? 'selected="selected"' : ''; ?>>EX</option>
							<option value="RI" <?php echo ($tipo_intervencion == "RI") ? 'selected="selected"' : ''; ?>>RI</option>
						</select>
					</td>
				</tr>-->
				<tr>
					<td>Mes</td>
					<td><input type="month" name="mes" id="mes" value="<?php echo $mes;?>" max="<?php echo date("Y-m");?>" /></td>
					<!--<td>Fecha Término</td>
					<td><input type="date" name="fecha_termino" id="fecha_termino" value="<?php echo $fecha_termino;?>" max="<?php echo date("Y-m-d");?>" /></td>-->
					<td align="right" colspan="2">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Reporte/efectividad_trabajos'; return false;" />
						<input type="submit" name="filtro_aceptar" value="Aceptar" class="greenB" />
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		</form>
	</div>
<!-- Main content wrapper -->
<div class="wrapper">
<div class="widget">
	<div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Eventos Planificados vs. Ejecutados</h6></div>
	  <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
		  <thead>
			<tr>
				<td>Faena</td>
				<td>N° Eventos Planificados</td>
				<td>N° Eventos Realizados</td>
				<td>%</td>
                <td width="50"></td>
			</tr>
		  </thead>
		  <tbody>
			<?php
			foreach ($realizados as $key => $value) { 
			?>
			  <tr>
				<td><?php echo $key;?></td>
				<td><?php echo $programados[$key];?></td>
				<td><?php echo $value;?></td>
				<td><?php echo number_format($value*100/$programados[$key],0);?>%</td>
                <td><input type="button" value="Detalle" onClick="window.location='/Reporte/efectividad_trabajos/<?php echo $key;?>/<?php echo $mes;?>'; return false;" /></td>
			</tr>
			  <?php } ?>
		  </tbody>
	  </table>
	</div>

<?php if ($desplegar_detalle == "1") { ?>
	<div class="widget">
		<div class="title"><img src="/images/icons/dark/chart.png" alt="" class="titleIcon"><h6>Detalle <?php echo $faena_nombre;?></h6> <input type="button" value="Cerrar" onClick="window.location='/Reporte/efectividad_trabajos'; return false;" style="margin-top: 4px;float: right;margin-right: 4px;" /></div>
		<table width="100%" border="0" cellpadding="10" cellspacing="0" style="margin-top: 1%;">
			<tr>
				<td width="50%" align="center"><div id="chart-container-detalle"></div></td>
				<td align="center"><div id="chart-container-detalle2"></div></div></td>
			</tr>
		</table>
	</div>
<?php } ?>

    <div class="widget">
        <div class="title"><img src="/images/icons/dark/chart.png" alt="" class="titleIcon"><h6>Resumen</h6></div>
        <div id="chart-container-1"></div>
    </div>
</div>