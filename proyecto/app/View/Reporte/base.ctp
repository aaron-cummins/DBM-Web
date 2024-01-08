<?php
	App::import('Controller', 'Utilidades');
	App::import('Controller', 'UtilidadesReporte');
	$util = new UtilidadesController();
	$utilReporte = new UtilidadesReporteController();
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Filtros</span>
					<span class="caption-helper">Aplique uno o más filtros</span>
				</div>
			</div>
			<div class="portlet-body form">
                <div class="col-lg-12" id="alerta_fechas" style="display: none">
                    <div class="alert alert-danger">
                        <strong>Fechas: </strong>  <span class="mensaje_fechas"></span>
                    </div>
                </div>
				<!-- BEGIN FORM-->
				<form action="/Reporte/Base" class="horizontal-form" method="get" name="base_report" id="base_report">
					<div class="form-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Faena</label>
									<select class="form-control" name="faena_id" id="faena_id" required="required"> 
										<option value="">Seleccione una opción</option>
										<?php foreach($faenas as $key => $value) { ?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php }?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Flota</label>
									<select class="form-control" name="flota_id" id="flota_id"> 
										<option value="">Todos</option>
										<?php
										foreach($flotas as $key => $value) { ?>
											<option value="<?php echo $value["FaenaFlota"]["faena_id"];?>_<?php echo $value["FaenaFlota"]["flota_id"];?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"];?>"><?php echo $value["Flota"]["nombre"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Unidad</label>
										<select class="form-control" name="unidad_id" id="unidad_id" > 
										<option value="">Todos</option>
										<?php
										foreach($unidades as $key => $value) { ?>
											<option value="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>_<?php echo $value["Unidad"]["id"];?>" faena_flota="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>"><?php echo $value["Unidad"]["unidad"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Resultados por página</label>
									<select class="form-control" name="limit">
										<option value="" <?php echo $limit == "" ? "selected=\"selected\"" : ""; ?>>Todos</option>
										<option value="10" <?php echo $limit == 10 ? "selected=\"selected\"" : ""; ?>>10</option>
										<option value="25" <?php echo $limit == 25 ? "selected=\"selected\"" : ""; ?>>25</option>
										<option value="50" <?php echo $limit == 50 ? "selected=\"selected\"" : ""; ?>>50</option>
										<option value="100" <?php echo $limit == 100 ? "selected=\"selected\"" : ""; ?>>100</option>
									</select> 
								</div>
							</div>
							
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Correlativo</label>
									<input type="number" name="correlativo" class="form-control" min="1" value="<?php echo @$correlativo;?>"> </div>
							</div>
							<div class="col-md-3 ">
								<div class="form-group">
									<label>Duración</label>
									<input type="number" name="duracion" class="form-control" min="0" value="<?php echo @$duracion;?>"> </div>
							</div>
							<div class="col-md-3 ">
								<div class="form-group">
									<label>Fecha Inicio</label>
									<input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio;?>" required="required" /> </div>
							</div>
							<div class="col-md-3 ">
								<div class="form-group">
									<label>&nbsp;</label>
									<input type="date" name="fecha_termino" id="fecha_termino" class="form-control" value="<?php echo $fecha_termino;?>" required="required" /> </div>
							</div>
						</div>
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Reporte/Base';">Limpiar</button>
						<button type="submit" class="btn blue btn-submit-alert" name="btn-aplicar">
							<i class="fa fa-filter"></i> Aplicar</button>
						<button type="submit" class="btn green btn-submit-alert" name="btn-descargar">
							<i class="fa fa-file-excel-o"></i> Descargar Excel</button>
						<button type="submit" class="btn green btn-submit-alert" name="btn-descargar-csv">
							<i class="fa fa-file-o"></i> Descargar CSV</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$("#fecha_inicio, #fecha_termino, #unidad_id").change(() => {
		fechas();
	});

	$("#faena_id").change(() => {
		$("#flota_id").val("");
		$("#unidad_id").val("");
		fechas();
	});

	$("#flota_id").change(() => {
		$("#unidad_id").val("");
		fechas();
	});

    function fechas() {
        var dia_milisegundos = 86400000;
		var unidad = $("#unidad_id").val();
		var flota = $("#flota_id").val();
		var faena = $("#faena_id").val();

        let ini = new Date($("#fecha_inicio").val());
        let fin = new Date($("#fecha_termino").val());

        if(ini > fin) {
            document.getElementById("alerta_fechas").style.display = "block";
            $(".mensaje_fechas").text("La fecha de termino no puede ser menor a la de inicio.");
            $(".btn-submit-alert").attr('disabled', 'disabled');
            return false;
        }else{
            document.getElementById("alerta_fechas").style.display = "none";
            $(".btn-submit-alert").removeAttr('disabled');
        }

        let dif_mili = fin - ini;
        let dif_dia = dif_mili / dia_milisegundos;

        if(unidad == "") {
			if (dif_dia > 180) {
				document.getElementById("alerta_fechas").style.display = "block";
				$(".btn-submit-alert").attr('disabled', 'disabled');
				$(".mensaje_fechas").text("La diferencia de fechas no puede ser mayor a 6 meses. Esto solo se permite para descargar la información de un equipo.");
				return false;
			} else {
				document.getElementById("alerta_fechas").style.display = "none";
				$(".btn-submit-alert").removeAttr('disabled');
			}
		}else{
			document.getElementById("alerta_fechas").style.display = "none";
			$(".btn-submit-alert").removeAttr('disabled');
		}
    }

</script>


<?php if (count($intervenciones) > 0) {  ?>

<?php
/*
echo "<pre>"; print_r($intervenciones); 
echo "</pre>";
exit;
*/
?>


<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Historial</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="" class="horizontal-form" method="post">
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<!--
					<div class="row">
						<div class="col-md-12">
							<div class="dt-buttons">
								<a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="/Reporte/Descargar/Excel?t=<?php echo time() . md5(time());?>&fecha_inicio=<?php echo $fecha_inicio;?>&fecha_termino=<?php echo $fecha_termino;?>&faena_id=<?php echo $faena_id;?>&correlativo=<?php echo $correlativo;?>&duracion=<?php echo $duracion_filtro;?>&flota_id=<?php echo $flota_id;?>&unidad_id=<?php echo $unidad_id;?>"><i class="fa fa-file-excel-o"></i> <span>Descargar</span></a>
							</div>
						</div>
					</div>
					-->
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<thead>
									<tr>
										<th>Correlativo</th>
										<th>Folio</th>
										<th>Faena</th>
										<th>Flota</th>
										<th>Unidad</th>
										<th>Esn</th>
										<th>Tipo</th>
										<th>Actividad</th>
										<th>Responsable</th>
										<th>Categoría</th>
										<th colspan="2">Inicio</th>
										<th colspan="2">Término</th>
										<th>Duración</th>
										<th>Sintoma</th>
										<th>Elemento</th>
										<th>Número Os Sap</th>
									</tr>
									</thead>
									<tbody>
									<?php 
										$i = 1;
										foreach ($intervenciones as $intervencion) {
											if($intervencion["ReporteBase"]["categoria"] == "Inicial") {
												echo '<tr style="background-color: black; color: white;">';
											} elseif($intervencion["ReporteBase"]["categoria"] == "Continuación") { 
												echo '<tr style="background-color: gray; color: white;">';
											} elseif($intervencion["ReporteBase"]["categoria"] == "Intervención") { 
												echo '<tr style="background-color: white; color: black;">';
											} elseif($intervencion["ReporteBase"]["categoria"] == "Tiempo") { 
												echo '<tr style="background-color: #DDEBF7; color: black;">';
											}else{
												echo '<tr>';
											}
										?>
										
										
											<td><?php echo $intervencion["ReporteBase"]["correlativo"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["folio"];?></td>
											<td nowrap><?php echo $intervencion["ReporteBase"]["faena"];?></td>
											<td nowrap><?php echo $intervencion["ReporteBase"]["flota"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["unidad"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["esn"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["tipo"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["actividad"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["responsable"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["categoria"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["fecha_inicio"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["hora_inicio"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["fecha_termino"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["hora_termino"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["tiempo"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["sintoma"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["elemento"];?></td>
											<td><?php echo $intervencion["ReporteBase"]["numero_os_sap"];?></td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>
