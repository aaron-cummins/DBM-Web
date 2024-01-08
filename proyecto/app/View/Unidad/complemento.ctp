<?php
	$hr_historico_equipo = 0;	
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
				<form action="/Unidad/Complemento" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label>Faena</label>
									<select class="form-control" name="faena_id" id="faena_id" required="required"> 
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
									<select class="form-control" name="flota_id" id="flota_id" required="required"> 
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
										<select class="form-control" name="unidad_id" id="unidad_id" required="required"> 
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
									<input type="number" placeholder="Ingrese ESN [Placa]" name="esn_placa" id="esn_placa" class="form-control" value="<?php echo $esn_placa;?>" required="required" />
								</div>
							</div>
							
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Unidad/Complemento';">Limpiar</button>
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
		<form action="/Unidad/Complemento" class="horizontal-form" method="post">
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
										<th style="width: 45px;"></th>
									</tr>
									<?php foreach ($estado_motor as $unidad) { 
										if($unidad["EstadosMotores"]["fecha_ps"] != null && $unidad["EstadosMotores"]["fecha_ps"] != '') {
											$unidad["EstadosMotores"]["fecha_ps"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_ps"]));
										}
										if($unidad["EstadosMotores"]["fecha_falla"] != null && $unidad["EstadosMotores"]["fecha_falla"] != '') {
											$unidad["EstadosMotores"]["fecha_falla"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_falla"]));
										}
										if(!is_numeric($unidad["EstadosMotores"]["hr_equipo"])) {
											$unidad["EstadosMotores"]["hr_equipo"] = $unidad["EstadosMotores"]["hr_equipo_instalacion"] + $unidad["EstadosMotores"]["hr_operadas_motor"];
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
										<?php
											$url = "/Unidad/Complemento?faena_id=$faena_id&flota_id=$faena_id"."_"."$flota_id&unidad_id=$faena_id"."_"."$flota_id"."_"."$unidad_id&esn_placa=$esn_placa&id={$unidad["EstadosMotores"]["id"]}";
											echo "<td><a href=\"$url\" class=\"btn btn-sm btn-outline blue tooltips\" data-container=\"body\" data-placement=\"top\" data-original-title=\"Ver datos complementarios\"><i class=\"fa fa-search\"></i> </a></td>";
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
		<form action="/Unidad/Complemento" class="horizontal-form" method="post">
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
										<th style="width: 45px;"></th>
									</tr>
									<?php foreach ($estado_motor2 as $unidad) { 
										if($unidad["EstadosMotores"]["fecha_ps"] != null && $unidad["EstadosMotores"]["fecha_ps"] != '') {
											$unidad["EstadosMotores"]["fecha_ps"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_ps"]));
										}
										if($unidad["EstadosMotores"]["fecha_falla"] != null && $unidad["EstadosMotores"]["fecha_falla"] != '') {
											$unidad["EstadosMotores"]["fecha_falla"] = date("d-m-Y", strtotime($unidad["EstadosMotores"]["fecha_falla"]));
										}
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
										<?php
											$url = "/Unidad/Complemento?faena_id=$faena_id&flota_id=$faena_id"."_"."$flota_id&unidad_id=$faena_id"."_"."$flota_id"."_"."$unidad_id&esn_placa=$esn_placa&id={$unidad["EstadosMotores"]["id"]}";
											echo "<td><a href=\"$url\" class=\"btn btn-sm btn-outline blue tooltips\" data-container=\"body\" data-placement=\"top\" data-original-title=\"Ver datos complementarios\"><i class=\"fa fa-search\"></i> </a></td>";
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
<?php if (isset($data["EstadosMotores"]["id"])) { 
	if($data["EstadosMotores"]["fecha_ps"] != null && $data["EstadosMotores"]["fecha_ps"] != '') {
		$data["EstadosMotores"]["fecha_ps"] = date("Y-m-d", strtotime($data["EstadosMotores"]["fecha_ps"]));
	}
	if($data["EstadosMotores"]["fecha_falla"] != null && $data["EstadosMotores"]["fecha_falla"] != '') {
		$data["EstadosMotores"]["fecha_falla"] = date("Y-m-d", strtotime($data["EstadosMotores"]["fecha_falla"]));
	}
	if($data["EstadosMotores"]["fecha_llegada_taller"] != null && $data["EstadosMotores"]["fecha_llegada_taller"] != '') {
		$data["EstadosMotores"]["fecha_llegada_taller"] = date("Y-m-d", strtotime($data["EstadosMotores"]["fecha_llegada_taller"]));
	}
	
	$hr_historico_equipo = $data["EstadosMotores"]["hr_equipo_instalacion"] + $data["EstadosMotores"]["hr_operadas_motor"];
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
				<form action="/Unidad/Complemento" class="horizontal-form" method="post" role="form">
					<input type="hidden" name="data[id]" value="<?php echo $data["EstadosMotores"]["id"];?>" />
					<div class="form-group">
						<div class="row">
							<label for="tsr" class="col-md-2 control-label">TSR</label>
							<div class="col-md-4">
								<input name="data[tsr]" class="form-control" required="required" type="text" id="tsr" value="<?php echo $data["EstadosMotores"]["tsr"];?>">
							</div>
							<label for="estado_final" class="col-md-2 control-label">Estado final</label>
							<div class="col-md-4">
								<input name="data[estado_final]" class="form-control" required="required" type="text" id="estado_final" value="<?php echo $data["EstadosMotores"]["estado_final"];?>">
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="causa_raiz" class="col-md-2 control-label">Causa raíz</label>
							<div class="col-md-4">
								<input name="data[causa_raiz]" class="form-control" required="required" type="text" id="causa_raiz" value="<?php echo $data["EstadosMotores"]["causa_raiz"];?>">
							</div>
							<label for="costo" class="col-md-2 control-label">Costo</label>
							<div class="col-md-4">
								<input name="data[costo]" class="form-control" required="required" type="number" id="costo" value="<?php echo $data["EstadosMotores"]["costo"];?>">
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="responsable_id" class="col-md-2 control-label">Responsable</label>
							<div class="col-md-4">
								<select class="form-control" name="data[responsable_id]" id="responsable_id"> 
									<?php foreach($responsables as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $key == $data["EstadosMotores"]["responsable_id"] ? " selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						
						<label for="ot" class="col-md-2 control-label">OT</label>
							<div class="col-md-4">
								<input name="data[ot]" class="form-control" required="required" type="text" id="ot" value="<?php echo $data["EstadosMotores"]["ot"];?>">
							</div>
							</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="fecha_llegada_taller" class="col-md-2 control-label">Fecha llegada taller</label>
							<div class="col-md-4">
								<input name="data[fecha_llegada_taller]" class="form-control" required="required" type="date" id="fecha_llegada_taller" value="<?php echo $data["EstadosMotores"]["fecha_llegada_taller"];?>" max="<?php echo date("Y-m-d"); ?>" />
							</div>
						
						<label for="lugar_reparacion" class="col-md-2 control-label">Lugar reparación</label>
							<div class="col-md-4">
								<input name="data[lugar_reparacion]" class="form-control" required="required" type="text" id="lugar_reparacion" value="<?php echo $data["EstadosMotores"]["lugar_reparacion"];?>">
							</div>
							</div>
					</div>	
					<h3 class="form-section"></h3>
					<div class="form-group">
						<div class="row">
							<label for="esn" class="col-md-2 control-label">ESN</label>
							<div class="col-md-4">
								<input name="data[esn]" class="form-control" required="required" type="text" id="esn" value="<?php echo $data["EstadosMotores"]["esn"];?>">
							</div>
						
							<label for="hr_historico_equipo" class="col-md-2 control-label">Horas históricas equipo</label>
							<div class="col-md-4">
								<input name="data[hr_historico_equipo]" class="form-control" required="required" type="text" id="hr_historico_equipo" value="<?php echo $hr_historico_equipo; ?>">
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="esn_placa" class="col-md-2 control-label">ESN [Placa]</label>
							<div class="col-md-4">
								<input name="data[esn_placa]" class="form-control" required="required" type="text" id="esn_placa" value="<?php echo $data["EstadosMotores"]["esn_placa"];?>">
							</div>
							<?php if(@$data["EstadosMotores"]["fecha_falla"] != '' && @$data["EstadosMotores"]["fecha_falla"] != null) {?>
							<label for="fecha_falla" class="col-md-2 control-label">Fecha falla o detención</label>
							<div class="col-md-4">
								<input name="data[fecha_falla]" class="form-control" required="required" type="date" id="fecha_falla" value="<?php echo $data["EstadosMotores"]["fecha_falla"];?>" max="<?php echo date("Y-m-d"); ?>" />
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
							<?php if(@$data["EstadosMotores"]["fecha_falla"] != '' && @$data["EstadosMotores"]["fecha_falla"] != null) {?>
							<label for="motivo_cambio" class="col-md-2 control-label">Motivo del cambio</label>
							<div class="col-md-4">
								<input name="data[motivo_cambio]" class="form-control" type="text" id="motivo_cambio" value="<?php echo $data["EstadosMotores"]["motivo_cambio"];?>">
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
							<?php if(@$data["EstadosMotores"]["fecha_falla"] != '' && @$data["EstadosMotores"]["fecha_falla"] != null) {?>
							<label for="tipo_salida_id" class="col-md-2 control-label">Tipo salida</label>
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
									<option value="">Seleccione una opción</option>
									<option value="1" <?php echo "1" == $data["EstadosMotores"]["estado_motor_instalacion_id"] ? " selected=\"selected\"" : ""; ?>>Motor nuevo</option>
									<option value="2" <?php echo "2" == $data["EstadosMotores"]["estado_motor_instalacion_id"] ? " selected=\"selected\"" : ""; ?>>Motor original</option>
									<option value="3" <?php echo "3" == $data["EstadosMotores"]["estado_motor_instalacion_id"] ? " selected=\"selected\"" : ""; ?>>Overhaul</option>
									<option value="4" <?php echo "4" == $data["EstadosMotores"]["estado_motor_instalacion_id"] ? " selected=\"selected\"" : ""; ?>>Reparación parcial</option>
									<option value="5" <?php echo "5" == $data["EstadosMotores"]["estado_motor_instalacion_id"] ? " selected=\"selected\"" : ""; ?>>Sin información</option>
									<option value="6" <?php echo "6" == $data["EstadosMotores"]["estado_motor_instalacion_id"] ? " selected=\"selected\"" : ""; ?>>Sin modificación</option>
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
								<input name="data[fecha_ps]" class="form-control" required="required" type="date" id="fecha_ps" value="<?php echo $data["EstadosMotores"]["fecha_ps"];?>" max="<?php echo date("Y-m-d"); ?>" />
							</div>
							<label for="estado_equipo_id" class="col-md-2 control-label">Estado equipo</label>
							<div class="col-md-4">
								<select class="form-control" id="estado_equipo_id" disabled="disabled"> 
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
							<label for="hr_operadas_motor" class="col-md-2 control-label">Horas operadas</label>
							<div class="col-md-4">
								<?php if(@$data["EstadosMotores"]["fecha_falla"] != null || @$data["EstadosMotores"]["fecha_falla"] != '') { ?>
									<input name="data[hr_operadas_motor]" class="form-control" required="required" type="text" id="hr_operadas_motor" value="<?php echo $data["EstadosMotores"]["hr_operadas_motor"];?>" />
								<?php } else { ?>
									<input name="data[hr_operadas_motor]" class="form-control" readonly="readonly" type="text" id="hr_operadas_motor" value="0" />
								<?php } ?>
							</div>
							<label for="estado_motor_id" class="col-md-2 control-label">Estado motor</label>
							<div class="col-md-4">
								<select class="form-control" id="estado_motor_id" disabled="disabled"> 
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
								<input name="data[hr_acumuladas_motor]" class="form-control" required="required" readonly="readonly" type="text" id="hr_acumuladas_motor" value="<?php echo $data["EstadosMotores"]["hr_acumuladas_motor"];?>" />
							</div>
							<?php if(@$data["EstadosMotores"]["fecha_falla"] != '' && @$data["EstadosMotores"]["fecha_falla"] != NULL) { ?>
							<?php } else { ?>
							
							<label for="intervencion_id" class="col-md-2 control-label">Correlativo cambio</label>
							<div class="col-md-4">
								<input name="data[correlativo_montaje]" class="form-control" required="required" type="text" id="correlativo_montaje" value="<?php echo $data["EstadosMotores"]["correlativo_montaje"];?>" />
							</div>
							
							<?php } ?>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="hr_historico_motor" class="col-md-2 control-label">Horas históricas motor</label>
							<div class="col-md-4">
								<input type="hidden" id="hr_historico_motor_base" value="<?php echo $hr_historico_motor  - $data["EstadosMotores"]["hr_operadas_motor"]; ?>">
								<input name="data[hr_historico_motor]" class="form-control" readonly="readonly" required="required" type="text" id="hr_historico_motor" value="<?php echo $data["EstadosMotores"]["hr_historico_motor"];?>">
							</div>
						</div>
					</div>	
					
					
					<div class="row">
						<div class="col-md-12">
							<div class="form-actions right">
								<button type="submit" class="btn blue">
									<i class="fa fa-save"></i> Guardar</button>
								<button type="button" class="btn default" onclick="window.location='/Unidad/Complemento';">Cancelar</button>
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