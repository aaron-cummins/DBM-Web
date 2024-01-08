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
								<input name="data[nombre]" class="form-control" required="required" type="text" id="nombre">
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="codigo" class="col-md-2 control-label">Abreviacion</label>
							<div class="col-md-4">
								<input name="data[codigo]" class="form-control" required="required" type="text" id="codigo">
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="zona_id" class="col-md-2 control-label">Zona</label>
							<div class="col-md-4">
								<select class="form-control" name="data[zona_id]" required="required" id="zona_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($zonas as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select> 
							</div>
						</div>
					</div>	
					<div class="form-group">
						<div class="row">
							<label for="zona_numerica_id" class="col-md-2 control-label">Zona 2</label>
							<div class="col-md-4">
								<select class="form-control" name="data[zona_numerica_id]" required="required" id="zona_numerica_id"> 
									<option value="">Seleccione una opción</option>
									<?php foreach($zonas_numericas as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select> 
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<label for="altura" class="col-md-2 control-label">Altura</label>
							<div class="col-md-4">
								<input name="data[altura]" class="form-control" required="required" type="number" id="altura">
								<span class="help-block"> Altura en metros </span>
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
								<button type="button" class="btn default" onclick="window.location='/Faena';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>