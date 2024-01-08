<?php
	$hr_equipo_instalacion = 0;	
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
				<form action="/Unidad/Montajes" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Faena</label>
									<select class="form-control" name="faena_id" id="faena_id"> 
										<option value="">Seleccione una opción</option>
										<?php foreach($faenas as $key => $value) { ?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Flota</label>
									<select class="form-control" name="flota_id" id="flota_id" > 
										<option value="">Seleccione una opción</option>
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
										<option value="">Seleccione una opción</option>
										<?php
										foreach($unidades as $key => $value) { ?>
											<option value="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>_<?php echo $value["Unidad"]["id"];?>" faena_flota="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>"><?php echo $value["Unidad"]["unidad"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>ESN [Placa]</label>
									<input type="number" name="esn_placa" id="esn_placa" class="form-control" value="<?php echo $esn_placa;?>" />
								</div>
							</div>
							
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Unidad/Montajes';">Limpiar</button>
						<button type="submit" class="btn blue" name="btn-filtro">
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
										if(!is_numeric($unidad["EstadosMotores"]["hr_equipo"])) {
											$unidad["EstadosMotores"]["hr_equipo"] = $unidad["EstadosMotores"]["hr_equipo_instalacion"] + $unidad["EstadosMotores"]["hr_operadas_motor"];
										}
										$hr_equipo_instalacion = $unidad["EstadosMotores"]["hr_equipo"];
										if(!is_numeric($unidad["EstadosMotores"]["hr_operadas_motor"])) {
											$unidad["EstadosMotores"]["hr_operadas_motor"] = '0';
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_equipo"])) {
											$unidad["EstadosMotores"]["hr_equipo"] = $unidad["EstadosMotores"]["hr_equipo_instalacion"] + $unidad["EstadosMotores"]["hr_operadas_motor"];
										}
										?>
								
										<?php echo "<td>{$unidad["Faena"]["nombre"]}</td>"; ?> 
										<?php echo "<td>{$unidad["Flota"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Unidad"]["unidad"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["esn_placa"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadoEquipo"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadoMotor"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["fecha_ps"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["hr_operadas_motor"]}</td>"; ?>
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
<?php if($esn_placa != ''){ ?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Historial del ESN</span>
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
										<th>Estado motor</th>
										<th>PS</th>
										<th>HR operadas motor</th>
										<th>Fecha falla</th>
									</tr>
									<?php foreach ($estado_motor2 as $unidad) { 
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
										$hr_motor_instalacion += $unidad["EstadosMotores"]["hr_operadas_motor"] + $unidad["EstadosMotores"]["hr_motor_instalacion"];
										if(!is_numeric($unidad["EstadosMotores"]["hr_operadas_motor"])) {
											$unidad["EstadosMotores"]["hr_operadas_motor"] = '0';
										}
									?>
										<?php echo "<td>{$unidad["Faena"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Flota"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Unidad"]["unidad"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["esn_placa"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadoMotor"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["fecha_ps"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["hr_operadas_motor"]}</td>"; ?>
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
	$check_esn = true;
	if(count($estado_motor) > 0) {
		$registro = $estado_motor[count($estado_motor) - 1];
		if (count($estado_motor2) > 0) {
			$registro2 = $estado_motor2[count($estado_motor2) - 1];
			//$check_esn = $registro2["EstadosMotores"]["estado_motor_id"] != 2;
		}
	}
	
	$disponible_montaje_reactivacion = false;
	
	//print_r($registro["EstadosMotores"]["estado_equipo_id"]);
	//print_r($registro["EstadosMotores"]["estado_motor_id"]);
	//print_r($registro2["EstadosMotores"]["estado_equipo_id"]);
	//print_r($registro2["EstadosMotores"]["estado_motor_id"]);
	
	// ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			FINAL
	// DETENIDO (2)			INSTALADO (2)		ACTIVO			DETENIDO (2)		INSTALADO (2)		BLOQUEADO		REACTIVAR
	if($registro["EstadosMotores"]["estado_equipo_id"] == 2 && $registro["EstadosMotores"]["estado_motor_id"] == 2) {
		if($registro2["EstadosMotores"]["estado_equipo_id"] == 2 && $registro2["EstadosMotores"]["estado_motor_id"] == 2) {
			$disponible_montaje_reactivacion = true;
		}
	}
	
	// ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			FINAL
	// DABA (4)				INSTALADO (2)		ACTIVO			DABA (4)			INSTALADO (2)		BLOQUEADO		REACTIVAR
	if($registro["EstadosMotores"]["estado_equipo_id"] == 4 && $registro["EstadosMotores"]["estado_motor_id"] == 2) {
		if($registro2["EstadosMotores"]["estado_equipo_id"] == 4 && $registro2["EstadosMotores"]["estado_motor_id"] == 2) {
			$disponible_montaje_reactivacion = true;
		}
	}
	
	// ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			FINAL
	// OPERATIVO (1)		DESMONTADO (1)		ACTIVO			OPERATIVO (1)		DESMONTADO (1)		ACTIVO			MONTAR
	if($registro["EstadosMotores"]["estado_equipo_id"] == 1 && $registro["EstadosMotores"]["estado_motor_id"] == 1) {
		if($registro2["EstadosMotores"]["estado_equipo_id"] == 1 && $registro2["EstadosMotores"]["estado_motor_id"] == 1) {
			$disponible_montaje_reactivacion = true;
		}
	}
	
	// ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			FINAL
	// DETENIDO (2)			DESMONTADO (1)		ACTIVO			DETENIDO (2)		DESMONTADO (1)		ACTIVO			MONTAR-REACTIVAR
	if($registro["EstadosMotores"]["estado_equipo_id"] == 2 && $registro["EstadosMotores"]["estado_motor_id"] == 1) {
		if($registro2["EstadosMotores"]["estado_equipo_id"] == 2 && $registro2["EstadosMotores"]["estado_motor_id"] == 1) {
			$disponible_montaje_reactivacion = true;
		}
	}
	
	// ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			FINAL
	// DABA (4)				DESMONTADO (1)		ACTIVO			DABA (4)			DESMONTADO (1)		ACTIVO			MONTAR-REACTIVAR
	if($registro["EstadosMotores"]["estado_equipo_id"] == 4 && $registro["EstadosMotores"]["estado_motor_id"] == 1) {
		if($registro2["EstadosMotores"]["estado_equipo_id"] == 4 && $registro2["EstadosMotores"]["estado_motor_id"] == 1) {
			$disponible_montaje_reactivacion = true;
		}
	}
	
	// ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			FINAL
	// OPERATIVO (1)		DABA (3)			ACTIVO			OPERATIVO (1)		DABA (3)			ACTIVO			MONTAR
	if($registro["EstadosMotores"]["estado_equipo_id"] == 1 && $registro["EstadosMotores"]["estado_motor_id"] == 3) {
		if($registro2["EstadosMotores"]["estado_equipo_id"] == 1 && $registro2["EstadosMotores"]["estado_motor_id"] == 3) {
			$disponible_montaje_reactivacion = true;
		}
	}
	
	// ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			FINAL
	// DETENIDO (2)			DABA (3)			ACTIVO			DETENIDO (2)		DABA (3)			ACTIVO			MONTAR-REACTIVAR
	if($registro["EstadosMotores"]["estado_equipo_id"] == 2 && $registro["EstadosMotores"]["estado_motor_id"] == 3) {
		if($registro2["EstadosMotores"]["estado_equipo_id"] == 2 && $registro2["EstadosMotores"]["estado_motor_id"] == 3) {
			$disponible_montaje_reactivacion = true;
		}
	}
	
	// ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			ESTADO EQUIPO		ESTADO MOTOR		ACCIÓN			FINAL
	// DABA (4)				DABA (3)			ACTIVO			DABA (4)			DABA (3)			ACTIVO			MONTAR-REACTIVAR
	if($registro["EstadosMotores"]["estado_equipo_id"] == 4 && $registro["EstadosMotores"]["estado_motor_id"] == 3) {
		if($registro2["EstadosMotores"]["estado_equipo_id"] == 4 && $registro2["EstadosMotores"]["estado_motor_id"] == 3) {
			$disponible_montaje_reactivacion = true;
		}
	}
	
	
	if ($registro["EstadosMotores"]["estado"] == '2' || $registro["EstadosMotores"]["estado"] == '1') {
		//$disponible_montaje_reactivacion = false;
	}

	if (!$check_esn) {
		$disponible_montaje_reactivacion = false;
	}

	if (count($estado_motor) == 0 || count($estado_motor2) == 0) {
            $disponible_montaje_reactivacion = true;
            if ($registro["EstadosMotores"]["estado_motor_id"] == 2 || $registro2["EstadosMotores"]["estado_motor_id"] == 2) {
                $disponible_montaje_reactivacion = false;
            }
	}

	//var_dump($disponible_montaje_reactivacion);
	//if(count($estado_motor) == 0 || ($registro["EstadosMotores"]["estado_motor_id"] != 2 && $check_esn && $registro["EstadosMotores"]["estado"] != '2' && $registro["EstadosMotores"]["estado"] != '1')) {
	if ($disponible_montaje_reactivacion) {
		if(!isset($registro["EstadosMotores"]["tipo_contrato_id"])){
			$registro["EstadosMotores"]["tipo_contrato_id"] = -1;
		}
		
		unset($estados_equipos[2]);
		unset($estados_equipos[4]);
		unset($estados_motor[1]);
		unset($estados_motor[3]);
?>
<div class="row">
	<div class="col-md-12">
	
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Información de montaje</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" role="form">
					<input type="hidden" name="data[flota_id]" id="flota_id" value="<?php echo $flota_id; ?>" />
					<input type="hidden" name="data[unidad_id]" id="unidad_id" value="<?php echo $unidad_id; ?>" />
					<input type="hidden" name="data[estado]" value="1" class="form-montaje" />
					<input type="hidden" value="<?php echo @$registro["EstadosMotores"]["estado_equipo_id"];?>" class="estado_equipo_equipo_id" />
					<input type="hidden" value="<?php echo @$registro["EstadosMotores"]["estado_motor_id"];?>" class="estado_equipo_motor_id" />
					<input type="hidden" value="<?php echo @$registro2["EstadosMotores"]["estado_equipo_id"];?>" class="estado_esn_equipo_id" />
					<input type="hidden" value="<?php echo @$registro2["EstadosMotores"]["estado_motor_id"];?>" class="estado_esn_motor_id" />
					<div class="form-group">
						<div class="row">
							<label for="url" class="col-md-2 control-label">ESN [Placa]</label>
							<div class="col-md-4">
								<input name="data[esn_placa]" class="form-control" required="required" readonly="readonly" type="text" id="esn_placa"value="<?php echo $esn_placa; ?>" />
							</div>
						
							<label for="fecha_ps" class="col-md-2 control-label">Fecha puesta servicio</label>
							<div class="col-md-4">
								<input name="data[fecha_ps]" class="form-control" required="required" type="date" id="fecha_ps" max="<?php echo date("Y-m-d"); ?>">
							</div>
                                                      
                                                        
						</div>
					</div>	
                                        
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6"></div>
                                                <label for="axial" class="col-md-2 control-label">Medición axial (mm)</label>
                                                <div class="col-md-4">
                                                        <input name="data[medicion_axial]" class="form-control" required="required" type="text" id="medicion_axial" max="">
                                                </div>
                                            </div>
					</div>	
                                        
					<div class="form-group">
						<div class="row">
							<label for="url" class="col-md-2 control-label">Horas equipo instalación</label>
							<div class="col-md-4">
								<input name="data[hr_equipo_instalacion]" class="form-control" required="required" readonly="readonly" type="text" id="hr_equipo_instalacion" value="<?php echo $hr_equipo_instalacion; ?>">
							</div>
							<label for="url" class="col-md-2 control-label">Estado equipo</label>
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
							<label for="url" class="col-md-2 control-label">Horas motor instalación</label>
							<div class="col-md-4">
								<input name="data[hr_motor_instalacion]" class="form-control" required="required" type="text" id="hr_motor_instalacion" />
							</div>
							<label for="url" class="col-md-2 control-label">Estado motor</label>
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
							<label for="url" class="col-md-2 control-label">Estado motor en instalación</label>
							<div class="col-md-4">
								<select class="form-control" name="data[estado_motor_instalacion_id]" id="estado_motor_instalacion_id" required="required"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($estado_motor_instalacion as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $registro["EstadosMotores"]["estado_motor_instalacion_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
							<label for="url" class="col-md-2 control-label">Correlativo cambio</label>
							<div class="col-md-4">
								<input name="data[correlativo_montaje]" class="form-control" required="required" type="number" id="correlativo_montaje" />
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="url" class="col-md-2 control-label">Contrato</label>
							<div class="col-md-4">
								<?php if(count($estado_motor) == 0 && count($estado_motor2) == 0) { ?>
								<select class="form-control" name="data[tipo_contrato_id]" id="tipo_contrato_id"> 
									<?php foreach($tipo_contratos as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $registro["EstadosMotores"]["tipo_contrato_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
								<?php } else { ?>
								<input name="data[tipo_contrato_id]" class="form-control" type="hidden" value="<?php echo $registro["EstadosMotores"]["tipo_contrato_id"];?>">
								<select class="form-control" id="tipo_contrato_id" disabled="disabled"> 
									<?php foreach($tipo_contratos as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $registro["EstadosMotores"]["tipo_contrato_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
								<?php } ?>
							</div>
							<label for="url" class="col-md-2 control-label">Nuevo contrato</label>
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
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="url" class="col-md-2 control-label">Termino contrato</label>
							<div class="col-md-4">
								<input name="data[termino_contrato]" class="form-control" type="checkbox" id="termino_contrato" value="1">
							</div>
							<label for="url" class="col-md-2 control-label">Cambio contrato</label>
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
								<button type="button" class="btn default" onclick="window.location='/Unidad/Montajes';">Cancelar</button>
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
<?php } elseif($registro["EstadosMotores"]["estado_motor_id"] == 2 ) { ?>
<div class="alert alert-danger">
	<strong>Alerta</strong> La unidad ya tiene un motor instalado, para montar el nuevo motor debe realizar el desmontaje del actual motor. 
</div>
<?php } else { ?>
<div class="alert alert-danger">
	<strong>Alerta</strong> La unidad ya se encuentra montada.
</div>
<?php } ?>
<?php } ?>
<?php } ?>
