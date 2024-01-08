<div class="row">
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold">Trabajos en espera de revisión (agrupados por días)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="trabajos-espera-revision"></div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold">Trabajos en espera de ejecución (agrupados por días)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="trabajos-espera-ejecucion"></div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold">Distribución por sistemas (cantidad de elementos intervenidos)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="distribucion-sistemas-cantidad"></div>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold">Distribución por sistemas (tiempo en minutos)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="distribucion-sistemas"></div>
			</div>
		</div>
	</div>
	
	
</div>

<div class="row">
	
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold">Distribución por subsistemas (cantidad de elementos intervenidos)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="distribucion-subsistemas-cantidad"></div>
			</div>
		</div>
	</div>
	
	
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold">Distribución por subsistemas (tiempo en minutos)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="distribucion-subsistemas"></div>
			</div>
		</div>
	</div>
	
	
</div>

<div class="row">
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold">Distribución por tipo (cantidad de elementos intervenidos)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="distribucion-tipo-cantidad"></div>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold">Distribución por tipo (tiempo en minutos)</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<div id="distribucion-tipo-tiempo"></div>
			</div>
		</div>
	</div>
</div>

<script>
	google.charts.setOnLoadCallback(drawSistemas);
	google.charts.setOnLoadCallback(drawSistemasCantidad);
	google.charts.setOnLoadCallback(drawSubsistemas);
	google.charts.setOnLoadCallback(drawSubsistemasCantidad);
	google.charts.setOnLoadCallback(drawTipoCantidad);
	google.charts.setOnLoadCallback(drawTipoTiempo);
	google.charts.setOnLoadCallback(drawTrabajosEspera);
	google.charts.setOnLoadCallback(drawTrabajosPlanificados);
	
	var colores_semaforo = ['#109618', '#ffd700', '#dc3912'];
	var colores_semaforo_back = ['#dc3912', '#ffd700', '#109618'];
		
	function drawTipoCantidad() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Tipo');
      data.addColumn('number', 'Cantidad');
      data.addRows([
		<?php
			foreach($distribucion_tipo as $key => $value) {
				echo "['$key',$value],";
			}
		?>
      ]);

      // Instantiate and draw the chart.
      var chart = new google.visualization.PieChart(document.getElementById('distribucion-tipo-cantidad'));
      chart.draw(data, null);
    }
    
    function drawTipoTiempo() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Tipo');
      data.addColumn('number', 'Tiempo');
      data.addRows([
		<?php
			foreach($distribucion_tipo_tiempo as $key => $value) {
				echo "['$key',$value],";
			}
		?>
      ]);

      // Instantiate and draw the chart.
      var chart = new google.visualization.PieChart(document.getElementById('distribucion-tipo-tiempo'));
      chart.draw(data, null);
    }
    
	function drawSistemas() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Sistema');
      data.addColumn('number', 'Tiempo');
      data.addRows([
		<?php
			foreach($distribucion_sistemas as $key => $value) {
				echo "['$key',$value],";
			}
		?>
      ]);

      // Instantiate and draw the chart.
      var chart = new google.visualization.PieChart(document.getElementById('distribucion-sistemas'));
      chart.draw(data, null);
    }
    
    function drawSistemasCantidad() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Sistema');
      data.addColumn('number', 'Cantidad');
      data.addRows([
		<?php
			foreach($distribucion_sistemas_cantidad as $key => $value) {
				echo "['$key',$value],";
			}
		?>
      ]);

      // Instantiate and draw the chart.
      var chart = new google.visualization.PieChart(document.getElementById('distribucion-sistemas-cantidad'));
      chart.draw(data, null);
    }

	function drawSubsistemas() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Subsistema');
      data.addColumn('number', 'Tiempo');
      data.addRows([
		<?php
			foreach($distribucion_subsistemas as $key => $value) {
				echo "['$key',$value],";
			}
		?>
      ]);

      // Instantiate and draw the chart.
      var chart = new google.visualization.PieChart(document.getElementById('distribucion-subsistemas'));
      chart.draw(data, null);
    }
    
    function drawSubsistemasCantidad() {
      // Define the chart to be drawn.
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Subsistema');
      data.addColumn('number', 'Cantidad');
      data.addRows([
		<?php
			foreach($distribucion_subsistemas_cantidad as $key => $value) {
				echo "['$key',$value],";
			}
		?>
      ]);

      // Instantiate and draw the chart.
      var chart = new google.visualization.PieChart(document.getElementById('distribucion-subsistemas-cantidad'));
      chart.draw(data, null);
    }
	
	function drawTrabajosEspera() {
		var jsonData = JSON.parse('<?php echo json_encode($trabajos_espera);?>');
		var data = new google.visualization.DataTable(jsonData);
		var options = {'title':"Trabajos en espera de revisión\nAgrupados por cantidad de días",
                     'height':300,
					 'backgroundColor':'#f9f9f9',
					 'colors':colores_semaforo,
					 'legend':{'position': 'bottom'},
					 'pieSliceText': 'value'};

		var chart = new google.visualization.PieChart(document.getElementById('trabajos-espera-revision'));
		function select() {
			var selection = chart.getSelection();
			var str = "";
			for (var i = 0; i < selection.length; i++) {
				var item = selection[i];
				if (item.row != null) {
					str = data.getFormattedValue(item.row, 0);
				} 
			}
			
			if (str == "< 1 dia") {
				<?php
					$f0=strtotime("now");
					$f0=date("Y-m-d",$f0);
				?>
				window.location = "/Trabajo/Historial?fecha_inicio=<?php echo $f0;?>&fecha_termino=<?php echo $f0;?>&estado=7";
				return false;
			} else if (str == "1 - 2 dias") {
				<?php
					$f1=strtotime("-1 day");
					$f2=strtotime("-2 day");
					$f1=date("Y-m-d",$f1);
					$f2=date("Y-m-d",$f2);
				?>
				window.location = "/Trabajo/Historial?fecha_inicio=<?php echo $f2;?>&fecha_termino=<?php echo $f1;?>&estado=7";
				return false;
			} else if (str == "> 2 dias") {
				<?php
					$f3=strtotime("-3 day");
					$f3=date("Y-m-d",$f3);
					$f4=strtotime("-365 day");
					$f4=date("Y-m-d",$f4);
				?>
				window.location = "/Trabajo/Historial?fecha_inicio=<?php echo $f4;?>&fecha_termino=<?php echo $f3;?>&estado=7";
				return false;
			}
		}
		google.visualization.events.addListener(chart, 'select', select);
		chart.draw(data, null);
	}
	
	function drawTrabajosPlanificados() {
		var jsonData = JSON.parse('<?php echo json_encode($trabajos_planificados);?>');
		var data = new google.visualization.DataTable(jsonData);
		var options = {'title':"Trabajos en espera de ejecución\nAgrupados por cantidad de días",
                     'height':300,
					 'backgroundColor':'#f9f9f9',
					 'colors':colores_semaforo,
					 'legend':{'position': 'bottom'},
					 'pieSliceText': 'value'};

		var chart = new google.visualization.PieChart(document.getElementById('trabajos-espera-ejecucion'));
		function select() {
			var selection = chart.getSelection();
			var str = "";
			for (var i = 0; i < selection.length; i++) {
				var item = selection[i];
				if (item.row != null) {
					str = data.getFormattedValue(item.row, 0);
				} 
			}
			
			if (str == "< 1 dia") {
				<?php
					$f0=strtotime("now");
					$f0=date("Y-m-d",$f0);
				?>
				window.location = "/Trabajo/Historial?fecha_inicio=<?php echo $f0;?>&fecha_termino=<?php echo $f0;?>&estado=2";
				return false;
			} else if (str == "1 - 2 dias") {
				<?php
					$f1=strtotime("-1 day");
					$f2=strtotime("-2 day");
					$f1=date("Y-m-d",$f1);
					$f2=date("Y-m-d",$f2);
				?>
				window.location = "/Trabajo/Historial?fecha_inicio=<?php echo $f2;?>&fecha_termino=<?php echo $f1;?>&estado=2";
				return false;
			} else if (str == "> 2 dias") {
				<?php
					$f3=strtotime("-3 day");
					$f3=date("Y-m-d",$f3);
					$f4=strtotime("-365 day");
					$f4=date("Y-m-d",$f4);
				?>
				window.location = "/Trabajo/Historial?fecha_inicio=<?php echo $f4;?>&fecha_termino=<?php echo $f3;?>&estado=2";
				return false;
			}
		}
		google.visualization.events.addListener(chart, 'select', select);
		chart.draw(data, null);
	}
</script>