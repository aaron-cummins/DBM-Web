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
				<form action="/Motor/Agregar" class="horizontal-form" method="post" role="form">
					
					<div class="form-group">
						<div class="row">
							<label for="nombre" class="col-md-2 control-label">Motor</label>
							<div class="col-md-4">
								<input name="data[nombre]" class="form-control" required="required" type="text" id="nombre">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<label for="tipo_admision_id" class="col-md-2 control-label">Tipo Admisión</label>
							<div class="col-md-4">
								<select name="data[tipo_admision_id]" class="form-control" type="text" id="tipo_admision_id">
									<option value=""> Seleccione una opción </option>
									<?php
									foreach($tipos_admision as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<label for="tipo_emision_id" class="col-md-2 control-label">Tipo Emisión</label>
							<div class="col-md-4">
								<select name="data[tipo_emision_id]" class="form-control" type="text" id="tipo_emision_id">
									<option value=""> Seleccione una opción </option>
									<?php
									foreach($tipos_emision as $key => $value) { ?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<!--
					<div class="form-group">
						<div class="row">
							<label for="modelo_motor" class="col-md-2 control-label">Modelo Motor</label><!-- Motor + Admisión + Emisión
							<div class="col-md-4">
								<input name="data[modelo_motor]" class="form-control" required="required" type="text" id="modelo_motor" readonly="readonly">
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="row">
							<label for="arreglo_motor" class="col-md-2 control-label">Arreglo Motor</label><!-- Motor + Emisión 
							<div class="col-md-4">
								<input name="data[arreglo_motor]" class="form-control" required="required" type="text" id="arreglo_motor" readonly="readonly">
							</div>
						</div>
					</div>
					-->
					<div class="row">
						<div class="col-md-2">
						</div>
						<div class="col-md-4">
							<div class="form-actions">
								<button type="submit" class="btn blue">
									<i class="fa fa-save"></i> Guardar</button>
								<button type="button" class="btn default" onclick="window.location='/Motor';">Cancelar</button>
							</div>
						</div>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>