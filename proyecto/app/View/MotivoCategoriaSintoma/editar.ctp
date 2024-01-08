<div class="row">
	<div class="col-md-12">
	
	<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Editar registro</span>
					<span class="caption-helper"></span>
				</div>
			</div>
			<div class="portlet-body">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post" role="form">
					
					<div class="form-group">
						<div class="row">
							<label for="zona_id" class="col-md-2 control-label">Motivo de llamado</label>
							<div class="col-md-4">
								<select class="form-control" name="data[motivo_id]" required="required"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($motivos as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $data["MotivoCategoriaSintoma"]["motivo_id"] == $key ? 'selected="selected"' : ''; ?>><?php echo $value;?></option>
									<?php } ?>
								</select> 
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="zona_id" class="col-md-2 control-label">Categoría</label>
							<div class="col-md-4">
								<select class="form-control" name="data[categoria_id]" required="required"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($categorias as $key => $value) { ?>
										<option value="<?php echo $key;?>" <?php echo $data["MotivoCategoriaSintoma"]["categoria_id"] == $key ? 'selected="selected"' : ''; ?>><?php echo $value;?></option>
									<?php } ?>
								</select> 
							</div>
						</div>
					</div>	
					
					<div class="form-group">
						<div class="row">
							<label for="zona_id" class="col-md-2 control-label">Síntoma</label>
							<div class="col-md-4">
								<select class="form-control" name="data[sintoma_id]" required="required"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($sintomas as $key => $value) { ?>
										<?php if ($value["Sintoma"]["codigo"] != '0' && $value["Sintoma"]["codigo"] != NULL) { ?>
										<option value="<?php echo $value["Sintoma"]["id"];?>" <?php echo $data["MotivoCategoriaSintoma"]["sintoma_id"] == $value["Sintoma"]["id"] ? 'selected="selected"' : ''; ?>>FC <?php echo $value["Sintoma"]["codigo"];?> <?php echo $value["Sintoma"]["nombre"];?></option>
										<?php } else { ?>
										<option value="<?php echo $value["Sintoma"]["id"];?>" <?php echo $data["MotivoCategoriaSintoma"]["sintoma_id"] == $value["Sintoma"]["id"] ? 'selected="selected"' : ''; ?>><?php echo $value["Sintoma"]["nombre"];?></option>
										<?php } ?>
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
								<button type="button" class="btn default" onclick="window.location='/MotivoCategoriaSintoma';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>