<script type="text/javascript">
    google.load('visualization', '1.0', {'packages':['corechart', 'line', 'bar']});
</script>

<script type="text/javascript">
$(document).ready(function(){
	$(".detalle_graficos").hide();
	//google.load('visualization', '1.0', {'packages':['corechart', 'line']});
	google.setOnLoadCallback(drawCharts);
	function drawCharts() {
		var colores_semaforo = ['#109618', '#ffd700', '#dc3912'];
		var colores_semaforo_back = ['#dc3912', '#ffd700', '#109618'];
		
		var chart = new google.visualization.LineChart(document.getElementById('chart-container-detalle-1'));
		var jsonData = JSON.parse('<?php echo json_encode($datos_grafico_1);?>');
		var data = new google.visualization.DataTable(jsonData);
		var options = {'title':"Disponibilidad",
					 'height':300,
					 'backgroundColor':'#f9f9f9',
					  'colors':['black', '#dc3912'],
					 'legend':{'position': 'bottom'}, 
					hAxis: {
					  title: 'Día',
					  maxValue: 30,
					  minValue: 1
					},
					vAxis: {
					  title: '%',
					  maxValue: 100,
					  minValue: 99
					}}; 
					
		var dia = -1;		
		function selectHandler() {
			$("#chart-container-detalle-2").fadeOut("fast");
			$(".detalle_graficos").fadeOut("fast");
			$("#chart-container-detalle-2").html("");
			var selection = chart.getSelection();
			  var message = '';
			  
			  for (var i = 0; i < selection.length; i++) {
				var item = selection[i];
				if (item.row != null && item.column != null) {
				  var str = data.getFormattedValue(item.row, item.column);
				  var str2 = data.getColumnLabel(item.column);
				  message += '{row:' + item.row + ',column:' + item.column + '} = ' + str + ' ' +str2+ '\n';
				  dia = item.row;
				} else if (item.row != null) {
				  var str = data.getFormattedValue(item.row, 0);
				  var str2 = data.getColumnLabel(0);
				  message += '{row:' + item.row + ', column:none}; value (col 0) = ' + str + ' ' +str2+ '\n';
				  dia = item.row;
				} else if (item.column != null) {
				  var str = data.getFormattedValue(0, item.column);
				  var str2 = data.getColumnLabel(item.column);
				  message += '{row:none, column:' + item.column + '}; value (row 0) = ' + str + ' ' +str2+ '\n';
				  dia = item.row;
				}
			  }
			  if (message == '') {
				message = 'nothing';
				dia = -1;
			  }
			  dia = parseInt("" + dia) + 1;
			  console.log('Ha seleccionado el día ' + dia);
			  
			  if (dia > 0) {
				// Se filtran los datos del grafico con el dia seleccionado
				var chart3 = new google.visualization.PieChart(document.getElementById('chart-container-detalle-2'));
				
				$.get( "/Principal/gestion_ajax_pie_indisponibilidad/"+dia+"/"+$("#fecha").val(), function(data) {
					if (data == "0") {
						$("#chart-container-detalle-2").html("No hay datos para el día seleccionado.");
					} else {
						var jsonData3 = JSON.parse(data);
						var data3 = new google.visualization.DataTable(jsonData3);
						var options3 = {'title':"Indisponibilidad Física día " + dia + ". (en %)",
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
							  //console.log(str + " / dia " + dia);
							  
							  if (str == "DCC") {
								$(".detalle_graficos").fadeIn("slow");
							  
							  }
						}
						
						google.visualization.events.addListener(chart3, 'select', seleccionPie3);
						chart3.draw(data3, options3);
					}
				});
			  } else {
				  
				// Se reinicia el grafico ya que se quito la seleccion  
				var chart3 = new google.visualization.PieChart(document.getElementById('chart-container-detalle-2'));
				var jsonData3 = JSON.parse('<?php echo json_encode($datos_grafico_2);?>');
				var data3 = new google.visualization.DataTable(jsonData3);
				var options3 = {'title':"Indisponibilidad Física 30 días (en %).",
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
					  console.log(str + " / dia " + dia);
				}
				
				google.visualization.events.addListener(chart3, 'select', seleccionPie3);
				chart3.draw(data3, options3);
			  }
			  
			  $("#chart-container-detalle-2").fadeIn("slow");
		}

		google.visualization.events.addListener(chart, 'select', selectHandler);
		chart.draw(data, options);
		
		var chart2 = new google.visualization.PieChart(document.getElementById('chart-container-detalle-2'));
		var jsonData2 = JSON.parse('<?php echo json_encode($datos_grafico_2);?>');
		var data2 = new google.visualization.DataTable(jsonData2);
		var options2 = {'title':"Indisponibilidad Física 30 días. (en %)",
                     'height':300,
					 'pieSliceText': 'value',
					 'backgroundColor':'#f9f9f9',
					 'colors':['#dc3912', 'blue', '#ffd700'],
					 'legend':{'position': 'bottom'}};
		
		function seleccionPie() {
			var selection = chart2.getSelection();
			  var str = "";
			  for (var i = 0; i < selection.length; i++) {
				var item = selection[i];
				if (item.row != null) {
				  str = data2.getFormattedValue(item.row, 0);
				} 
			  }
			  console.log(str + " / dia " + dia);
		}
		
		google.visualization.events.addListener(chart2, 'select', seleccionPie);
      	chart2.draw(data2, options2);
		
		var chart4 = new google.visualization.ScatterChart(document.getElementById('chart-container-detalle-3'));
		var jsonData4 = JSON.parse('<?php echo json_encode($datos_grafico_4);?>');
		var data4 = new google.visualization.DataTable(jsonData4);
		var options4 = {'title':"Intervenciones vs. Duración por Faena",
                     'height':300,
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
		chart4.draw(data4, options4);
		
		chart4 = new google.visualization.ScatterChart(document.getElementById('chart-puntos-equipo'));
		jsonData4 = JSON.parse('<?php echo json_encode($datos_grafico_5);?>');
		data4 = new google.visualization.DataTable(jsonData4);
		options4 = {'title':"Intervenciones vs. Duración por Equipo",
                     'height':300,
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
		chart4.draw(data4, options4);
		
		chart4 = new google.visualization.ScatterChart(document.getElementById('chart-puntos-motor'));
		jsonData4 = JSON.parse('<?php echo json_encode($datos_grafico_6);?>');
		data4 = new google.visualization.DataTable(jsonData4);
		options4 = {'title':"Intervenciones vs. Duración por Motor",
                     'height':300,
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
		chart4.draw(data4, options4);
		
		chart4 = new google.visualization.ColumnChart(document.getElementById('chart-container-detalle-4'));
		jsonData4 = JSON.parse('<?php echo json_encode($datos_grafico_7);?>');
		data4 = new google.visualization.DataTable(jsonData4);
		options4 = {'title':"Intervenciones por Sistema",
                     'height':300,
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
					bar: { groupWidth: "100%" }};
		chart4.draw(data4, options4);
		
		chart4 = new google.visualization.ColumnChart(document.getElementById('chart-container-detalle-5'));
		jsonData4 = JSON.parse('<?php echo json_encode($datos_grafico_8);?>');
		data4 = new google.visualization.DataTable(jsonData4);
		options4 = {'title':"Intervenciones por Subsistema",
                     'height':300,
					 'backgroundColor':'#f9f9f9',
					 'colors':['grey'],
					 'legend':{'position': 'none'},
					 hAxis: {
					  title: 'Subsistemas'//,
					 //maxValue: 30,
					  //minValue: 1
					},
					vAxis: {
					  title: 'Intervenciones'//,
					  //maxValue: 100,
					  //minValue: 90
					},
					bar: { groupWidth: "100%" }};
		chart4.draw(data4, options4);
		
		chart4 = new google.visualization.ColumnChart(document.getElementById('chart-container-detalle-6'));
		jsonData4 = JSON.parse('<?php echo json_encode($datos_grafico_9);?>');
		data4 = new google.visualization.DataTable(jsonData4);
		options4 = {'title':"Intervenciones por Elementos",
                     'height':300,
					 'backgroundColor':'#f9f9f9',
					 'colors':['grey'],
					 'legend':{'position': 'none'},
					 hAxis: {
					  title: 'Elementos'//,
					 //maxValue: 30,
					  //minValue: 1
					},
					vAxis: {
					  title: 'Intervenciones'//,
					  //maxValue: 100,
					  //minValue: 90
					},
					bar: { groupWidth: "100%" }};
		chart4.draw(data4, options4);
	}
});
</script>
	<div class="wrapper">
 		<form method="POST">
       <div class="widget">
        <div class="title"><img src="/images/icons/dark/frames.png" alt="" class="titleIcon"><h6>Filtro</h6></div>
          <table cellpadding="0" cellspacing="0" width="100%" class="sTable">
              <tbody>
				<tr>
					<td>Mes</td>
					<td><input type="month" name="fecha" id="fecha" value="<?php echo $mes;?>" max="<?php echo date("Y-m");?>" /></td>
					<td align="right">
						<input type="button" name="filtro_limpiar" value="Limpiar" class="greyishB" onclick="window.location='/Planificacion/historial'; return false;" />
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
				<?php //print_r($intervenciones); ?>
				<?php //print_r($datos); ?>
				<table width="100%" border="0" cellpadding="10" cellspacing="0" style="margin-top: 1%;">
					<tr>
						<td width="50%" align="center" style="vertical-align: middle !important;"><div id="chart-container-detalle-1" style="vertical-align: middle !important;"></div></td>
						<td align="center" style="vertical-align: middle !important;">
                        	<div id="chart-container-detalle-2" style="vertical-align: middle !important;"></div>
                        </td>
					</tr>
					<tr class="detalle_graficos" style="display: none; height: 300px;">
						<td align="center" width="50%"><div id="chart-container-detalle-3"></div></td>
						<td align="center"><div id="chart-container-detalle-4"></div></td>
					</tr>
                    <tr class="detalle_graficos" style="display: none; height: 300px;">
						<td align="center" width="50%"><div id="chart-puntos-motor"></div></td>
						<td align="center"><div id="chart-container-detalle-5"></div></td>
					</tr>
                    <tr class="detalle_graficos" style="display: none; height: 300px;">
						<td align="center" width="50%"><div id="chart-puntos-equipo"></div></td>
						<td align="center"><div id="chart-container-detalle-6"></div></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>