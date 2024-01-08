
<div class="row">
	<div class="col-md-12">
	
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Modificar registro</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" role="form">
					 
					<div class="form-group">
						<div class="row">
							<label for="nombre" class="col-md-2 control-label">Fluido</label>
							<div class="col-md-4">
								<input name="data[nombre]" class="form-control" required="required" type="text" id="nombre" value="<?php echo $data["Fluido"]["nombre"];?>" />
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="fluido_unidad_id" class="col-md-2 control-label">Unidad</label>
							<div class="col-md-4">
								<select class="form-control" name="data[unidad_id]" required="required" id="fluido_unidad_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($unidades as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $data["Fluido"]["unidad_id"] == $key ? "selected=\"selected\"":"";?>><?php echo $value;?></option>
									<?php } ?>
								</select> 
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="tipos_ingresos_id" class="col-md-2 control-label">Tipo ingreso</label>
							<div class="col-md-4">
								<span class="multiselect-native-select">
									<select class="form-control selectpicker" multiple="" name="tipos_ingresos[]" id="tipos_ingresos_id">
										<?php 
										foreach($tipos_ingresos as $key => $value) {
										if(in_array($key, explode(",", $data["Fluido"]["tipo_ingreso"]))) {
												$selected = ' selected="selected"';
											}else{
												$selected = '';
											}
										?>
										<option value="<?php echo $key;?>"<?php echo @$selected;?>><?php echo $value;?></option>
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
								<button type="button" class="btn default" onclick="window.location='/Fluidos';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>