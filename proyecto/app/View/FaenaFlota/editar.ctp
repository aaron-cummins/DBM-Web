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
				<form action="" class="horizontal-form" method="post" role="form">
					<div class="form-group">
						<div class="row">
							<label for="faena_id" class="col-md-2 control-label">Faena</label>
							<div class="col-md-4">
								<select class="form-control" name="data[faena_id]" required="required"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($faenas as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $data["FaenaFlota"]["faena_id"] == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="flota_id" class="col-md-2 control-label">Flota</label>
							<div class="col-md-4">
								<select class="form-control" name="data[flota_id]" required="required"> 
									<option value="">Seleccione una opción</option>
									<?php
									foreach($flotas as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $data["FaenaFlota"]["flota_id"] == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="motor_id" class="col-md-2 control-label">Motor</label>
							<div class="col-md-4">
								<select class="form-control" name="data[motor_id]" required="required" id="motor_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($motores as $key => $value) { ?>
									<option value="<?php echo $value["Motor"]["id"];?>" <?php echo $data["FaenaFlota"]["motor_id"] == $value["Motor"]["id"] ? 'selected="selected"' : '';?>><?php echo $value["Motor"]["nombre"];?> <?php echo $value["TipoEmision"]["nombre"];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="avance_teorico" class="col-md-2 control-label">Avance teórico</label>
							<div class="col-md-4">
								<input name="data[avance_teorico]" class="form-control" required="required" type="text" id="avance_teorico" value="<?php echo $data["FaenaFlota"]["avance_teorico"];?>" />
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
								<button type="button" class="btn default" onclick="window.location='/FaenaFlota';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>