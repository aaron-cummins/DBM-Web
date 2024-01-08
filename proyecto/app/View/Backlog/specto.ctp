<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Crear Backlog</span>
					<span class="caption-helper">Specto</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post">
					<input name="data[id]" class="form-control" type="hidden" id="id" value="<?php echo $data["id"];?>"  />
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Faena</label>
									<select class="form-control" name="data[faena_id]" required="required" id="faena_id"> 
										<option value="">Seleccione una opción</option>
										<?php foreach($faenas as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo @$data["faena_id"] == $key ? "selected=\"selected\"" : "";?>><?php echo $value;?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Flota</label>
									<select class="form-control" name="data[flota_id]" required="required" id="flota_id"> 
										<option value="">Seleccione una opción</option>
										<?php
										foreach($flotas as $key => $value) { ?>
											<option value="<?php echo $value["FaenaFlota"]["faena_id"];?>_<?php echo $value["FaenaFlota"]["flota_id"];?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"];?>"><?php echo $value["Flota"]["nombre"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Equipo</label>
									<select class="form-control" name="data[unidad_id]" id="unidad_id" required="required"> 
										<option value="">Seleccione una opción</option>
										<?php
										foreach($unidades as $key => $value) { ?>
											<option value="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>_<?php echo $value["Unidad"]["id"];?>" faena_flota="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>" motor_id="<?php echo $value["Unidad"]["motor_id"];?>"><?php echo $value["Unidad"]["unidad"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Criticidad</label>
									<select class="form-control" name="data[criticidad_id]" required="required" id="criticidad_id"> 
										<option value="">Seleccione una opción</option>
										<option value="1" <?php echo @$data["criticidad_id"] == 1 ? "selected=\"selected\"" : "";?>>Alto</option>
										<option value="2" <?php echo @$data["criticidad_id"] == 2 ? "selected=\"selected\"" : "";?>>Medio</option>
										<option value="3" <?php echo @$data["criticidad_id"] == 3 ? "selected=\"selected\"" : "";?>>Bajo</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Responsable</label>
									<select class="form-control" name="data[responsable_id]" required="required" id="responsable_id"> 
										<option value="">Seleccione una opción</option>
										<option value="1" <?php echo @$data["responsable_id"] == 1 ? "selected=\"selected\"" : "";?>>DCC</option>
										<option value="2" <?php echo @$data["responsable_id"] == 2 ? "selected=\"selected\"" : "";?>>OEM</option>
										<option value="3" <?php echo @$data["responsable_id"] == 3 ? "selected=\"selected\"" : "";?>>MINA</option>
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Sistema</label>
									<select class="form-control" name="data[sistema_ids]" required="required" id="sistema_ids"> 
										<option value="">Seleccione una opción</option>
										<?php foreach($sistemas as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo @$data["sistema_id"] == $key ? "selected=\"selected\"" : "";?>><?php echo $value;?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Categoría síntoma</label>
									<select class="form-control" name="data[categoria_sintoma_id]" required="required" id="categoria_sintoma_id"> 
										<option value="">Seleccione una opción</option>
										<?php foreach($categoria_sintoma as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo @$data["categoria_sintoma_id"] == $key ? "selected=\"selected\"" : "";?>><?php echo $value;?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Síntoma</label>
									<select class="form-control" name="data[sintoma_id]" required="required" id="sintoma_id"> 
										<option value="">Seleccione una opción</option>
										<?php foreach($sintomas as $key => $value) { ?>
											<?php if ($value['Sintoma']["codigo"] != "0" && $value['Sintoma']["codigo"] != "") { ?>
											<option value="<?php echo $value['Sintoma']["id"];?>" sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"];?>" style="display: none;"><?php echo $value['Sintoma']["codigo"];?> - <?php echo $value['Sintoma']["nombre"];?></option>
											<?php } else { ?>
											<option value="<?php echo $value['Sintoma']["id"];?>" sintoma_categoria_id="<?php echo $value['Sintoma']["sintoma_categoria_id"];?>" style="display: none;"><?php echo $value['Sintoma']["nombre"];?></option>

											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Comentario</label>
									<textarea name="data[comentario]" class="form-control" required="required" id="comentario"><?php echo @$data["comentario"];?></textarea>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label>Tiempo estimado</label>
									<input name="data[tiempo_estimado_hora]" class="form-control" placeholder="Ingrese hora" min="0" type="number" id="tiempo_estimado_hora" value="<?php echo @$data["tiempo_estimado_hora"];?>" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>&nbsp;</label>
									<select class="form-control" name="data[tiempo_estimado_minuto]" id="tiempo_estimado_minuto"> 
										<option value="">Seleccione minuto</option>
										<option value="00" <?php echo is_numeric(@$data["tiempo_estimado_minuto"]) && @$data["tiempo_estimado_minuto"] == 0 ? "selected=\"selected\"" : "";?>>00</option>
										<option value="15" <?php echo @$data["tiempo_estimado_minuto"] == 15 ? "selected=\"selected\"" : "";?>>15</option>
										<option value="30" <?php echo @$data["tiempo_estimado_minuto"] == 30 ? "selected=\"selected\"" : "";?>>30</option>
										<option value="45" <?php echo @$data["tiempo_estimado_minuto"] == 45 ? "selected=\"selected\"" : "";?>>45</option>
									</select>									
								</div>
							</div>
							
						</div>
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Backlog/';">Cancelar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-filter"></i> Guardar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
