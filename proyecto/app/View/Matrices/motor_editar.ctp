<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Editar registro</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="" class="horizontal-form" method="post">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Motor</label>
									<select class="form-control" name="motor_id" required="required">
										<option value="">Seleccione una opción</option>
										<?php
											foreach ($motores as $motor) { 
												echo "<option value=\"{$motor["Motor"]["id"]}\" ".(($motor_id==$motor["Motor"]["id"]) ? 'selected="selected"' : '').">{$motor["Motor"]["nombre"]} {$motor["TipoEmision"]["nombre"]}</option>"."\n"; 
											}
										?>									</select> 
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Sistema</label>
									<select class="form-control" name="sistema_id" required="required">
										<option value="">Seleccione una opción</option>
										<?php
											foreach ($sistemas as $sistema) { 
												echo "<option value=\"{$sistema["Sistema"]["id"]}\" ".(($sistema_id==$sistema["Sistema"]["id"]) ? 'selected="selected"' : '').">{$sistema["Sistema"]["nombre"]}</option>"."\n"; 
											}
										?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Subsistema</label>
									<select class="form-control" name="subsistema_id" required="required">
										<option value="">Seleccione una opción</option>
										<?php
											foreach ($subsistemas as $subsistema) { 
												echo "<option value=\"{$subsistema["Subsistema"]["id"]}\" ".(($subsistema_id==$subsistema["Subsistema"]["id"]) ? 'selected="selected"' : '').">{$subsistema["Subsistema"]["nombre"]}</option>"."\n"; 
											}
										?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Nº ID</label>
									<input type="text" class="form-control" name="codigo" value="<?php echo $codigo;?>" placeholder="Ingrese un dato" required="required" /> 
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Elemento</label>
									<select class="form-control" name="elemento_id" required="required">
										<option value="">Seleccione una opción</option>
										<?php
											foreach ($elementos as $elemento) { 
												echo "<option value=\"{$elemento["Elemento"]["id"]}\" ".(($elemento_id==$elemento["Elemento"]["id"]) ? 'selected="selected"' : '').">{$elemento["Elemento"]["nombre"]}</option>"."\n"; 
											}
										?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Pool</label>
									<select class="form-control" name="pool" required="required">
										<option value="">Seleccione una opción</option>
										<option value="1"<?php echo $pool == '1' ? "selected=\"selected\"" : ""; ?>>SI</option>
										<option value="0"<?php echo $pool == '0' ? "selected=\"selected\"" : ""; ?>>NO</option>
									</select> 
								</div>
							</div>
							
							<div class="col-md-12">
								
								<div class="portlet box grey">
	                                <div class="portlet-title">
	                                    <div class="caption">
	                                        Posición Subsistema</div>
	                                    <div class="tools">
	                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
	                                    </div>
	                                </div>
	                                <div class="portlet-body tabs-below" style="display: none;">
	                                	<div class="mt-checkbox-inline">
										<?php foreach ($posiciones_subsistemas as $var) { ?>
										<div class="col-md-3">
											<label class="mt-checkbox">
												<?php if (isset($posicion_subsistema[$var["Posiciones_Subsistema"]["id"]]) && $posicion_subsistema[$var["Posiciones_Subsistema"]["id"]] == true) { ?>
													<input type="checkbox" name="posicion_subsistema[]" id="<?php echo $var["Posiciones_Subsistema"]["id"]; ?>" value="<?php echo $var["Posiciones_Subsistema"]["id"]; ?>" checked="checked" />
												<?php } else { ?>
													<input type="checkbox" name="posicion_subsistema[]" id="<?php echo $var["Posiciones_Subsistema"]["id"]; ?>" value="<?php echo $var["Posiciones_Subsistema"]["id"]; ?>" />
												<?php } ?>
			                                    <?php echo $var["Posiciones_Subsistema"]["nombre"]; ?>
			                                    <span></span>
			                                </label>
			                            </div>
										<?php } ?>
										</div>
	                                </div>
	                            </div>
								<!--
								<div class="form-group">
									<label>Posición Subsistema</label>
									<div class="mt-checkbox-inline">
									<?php foreach ($posiciones_subsistemas as $var) { ?>
									<div class="col-md-3">
										<label class="mt-checkbox">
											<?php if (isset($posicion_subsistema[$var["Posiciones_Subsistema"]["id"]]) && $posicion_subsistema[$var["Posiciones_Subsistema"]["id"]] == true) { ?>
												<input type="checkbox" name="posicion_subsistema[]" id="<?php echo $var["Posiciones_Subsistema"]["id"]; ?>" value="<?php echo $var["Posiciones_Subsistema"]["id"]; ?>" checked="checked" />
											<?php } else { ?>
												<input type="checkbox" name="posicion_subsistema[]" id="<?php echo $var["Posiciones_Subsistema"]["id"]; ?>" value="<?php echo $var["Posiciones_Subsistema"]["id"]; ?>" />
											<?php } ?>
		                                    <?php echo $var["Posiciones_Subsistema"]["nombre"]; ?>
		                                    <span></span>
		                                </label>
		                            </div>
									<?php } ?>
									</div>
								</div>
								-->
							</div>

							<div class="col-md-12">
								<div class="portlet box grey">
	                                <div class="portlet-title">
	                                    <div class="caption">
	                                        Posición Elemento</div>
	                                    <div class="tools">
	                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
	                                    </div>
	                                </div>
	                                <div class="portlet-body tabs-below" style="display: none;">
	                                	<div class="mt-checkbox-inline">
										<?php foreach ($posiciones_elementos as $var) { ?>
										<div class="col-md-3">
											<label class="mt-checkbox">
												<?php if (isset($posicion_elemento[$var["Posiciones_Elemento"]["id"]]) && $posicion_elemento[$var["Posiciones_Elemento"]["id"]] == true) { ?>
													<input type="checkbox" name="posicion_elemento[]" id="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" value="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" checked="checked" />
												<?php } else { ?>
													<input type="checkbox" name="posicion_elemento[]" id="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" value="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" />
												<?php } ?>
			                                    <?php echo $var["Posiciones_Elemento"]["nombre"]; ?>
			                                    <span></span>
			                                </label>
			                            </div>
										<?php } ?>
										</div>
	                                </div>
	                            </div>
								<!--<div class="form-group">
									<label>Posición Elemento</label>
									<div class="mt-checkbox-inline">
									<?php foreach ($posiciones_elementos as $var) { ?>
									<div class="col-md-3">
										<label class="mt-checkbox">
											<?php if (isset($posicion_elemento[$var["Posiciones_Elemento"]["id"]]) && $posicion_elemento[$var["Posiciones_Elemento"]["id"]] == true) { ?>
												<input type="checkbox" name="posicion_elemento[]" id="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" value="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" checked="checked" />
											<?php } else { ?>
												<input type="checkbox" name="posicion_elemento[]" id="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" value="<?php echo $var["Posiciones_Elemento"]["id"]; ?>" />
											<?php } ?>
		                                    <?php echo $var["Posiciones_Elemento"]["nombre"]; ?>
		                                    <span></span>
		                                </label>
		                            </div>
									<?php } ?>
									</div>
								</div>-->
							</div>
							
							<div class="col-md-12">
								<div class="portlet box grey">
	                                <div class="portlet-title">
	                                    <div class="caption">
	                                        Diagnósticos</div>
	                                    <div class="tools">
	                                        <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
	                                    </div>
	                                </div>
	                                <div class="portlet-body tabs-below" style="display: none;">
	                                	<div class="mt-checkbox-inline">
										<?php foreach ($diagnosticos as $var) { ?>
											<div class="col-md-3">
												<label class="mt-checkbox">
				                                    <?php if (isset($diagnostico_elemento[$var["Diagnostico"]["id"]]) && $diagnostico_elemento[$var["Diagnostico"]["id"]] == true) { ?>
														<input type="checkbox" name="diagnostico[]" id="<?php echo $var["Diagnostico"]["id"]; ?>" value="<?php echo $var["Diagnostico"]["id"]; ?>" checked="checked" />
													<?php } else { ?>
														<input type="checkbox" name="diagnostico[]" id="<?php echo $var["Diagnostico"]["id"]; ?>" value="<?php echo $var["Diagnostico"]["id"]; ?>" />
													<?php } ?>
				                                    <?php echo $var["Diagnostico"]["nombre"]; ?>
				                                    <span></span>
				                                </label>
				                            </div>
										<?php } ?>
										</div>
	                                </div>
	                            </div>

								<!--
								<div class="form-group">
									<label>Diagnósticos</label>
									<div class="mt-checkbox-inline">
									<?php foreach ($diagnosticos as $var) { ?>
										<div class="col-md-3">
											<label class="mt-checkbox">
			                                    <?php if (isset($diagnostico_elemento[$var["Diagnostico"]["id"]]) && $diagnostico_elemento[$var["Diagnostico"]["id"]] == true) { ?>
													<input type="checkbox" name="diagnostico[]" id="<?php echo $var["Diagnostico"]["id"]; ?>" value="<?php echo $var["Diagnostico"]["id"]; ?>" checked="checked" />
												<?php } else { ?>
													<input type="checkbox" name="diagnostico[]" id="<?php echo $var["Diagnostico"]["id"]; ?>" value="<?php echo $var["Diagnostico"]["id"]; ?>" />
												<?php } ?>
			                                    <?php echo $var["Diagnostico"]["nombre"]; ?>
			                                    <span></span>
			                                </label>
			                            </div>
									<?php } ?>
									</div>
								</div>
								-->
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Estado</label>
									<select class="form-control" name="estado" required="required">
										<option value="">Seleccione una opción</option>
										<option value="1"<?php echo $estado == '1' ? "selected=\"selected\"" : ""; ?>>Activo</option>
										<option value="0"<?php echo $estado == '0' ? "selected=\"selected\"" : ""; ?>>Inactivo</option>
									</select> 
								</div>
							</div>
							
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Matrices/Motor';">Cancelar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-save"></i> Guardar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>