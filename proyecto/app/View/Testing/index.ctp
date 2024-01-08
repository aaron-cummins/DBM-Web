<?php
	$fi = strtotime($fecha_inicio);
	$ft = strtotime($fecha_termino);
?>
<script type="text/javascript">
    google.load('visualization', '1.0', {'packages':['corechart', 'line', 'bar']});
</script>
<script type="text/javascript">
$(document).ready(function(){
	$("#chart-container-detalle-2").html("Seleccione un día en el gráfico de Disponibilidad");
	google.setOnLoadCallback(desplegarDisponibilidad);
	function desplegarDisponibilidad() {
		var chart = new google.visualization.LineChart(document.getElementById('chart-container-detalle-1'));
		var jsonData = JSON.parse('<?php echo json_encode($datos_grafico_1);?>');
		var data = new google.visualization.DataTable(jsonData);
		var options = {'title':"Disponibilidad Física y Contractual entre el <?php echo date("d-m", $fi);?> y el <?php echo date("d-m", $ft);?>",
					 'height':250,
					 'backgroundColor':'#f9f9f9',
					  'colors':['#dc3912', 'black'],
					 'legend':{'position': 'none'}, 
					hAxis: {
					  title: ''
					},
					vAxis: {
					  title: '% disponibilidad'
					}}; 
		function seleccionarDia() {
			$(".detalle_graficos").hide();
			var selection = chart.getSelection();
			var message = '';
			var item = selection[0];
			if (item != undefined && item.row != null && item.column != null) {
				var dia = data.getValue(item.row, 0);
				$("#chart-container-detalle-2").html("Ha seleccionado el " + dia);
				$("#fecha_seleccionada").val(dia);
				if (dia != "" && dia.length == 10) {
				// Se filtran los datos del grafico con el dia seleccionado
				var chart3 = new google.visualization.PieChart(document.getElementById('chart-container-detalle-2'));
				
				$.get( "/Gestion/gestion_ajax_pie_indisponibilidad/"+dia+"/"+$("#faena_id").val(), function(data) {
					if (data == "0") {
						$("#chart-container-detalle-2").html("No hay datos para el día seleccionado.");
					} else {
						var jsonData3 = JSON.parse(data);
						var data3 = new google.visualization.DataTable(jsonData3);
						var options3 = {'title':"Indisponibilidad Física del " + dia + " (%).",
									 'height':300,
									 'pieSliceText': 'value',
									 'backgroundColor':'#f9f9f9',
									 'colors':['#dc3912', 'blue', '#ffd700'],
									 'legend':{'position': 'bottom'}};
						function seleccionPie3() {
							var selection = chart3.getSelection();
							var str = "";
							for (var i = 0; i < selection.length; i++) {
								var item = selection[i];
								if (item.row != null) {
								  str = data3.getFormattedValue(item.row, 0);
								} 
							}
							if (str == "DCC") {
								$.get( "/Gestion/intervenciones_duracion/"+$("#fecha_seleccionada").val()+"/"+$("#faena_id").val(), function(data) {
									var chart3 = new google.visualization.ScatterChart(document.getElementById('chart-container-detalle-3'));
									var jsonData3 = JSON.parse(data);
									var data3 = new google.visualization.DataTable(jsonData3);
									var options3 = {'title':"Intervenciones vs. Duración por Faena",
												 'height':300,
												 'width': '100%',
												 'backgroundColor':'#f9f9f9',
												 'colors':['black', 'blue', '#ffd700'],
												 'legend':{'position': 'none'},
												 hAxis: {
												  title: 'Cantidad de Intervenciones'//,
												 //maxValue: 30,
												  //minValue: 1
												},
												vAxis: {
												  title: 'Duración Promedio (hrs)'//,
												  //maxValue: 100,
												  //minValue: 90
												}};
									chart3.draw(data3, options3);
									$(".detalle_graficos").show();
									$("#chart-container-detalle-3").fadeIn("slow");
								});
								$.get( "/Gestion/intervenciones_sistema/"+$("#fecha_seleccionada").val()+"/"+$("#faena_id").val(), function(data) {
									var chart4 = new google.visualization.ColumnChart(document.getElementById('chart-container-detalle-4'));
									var jsonData4 = JSON.parse(data);
									var data4 = new google.visualization.DataTable(jsonData4);
									var options4 = {'title':"Intervenciones por Sistema",
											 'height':300,
											 'width': '100%',
											 'backgroundColor':'#f9f9f9',
											 'colors':['grey'],
											 'legend':{'position': 'none'},
											 hAxis: {
											  title: 'Sistemas'//,
											 //maxValue: 30,
											  //minValue: 1
											},
											vAxis: {
											  title: 'Intervenciones'//,
											  //maxValue: 100,
											  //minValue: 90
											},
											bar: { groupWidth: "100%" },
											trendlines: { 1: { }}
											};
									chart4.draw(data4, options4);
									$(".detalle_graficos").show();
									$("#chart-container-detalle-4").fadeIn("slow");
								});
							}
						}
						google.visualization.events.addListener(chart3, 'select', seleccionPie3);
						chart3.draw(data3, options3);
					}
				});
			  }
			} else {
				$("#chart-container-detalle-2").html("Seleccione un día en el gráfico de Disponibilidad");
				$("#fecha_seleccionada").val("");
			}
			$("#chart-container-detalle-2").fadeIn("slow");
		}
		google.visualization.events.addListener(chart, 'select', seleccionarDia);
		chart.draw(data, options);
	}
});
</script>
<input type="hidden" id="fecha_seleccionada" value="" />
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
				<td>Período Fecha</td>
				<td>
					<input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo $fecha_inicio;?>" max="<?php echo date("Y-m-d");?>" /> a 
					<input type="date" name="fecha_termino" id="fecha_termino" value="<?php echo $fecha_termino;?>" max="<?php echo date("Y-m-d");?>" />
				</td>
				<td align="right" colspan="2">
					<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Gestion'; return false;" />
					<input type="submit" name="filtro_aceptar" value="Aceptar" class="greenB" />
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	</form>
</div>
<div class="wrapper">
	<div class="widgets">
		<div class="one">
			<div class="widget">
				<div class="title"><img src="/images/icons/dark/chart.png" alt="" class="titleIcon"><h6><?php echo $titulo;?></h6></div>
				<table width="100%" border="0" cellpadding="10" cellspacing="0" style="margin-top: 1%;">
					<tr>
						<td width="75%" align="center" style="vertical-align: middle !important;">
							<div id="chart-container-detalle-1" style="vertical-align: middle !important;"></div>
						</td>
						<td align="center" style="vertical-align: middle !important;">
							<div id="chart-container-detalle-2" style="vertical-align: middle !important;"></div>
                        </td>
					</tr>
				</table>
				<table class="detalle_graficos" width="100%" border="0" cellpadding="10" cellspacing="0" style="margin-top: 1%; display: none; height: 300px;"">
					<tr>
						<td align="center" width="50%"><div id="chart-container-detalle-3" style="vertical-align: middle !important;"></div></td>
						<td  align="center"><div id="chart-container-detalle-4" style="vertical-align: middle !important;"></div></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>