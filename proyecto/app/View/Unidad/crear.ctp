<div class="row">
	<div class="col-md-12">
	
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Agregar registro</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/Unidad/crear" class="horizontal-form" method="post" role="form">
					<input name="data[e]" class="form-control" type="hidden" id="e" value="2">
					<div class="form-group">
						<div class="row">
							<label for="faena_id" class="col-md-2 control-label">Faena</label>
							<div class="col-md-4">
								<select class="form-control" name="data[faena_id]" required="required"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($faenas as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					<h3 class="form-section"></h3>
					<div class="form-group">
						<div class="row">
							<label for="flota_id" class="col-md-2 control-label">Flota</label>
							<div class="col-md-4">
								<select class="form-control" name="data[flota_id]" required="required"> 
									<option value="">Seleccione una opción</option>
									<?php
									foreach($flotas as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
						
					<div class="form-group">
						<div class="row">
							<label for="modelo_equipo" class="col-md-2 control-label">Modelo equipo</label>
							<div class="col-md-4">
								<input name="data[modelo_equipo]" class="form-control" required="required" type="text" id="modelo_equipo">
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="nserie" class="col-md-2 control-label">Nª serie equipo</label>
							<div class="col-md-4">
								<input name="data[nserie]" class="form-control" required="required" type="text" id="nserie">
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="unidad" class="col-md-2 control-label">Unidad</label>
							<div class="col-md-4">
								<input name="data[unidad]" class="form-control" required="required" type="text" id="unidad">
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
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
					<h3 class="form-section"></h3>
					<div class="form-group">
						<div class="row">
							<label for="motor_id" class="col-md-2 control-label">Modelo motor</label>
							<div class="col-md-4">
								<select class="form-control" name="data[motor_id]" required="required" id="motor_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($motores as $key => $value) { ?>
										<option value="<?php echo $value["Motor"]["id"];?>"><?php echo $value["Motor"]["nombre"];?> <?php echo $value["TipoAdmision"]["nombre"];?> <?php echo $value["TipoEmision"]["nombre"];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="tipo_contrato_id" class="col-md-2 control-label">Tipo contrato</label>
							<div class="col-md-4">
								<select class="form-control" name="data[tipo_contrato_id]" required="required" id="tipo_contrato_id"> 
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
							<label for="avance_teorico" class="col-md-2 control-label">Avance teórico</label>
							<div class="col-md-4">
								<input name="data[avance_teorico]" class="form-control" required="required" type="text" id="avance_teorico" />
							</div>
						</div>
					</div>		
					
					<div class="row">
						<div class="col-md-2">
						</div>
						<div class="col-md-4">
							<div class="form-actions">
								<button type="submit" class="btn blue">
									<i class="fa fa-save"></i> Guardar</button>
								<button type="button" class="btn default" onclick="window.location='/Unidad';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>