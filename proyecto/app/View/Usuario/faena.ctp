<div class="row">
	<div class="col-md-12">
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Asignación Faenas</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" role="form">
					
					<div class="form-group">
						<div class="row">
							<label for="usuario" class="col-md-2 control-label">Rut</label>
							<div class="col-md-4">
								<input name="data[usuario]" class="form-control" readonly="readonly" type="text" id="usuario" value="<?php echo $data["Usuario"]["usuario"]; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="nombres" class="col-md-2 control-label">Nombre</label>
							<div class="col-md-4">
								<input name="data[nombres]" class="form-control" readonly="readonly" type="text" id="nombres" value="<?php echo $data["Usuario"]["nombres"]; ?> <?php echo $data["Usuario"]["apellidos"]; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="correo_electronico" class="col-md-2 control-label">Correo electrónico</label>
							<div class="col-md-4">
								<input name="data[correo_electronico]" class="form-control" readonly="readonly" type="text" id="correo_electronico" value="<?php echo $data["Usuario"]["correo_electronico"]; ?>">
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="row">
							<label for="faenas" class="col-md-2 control-label">Faenas asignadas</label>
							<div class="col-md-4">
								<span class="multiselect-native-select">
									<select class="form-control selectpicker" multiple="" name="faenas[]" id="faenas" required="required">
										<?php 
										foreach($faenas as $key => $value) { 
										if(in_array($key, $faenas_permiso)) {
												$selected = ' selected="selected"';
											}else{
												$selected = '';
											}
										?>
										<option value="<?php echo $key;?>"<?php echo $selected;?>><?php echo $value;?></option>
										<?php } ?>
									</select>
								</span>
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
								<button type="button" class="btn default" onclick="window.location='/Usuario';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>