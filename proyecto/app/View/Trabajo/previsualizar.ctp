<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-dark"></i>
					<span class="caption-subject font-dark bold uppercase">Detalle</span>
					<span class="caption-helper">Trabajo</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Correlativo</label>
									<input name="correlativo" class="form-control" type="number" id="correlativo" readonly="readonly" value="<?php echo $registro["correlativo_final"];?>" />
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label>Folio</label>
									<input name="folio" class="form-control" type="number" id="folio" readonly="readonly" value="<?php echo $registro["id"];?>" />
								</div>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-dark"></i>
					<span class="caption-subject font-dark bold uppercase">Técnicos</span>
					<span class="caption-helper">Participantes</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<?php foreach($tecnicos as $key => $value) { ?>
							<div class="col-md-12">
								<div class="form-group">
									<label>Técnico <?php echo $tipos[$key]; ?></label>
									<input name="correlativo" class="form-control" type="text" id="correlativo" readonly="readonly" value="<?php echo $value;?>" />
								</div>
							</div>
							<?php } ?>
							<div class="col-md-6">
								<div class="form-group">
									<label>Supervisor responsable</label>
									<input name="correlativo" class="form-control" type="text" id="correlativo" readonly="readonly" value="<?php echo $value;?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Supervisor aprobardor</label>
									<input name="correlativo" class="form-control" type="text" id="correlativo" readonly="readonly" value="<?php echo $value;?>" />
								</div>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-dark"></i>
					<span class="caption-subject font-dark bold uppercase">Información</span>
					<span class="caption-helper">Base</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Tipo intervención</label>
									<input name="correlativo" class="form-control" type="text" id="correlativo" readonly="readonly" value="<?php echo $registro["tipointervencion"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Faena</label>
									<input name="correlativo" class="form-control" type="text" id="faena_id" readonly="readonly" value="<?php echo $registro["faena_id"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Flota</label>
									<input name="correlativo" class="form-control" type="text" id="flota_id" readonly="readonly" value="<?php echo $registro["flota_id"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Equipo</label>
									<input name="correlativo" class="form-control" type="text" id="equipo_id" readonly="readonly" value="<?php echo $registro["unidad_id"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>ESN</label>
									<input name="correlativo" class="form-control" type="text" id="equipo_id" readonly="readonly" value="<?php echo $registro["esn"];?>" />
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Motivo llamado</label>
									<input name="correlativo" class="form-control" type="text" id="equipo_id" readonly="readonly" value="<?php echo $registro["unidad_id"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Categoría</label>
									<input name="correlativo" class="form-control" type="text" id="equipo_id" readonly="readonly" value="<?php echo $registro["unidad_id"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Síntoma</label>
									<input name="correlativo" class="form-control" type="text" id="equipo_id" readonly="readonly" value="<?php echo $registro["unidad_id"];?>" />
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Lugar reparación</label>
									<input name="correlativo" class="form-control" type="text" id="equipo_id" readonly="readonly" value="<?php echo $registro["unidad_id"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Turno</label>
									<input name="correlativo" class="form-control" type="text" id="equipo_id" readonly="readonly" value="<?php echo $registro["turno"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Período</label>
									<input name="correlativo" class="form-control" type="text" id="equipo_id" readonly="readonly" value="<?php echo $registro["periodo"];?>" />
								</div>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-dark"></i>
					<span class="caption-subject font-dark bold uppercase">Información</span>
					<span class="caption-helper">Flujo</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Cambio módulo</label>
									<input name="correlativo" class="form-control" type="text" id="correlativo" readonly="readonly" value="<?php echo "";?>" />
								</div>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<?php if (count($elementos) > 0) { ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-dark"></i>
					<span class="caption-subject font-dark bold uppercase">Elementos</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<tr>
											<th>#</th>
											<th>Sistema</th>
											<th>Subsistema</th>
											<th>Posición</th>
											<th>ID</th>
											<th>Elemento</th>
											<th>Posición</th>
											<th>Diagnóstico</th>
											<th>Solución</th>
											<th>Tipo</th>
											<th>NP sale</th>
											<th>NP entra</th>
											<th>Duración</th>
											<th>Imagen</th>
										</tr>
										<?php
											$cantidad_elementos = count($elementos);
											$total_tiempo = 0;
											for ($i = 0; $i < $cantidad_elementos; $i++) {
												$elemento = $elementos[$i]["IntervencionElementos"];
										  ?>
										<tr>
											<td><?php echo $i+1;?></td>
											<td><?php echo $elementos[$i]["Sistema"]["nombre"];?></td>
											<td><?php echo $elementos[$i]["Subsistema"]["nombre"];?></td>
											<td><?php echo $elementos[$i]["Posiciones_Subsistema"]["nombre"];?></td>
											<td><?php echo $elemento["id_elemento"];?></td>
											<td><?php echo $elementos[$i]["Elemento"]["nombre"];?></td>
											<td><?php echo $elementos[$i]["Posiciones_Elemento"]["nombre"];?></td>
											<td><?php echo $elementos[$i]["Diagnostico"]["nombre"];?></td>
											<td><?php echo $elementos[$i]["Solucion"]["nombre"];?></td>
											<td><?php echo $elementos[$i]["TipoElemento"]["nombre"];?></td>
											<td><?php echo $elemento["pn_saliente"];?></td>
											<td><?php echo $elemento["pn_entrante"];?></td>
											<td style="text-align: center;"><?php 
												$total_tiempo += $elemento["tiempo"];
												$hours  = floor($elemento["tiempo"] / 60); 
												$minutes = $elemento["tiempo"] % 60;
												echo str_pad($hours, 2, "0", STR_PAD_LEFT);
												echo ":";
												echo str_pad($minutes, 2, "0", STR_PAD_LEFT);
											
											?></td>
											<td></td>
										</tr>
										<?php } ?>
										<tr>
											<td colspan="12" style="text-align: right;">Tiempo total elementos</td>
									        <td style="text-align: center;"><?php
												$hours  = floor($total_tiempo / 60); 
												$minutes = $total_tiempo % 60;
												echo str_pad($hours, 2, "0", STR_PAD_LEFT);
												echo ":";
												echo str_pad($minutes, 2, "0", STR_PAD_LEFT);
											?></td>
											<td></td>
										</tr>
									</table>
								</div>
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
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="get">
						<button type="button" class="btn grey">
							<i class="fa fa-arrow-left"></i> Volver</button>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>