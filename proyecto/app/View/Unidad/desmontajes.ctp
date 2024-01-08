<?php
	// HR acumuladas motor = HR operadas motor + HR motor instalación
	// HR historicas = sumatoria HR acumuladas
	//$hr_historico_motor = 0;
	$hr_historico_equipo = 0;
	$hr_motor_instalacion = 0;
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
				<!-- BEGIN FORM-->
				<form action="/Unidad/Desmontajes" class="horizontal-form" method="get">
					<div class="form-body">
					<div class="row">
							<div class="col-md-4">
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
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Flota</label>
									<select class="form-control" name="flota_id" id="flota_id" required="required"> 
										<option value="">Seleccione una opción</option>
										<?php
										foreach($flotas as $key => $value) { ?>
											<option value="<?php echo $value["FaenaFlota"]["faena_id"];?>_<?php echo $value["FaenaFlota"]["flota_id"];?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"];?>"><?php echo $value["Flota"]["nombre"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Unidad</label>
										<select class="form-control" name="unidad_id" id="unidad_id" required="required" > 
										<option value="">Seleccione una opción</option>
										<?php
										foreach($unidades as $key => $value) { ?>
											<option value="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>_<?php echo $value["Unidad"]["id"];?>" faena_flota="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>"><?php echo $value["Unidad"]["unidad"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Unidad/Desmontajes';">Limpiar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-filter"></i> Aplicar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>

<?php if($faena_id != '' && $flota_id != '' && $unidad_id != ''){ ?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Historial de la unidad</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="" class="horizontal-form" method="post">
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th>Faena</th>
										<th>Flota</th>
										<th>Unidad</th>
										<th>ESN [Placa]</th>
										<th>Estado equipo</th>
										<th>Estado motor</th>
										<th>PS</th>
										<th>HR operadas motor</th>
										<!--<th>HR motor instalacion</th>
										<th>HR historico motor</th>
										<th>HR acumuladas motor</th>-->
										<th>HR equipo</th> 
										<th>Fecha falla</th>
									</tr>
									<?php foreach ($estado_motor as $unidad) { 
										if($unidad["EstadosMotores"]["estado"] == '2' || $unidad["EstadosMotores"]["estado"] == '1'){
											echo "<tr style=\"background-color:#fbe1e3;\">";
										}
										else 
										{
											echo "<tr>";
										}
										if($unidad["EstadosMotores"]["fecha_ps"] != null && $unidad["EstadosMotores"]["fecha_ps"] != '') {
											$unidad["EstadosMotores"]["fecha_ps"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_ps"]));
										}
										if($unidad["EstadosMotores"]["fecha_falla"] != null && $unidad["EstadosMotores"]["fecha_falla"] != '') {
											$unidad["EstadosMotores"]["fecha_falla"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_falla"]));
										}
										if(isset($unidad["EstadosMotores"]["hr_operadas_motor"]) && is_numeric($unidad["EstadosMotores"]["hr_operadas_motor"])) {
											//$hr_historico_motor += $unidad["EstadosMotores"]["hr_operadas_motor"];
										}
										if(isset($unidad["EstadosMotores"]["hr_motor_instalacion"]) && is_numeric($unidad["EstadosMotores"]["hr_motor_instalacion"])) {
											$hr_motor_instalacion += $unidad["EstadosMotores"]["hr_motor_instalacion"];
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_equipo"])) {
											$unidad["EstadosMotores"]["hr_equipo"] = $unidad["EstadosMotores"]["hr_equipo_instalacion"] + $unidad["EstadosMotores"]["hr_operadas_motor"];
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_operadas_motor"])) {
											$unidad["EstadosMotores"]["hr_operadas_motor"] = '0';
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_acumuladas_motor"])) {
											$unidad["EstadosMotores"]["hr_acumuladas_motor"] = $unidad["EstadosMotores"]["hr_operadas_motor"];// + $unidad["EstadosMotores"]["hr_motor_instalacion"];
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_historico_motor"])) {
											$unidad["EstadosMotores"]["hr_historico_motor"] = $unidad["EstadosMotores"]["hr_acumuladas_motor"];
										}
										$hr_historico_equipo += $unidad["EstadosMotores"]["hr_operadas_motor"];
									?>
										<?php echo "<td>{$unidad["Faena"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Flota"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Unidad"]["unidad"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["esn_placa"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadoEquipo"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadoMotor"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["fecha_ps"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["hr_operadas_motor"]}</td>"; ?>
										<!--
										<?php echo "<td>{$unidad["EstadosMotores"]["hr_motor_instalacion"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["hr_historico_motor"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["hr_acumuladas_motor"]}</td>"; ?>
										-->
										<?php echo "<td>{$unidad["EstadosMotores"]["hr_equipo"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["fecha_falla"]}</td>"; ?>
									</tr>
									<?php } ?>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5 col-sm-12">
							
						</div>
						<div class="col-md-7 col-sm-12">
						
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php 
	if (count($estado_motor) > 0) {
	$registro = $estado_motor[count($estado_motor) - 1];

	$disponible_desmontaje_detencion = false;

	// DABA (4)		INSTALADO (2)	ACTIVO
	if($registro["EstadosMotores"]["estado_motor_id"] == 2 && $registro["EstadosMotores"]["estado_equipo_id"] == 4) {
		$disponible_desmontaje_detencion = true;
	}
	// DETENIDO (2)	INSTALADO (2)	ACTIVO
	if($registro["EstadosMotores"]["estado_motor_id"] == 2 && $registro["EstadosMotores"]["estado_equipo_id"] == 2) {
		$disponible_desmontaje_detencion = true;
	}
	// OPERATIVO(1)	INSTALADO (2)	ACTIVO
	if($registro["EstadosMotores"]["estado_motor_id"] == 2 && $registro["EstadosMotores"]["estado_equipo_id"] == 1) {
		$disponible_desmontaje_detencion = true;
	}
	if ($registro["EstadosMotores"]["estado"] == '2' || $registro["EstadosMotores"]["estado"] == '1') {
		$disponible_desmontaje_detencion = false;
	}
	
	if($disponible_desmontaje_detencion) {
		if($registro["EstadosMotores"]["fecha_ps"] != null && $registro["EstadosMotores"]["fecha_ps"] != '') {
			$registro["EstadosMotores"]["fecha_ps"] = date("Y-m-d", strtotime($registro["EstadosMotores"]["fecha_ps"]));
		}
		if(!is_numeric($registro["EstadosMotores"]["hr_motor_instalacion"])) {
			$registro["EstadosMotores"]["hr_motor_instalacion"] = '0';
		}
		
		$estado_motor_id = $registro["EstadosMotores"]["estado_motor_id"];
		$estado_equipo_id = $registro["EstadosMotores"]["estado_equipo_id"];
		
		if($estado_equipo_id==4){
			//unset($estados_equipos[2]);
			//unset($estados_equipos[1]);
		}
		
		if($estado_equipo_id==2){
			//unset($estados_equipos[1]);
		}
		
?>
<div class="row">
	<div class="col-md-12">
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Información del desmontaje</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" role="form">
					<input type="hidden" name="data[id]" value="<?php echo $registro["EstadosMotores"]["id"];?>" />
					<input type="hidden" name="data[estado]" value="2" class="form-desmontaje" />
					<input type="hidden" value="<?php echo $estado_equipo_id;?>" class="estado_equipo_id" />
					<input type="hidden" value="<?php echo $estado_motor_id;?>" class="estado_motor_id" />
					<div class="form-group">
						<div class="row">
							<label for="esn_placa" class="col-md-2 control-label">ESN [Placa]</label>
							<div class="col-md-4">
								<input name="data[esn_placa]" class="form-control" readonly="readonly" type="text" id="esn_placa" value="<?php echo $registro["EstadosMotores"]["esn_placa"];?>">
							</div>
						
							<label for="hr_operadas_motor" class="col-md-2 control-label">Horas operadas motor</label>
							<div class="col-md-4">
								<input name="data[hr_operadas_motor]" class="form-control" required="required" type="text" id="hr_operadas_motor" value="<?php echo $registro["EstadosMotores"]["hr_operadas_motor"];?>">
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_motor_instalacion" class="col-md-2 control-label">Horas motor instalación</label>
							<div class="col-md-4">
								<input name="data[hr_motor_instalacion]" class="form-control" readonly="readonly" type="text" id="hr_motor_instalacion" value="<?php echo $registro["EstadosMotores"]["hr_motor_instalacion"];?>">
							</div>
							<label for="fecha_falla" class="col-md-2 control-label">Fecha falla o detención</label>
							<div class="col-md-4">
								<input name="data[fecha_falla]" class="form-control" required="required" type="date" id="fecha_falla" max="<?php echo date("Y-m-d");?>" />
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="fecha_ps" class="col-md-2 control-label">Fecha puesta servicio</label>
							<div class="col-md-4">
								<input name="data[fecha_ps]" class="form-control" readonly="readonly" type="date" id="fecha_ps" value="<?php echo $registro["EstadosMotores"]["fecha_ps"];?>" max="<?php echo date("Y-m-d"); ?>">
							</div>
							<label for="estado_equipo_id" class="col-md-2 control-label">Estado equipo</label>
							<div class="col-md-4">
								<select class="form-control" name="data[estado_equipo_id]" required="required" id="estado_equipo_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($estados_equipos as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_acumuladas_motor" class="col-md-2 control-label">Horas acumuladas motor</label>
							<div class="col-md-4">
								<input name="data[hr_acumuladas_motor]" class="form-control" readonly="readonly" readonly="readonly" type="text" id="hr_acumuladas_motor" value="<?php echo $registro["EstadosMotores"]["hr_motor_instalacion"]; ?>">
							</div>
							<label for="estado_motor_id" class="col-md-2 control-label">Estado motor</label>
							<div class="col-md-4">
								<select class="form-control" name="data[estado_motor_id]" required="required" id="estado_motor_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($estados_motor as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="$hr_historico_motor" class="col-md-2 control-label">Horas históricas motor</label>
							<div class="col-md-4">
								<input type="hidden" id="hr_historico_motor_base" value="<?php echo $hr_historico_motor - $registro["EstadosMotores"]["hr_operadas_motor"]; ?>">
								<input name="data[hr_historico_motor]" class="form-control" readonly="readonly" readonly="readonly" type="text" id="hr_historico_motor" value="<?php echo $hr_historico_motor; ?>">
							</div>
							<label for="motivo_cambio" class="col-md-2 control-label">Motivo del cambio</label>
							<div class="col-md-4">
								<input name="data[motivo_cambio]" class="form-control" required="required" type="text" id="motivo_cambio">
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_historico_equipo" class="col-md-2 control-label">Horas históricas equipo</label>
							<div class="col-md-4">
								<input name="data[hr_historico_equipo]" class="form-control" readonly="readonly" type="text" id="hr_historico_equipo" value="<?php echo $hr_historico_equipo; ?>">
							</div>
							<label for="tipo_salida" class="col-md-2 control-label">Tipo salida</label>
							<div class="col-md-4">
								<select class="form-control" name="data[tipo_salida_id]" id="tipo_salida_id"> 
									<?php foreach($tipo_salidas as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="tipo_contrato_id" class="col-md-2 control-label">Contrato</label>
							<div class="col-md-4">
								<select class="form-control" id="tipo_contrato_id" disabled="disabled"> 
									<?php foreach($tipo_contratos as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $registro["EstadosMotores"]["tipo_contrato_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
							<label for="tipo_nuevo_contrato_id" class="col-md-2 control-label">Nuevo contrato</label>
							<div class="col-md-4">
								<select class="form-control" name="data[tipo_nuevo_contrato_id]" required="required" id="tipo_nuevo_contrato_id" disabled="disabled"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($tipo_contratos as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="termino_contrato" class="col-md-2 control-label">Termino contrato</label>
							<div class="col-md-4">
								<input name="data[termino_contrato]" class="form-control" type="checkbox" id="termino_contrato" value="1">
							</div>
							<label for="cambio_contrato" class="col-md-2 control-label">Cambio contrato</label>
							<div class="col-md-4">
								<input name="data[cambio_contrato]" class="form-control" type="checkbox" id="cambio_contrato">
							</div>
						</div>
					</div>	
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-actions right">
								<button type="submit" class="btn blue">
									<i class="fa fa-save"></i> Guardar</button>
								<button type="button" class="btn default" onclick="window.location='/Unidad/Desmontajes';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<?php } elseif($registro["EstadosMotores"]["estado"] == '2') { ?>
<div class="alert alert-danger">
	<strong>Alerta</strong> La unidad se encuentra en espera de aprobación de desmontaje.
</div>
<?php } elseif($registro["EstadosMotores"]["estado"] == '1') { ?>
<div class="alert alert-danger">
	<strong>Alerta</strong> La unidad se encuentra en espera de aprobación de montaje.
</div>
<?php } else { ?>
<div class="alert alert-danger">
	<strong>Alerta</strong> La unidad ya se encuentra desmontada.
</div>
<?php } ?>
<?php } ?>
<?php } ?>