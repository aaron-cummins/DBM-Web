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
				<form action="/Administracion/Imagenes" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Motor</label>
									<select class="form-control" name="motor_id" required="required" id="motor_id">
										<option value="">Seleccione una opción</option>
										<?php
											foreach ($motores as $motor) { 
												echo "<option value=\"{$motor["Motor"]["id"]}\" ".(($motor_id==$motor["Motor"]["id"]) ? 'selected="selected"' : '').">{$motor["Motor"]["nombre"]} {$motor["TipoEmision"]["nombre"]}</option>"."\n"; 
											}
										?>									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Sistema</label>
									<select class="form-control" name="sistema_id" required="required" id="sistema_id">
										<option value="">Seleccione una opción</option>
										<?php
											/*foreach ($sistemas as $sistema) { 
												echo "<option value=\"{$sistema["Sistema"]["id"]}\" ".(($sistema_id==$sistema["Sistema"]["id"]) ? 'selected="selected"' : '').">{$sistema["Sistema"]["nombre"]}</option>"."\n"; 
											}*/
										?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Subsistema</label>
									<select class="form-control" name="subsistema_id" required="required" id="subsistema_id">
										<option value="">Seleccione una opción</option>
										<?php
											/*foreach ($subsistemas as $subsistema) { 
												echo "<option value=\"{$subsistema["Subsistema"]["id"]}\" ".(($subsistema_id==$subsistema["Subsistema"]["id"]) ? 'selected="selected"' : '').">{$subsistema["Subsistema"]["nombre"]}</option>"."\n"; 
											}*/
										?>
									</select> 
								</div>
							</div>
							
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Administracion/Imagenes';">Limpiar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-filter"></i> Aplicar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<?php if (count($resultados)) { ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Imágenes</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/Administracion/Imagenes" class="horizontal-form" method="post" enctype="multipart/form-data">
					<input type="hidden" name="motor_id" value="<?php echo $motor_id;?>" />
					<input type="hidden" name="sistema_id" value="<?php echo $sistema_id;?>" />
					<input type="hidden" name="subsistema_id" value="<?php echo $subsistema_id;?>" />
					<div class="form-body">
						<div class="row">
							<div class="col-md-6" style="text-align: center;">
								<input type="hidden" name="image-name" value="<?php echo $image_source;?>" />
								<img src="/images/motor/<?php echo $image_source;?>.png" style="width: 80%;" />
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Nueva Imagen</label>
									<input type="file" id="file" name="data[file]" class="form-control" required="required" />
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<tr>
											<?php echo "<th style=\"width: 30px;\">#</th>";?>
											<th>ID</th>
											<th>Nombre</th>
											<th>Estado</th>
										</tr>
										<?php 
										$i = 1;
										foreach ($resultados as $resultado) {
										?>
											<tr>
												<td><?php echo $i++;?></td>
												<td><?php echo $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];?></td>
												<td><?php echo $resultado["Elemento"]["nombre"];?></td>
												<td><?php echo $resultado["Sistema_Subsistema_Motor_Elemento"]["e"] == 1 ? 'Activo' : 'Inactivo';?></td>
											</tr>
										<?php
											}
										?>
									</table>
								</div>
							</div>
							<div class="col-md-6">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
										<tr>
											<?php echo "<th style=\"width: 30px;\">#</th>";?>
											<th>ID</th>
											<th>Nombre</th>
											<th>ID Relación</th>
										</tr>
										<?php 
										$i = 1;
										foreach ($resultados_pendientes as $resultado) {
										?>
											<tr>
												<td><?php echo $i++;?></td>
												<td><?php echo $resultado["Sistema_Subsistema_Motor_Elemento"]["codigo"];?></td>
												<td><?php echo $resultado["Elemento"]["nombre"];?></td>
												<td><input type="text" name="elemento[<?php echo $resultado["Sistema_Subsistema_Motor_Elemento"]["id"];?>]" class="form-control" required="required" /></td>
											</tr>
										<?php
											}
										?>
									</table>
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Administracion/Imagenes';">Cancelar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-filter"></i> Guardar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<?php } ?>