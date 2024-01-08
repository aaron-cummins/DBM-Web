<?php
	$usuario = $this->Session->read('Usuario');
?>
<script type="text/javascript">
	google.load('visualization', '1.0', {'packages':['corechart']});
	google.setOnLoadCallback(drawCharts);
	
	function drawCharts() {
								// green, yellow, red
		var colores_semaforo = ['#109618', '#ffd700', '#dc3912'];
		var colores_semaforo_back = ['#dc3912', '#ffd700', '#109618'];
		
		var jsonData = JSON.parse('<?php echo json_encode($datos_grafico_5);?>');
		var data = new google.visualization.DataTable(jsonData);
		var options = {'title':"Trabajos planificados en espera de ejecución\nAgrupados por tipo de intervención",
                     'height':300,
					 'backgroundColor':'#f9f9f9',
					 'colors':['orange', '#ffd700', '#109618'],
					 'legend':{'position': 'bottom'},
					 'pieSliceText': 'value'};

      	var chart = new google.visualization.PieChart(document.getElementById('chart-container-detalle-4'));
		function select() {
			var selection = chart.getSelection();
			var str = "";
			for (var i = 0; i < selection.length; i++) {
				var item = selection[i];
				if (item.row != null) {
					str = data.getFormattedValue(item.row, 0);
				} 
			}
			window.location = "/Planificacion/historial/?estado=2&tipo_evento=PR&tipo_intervencion="+str;
			return false;
		}
	  	google.visualization.events.addListener(chart, 'select', select);
      	chart.draw(data, options);
		
		var jsonData2 = JSON.parse('<?php echo json_encode($datos_grafico_3);?>');
		var data2 = new google.visualization.DataTable(jsonData2);
		var options2 = {'title':"Trabajos planificados en espera de ejecución\nAgrupados por cantidad de días",
                     'height':300,
					 'backgroundColor':'#f9f9f9',
					 'colors':colores_semaforo,
					 'legend':{'position': 'bottom'},
					 'pieSliceText': 'value'};

      	var chart2 = new google.visualization.PieChart(document.getElementById('chart-container-detalle-3'));
		function select_2() {
			var selection = chart2.getSelection();
			var str = "";
			for (var i = 0; i < selection.length; i++) {
				var item = selection[i];
				if (item.row != null) {
					str = data2.getFormattedValue(item.row, 0);
				} 
			}
			
			if (str == "< 1 dia") {
				<?php
					$f0=strtotime("now");
					$f0=date("Y-m-d",$f0);
				?>
				window.location = "/Planificacion/historial/?estado=2&fecha=<?php echo $f0;?>&fecha_termino=<?php echo $f0;?>";
				return false;
			} else if (str == "1 - 2 dias") {
				<?php
					$f1=strtotime("-1 day");
					$f2=strtotime("-2 day");
					$f1=date("Y-m-d",$f1);
					$f2=date("Y-m-d",$f2);
				?>
				window.location = "/Planificacion/historial/?estado=2&fecha=<?php echo $f2;?>&fecha_termino=<?php echo $f1;?>";
				return false;
			} else if (str == "> 2 dias") {
				<?php
					$f3=strtotime("-3 day");
					$f3=date("Y-m-d",$f3);
					$f4=strtotime("-365 day");
					$f4=date("Y-m-d",$f4);
				?>
				window.location = "/Planificacion/historial/?estado=2&fecha=<?php echo $f4;?>&fecha_termino=<?php echo $f3;?>";
				return false;
			}
		}
	  	google.visualization.events.addListener(chart2, 'select', select_2);
      	chart2.draw(data2, options2);
		
		var jsonData3 = JSON.parse('<?php echo json_encode($datos_grafico_1);?>');
		var data3 = new google.visualization.DataTable(jsonData3);
		var options3 = {'title':"Trabajos en espera de revisión\nAgrupados por cantidad de días",
                     'height':300,
					 'backgroundColor':'#f9f9f9',
					 'colors':colores_semaforo,
					 'legend':{'position': 'bottom'},
					 'pieSliceText': 'value'};

      	var chart3 = new google.visualization.PieChart(document.getElementById('chart-container-detalle-1'));
		function select_1() {
			var selection = chart3.getSelection();
			var str = "";
			for (var i = 0; i < selection.length; i++) {
				var item = selection[i];
				if (item.row != null) {
					str = data3.getFormattedValue(item.row, 0);
				} 
			}
			
			if (str == "< 1 dia") {
				<?php
					$f0=strtotime("now");
					$f0=date("Y-m-d",$f0);
				?>
				window.location = "/Planificacion/historial/?fecha=<?php echo $f0;?>&fecha_termino=<?php echo $f0;?>&estado=7";
				return false;
			} else if (str == "1 - 2 dias") {
				<?php
					$f1=strtotime("-1 day");
					$f2=strtotime("-2 day");
					$f1=date("Y-m-d",$f1);
					$f2=date("Y-m-d",$f2);
				?>
				window.location = "/Planificacion/historial/?fecha=<?php echo $f2;?>&fecha_termino=<?php echo $f1;?>&estado=7";
				return false;
			} else if (str == "> 2 dias") {
				<?php
					$f3=strtotime("-3 day");
					$f3=date("Y-m-d",$f3);
					$f4=strtotime("-365 day");
					$f4=date("Y-m-d",$f4);
				?>
				window.location = "/Planificacion/historial/?fecha=<?php echo $f4;?>&fecha_termino=<?php echo $f3;?>&estado=7";
				return false;
			}
		}
	  	google.visualization.events.addListener(chart3, 'select', select_1);
      	chart3.draw(data3, options3);
		
		
		var jsonData4 = JSON.parse('<?php echo json_encode($datos_grafico_6);?>');
		var data4 = new google.visualization.DataTable(jsonData4);
		/*
		var options = {'title':"Trabajos en espera de revisión\nAgrupados por cantidad de días",
                     'height':300,
					 'backgroundColor':'#f9f9f9',
					 'colors':colores_semaforo,
					 'legend':{'position': 'bottom'}};
		*/
		var options4 = {
			'title':"Trabajos en espera de revisión\nAgrupados por cantidad de días y tipo de trabajo",
			height: 300,
			'legend':{'position': 'bottom'},
			'backgroundColor':'#f9f9f9',
			'colors':colores_semaforo_back,
			/*bar: { groupWidth: '75%' },*/
			isStacked: true,
		};
		function select_4() {
			var selection = chart4.getSelection()[0];
			if (selection != undefined && selection.row != undefined) {
				var dato = selection.column;
				//console.log(data4.getFormattedValue(selection.row, 0));
				// column-> 1: +2, 2: 1-2, 3: -1
				//console.dir(selection);
				if (data4.getFormattedValue(selection.row, 0) == "Terminados") {
					if(dato=='3'){
						<?php
							$f0=strtotime("now");
							$f0=date("Y-m-d",$f0);
						?>
						window.location = "/Planificacion/historial/?tipo_terminado=T&estado=7&fecha=<?php echo $f0;?>&fecha_termino=<?php echo $f0;?>";
						return false;
					}
					if(dato=='2'){
						<?php
							$f1=strtotime("-1 day");
							$f2=strtotime("-2 day");
							$f1=date("Y-m-d",$f1);
							$f2=date("Y-m-d",$f2);
						?>
						window.location = "/Planificacion/historial/?tipo_terminado=T&estado=7&fecha=<?php echo $f2;?>&fecha_termino=<?php echo $f1;?>";
						return false;
					}
					if(dato=='1'){
						<?php
							$f3=strtotime("-3 day");
							$f3=date("Y-m-d",$f3);
							$f4=strtotime("-365 day");
							$f4=date("Y-m-d",$f4);
						?>
						window.location = "/Planificacion/historial/?tipo_terminado=T&estado=7&fecha=<?php echo $f4;?>&fecha_termino=<?php echo $f3;?>";
						return false;
					}
				} else if (data4.getFormattedValue(selection.row, 0) == "Pendientes") {
					if(dato=='3'){
						<?php
							$f0=strtotime("now");
							$f0=date("Y-m-d",$f0);
						?>
						window.location = "/Planificacion/historial/?tipo_terminado=P&estado=7&fecha=<?php echo $f0;?>&fecha_termino=<?php echo $f0;?>";
						return false;
					}
					if(dato=='2'){
						<?php
							$f1=strtotime("-1 day");
							$f2=strtotime("-2 day");
							$f1=date("Y-m-d",$f1);
							$f2=date("Y-m-d",$f2);
						?>
						window.location = "/Planificacion/historial/?tipo_terminado=P&estado=7&fecha=<?php echo $f2;?>&fecha_termino=<?php echo $f1;?>";
						return false;
					}
					if(dato=='1'){
						<?php
							$f3=strtotime("-3 day");
							$f3=date("Y-m-d",$f3);
							$f4=strtotime("-365 day");
							$f4=date("Y-m-d",$f4);
						?>
						window.location = "/Planificacion/historial/?tipo_terminado=P&estado=7&fecha=<?php echo $f4;?>&fecha_termino=<?php echo $f3;?>";
						return false;
					}
				}
				return false;
			}
			//console.log(selection.row);
			//console.log(selection.row);
			//console.log(data4.getValue(selection.row, selection.column))
		}
      	var chart4 = new google.visualization.ColumnChart(document.getElementById('chart-container-detalle-2'));
		google.visualization.events.addListener(chart4, 'select', select_4);
      	chart4.draw(data4, options4);
		
    }
</script>
<div class="wrapper">
	<div class="widgets">
		<div class="one">
			<div class="widget">
				<div class="title"><img src="/images/icons/dark/chart.png" alt="" class="titleIcon"><h6><?php echo $titulo;?></h6></div>
				<?php //print_r($intervenciones); ?>
				<?php //print_r($datos); ?>
				<table width="99%" border="0" cellpadding="10" cellspacing="0" style="margin: 1% auto;" align="center">
					<tr>
						<td width="50%" align="left"><div id="chart-container-detalle-1"></div></td>
						<td align="right"><div id="chart-container-detalle-2"></div></td>
					</tr>
					<tr>
						<td align="left" width="50%"><div id="chart-container-detalle-3"></div></td>
						<td align="right"><div style="width:100%; height:300" id="chart-container-detalle-4"></div></td>
					</tr>
				</table>
                <?php if ($usuario["usuario"] == "16231310") {?>
                <!--<div id="chart_div" style="width:100%; height:300"></div>-->
                <?php } ?>
			</div>
		</div>
	</div>
</div>