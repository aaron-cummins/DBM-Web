<?php
	App::import('Controller', 'Utilidades');
	$app = new UtilidadesController();
	
	if($this->Session->read('usuario_id') == null || $this->Session->read('usuario_id') == null) {
		
	} else {
		$faenas_permiso = $this->Session->read("NombresFaenas"); 
		$faena_session = $this->Session->read("faena_id"); 
		$me_email = $this->Session->read('user_email');
		$me_image = $this->Session->read('google_image');
		$me_name = $this->Session->read('user_fullname');
		$usuario_id = $this->Session->read('usuario_id');
		$cargos = $this->Session->read("PermisosCargos");
	}		
?>

<?php if ($tiene_padre && $estado_padre != 4) { ?>
	<div class="alert alert-warning">
		<strong>Alerta!</strong> Esta intervención no se puede aprobar ya que la intervención anterior con folio <?php echo $padre_folio;?> no ha sido aprobada
	</div>
<?php } ?>
<!---->
<?php if ($estado == 2) { ?>
	<div class="alert alert-warning">
		<strong>Alerta!</strong> Esta intervención no se puede aprobar ya que aún no ha sido ingresada
	</div>
	
<?php } ?>
<!---->
<?php if ($estado == 4 && $this->request->query['edt'] == "0TxR056HBVhnli12LLGnhnANZR") { ?>
	<div class="alert alert-success">
		<strong>Alerta!</strong> Esta intervención ya se encuentra aprobada
	</div>
<?php } ?>
<!--formulario-->
<form action="" class="horizontal-form" method="post" id="form-intervencion">
	<input type="hidden" id="folio" name="folio" value="<?php echo $intervencion["Planificacion"]["folio"];?>" />
        <input type="hidden" id="id_intervencion" name="id_intervencion" value="<?php echo $intervencion["Planificacion"]["correlativo_final"];?>" />
	<input type="hidden" id="faena_id" name="faena_id" value="<?php echo $intervencion["Planificacion"]["faena_id"];?>" />
        <input type="hidden" id="flota_id" name="flota_id" value="<?php echo $intervencion["Planificacion"]["flota_id"];?>" />
	<input type="hidden" id="equipo_id" name="equipo_id" value="<?php echo $intervencion["Planificacion"]["unidad_id"];?>" />
	<input type="hidden" id="yes_no_option" name="yes_no_option" value=""/>
	<input type="hidden" id="int_fech" name="int_fech" value="<?php echo $fechas_anterior['IntervencionFechas']['id']?>">
	<!--Número OS SAP-->

	<?php 
	$a = json_decode($intervencion["Planificacion"]["json"], true);
	if($a["IntervencionTerminada"] == "S" || $a["MantencionTerminada"] == "S") { ?>

	<div class="form-group">
		<div class="row">
			<div class="col-lg-2">Número OS SAP</div>
			<div class="col-lg-6">
				<input type="text" id="os_sap" class="form-control" name="os_sap" value="<?php if($os_sap_) {  echo $os_sap_; } else {  echo $intervencion["Planificacion"]['os_sap']; }?>"/>
			</div>
            <div class="col-lg-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="md-checkbox-list">
                            <div class="md-checkbox">
                                <input type="checkbox" id="aplica_os_sap" name="aplica_os_sap" value="1" class="md-check" checked>
                                <label for="aplica_os_sap">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span> Aplica OS SAP</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
	<?php } else {?>
		<input type="hidden" id="os_sap" class="form-control" name="os_sap" value="<?php if($os_sap_) {  echo $os_sap_; } else {  echo $intervencion["Planificacion"]['os_sap']; }?>"/>
	<?php } ?>
	<?php if ($app->check_permissions_role(1, $faena_session, $usuario_id, $cargos) == TRUE) { ?>
		<?php if ( $tiene_continuacion ) { ?>
			<!--text-->
			<div class="row form-group">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<h4>
                                            <b>¿La continuación de este evento será realizada en su turno <b>actual</b>?</b>
						<?php if ($tiene_padre && $estado_padre != 4) { ?>
							<span class="span-answer">
										<span>
												<input 
														class="radio-detalle-answer" 
														type="radio" 
														name="options" 
														id="yes_option_detail"
														autocomplete="off"
														disabled="disabled">
														<?php if ( isset( $intervencion['Planificacion']['respuesta_continuacion_turno'] ) && $intervencion['Planificacion']['respuesta_continuacion_turno'] == false ) { echo 'checked'; } ?> 
												<span>
													<label for="yes_option_detail">SI</label>
												</span>
											</span>
											<span >
												<input 
														class="radio-detalle-answer" 
														type="radio" 
														name="options" 
														id="no_option_detail" 
														autocomplete="off" 
														disabled="disabled">
														<?php if ( isset( $intervencion['Planificacion']['respuesta_continuacion_turno'] ) && $intervencion['Planificacion']['respuesta_continuacion_turno'] == true ) { echo 'checked'; } ?> 
												<span>
													<label for="no_option_detail">NO</label>
												</span>
											</span>
									</span>
						<?php } else { ?>
							<?php if ($estado == 2) { ?>
								<span class="span-answer">
										<span>
												<input 
														class="radio-detalle-answer" 
														type="radio" 
														name="options" 
														id="yes_option_detail"
														autocomplete="off"
														disabled="disabled">
														<?php if ( isset( $intervencion['Planificacion']['respuesta_continuacion_turno'] ) && $intervencion['Planificacion']['respuesta_continuacion_turno'] == false ) { echo 'checked'; } ?> 
												<span>
													<label for="yes_option_detail">SI</label>
												</span>
											</span>
											<span >
												<input 
														class="radio-detalle-answer" 
														type="radio" 
														name="options" 
														id="no_option_detail" 
														autocomplete="off" 
														disabled="disabled">
														<?php if ( isset( $intervencion['Planificacion']['respuesta_continuacion_turno'] ) && $intervencion['Planificacion']['respuesta_continuacion_turno'] == true ) { echo 'checked'; } ?> 
												<span>
													<label for="no_option_detail">NO</label>
												</span>
											</span>
									</span>
							<?php } elseif ($estado == 4 && $this->request->query['edt'] == "0TxR056HBVhnli12LLGnhnANZR") { ?>
								<span class="span-answer">
										<span>
												<input 
													class="radio-detalle-answer" 
													type="radio" 
													name="options" 
													id="yes_option_detail"
													autocomplete="off" 
													disabled="disabled"
													<?php if ( isset( $intervencion['Planificacion']['respuesta_continuacion_turno'] ) && $intervencion['Planificacion']['respuesta_continuacion_turno'] == false ) { echo 'checked'; } ?> 
												>
												<span>
													<label for="yes_option_detail">SI</label>
												</span>
											</span>
											<span >
												<input 
													class="radio-detalle-answer" 
													type="radio" 
													name="options" 
													id="no_option_detail" 
													autocomplete="off" 
													disabled="disabled"
													<?php if ( isset( $intervencion['Planificacion']['respuesta_continuacion_turno'] ) && $intervencion['Planificacion']['respuesta_continuacion_turno'] == true ) { echo 'checked'; } ?> 
												>
												<span>
													<label for="no_option_detail">NO</label>
												</span>
											</span>
									</span>
							<?php } else { /**Estado Inicia (Cuando no se ha finalizado la intervención) **/?>
								<?php if ($app->check_permissions_role(1, $faena_session, $usuario_id, $cargos) == TRUE) { ?>
									<span class="span-answer">
										<span>
												<input 
													class="radio-detalle-answer" 
													type="radio" 
													name="options" 
													id="yes_option_detail" 
													autocomplete="off"
												>
												<span>
													<label for="yes_option_detail">SI</label>
												</span>
											</span>
											<span >
												<input 
													class="radio-detalle-answer" 
													type="radio" 
													name="options" 
													id="no_option_detail" 
													autocomplete="off"
												>
												<span>
													<label for="no_option_detail">NO</label>
												</span>
											</span>
									</span>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</h4>
					<span class="help-block help-block-detail hidden">Debe seleccionar una opción</span>
				</div>
			</div>
		<?php  } ?>
	<?php } ?>
	<!--Resumen intervención-->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="portlet box cummins">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-info"></i>Resumen intervención
						<span class="caption-helper"></span>
					</div>
					<div class="tools">
						<a href="javascript:;" class="expand" data-original-title="" title=""> </a>
					</div>
				</div>
				<div class="portlet-body" style="display: none;">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-2">Folio</div>
								<div class="col-lg-10"><input type="text" id="id" class="form-control" readonly="readonly" name="id" value="<?php echo $intervencion["Planificacion"]["id"];?>" /></div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-lg-2">Correlativo</div>
								<div class="col-lg-10"><input type="text" id="correlativo_final" class="form-control" readonly="readonly" name="correlativo_final" value="<?php echo $intervencion["Planificacion"]["correlativo_final"];?>" /></div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-lg-2">Faena</div>
								<div class="col-lg-10"><input type="text" id="" class="form-control" readonly="readonly" name="" value="<?php echo $intervencion["Faena"]["nombre"];?>" /></div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-lg-2">Flota</div>
								<div class="col-lg-10"><input type="text" id="" class="form-control" readonly="readonly" name="" value="<?php echo $intervencion["Flota"]["nombre"];?>" /></div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-lg-2">Equipo</div>
								<div class="col-lg-10"><input type="text" id="" class="form-control" readonly="readonly" name="" value="<?php echo $intervencion["Unidad"]["unidad"];?>" /></div>
							</div>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-lg-2">Tipo intervención</div>
								<div class="col-lg-10">
									<select name="tipointervencion" id="tipointervencion" class="form-control">
										<option value="<?php echo $intervencion["Planificacion"]["tipointervencion"];?>"><?php echo $intervencion["Planificacion"]["tipointervencion"];?></option>
									</select>
								</div>
							</div>
						</div>
					
				</div>
			</div>
		</div>
	</div>
	<!--Detalle término trabajo anterior e inicio trabajo actual-->
	<?php
		if ((isset($fechas_anterior['IntervencionFechas']['fecha_inicio_global']) && $fechas_anterior['IntervencionFechas']['fecha_inicio_global'] != '') && 
			(isset($fechas['IntervencionFechas']['fecha_termino_global']) && $fechas['IntervencionFechas']['fecha_termino_global'] != '')) {
	?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="portlet box cummins">
				<div class="portlet-title">
					<div class="caption">
					<i class="fa fa-calendar"></i>Detalle término trabajo anterior e inicio trabajo actual</div>
					<div class="tools">
						<a href="javascript:;" class="expand" data-original-title="" title=""> </a>
					</div>
				</div>
				<div class="portlet-body" style="display: none;">
					<div class="row">
						<div class="col-lg-12">Detalle de fechas</div>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-4">Fecha</div>
							<div class="col-lg-2">Hora</div>
							<div class="col-lg-2">Minuto</div>
							<div class="col-lg-2">Período</div>
						</div>
					</div>
				<?php
				/**
				 * Modificado "fecha_termino_global" por "fecha_termino_turno" ( Victor Smith )
				 */
					if((isset($fechas_anterior['IntervencionFechas']['fecha_termino_turno']) && $fechas_anterior['IntervencionFechas']['fecha_termino_turno'] != '')
					    || isset($fechas_anterior['IntervencionFechas']['termino_intervencion']) && $fechas_anterior['IntervencionFechas']['termino_intervencion'] != '') {
						
						if(isset($fechas_anterior['IntervencionFechas']['fecha_termino_turno']) && $fechas_anterior['IntervencionFechas']['fecha_termino_turno'] != '') {
							$fecha = new DateTime($fechas_anterior['IntervencionFechas']['fecha_termino_turno']);
							$label = "Término turno anterior";
						} else {
							$fecha = new DateTime($fechas_anterior['IntervencionFechas']['fecha_termino_global']); //new DateTime($fechas_anterior['IntervencionFechas']['termino_intervencion']);
							$label = "Término intervención anterior";
						}
                                        }else{
                                            $fecha = new DateTime($fechas_anterior['IntervencionFechas']['fecha_termino_global']); //new DateTime($fechas_anterior['IntervencionFechas']['termino_intervencion']);
                                            $label = "Término intervención anterior";
                                        }
                                        
                                        
                                        if(isset($fecha)){
				?>
				<!--Término turno anterior-->
				<div class="form-group">
						<div class="row">
					<div class="col-lg-2"><?php echo $label ?></div>
					<!---->
					<div class="col-lg-4">
						<input type="date" size="10" value="<?php echo $fecha->format('Y-m-d');?>" id="trabajo_anterior_fecha" class="f_3 delta2_data form-control" readonly="readonly" max="<?php echo date("Y-m-d");?>"  />
					</div>
					<!---->
					<div class="col-lg-2">
						<select id="trabajo_anterior_hora"  class="h_3 delta2_data form-control" >                                                    
						 <option value="<?php echo $fecha->format('h');?>" selected="selected"><?php echo $fecha->format('h');?></option>
						</select>
					</div>
					<!---->
					<div class="col-lg-2">
						<select id="trabajo_anterior_minuto" class="m_3 delta2_data form-control" >
						<option value="<?php echo $fecha->format('i');?>" selected="selected"><?php echo $fecha->format('i');?></option>
					</select>
					</div>
					<!---->
					<div class="col-lg-2">
						<select id="trabajo_anterior_periodo" class="p_3 delta2_data form-control">
							<option value="<?php echo $fecha->format('A');?>" selected="selected"><?php echo $fecha->format('A');?></option>
						</select>
					</div>
						</div>
					</div>
				  <?php
					} 
					?> 
					<?php
					if(isset($fechas['IntervencionFechas']['fecha_inicio_global']) && $fechas['IntervencionFechas']['fecha_inicio_global'] != '') {
						$fecha = new DateTime($fechas['IntervencionFechas']['fecha_inicio_global']);
					?>
				<!--Inicio trabajo actual-->
				<div class="form-group">
						<div class="row">
							<div class="col-lg-2">Inicio trabajo actual</div>
							<div class="col-lg-4">
								<input type="date" size="10" value="<?php echo $fecha->format('Y-m-d');?>" id="trabajo_fecha" readonly="readonly" class="f_4 form-control" max="<?php echo date("Y-m-d");?>" />
							</div>
							<div class="col-lg-2">
								<select id="trabajo_hora" class="h_4 form-control">
								<option value="<?php echo $fecha->format('h');?>" selected="selected"><?php echo $fecha->format('h');?></option>
								</select>
							</div>
							<div class="col-lg-2">
								<select id="trabajo_minuto" class="m_4 form-control">
								<option value="<?php echo $fecha->format('i');?>" selected="selected"><?php echo $fecha->format('i');?></option>
								</select>
							</div>
							<div class="col-lg-2">
								<select id="trabajo_periodo" class="p_4 form-control">
								<option value="<?php echo $fecha->format('A');?>" selected="selected"><?php echo $fecha->format('A');?></option>		
								</select>
							</div>
						</div>
					</div>
				  <?php
					} 
					?>
					<!----->
					<div class="row">
						<div class="col-lg-6">
							<div class="alert alert-success">
								<strong>Delta Disponible</strong> <span class="delta7_duracion"></span>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="alert alert-warning">
								<strong>Delta Ingresado</strong> <span id="d7_ing"></span>
							</div>
							
						</div>
					</div>
					<!---->
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-2">Hora</div>
							<div class="col-lg-2">Minuto</div>
							<div class="col-lg-2">Responsable</div>
							<div class="col-lg-2">Observación</div>
						</div>
					</div>
					<?php foreach($deltas_ as $key => $value){
							if($value["DeltaItem"]["grupo"] != "7"){
								continue;
							}
					?>
					<!---->
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2"><?php echo $value["DeltaItem"]["nombre"]; ?></div>
							<div class="col-lg-2">
								<?php
									$hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
									$minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
								?>
								<input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours;?>" pattern="[0-9]*" min="0" class="form-control delta_hora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" required="required" />
							</div>
							<div class="col-lg-2">
								<select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" required="required">
								 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
								  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
								  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
								  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
								</select>
							</div>
							<div class="col-lg-2">
								<select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
								  <option value="0"></option>
								  <option value="1"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
								  <option value="2"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
								  <option value="3"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
								</select>
							</div>
							<div class="col-lg-4">
								<input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"];?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<!---->
	<?php if (!$tiene_continuacion ) { ?>
	<!--Detalle término trabajo e inicio operación-->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="portlet box cummins">
				<div class="portlet-title">
					<div class="caption">
					<i class="fa fa-calendar"></i>Detalle término trabajo e inicio operación</div>
					<div class="tools">
						<a href="javascript:;" class="expand" data-original-title="" title=""> </a>
					</div>
				</div>
				<div class="portlet-body" style="display: none;">
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2">Detalle de fechas</div>
							<div class="col-lg-4">Fecha</div>
							<div class="col-lg-2">Hora</div>
							<div class="col-lg-2">Minuto</div>
							<div class="col-lg-2">Período</div>
						</div>
					</div>
				<?php
					if(isset($fechas['IntervencionFechas']['fecha_termino_global']) && $fechas['IntervencionFechas']['fecha_termino_global'] != '') {
						$fecha = new DateTime($fechas['IntervencionFechas']['fecha_termino_global']);
				?>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2">Término trabajo</div>
							<div class="col-lg-4">
								<input type="date" size="10" value="<?php echo $fecha->format('Y-m-d');?>" id="trabajo_termino_fecha" class="form-control" readonly="readonly" max="<?php echo date("Y-m-d");?>" />
							</div>
							<div class="col-lg-2">
								<select id="trabajo_termino_hora"  class="form-control">
								  <option value="<?php echo $fecha->format('h');?>" selected="selected"><?php echo $fecha->format('h');?></option>
								</select>
							</div>
							<div class="col-lg-2">
								<select id="trabajo_termino_minuto" class="form-control">
								<option value="<?php echo $fecha->format('i');?>" selected="selected"><?php echo $fecha->format('i');?></option>
							</select>
							</div>
							<div class="col-lg-2">
								<select id="trabajo_termino_periodo" class="form-control">
									<option value="<?php echo $fecha->format('A');?>"><?php echo $fecha->format('A');?></option>
								</select>
							</div>
						</div>
					</div>
				  <?php
					} 
					?>
				<div class="form-group">
				<?php
					if(isset($fecha_operacion) && $fecha_operacion != NULL && $fecha_operacion != '') {
						$fecha_operacion = strtotime($fecha_operacion);
					}
				?>
					<div class="row">
						<div class="col-lg-2">Inicio operación</div>
						<div class="col-lg-4">
							<?php if(isset($fecha_operacion)) { ?>
							<input type="date" size="10" name="operacion[]" value="<?php echo date("Y-m-d", $fecha_operacion);?>" id="operacion_fecha" class="form-control" max="<?php echo date("Y-m-d");?>" required="required" />
							<?php } else { ?>
							<input type="date" size="10" name="operacion[]" value="" id="operacion_fecha" class="form-control" max="<?php echo date("Y-m-d");?>" required="required" />
							<?php } ?>
						</div>
						<div class="col-lg-2">
							<?php if(isset($fecha_operacion)) { ?>
							<select name="operacion[]" id="operacion_hora" class="form-control" required="required">
								<option value="">Seleccione una opción</option>
								<option value="01"<?php echo date("h", $fecha_operacion) == "01" ? " selected=\"selected\"" : ""; ?>>01</option>
								<option value="02"<?php echo date("h", $fecha_operacion) == "02" ? " selected=\"selected\"" : ""; ?>>02</option>
								<option value="03"<?php echo date("h", $fecha_operacion) == "03" ? " selected=\"selected\"" : ""; ?>>03</option>
								<option value="04"<?php echo date("h", $fecha_operacion) == "04" ? " selected=\"selected\"" : ""; ?>>04</option>
								<option value="05"<?php echo date("h", $fecha_operacion) == "05" ? " selected=\"selected\"" : ""; ?>>05</option>
								<option value="06"<?php echo date("h", $fecha_operacion) == "06" ? " selected=\"selected\"" : ""; ?>>06</option>
								<option value="07"<?php echo date("h", $fecha_operacion) == "07" ? " selected=\"selected\"" : ""; ?>>07</option>
								<option value="08"<?php echo date("h", $fecha_operacion) == "08" ? " selected=\"selected\"" : ""; ?>>08</option>
								<option value="09"<?php echo date("h", $fecha_operacion) == "09" ? " selected=\"selected\"" : ""; ?>>09</option>
								<option value="10"<?php echo date("h", $fecha_operacion) == "10" ? " selected=\"selected\"" : ""; ?>>10</option>
								<option value="11"<?php echo date("h", $fecha_operacion) == "11" ? " selected=\"selected\"" : ""; ?>>11</option>
								<option value="12"<?php echo date("h", $fecha_operacion) == "12" ? " selected=\"selected\"" : ""; ?>>12</option>
							</select>
							<?php } else { ?>
							<select name="operacion[]" id="operacion_hora" class="form-control" required="required">
								<option value="">Seleccione una opción</option>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
							<?php } ?>
						</div>
						<div class="col-lg-2">
							<?php if(isset($fecha_operacion)) { ?>
							<select name="operacion[]" id="operacion_minuto" class="form-control" required="required">
								<option value="">Seleccione una opción</option>
								<option value="00"<?php echo date("i", $fecha_operacion) == "00" ? " selected=\"selected\"" : ""; ?>>00</option>
								<option value="15"<?php echo date("i", $fecha_operacion) == "15" ? " selected=\"selected\"" : ""; ?>>15</option>
								<option value="30"<?php echo date("i", $fecha_operacion) == "30" ? " selected=\"selected\"" : ""; ?>>30</option>
								<option value="45"<?php echo date("i", $fecha_operacion) == "45" ? " selected=\"selected\"" : ""; ?>>45</option>
							</select>
							<?php } else { ?>
							<select name="operacion[]" id="operacion_minuto" class="form-control" required="required">
								<option value="">Seleccione una opción</option>
								<option value="00">00</option>
								<option value="15">15</option>
								<option value="30">30</option>
								<option value="45">45</option>
							</select>
							<?php } ?>
						</div>
						<div class="col-lg-2">
							<?php if(isset($fecha_operacion)) { ?>
							<select name="operacion[]" id="operacion_periodo" class="form-control" required="required">
								<option value="">Seleccione una opción</option>
								<option value="AM"<?php echo date("A", $fecha_operacion) == "AM" ? " selected=\"selected\"" : ""; ?>>AM</option>
								<option value="PM"<?php echo date("A", $fecha_operacion) == "PM" ? " selected=\"selected\"" : ""; ?>>PM</option>
							</select>
							<?php } else { ?>
							<select name="operacion[]" id="operacion_periodo" class="form-control" required="required">
								<option value="">Seleccione una opción</option>
								<option value="AM">AM</option>
								<option value="PM">PM</option>
							</select>
							<?php } ?>
						</div>
					  </div>
					</div>
					
					<div class="row">
						<div class="col-lg-6">
							<div class="alert alert-success">
								<strong>Delta Disponible</strong> <span class="delta8_duracion"></span>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="alert alert-warning">
								<strong>Delta Ingresado</strong> <span id="d8_ing"></span>
							</div>
							
						</div>
					</div>
				
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2"></div>
							<div class="col-lg-2">Hora</div>
							<div class="col-lg-2">Minuto</div>
							<div class="col-lg-2">Responsable</div>
							<div class="col-lg-2">Observación</div>
						</div>
					</div>
					<?php foreach($deltas_ as $key => $value){
							if($value["DeltaItem"]["grupo"] != "8"){
								continue;
							}
					?>
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2"><?php echo $value["DeltaItem"]["nombre"]; ?></div>
							<div class="col-lg-2">
								<?php
									$hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
									$minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
								?>
								<input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours;?>" pattern="[0-9]*" min="0" class="form-control delta_hora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" required="required" />
							</div>
							<div class="col-lg-2">
								<select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" required="required">
								 <option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
								  <option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
								  <option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
								  <option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
								</select>
							</div>
							<div class="col-lg-2">
								<select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
								  <option value="0"></option>
								  <option value="1"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
								  <option value="2"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
								  <option value="3"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
								</select>
							</div>
							<div class="col-lg-4">
								<input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"];?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<!---->
	<?php if ($app->check_permissions_role(1, $faena_session, $usuario_id, $cargos) == TRUE) { ?>
		<?php if ( $tiene_continuacion ) { ?>
			<!--Detalle tiempo adicional a la bitácora [Añadido por Victor Smith]-->
			<div class="row detail-section-added <?php if ( !isset( $intervencion['Planificacion']['respuesta_continuacion_turno'] ) ) { echo 'hidden'; } else if ( isset( $intervencion['Planificacion']['respuesta_continuacion_turno'] )   && $intervencion['Planificacion']['respuesta_continuacion_turno'] == false ){ echo 'hidden'; } ?>">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="portlet box cummins">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-calendar"></i>Detalle tiempo adicional a la bitácora
								<span class="caption-helper"></span>
							</div>
							<div class="tools">
								<a href="javascript:;" class="expand" data-original-title="" title=""> </a>
							</div>
						</div>
						<div class="portlet-body" style="display: none;">
							<!--Detalle de fechas-->
							<div class="form-group">
								<div class="row">
									<div class="col-lg-2">Detalle de fechas</div>
										<div class="col-lg-4">Fecha</div>
										<div class="col-lg-2">Hora</div>
										<div class="col-lg-2">Minuto</div>
										<div class="col-lg-2">Período</div>
									</div>
							</div>
							<!--Inicio delta-->
							<div class="form-group">
								<div class="row">
									<div class="col-lg-2">Inicio delta</div>
									<!--fecha-->
									<div class="col-lg-4">
											<?php if ( isset($fechas['IntervencionFechas']['fecha_termino_global']) && $fechas['IntervencionFechas']['fecha_termino_global'] != '') { 
												$fecha_inicio_delta = new DateTime( $fechas['IntervencionFechas']['fecha_termino_global'] );
											?>
												<input type="date" size="10" name="inicio_delta[]" value="<?php echo $fecha_inicio_delta->format('Y-m-d'); ?>" id="inicio_delta_fecha" class="form-control" max="<?php echo date("Y-m-d");?>" readonly="readonly"/>
											<?php } else { ?>
												<input type="date" size="10" name="inicio_delta[]" value="HOLA" id="inicio_delta_fecha" class="form-control" max="<?php echo date("Y-m-d");?>" readonly="readonly"/>
											<?php } ?>
									</div>
									<!--hora-->
									<div class="col-lg-2">
										<?php if ( isset($fechas['IntervencionFechas']['fecha_termino_global']) && $fechas['IntervencionFechas']['fecha_termino_global'] != '') { 
											$fecha_inicio_delta = new DateTime( $fechas['IntervencionFechas']['fecha_termino_global'] );
										?>
											<input type="text" name="inicio_delta[]" id="inicio_delta_hora" class="form-control" value="<?php echo $fecha_inicio_delta->format('h') ?>"readonly="readonly">
										<?php } else { ?>
										<select name="inicio_delta[]" id="inicio_delta_hora" class="form-control" readonly="readonly">
												<option value="">Seleccione una opción</option>
												<option value="01">01</option>
												<option value="02">02</option>
												<option value="03">03</option>
												<option value="04">04</option>
												<option value="05">05</option>
												<option value="06">06</option>
												<option value="07">07</option>
												<option value="08">08</option>
												<option value="09">09</option>
												<option value="10">10</option>
												<option value="11">11</option>
												<option value="12">12</option>
										</select>
										<?php } ?>
									</div>
									<!--minuto-->	
									<div class="col-lg-2">
									<?php if ( isset($fechas['IntervencionFechas']['fecha_termino_global']) && $fechas['IntervencionFechas']['fecha_termino_global'] != '') { 
											$fecha_inicio_delta = new DateTime( $fechas['IntervencionFechas']['fecha_termino_global'] );
										?>
											<input type="text" name="inicio_delta[]" id="inicio_delta_minuto" class="form-control" value="<?php echo $fecha_inicio_delta->format('i') ?>"readonly="readonly">
										<?php } else { ?>
										<select name="inicio_delta[]" id="inicio_delta_minuto" class="form-control" readonly="readonly">
												<option value="">Seleccione una opción</option>
												<option value="00">00</option>
												<option value="15">15</option>
												<option value="30">30</option>
												<option value="45">45</option>
										</select>
										<?php } ?>
									</div>
									<!--periodo-->	
									<div class="col-lg-2">
										<?php if ( isset($fechas['IntervencionFechas']['fecha_termino_global']) && $fechas['IntervencionFechas']['fecha_termino_global'] != '') { 
											$fecha_inicio_delta = new DateTime( $fechas['IntervencionFechas']['fecha_termino_global'] );
										?>
										<input type="text" name="inicio_delta[]" id="inicio_delta_periodo" class="form-control" value="<?php echo $fecha_inicio_delta->format('A') ?>"readonly="readonly">
										<?php } else { ?>
										<select name="inicio_delta[]" id="inicio_delta_periodo" class="form-control" readonly="readonly">
												<option value="">Seleccione una opción</option>
												<option value="AM">AM</option>
												<option value="PM">PM</option>
										</select>
										<?php } ?>
									</div>										
								</div>
							</div>
							<!--Término turno-->
							<div class="form-group">
								<div class="row">
									<div class="col-lg-2">
										Término turno
									</div>
									<!--Fecha-->
									<div class="col-lg-4">
										<?php if ( isset($fechas['IntervencionFechas']['fecha_termino_turno']) && $fechas['IntervencionFechas']['fecha_termino_turno'] != '') { 
											$fecha_termino_turno = new DateTime( $fechas['IntervencionFechas']['fecha_termino_turno'] );
										?>
										<input name="termino_turno[]" type="date" size="10" value="<?php echo $fecha_termino_turno->format('Y-m-d'); ?>" id="termino_turno_fecha" class="f_3 delta2_data form-control" max="<?php echo date("m-d-Y");?>"/>
										<?php } else {?>
										<input name="termino_turno[]" type="date" size="10" value="" id="termino_turno_fecha" class="f_3 delta2_data form-control" max="<?php echo date("m-d-Y");?>"/>
										<?php }?>
									</div>
									<!--Hora-->
									<div class="col-lg-2">
										<?php if ( isset( $fecha_termino_turno ) ) { ?>
										<select name="termino_turno[]" id="termino_turno_hora" class="form-control">
														<option value="">Seleccione una opción </option>
														<option value="01"<?php echo $fecha_termino_turno->format('h') == "01" ? " selected=\"selected\"" : ""; ?>>01</option>
														<option value="02"<?php echo $fecha_termino_turno->format('h') == "02" ? " selected=\"selected\"" : ""; ?>>02</option>
														<option value="03"<?php echo $fecha_termino_turno->format('h') == "03" ? " selected=\"selected\"" : ""; ?>>03</option>
														<option value="04"<?php echo $fecha_termino_turno->format('h') == "04" ? " selected=\"selected\"" : ""; ?>>04</option>
														<option value="05"<?php echo $fecha_termino_turno->format('h') == "05" ? " selected=\"selected\"" : ""; ?>>05</option>
														<option value="06"<?php echo $fecha_termino_turno->format('h') == "06" ? " selected=\"selected\"" : ""; ?>>06</option>
														<option value="07"<?php echo $fecha_termino_turno->format('h') == "07" ? " selected=\"selected\"" : ""; ?>>07</option>
														<option value="08"<?php echo $fecha_termino_turno->format('h') == "08" ? " selected=\"selected\"" : ""; ?>>08</option>
														<option value="09"<?php echo $fecha_termino_turno->format('h') == "09" ? " selected=\"selected\"" : ""; ?>>09</option>
														<option value="10"<?php echo $fecha_termino_turno->format('h') == "10" ? " selected=\"selected\"" : ""; ?>>10</option>
														<option value="11"<?php echo $fecha_termino_turno->format('h') == "11" ? " selected=\"selected\"" : ""; ?>>11</option>
														<option value="12"<?php echo $fecha_termino_turno->format('h') == "12" ? " selected=\"selected\"" : ""; ?>>12</option>
										</select>
										<?php } else { ?>
										<select name="termino_turno[]" id="termino_turno_hora" class="form-control" >
														<option value="">Seleccione una opción</option>
														<option value="01">01</option>
														<option value="02">02</option>
														<option value="03">03</option>
														<option value="04">04</option>
														<option value="05">05</option>
														<option value="06">06</option>
														<option value="07">07</option>
														<option value="08">08</option>
														<option value="09">09</option>
														<option value="10">10</option>
														<option value="11">11</option>
														<option value="12">12</option>
										</select>
										<?php } ?>
									</div>
									<!--Minuto-->
									<div class="col-lg-2">
										<?php if( isset( $fecha_termino_turno ) ) { ?>
										<select name="termino_turno[]" id="termino_turno_minuto" class="form-control">
											<option value="">Seleccione una opción</option>
											<option value="00"<?php echo $fecha_termino_turno->format('i') == "00" ? " selected=\"selected\"" : ""; ?>>00</option>
											<option value="15"<?php echo $fecha_termino_turno->format('i') == "15" ? " selected=\"selected\"" : ""; ?>>15</option>
											<option value="30"<?php echo $fecha_termino_turno->format('i') == "30" ? " selected=\"selected\"" : ""; ?>>30</option>
											<option value="45"<?php echo $fecha_termino_turno->format('i') == "45" ? " selected=\"selected\"" : ""; ?>>45</option>
										</select>
										<?php } else { ?>
										<select name="termino_turno[]" id="termino_turno_minuto" class="form-control"> 
														<option value="">Seleccione una opción</option>
														<option value="00">00</option>
														<option value="15">15</option>
														<option value="30">30</option>
														<option value="45">45</option>
										</select>
										<?php } ?>
									</div>
									<!--Periodo-->	
									<div class="col-lg-2">
										<?php if(isset($fecha_termino_turno)) { ?>
										<select name="termino_turno[]" id="termino_turno_periodo" class="form-control">
												<option value="">Seleccione una opción</option>
												<option value="AM"<?php echo $fecha_termino_turno->format('A') == "AM" ? " selected=\"selected\"" : ""; ?>>AM</option>
												<option value="PM"<?php echo $fecha_termino_turno->format('A') == "PM" ? " selected=\"selected\"" : ""; ?>>PM</option>
										</select>
										<?php } else { ?>
										<select name="termino_turno[]" id="termino_turno_periodo" class="form-control">
												<option value="">Seleccione una opción</option>
												<option value="AM">AM</option>
												<option value="PM">PM</option>
										</select>
										<?php } ?>
									</div>																					
								</div>
							</div>
							<!--Delta Disponible & Delta Ingresado-->
							<div class="form-group">
								<div class="row">
									<div class="col-lg-6">
										<div class="alert alert-success">
											<strong>Delta Disponible</strong> <span class="delta9_duracion"></span>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="alert alert-warning">
											<strong>Delta Ingresado</strong> <span id="d9_ing"></span>
										</div>
									</div>
								</div>
							</div>
							<!--fechas[headers]-->
							<div class="form-group">
								<div class="row">
									<div class="col-lg-2"></div>
									<div class="col-lg-2">Hora</div>
									<div class="col-lg-2">Minuto</div>
									<div class="col-lg-2">Responsable</div>
									<div class="col-lg-2">Observación</div>
								</div>
							</div>
							<!--Deltas-->
							<?php foreach ( $deltas_ as $key => $value ) {
									if ( $value["DeltaItem"]["grupo"] != "9" ) {
											continue;
									}
							?>	
							<div class="form-group">
								<div class="row">
										<div class="col-lg-2"><?php echo $value["DeltaItem"]["nombre"]; ?></div>
										<div class="col-lg-2">
												<?php
														$hours = floor($deltas[$value["DeltaItem"]["id"]]["tiempo"] / 60);
														$minutes = ($deltas[$value["DeltaItem"]["id"]]["tiempo"] % 60);
												?>
												<input name="delta[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_hora<?php echo $value["DeltaItem"]["id"]; ?>" type="number" size="4" value="<?php echo $hours;?>" pattern="[0-9]*" min="0" class="form-control delta_hora delta_add_bitacora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
										</div>
										<div class="col-lg-2">
												<select name="delta_m[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_minuto<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_minuto delta_add_bitacora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
														<option value="00"<?php if($minutes=="0"){echo " selected=\"selected\"";};?>>00</option>
														<option value="15"<?php if($minutes=="15"){echo " selected=\"selected\"";};?>>15</option>
														<option value="30"<?php if($minutes=="30"){echo " selected=\"selected\"";};?>>30</option>
														<option value="45"<?php if($minutes=="45"){echo " selected=\"selected\"";};?>>45</option>
												</select>
										</div>
										<div class="col-lg-2">
												<select name="delta_r[<?php echo $value["DeltaItem"]["id"]; ?>]" id="delta_responsable<?php echo $value["DeltaItem"]["id"]; ?>" class="form-control delta_responsable delta_responsable_add_bitacora" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>">
														<option value="0"></option>
														<option value="1"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="1"){echo " selected=\"selected\"";};?>>DCC</option>
														<option value="2"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="2"){echo " selected=\"selected\"";};?>>OEM</option>
														<option value="3"<?php if($deltas[$value["DeltaItem"]["id"]]["delta_responsable_id"]=="3"){echo " selected=\"selected\"";};?>>MINA</option>
												</select>
										</div>
										<div class="col-lg-4">
												<input name="delta_o[<?php echo $value["DeltaItem"]["id"]; ?>]" type="text" id="delta_observacion<?php echo $value["DeltaItem"]["id"]; ?>" value="<?php echo $deltas[$value["DeltaItem"]["id"]]["observacion"];?>" class="form-control" grupo="<?php echo $value["DeltaItem"]["grupo"]; ?>" />
										</div>
								</div>
							</div>
							<?php } ?>		
						</div>
					</div>
				</div>
			</div>
		<?php } ?>			
	<?php } ?>
        
                        
        <!-- Seleccion de BAcklog -->                
        <?php if($intervencion["Planificacion"]["tipointervencion"] != 'MP') { ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="portlet box cummins">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cubes "></i>Seleccion de Backlog
                            <span class="caption-helper"></span>
                        </div>
                        <div class="tools">
                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body" style="display: block;">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-1">Backlog</div>
                                <div class="col-lg-4">
                                                 
                                    
                                    <span class="multiselect-native-select">
                                        <select class="form-control selectpicker" multiple="" name="backlog_id[]" id="backlog_ids" >
                                            <?php 
                                                $sel = false;
                                                foreach($backlogs as $b) { 
                                                    if($b['Backlog']['intervencion_id'] == $intervencion["Planificacion"]["correlativo_final"]){
                                                        $sel = true;
                                                    } 
                                                }
                                                
                                                if(!$sel){
                                            ?>
                                                <option selected="selected" value="">Seleccione backlog</option>
                                                <?php }else{ ?>
                                                <option value="">Seleccione backlog</option>
                                                <?php } ?>
                                            <option value="0" descripcion="Sin backlogs seleccionados">Sin backlog</option>
                                            <?php
                                                foreach($backlogs as $b) { 
                                                        $criticidad = "";
                                                        $responsable = "";
                                                        $style = "";
                                                        if ($b['Backlog']['criticidad_id'] == "1") {
                                                                $criticidad = "Alto";
                                                                $style = "danger";
                                                        } else if ($b['Backlog']['criticidad_id'] == "2") {
                                                                $criticidad = "Medio";
                                                                $style = "warning";
                                                        } else if ($b['Backlog']['criticidad_id'] == "3") {
                                                                $criticidad = "Bajo";
                                                                $style = "success";
                                                        }
                                                        if($b['Backlog']['responsable_id'] == "1"){
                                                                $responsable = "DCC";
                                                        }else if ($b['Backlog']['responsable_id'] == "2"){
                                                                $responsable = "OEM";
                                                        }else if ($b['Backlog']['responsable_id'] == "3"){
                                                                $responsable = "MINA";
                                                        }
                                                        //$fecha = new Date(item.Backlog.fecha_creacion);
                                                        if($b['Backlog']['intervencion_id'] == $intervencion["Planificacion"]["correlativo_final"]){
                                                            echo "<option selected='selected' data-content=\"F-". $b['Backlog']['id'] ." | <span class='label label-$style'>$criticidad</span> ".$b['Backlog']['fecha_creacion']." | ". $responsable . " | ". $b['Sistema']['nombre']."\" descripcion=\"".$b['Backlog']['comentario']."\" sintoma_id=".$b['Backlog']['sintoma_id']." categoria_sintoma_id=".$b['Backlog']['categoria_sintoma_id']." value=\"".$b['Backlog']['id']."\"></option>\n";
                                                        }else{
                                                            echo "<option data-content=\"F-". $b['Backlog']['id'] ." | <span class='label label-$style'>$criticidad</span> ".$b['Backlog']['fecha_creacion']." | ". $responsable . " | ". $b['Sistema']['nombre']."\" descripcion=\"".$b['Backlog']['comentario']."\" sintoma_id=".$b['Backlog']['sintoma_id']." categoria_sintoma_id=".$b['Backlog']['categoria_sintoma_id']." value=\"".$b['Backlog']['id']."\"></option>\n";
                                                        }
                                                        //echo '<option value="'.$b['Backlog']['id'].'" selected="selected">F-'. $b['Backlog']['id'] .' | <span class="label label-'.$style.'">'.$criticidad.'</span> '.$b['Backlog']['fecha_creacion'].' | '. $responsable . ' | '. $b['Sistema']['nombre'].'</option>';
                                                        ?>
                                                           <!-- <option value="<?php echo $b['Backlog']['id'] ?>" selected="selected">F-<?php echo $b['Backlog']['id'] ?> | <span class="label label-<?php echo $style ?>"><?php echo $criticidad ?></span> <?php echo $b['Backlog']['fecha_creacion'].' | '. $responsable . ' | '. $b['Sistema']['nombre'] ?></option>-->
                                                        
                                                   <?php }
                                            ?>
                                        </select>
                                    </span>
                                </div>
                            <!--</div>
                        </div>
                        <div class="form-group">
                            <div class="row">-->
                                <div class="col-lg-1">Información backlog</div>
                                <div class="col-lg-6">
                                    <textarea rows="5" name="informacion_backlog" required="required" class="form-control" id="informacion_backlog" disabled="disabled" readonly="readonly"><?php foreach($backlogs as $b) { if($b['Backlog']['intervencion_id'] == $intervencion["Planificacion"]["correlativo_final"]){ echo "F-". $b['Backlog']['id'] . " - ". $b['Backlog']['comentario']."\n";}}?></textarea>
                                </div>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
	</div>                
        <?php } ?>        
	<!--Otra información-->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="portlet box cummins">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-calendar"></i>Otra información
						<span class="caption-helper"></span>
					</div>
					<div class="tools">
						<a href="javascript:;" class="expand" data-original-title="" title=""> </a>
					</div>
				</div>
				<div class="portlet-body" style="display: none;">
				<!---->		
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2">Número de Lotes Componentes</div>
							<div class="col-lg-5"><input type="text" id="lotes_entra" class="form-control" name="lotes_entra" placeholder="Entra" /></div>
							<div class="col-lg-5"><input type="text" id="lotes_sale" class="form-control" name="lotes_sale" placeholder="Sale"  /></div>
						</div>
					</div>
				<!---->	
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2">Causa Raíz Evento</div>
							<div class="col-lg-10">
								<textarea name="causaraiz" class="form-control" rows="3"></textarea>
							</div>
						</div>
					</div>
				<!---->	
					<div class="form-group">
						<div class="row">
							<div class="col-lg-2">Observación</div>
							<div class="col-lg-10">
								<textarea name="observacionrevision" class="form-control" rows="3" ></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--Cancelar/Guardar-->
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="portlet light bordered">
				<div class="portlet-body form">
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Trabajo/Historial';">Cancelar</button>
						<?php if ($tiene_padre && $estado_padre != 4) { ?>
						<?php } else { ?>
							<?php if ($estado == 2) { ?>
							<?php } elseif ($estado == 4 && $this->request->query['edt'] == "0TxR056HBVhnli12LLGnhnANZR") { ?>
							<?php } else { ?>							
								<?php if ($app->check_permissions_role(1, $faena_session, $usuario_id, $cargos) == TRUE) { ?>
								<button type="button" class="btn blue submit-aprobar" value="Aprobar">
									<i class="fa fa-save"></i> 
									Aprobar
								</button>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<!--modal confirm-->
<div id="confirma_guardado" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
	<div class="modal-body">
        <p> ¿Realmente desea aprobar esta intervención? </p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline red">Cancelar</button>
        <button type="button" class="btn green btn-submit-modal" value="Guardar">Aprobar</button>
    </div>
</div>
<!--modal error-->
<div id="modal_mensaje_error" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
	<div class="modal-body">
        <p class="mensaje_modal"></p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline red">Aceptar</button>
    </div>
</div>
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

    $("#aplica_os_sap").change(() => {

        let aplica_os = document.getElementById("aplica_os_sap");
        console.log(aplica_os.checked);
        if(!aplica_os.checked) {
            $("#os_sap").attr('disabled', "disabled");
        }else{
            $("#os_sap").removeAttr('disabled');
        }
    });



    let $select = $('#backlog_ids');
    $select.on('change', () => {
        let selecteds = [];

        // Buscamos los option seleccionados
        $select.children(':selected').each((idx, el) => {
          // Obtenemos los atributos que necesitamos
          selecteds.push({
            id: el.id,
            value: el.value,
            descrip: el.getAttribute("descripcion")
          });
        });
        $("#informacion_backlog").val("");
        var informacion = "";
        for(i = 0; i < selecteds.length; i ++){
            informacion = informacion + 'F: ' + selecteds[i]['value'] + ' - ' + selecteds[i]['descrip'] + '\n';
          }
         $("#informacion_backlog").removeAttr("disabled");
         $("#informacion_backlog").val(informacion);
        //
        console.log(selecteds);
    });
</script>
