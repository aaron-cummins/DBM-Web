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
			<div class="portlet-body">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" role="form">
					
					<div class="form-group">
						<div class="row">
							<label for="nombre" class="col-md-2 control-label">Faena</label>
							<div class="col-md-4">
								<select class="form-control" name="data[faena_id]" required="required" id="faena_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($faenas as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="url" class="col-md-2 control-label">Cargo</label>
							<div class="col-md-4">
								<select class="form-control" name="data[cargo_id]" required="required" id="cargo_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($cargos as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="url" class="col-md-2 control-label">Usuario</label>
							<div class="col-md-4">
								<select class="form-control selectpicker" data-live-search="true" data-size="8" required="required" id="usuario_id"name="data[usuario_id]">
									<option value="">Seleccione una opción</option>
									<?php foreach($usuarios as $key => $value) { ?>
										<option value="<?php echo $value["Usuario"]["id"];?>"><?php echo ucwords(strtolower($value["Usuario"]["nombres"]));?> <?php echo ucwords(strtolower($value["Usuario"]["apellidos"]));?></option>
									<?php } ?>
								</select>
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
								<button type="button" class="btn default" onclick="window.location='/Permiso/usuario';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>