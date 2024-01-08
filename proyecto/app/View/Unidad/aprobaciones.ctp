<?php 
$hr_historico_motor = 0;
if(count($pendientes)) { ?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Registros pendientes</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="/Unidad/Aprobaciones" class="horizontal-form" method="post">
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th>Tipo</th>
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
										<th style="width: 45px;"></th>
									</tr>
									<?php foreach ($pendientes as $unidad) { 
										if($unidad["EstadosMotores"]["fecha_ps"] != null && $unidad["EstadosMotores"]["fecha_ps"] != '') {
											$unidad["EstadosMotores"]["fecha_ps"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_ps"]));
										}
										if($unidad["EstadosMotores"]["fecha_falla"] != null && $unidad["EstadosMotores"]["fecha_falla"] != '') {
											$unidad["EstadosMotores"]["fecha_falla"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_falla"]));
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_operadas_motor"])) {
											$unidad["EstadosMotores"]["hr_operadas_motor"] = '0';
										} 
										if(!is_numeric($unidad["EstadosMotores"]["hr_equipo_instalacion"])) {
											$unidad["EstadosMotores"]["hr_equipo_instalacion"] = '0';
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_equipo"])) {
											$unidad["EstadosMotores"]["hr_equipo"] = $unidad["EstadosMotores"]["hr_equipo_instalacion"] + $unidad["EstadosMotores"]["hr_operadas_motor"];
										}
									?>
									<tr>
										<?php if($unidad["EstadosMotores"]["estado"] == 1) {?>
										<?php echo "<td>Montaje</td>"; ?>
										<?php } ?>
										<?php if($unidad["EstadosMotores"]["estado"] == 2) {?>
										<?php echo "<td>Desmontaje</td>"; ?>
										<?php } ?>
					
										
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
										<?php
											$url = "/Unidad/Aprobaciones?faena_id={$unidad["EstadosMotores"]["faena_id"]}&flota_id={$unidad["EstadosMotores"]["faena_id"]}"."_"."{$unidad["EstadosMotores"]["flota_id"]}&unidad_id={$unidad["EstadosMotores"]["faena_id"]}"."_"."{$unidad["EstadosMotores"]["flota_id"]}"."_"."{$unidad["EstadosMotores"]["unidad_id"]}&esn_placa={$unidad["EstadosMotores"]["esn_placa"]}&id={$unidad["EstadosMotores"]["id"]}";
											echo "<td><a href=\"$url\" class=\"btn btn-sm btn-outline blue tooltips\" data-container=\"body\" data-placement=\"top\" data-original-title=\"Ver detalle\"><i class=\"fa fa-search\"></i> </a></td>";
										?>
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
<?php } ?>
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
		<form action="/Unidad/Aprobaciones" class="horizontal-form" method="post">
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
										<th>ESN</th>
										<th>ESN [Placa]</th>
										<th>Estado equipo</th>
										<th>Estado motor</th>
										<th>PS</th>
										<th>HR operadas motor</th>
										<th>HR equipo</th>
										<th>Fecha falla</th>
									</tr>
									<?php foreach ($estado_motor as $unidad) {
										if($unidad["EstadosMotores"]["fecha_ps"] != null && $unidad["EstadosMotores"]["fecha_ps"] != '') {
											$unidad["EstadosMotores"]["fecha_ps"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_ps"]));
										}
										if($unidad["EstadosMotores"]["fecha_falla"] != null && $unidad["EstadosMotores"]["fecha_falla"] != '') {
											$unidad["EstadosMotores"]["fecha_falla"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_falla"]));
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_operadas_motor"])) {
											$unidad["EstadosMotores"]["hr_operadas_motor"] = '0';
										} 
										if(!is_numeric($unidad["EstadosMotores"]["hr_equipo_instalacion"])) {
											$unidad["EstadosMotores"]["hr_equipo_instalacion"] = '0';
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_equipo"])) {
											$unidad["EstadosMotores"]["hr_equipo"] = $unidad["EstadosMotores"]["hr_equipo_instalacion"] + $unidad["EstadosMotores"]["hr_operadas_motor"];
										}
										if($unidad["EstadosMotores"]["esn"] == '')
										{
											$unidad["EstadosMotores"]["esn"] = $unidad["EstadosMotores"]["esn_placa"];
										}
									?>
									<tr>
										<?php echo "<td>{$unidad["Faena"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Flota"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Unidad"]["unidad"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["esn"]}</td>"; ?>
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
		<form action="/Unidad/Aprobaciones" class="horizontal-form" method="post">
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
										<th>ESN</th>
										<th>ESN [Placa]</th>
										<th>Estado motor</th>
										<th>PS</th>
										<th>HR operadas motor</th>
										<th>Fecha falla</th>
									</tr>
									<?php foreach ($estado_motor2 as $unidad) {
										if($unidad["EstadosMotores"]["fecha_ps"] != null && $unidad["EstadosMotores"]["fecha_ps"] != '') {
											$unidad["EstadosMotores"]["fecha_ps"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_ps"]));
										}
										if($unidad["EstadosMotores"]["fecha_falla"] != null && $unidad["EstadosMotores"]["fecha_falla"] != '') {
											$unidad["EstadosMotores"]["fecha_falla"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_falla"]));
										}
										if($unidad["EstadosMotores"]["esn"] == '')
										{
											$unidad["EstadosMotores"]["esn"] = $unidad["EstadosMotores"]["esn_placa"];
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_operadas_motor"])) {
											$unidad["EstadosMotores"]["hr_operadas_motor"] = '0';
										} 
										$hr_historico_motor += $unidad["EstadosMotores"]["hr_operadas_motor"];
										?>
									<tr>
										<?php echo "<td>{$unidad["Faena"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Flota"]["nombre"]}</td>"; ?>
										<?php echo "<td>{$unidad["Unidad"]["unidad"]}</td>"; ?>
										<?php echo "<td>{$unidad["EstadosMotores"]["esn"]}</td>"; ?>
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
<?php if (isset($data["EstadosMotores"]["id"])) {
	if($data["EstadosMotores"]["fecha_ps"] != null && $data["EstadosMotores"]["fecha_ps"] != '') {
		$data["EstadosMotores"]["fecha_ps"] = date("Y-m-d", strtotime($data["EstadosMotores"]["fecha_ps"]));
	}
	if($data["EstadosMotores"]["fecha_falla"] != null && $data["EstadosMotores"]["fecha_falla"] != '') {
		$data["EstadosMotores"]["fecha_falla"] = date("Y-m-d", strtotime($data["EstadosMotores"]["fecha_falla"]));
	}
	if($data["EstadosMotores"]["esn"] == '') {
		$data["EstadosMotores"]["esn"] = $data["EstadosMotores"]["esn_placa"];
	}
	if(!is_numeric($data["EstadosMotores"]["hr_operadas_motor"])) {
		$data["EstadosMotores"]["hr_operadas_motor"] = '0';
	} 
	if(!is_numeric($data["EstadosMotores"]["hr_historico_motor"])) {
		$data["EstadosMotores"]["hr_historico_motor"] = $data["EstadosMotores"]["hr_operadas_motor"] + $data["EstadosMotores"]["hr_motor_instalacion"];
	}
	
	if($data["EstadosMotores"]["estado"] == 1) {
		unset($estados_equipos[2]);
		unset($estados_equipos[4]);
		unset($estados_motor[1]);
		unset($estados_motor[3]);
	}
?>
<div class="row">
	<div class="col-md-12">
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Datos complementarios</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/Unidad/Aprobaciones" class="horizontal-form" method="post" role="form">
					<input type="hidden" name="data[id]" value="<?php echo $data["EstadosMotores"]["id"];?>" />
					<input type="hidden" name="data[unidad_id]" value="<?php echo $data["EstadosMotores"]["unidad_id"];?>" />
					<?php if(@$data["EstadosMotores"]["fecha_falla"] != null || @$data["EstadosMotores"]["fecha_falla"] != '') {?>
						<input type="hidden" name="data[aprobador_desmontaje_id]" value="<?php echo $this->Session->read('usuario_id');?>" />
					<?php } else { ?>
						<input type="hidden" name="data[aprobador_montaje_id]" value="<?php echo $this->Session->read('usuario_id');?>" />
					<?php } ?>					
					<div class="form-group">
						<div class="row">
							<label for="esn" class="col-md-2 control-label">ESN</label>
							<div class="col-md-4">
								<input name="data[esn]" class="form-control" required="required" type="text" id="esn" value="<?php echo $data["EstadosMotores"]["esn"];?>">
							</div>
							<label for="hr_historico_equipo" class="col-md-2 control-label">Horas históricas equipo</label>
							<div class="col-md-4">
								<input name="data[hr_historico_equipo]" class="form-control" required="required" type="text" id="hr_historico_equipo" value="<?php echo $data["EstadosMotores"]["hr_equipo_instalacion"] +$data["EstadosMotores"]["hr_operadas_motor"];?>">
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="esn_placa" class="col-md-2 control-label">ESN [Placa]</label>
							<div class="col-md-4">
								<input name="data[esn_placa]" class="form-control" required="required" type="text" id="esn_placa" value="<?php echo $data["EstadosMotores"]["esn_placa"];?>">
							</div>
							<?php if(@$data["EstadosMotores"]["fecha_falla"] != null || @$data["EstadosMotores"]["fecha_falla"] != '') {?>
							<label for="fecha_falla" class="col-md-2 control-label">Fecha falla o detención</label>
							<div class="col-md-4">
								<?php if($unidad["EstadosMotores"]["estado"] == 1) {?>
								<input name="data[fecha_falla]" class="form-control" disabled="disabled" type="date" id="fecha_falla" value="<?php echo $data["EstadosMotores"]["fecha_falla"];?>" max="<?php echo date("Y-m-d"); ?>">
								<?php } else { ?>
								<input name="data[fecha_falla]" class="form-control" required="required" type="date" id="fecha_falla" value="<?php echo $data["EstadosMotores"]["fecha_falla"];?>" max="<?php echo date("Y-m-d"); ?>">
								<?php } ?>
							</div>
							<?php } ?>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_equipo_instalacion" class="col-md-2 control-label">Horas equipo instalación</label>
							<div class="col-md-4">
								<input name="data[hr_equipo_instalacion]" class="form-control" required="required" type="text" id="hr_equipo_instalacion" value="<?php echo $data["EstadosMotores"]["hr_equipo_instalacion"];?>">
							</div>
							<?php if(@$data["EstadosMotores"]["fecha_falla"] != null || @$data["EstadosMotores"]["fecha_falla"] != '') {?>
							<label for="motivo_cambio" class="col-md-2 control-label">Motivo del cambio</label>
							<div class="col-md-4">
								<input name="data[motivo_cambio]" class="form-control" required="required" type="text" id="motivo_cambio" value="<?php echo $data["EstadosMotores"]["motivo_cambio"];?>">
							</div>
							<?php } ?>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_motor_instalacion" class="col-md-2 control-label">Horas motor instalación</label>
							<div class="col-md-4">
								<input name="data[hr_motor_instalacion]" class="form-control" required="required" type="text" id="hr_motor_instalacion" value="<?php echo $data["EstadosMotores"]["hr_motor_instalacion"];?>">
							</div>
							<?php if(@$data["EstadosMotores"]["fecha_falla"] != null || @$data["EstadosMotores"]["fecha_falla"] != '') {?>
							<label for="tipo_salida" class="col-md-2 control-label">Tipo salida</label>
							<div class="col-md-4">
								<select class="form-control" name="data[tipo_salida_id]" id="tipo_salida_id"> 
									<?php foreach($tipo_salidas as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $data["EstadosMotores"]["tipo_salida_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
							<?php } ?>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="estado_motor_instalacion_id" class="col-md-2 control-label">Estado motor en instalación</label>
							<div class="col-md-4">
								<select class="form-control" name="data[estado_motor_instalacion_id]" id="estado_motor_instalacion_id" required="required"> 	
									<?php foreach($estado_motor_instalacion as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $data["EstadosMotores"]["estado_motor_instalacion_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
							<label for="tipo_contrato_id" class="col-md-2 control-label">Contrato</label>
							<div class="col-md-4">
								<select class="form-control" name="data[tipo_contrato_id]" id="tipo_contrato_id"> 
									<?php foreach($tipo_contratos as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $data["EstadosMotores"]["tipo_contrato_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="fecha_ps" class="col-md-2 control-label">Fecha puesta en servicio</label>
							<div class="col-md-4">
								<input name="data[fecha_ps]" class="form-control" required="required" type="date" id="fecha_ps" value="<?php echo $data["EstadosMotores"]["fecha_ps"];?>" max="<?php echo date("Y-m-d"); ?>">
							</div>
							<label for="estado_equipo_id" class="col-md-2 control-label">Estado equipo</label>
							<div class="col-md-4">
								<select class="form-control" name="data[estado_equipo_id]" required="required" id="estado_equipo_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($estados_equipos as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $data["EstadosMotores"]["estado_equipo_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_operadas_motor" class="col-md-2 control-label">Horas operadas motor</label>
							<div class="col-md-4">
								<?php if(@$data["EstadosMotores"]["fecha_falla"] != null || @$data["EstadosMotores"]["fecha_falla"] != '') { ?>
									<input name="data[hr_operadas_motor]" class="form-control" required="required" type="text" id="hr_operadas_motor" value="<?php echo $data["EstadosMotores"]["hr_operadas_motor"];?>" />
								<?php } else { ?>
									<input name="data[hr_operadas_motor]" class="form-control" readonly="readonly" type="text" id="hr_operadas_motor" value="0" />
								<?php } ?>
							</div>
							<label for="estado_motor_id" class="col-md-2 control-label">Estado motor</label>
							<div class="col-md-4">
								<select class="form-control" name="data[estado_motor_id]" required="required" id="estado_motor_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($estados_motor as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $data["EstadosMotores"]["estado_motor_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_acumuladas_motor" class="col-md-2 control-label">Horas acumuladas motor</label>
							<div class="col-md-4">
								<input name="data[hr_acumuladas_motor]" class="form-control" required="required" readonly="readonly" type="text" id="hr_acumuladas_motor" value="<?php echo $data["EstadosMotores"]["hr_operadas_motor"] + $data["EstadosMotores"]["hr_motor_instalacion"];?>">
							</div>
							<?php if(@$data["EstadosMotores"]["fecha_falla"] == null || @$data["EstadosMotores"]["fecha_falla"] == '') {?>
							<label for="intervencion_id" class="col-md-2 control-label">Correlativo cambio</label>
							<div class="col-md-4">
								<input name="data[correlativo_montaje]" class="form-control" required="required" type="text" id="correlativo_montaje" value="<?php echo $data["EstadosMotores"]["correlativo_montaje"];?>">
							</div>
							<?php } ?>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_historico_motor" class="col-md-2 control-label">Horas históricas motor</label>
							<div class="col-md-4">
								<input type="hidden" id="hr_historico_motor_base" value="<?php echo $hr_historico_motor - $data["EstadosMotores"]["hr_operadas_motor"]; ?>">
								<input name="data[hr_historico_motor]" class="form-control" required="required" readonly="readonly" type="text" id="hr_historico_motor" value="<?php echo $hr_historico_motor;?>">
							</div>
						</div>
					</div>	
					
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-actions right">
								<button type="submit" class="btn blue">
									<i class="fa fa-save"></i> Aprobar</button>
								<button type="button" class="btn default" onclick="window.location='/Unidad/Aprobaciones';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<?php } ?>
<?php } ?>
<?php } ?>