 <?php
	App::import('Controller', 'Utilidades');
        $usuario = $this->Session->read('Usuario');
	$util = new UtilidadesController();
	$paginator = $this->Paginator;
	$inicio = $limit * ($paginator->current() - 1) + 1;
	$termino = $inicio + $limit - 1;
	if ($termino > $paginator->params()['count']) {
		$termino = $paginator->params()['count'];
	}
        
        $faena_session = $this->Session->read("faena_id");
        $usuario_id = $this->Session->read('usuario_id');
        $cargos = $this->Session->read("PermisosCargos");
        
        //print_r($registros);
        ?>


<style>
	.modal{
		width: auto !important;
		background-color: transparent;
		border: 0;
		box-shadow: none;
	}
</style>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="icon-equalizer font-blue-hoki"></i>
					<span class="caption-subject font-blue-hoki bold uppercase">Filtros</span>
					<span class="caption-helper">Aplique uno o más filtros</span>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<form action="/Trabajo/Historial" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-2">
								<div class="form-group">
									<label>Faena</label>
									<select class="form-control" name="faena_id" id="faena_id"> 
										<option value="">Todos</option>
										<?php foreach($faenas as $key => $value) { ?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php }?>
									</select>
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label>Flota</label>
									<select class="form-control" name="flota_id" id="flota_id" > 
										<option value="">Todos</option>
										<?php
										foreach($flotas as $key => $value) { ?>
											<option value="<?php echo $value["FaenaFlota"]["faena_id"];?>_<?php echo $value["FaenaFlota"]["flota_id"];?>" faena_id="<?php echo $value["FaenaFlota"]["faena_id"];?>"><?php echo $value["Flota"]["nombre"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label>Unidad</label>
										<select class="form-control" name="unidad_id" id="unidad_id" > 
										<option value="">Todos</option>
										<?php
										foreach($unidades as $key => $value) { ?>
											<option value="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>_<?php echo $value["Unidad"]["id"];?>" faena_flota="<?php echo $value["Unidad"]["faena_id"];?>_<?php echo $value["Unidad"]["flota_id"];?>"><?php echo $value["Unidad"]["unidad"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-2">
								<div class="form-group">
									<label>Resultados por página</label>
									<select class="form-control" name="limit">
										<option value="10" <?php echo @$limit == 10 ? "selected=\"selected\"" : ""; ?>>10</option>
										<option value="25" <?php echo @$limit == 25 ? "selected=\"selected\"" : ""; ?>>25</option>
										<option value="50" <?php echo @$limit == 50 ? "selected=\"selected\"" : ""; ?>>50</option>
										<option value="100" <?php echo @$limit == 100 ? "selected=\"selected\"" : ""; ?>>100</option>
									</select> 
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Evento</label>
									<select class="form-control" name="tipo_evento" id="tipo_evento">
										<option value="">Todos</option>
										<option value="PR" <?php echo $_GET["tipo_evento"] == 'PR' ? "selected=\"selected\"" : ""; ?>>Programado</option>
										<option value="NP" <?php echo $_GET["tipo_evento"] == 'NP' ? "selected=\"selected\"" : ""; ?>>No Programado</option>
									</select> 
								</div>
							</div>
							<!--/span-->
							<div class="col-md-2">
								<div class="form-group">
									<label>Tipo</label>
									<select class="form-control" name="tipointervencion" id="tipointervencion"> 
										<option value="">Todos</option>
										<option value="MP" <?php echo @$tipointervencion == 'MP' ? "selected=\"selected\"" : ""; ?>>MP</option>
										<option value="RP" <?php echo @$tipointervencion == 'RP' ? "selected=\"selected\"" : ""; ?>>RP</option>
										<option value="OP" <?php echo @$tipointervencion == 'OP' ? "selected=\"selected\"" : ""; ?>>OP</option>
										<option value="EX" <?php echo @$tipointervencion == 'EX' ? "selected=\"selected\"" : ""; ?>>EX</option>
										<option value="RI" <?php echo @$tipointervencion == 'RI' ? "selected=\"selected\"" : ""; ?>>RI</option>
									</select>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-2">
								<div class="form-group">
									<label>Supervisor Responsable</label>
									<select class="form-control" name="supervisor_responsable" id="supervisor_responsable" > 
										<option value="">Todos</option>
										<?php
										foreach($supervisores as $key => $value) { ?>
											<option value="<?php echo $value["PermisoUsuario"]["faena_id"];?>_<?php echo $value["PermisoUsuario"]["usuario_id"];?>" faena_id="<?php echo $value["PermisoUsuario"]["faena_id"];?>"><?php echo $value["Usuario"]["nombres"];?> <?php echo $value["Usuario"]["apellidos"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Estado</label>
									<select class="form-control" name="estado" id="estado">
										<option value="" selected="selected">Todos</option>
										<option value="1" <?php echo @$estado == 1 ? "selected=\"selected\"" : ""; ?>>Borrador</option>
										<option value="2" <?php echo @$estado == 2 ? "selected=\"selected\"" : ""; ?>>Planificado</option>
										<option value="7" <?php echo @$estado == 7 ? "selected=\"selected\"" : ""; ?>>Sin Revisar</option>
										<option value="4" <?php echo @$estado == 4 ? "selected=\"selected\"" : ""; ?>>Aprobado DCC</option>
										<option value="6" <?php echo @$estado == 6 ? "selected=\"selected\"" : ""; ?>>Rechazado Cliente</option>
										<option value="5" <?php echo @$estado == 5 ? "selected=\"selected\"" : ""; ?>>Aprobado Cliente</option>
									</select>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-2 ">
								<div class="form-group">
									<label>Correlativo</label>
									<input type="number" name="correlativo" class="form-control" value="<?php echo @$correlativo;?>" min="0" /> </div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Folio</label>
									<input type="number" name="folio" class="form-control" value="<?php echo @$folio;?>" min="0" /> </div>
							</div>
							<div class="col-md-2 ">
								<div class="form-group">
									<label>Fecha Inicio</label>
									<input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio;?>" required="required" /> </div>
							</div>
							<div class="col-md-2 ">
								<div class="form-group">
									<label>&nbsp;</label>
									<input type="date" name="fecha_termino" class="form-control" value="<?php echo $fecha_termino;?>" required="required" /> </div>
							</div>
						</div>
					<!--</div>-->
					</div>
					<div class="form-actions right">
                                            	<button type="button" class="btn default" onclick="window.location='/Trabajo/Historial';">Limpiar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-filter"></i> Aplicar</button>
                                                <?php //if (($cargo == 4 || $cargo == 6 || $cargo == 7 || $cargo == 8 || $cargo == 10)){ 
                                                        if($util->check_permissions_menu_item("Trabajo","replanificar",$faena_session,$usuario_id,$cargos)) {?>
                                                    <button type="submit" class="btn green" name="btn-descargar" >Descargar Plan</button>
                                                <?php } ?>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
		
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="portlet light">
			<div class="portlet-title">
				<div class="caption font-dark">
					<i class="icon-settings font-dark"></i>
					<span class="caption-subject bold uppercase">Historial</span>
				</div>
				<div class="tools"> </div>
			</div>
			<div class="portlet-body form">
				<!--<form action="" class="horizontal-form" method="post">-->
					
					<div id="table_1_wrapper" class="dataTables_wrapper">
						<div class="form-body">
							
							<div class="row">
								<div class="col-md-12">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
											<tr>
												<th>#</th>
												<?php echo "<th>" . $paginator->sort('Faena.nombre', 'Faena') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Flota.nombre', 'Flota') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Unidad.unidad', 'Equipo') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Planificacion.correlativo_final', 'Correlativo') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Planificacion.id', 'Folio') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Planificacion.fecha_inicio', 'Inicio') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Planificacion.esn', 'ESN') . "</th>"; ?>
												<?php echo "<th>" . $paginator->sort('Planificacion.tipointervencion', 'Tipo') . "</th>"; ?>
												<th>Descripción</th>
												<th style="width: 75px;">Duración</th>
												<?php echo "<th>" . $paginator->sort('Estado.nombre', 'Estado') . "</th>"; ?>
                                                                                                <?php echo "<th>Replanificado</th>"; ?>
												<th style="width: auto;">Acciones</th>
											</tr>
										<?php
											$i = $limit*($paginator->current()-1)+1;
											foreach( $registros as $registro ) {
												$json = json_decode($registro["Planificacion"]["json"], true);
												$registro['Planificacion']['fecha_inicio'] = strtotime($registro['Planificacion']['fecha'] . ' ' . $registro['Planificacion']['hora']);
												$registro['Planificacion']['fecha_inicio'] = date("d-m-Y h:i A", $registro['Planificacion']['fecha_inicio']);
												
												if (isset($folio) && $folio == $registro['Planificacion']['id']) {
													echo "<tr style=\"background-color: #f2dede;\">";
												} else {
													echo "<tr>";
												}
												echo "<td>$i</td>";
												echo "<td>{$registro['Faena']['nombre']}</td>";
												echo "<td>{$registro['Flota']['nombre']}</td>";
												echo "<td>{$registro['Unidad']['unidad']}</td>";
												echo "<td>{$registro['Planificacion']['correlativo_final']}</td>";
												echo "<td>{$registro['Planificacion']['id']}</td>";
												echo "<td>{$registro['Planificacion']['fecha_inicio']}</td>";
												echo "<td>";
		
												//$esn_ = $util->getESN($registro['Planificacion']['faena_id'],$registro['Planificacion']['flota_id'],$util->getMotor($registro['Planificacion']['unidad_id']),$util->getUnidad($registro['Planificacion']['unidad_id']),$registro['Planificacion']['fecha']);
												echo $esn_;
												$esn = "";
												if($esn_==''){
													/*if (@$json["esn_conexion"] != "") {
														$esn =  @$json["esn_conexion"];
													} elseif (@$json["esn_nuevo"] != "") {
														$esn = @$json["esn_nuevo"];
													} elseif (@$json["esn"] != "") {
														$esn =  @$json["esn"];
													} else {*/
														//$esn =  $registro['Planificacion']['esn'];
                                                                                                                $esn =  $registro['est_mot']['esn_placa'];
													//}
													
													if (is_numeric($esn)) {
														echo $esn;
													} else {
														echo $util->esnPadre($registro['Planificacion']['padre']);
													}
												}
												
												echo "</td>";
												echo "<td>";
												if ($registro['Planificacion']['padre'] != NULL && $registro['Planificacion']['padre'] != '') {
													if($registro['Planificacion']['tipointervencion']=="BL"){
														echo "cOP";
													}else{
														echo "c".strtoupper($registro['Planificacion']['tipointervencion']);
													}
												} else {
													if($registro['Planificacion']['tipointervencion']=="BL"){
														echo "OP";
													}else{
														echo strtoupper($registro['Planificacion']['tipointervencion']);
													}
												}
												echo "</td>";
												echo "<td>";
												if (strtoupper($registro['Planificacion']['tipointervencion']) == 'MP') {
													if (isset($json["tipo_programado"])) {
														if ($json["tipo_programado"] == "1500") {
															echo "Overhaul";
														} else {
															echo $json["tipo_programado"];
														}	
													} else {					
														if ($registro['Planificacion']['tipomantencion'] == "1500") {
															echo "Overhaul";
														} else {
															echo $registro['Planificacion']['tipomantencion'];
														}
													}
												} elseif ((strtoupper($registro['Planificacion']['tipointervencion']) == 'BL' || strtoupper($registro['Planificacion']['tipointervencion']) == 'OP') && $registro['Planificacion']['backlog_id'] != null) {
													echo $util->getBacklogInfo($registro['Planificacion']['backlog_id']);
												} else {
													echo $util->getSintoma($registro['Planificacion']['sintoma_id']);
												}
												echo "</td>";
												echo "<td align=\"center\">{$registro['Planificacion']['tiempo_trabajo']}</td>";
												echo "<td>{$registro['Estado']['nombre']}</td>";
                                                                                                echo "<td>";
                                                                                                    if($registro[0]['replan'] > 0){
                                                                                                        echo "SI";
                                                                                                    }else{
                                                                                                        echo "NO";
                                                                                                    }
                                                                                                echo "</td>";
												echo "<td>";
                                                                                                    if($registro[0]['replan'] > 0){
                                                                                                        if(($registro['Planificacion']['estado']) && ($cargo == 4 || $cargo == 7 || $cargo == 8)){
                                                                                                            echo '<a class="btn btn-sm btn-outline dark tooltips" data-toggle="modal" onclick="getHistorial('.$registro['Planificacion']['id'].');" href="#historial_'.$registro['Planificacion']['id'].'" data-container="body" data-placement="top" data-original-title="Historial replaniicaciones"><i class="fa fa-list-alt"></i> </a>';
                                                                                                        }
                                                                                                    }
												echo '<a class="btn btn-sm red btn-outline tooltips" data-toggle="modal" href="#modal_'.$registro['Planificacion']['id'].'" data-container="body" data-placement="top" data-original-title="Comentarios"><i class="fa fa-comments"></i>  </a>';
												echo '<a class="btn btn-sm red btn-outline tooltips" href="/Pdf/Resumen/'.$registro['Planificacion']['id'].'" data-container="body" data-placement="top" data-original-title="Ver PDF" target="_blank"><i class="fa fa-file-pdf-o"></i>  </a>';
												echo "<a href=\"/Trabajo/Detalle2/{$registro['Planificacion']['id']}?edt=0TxR056HBVhnli12LLGnhnANZR\" class=\"btn btn-sm btn-outline blue tooltips\" data-container=\"body\" data-placement=\"top\" data-original-title=\"Detalle intervención\"><i class=\"fa fa-search\"></i> </a>";
                                                                                                
                                                                                                if(($registro['Planificacion']['estado'] == 4 || $intervencion["Planificacion"]["estado"] == 5) && ($cargo == 4 || $cargo == 8)){
                                                                                                    echo "<a href=\"/Trabajo/Detalle2/{$registro['Planificacion']['id']}?edt=1ANZRTxR056HBVhnli12LLGnhnD\" class=\"btn btn-sm btn-outline green tooltips\" data-container=\"body\" data-placement=\"top\" data-original-title=\"Editar intervención\"><i class=\"fa fa-pencil\"></i> </a>";
                                                                                                }
                                                                                                if(($registro['Planificacion']['estado'] == 2) && ($util->check_permissions_menu_item("Trabajo","replanificar",$faena_session,$usuario_id,$cargos))){
                                                                                                    if($registro['Planificacion']['tipointervencion'] == 'MP' || $registro['Planificacion']['tipointervencion'] == 'RP' || $registro['Planificacion']['tipointervencion'] == 'OP'){
                                                                                                        if($registro['Planificacion']['correlativo_final'] == $registro['Planificacion']['id']){
                                                                                                            echo '<a class="btn btn-sm btn-outline green tooltips" data-toggle="modal" href="#replanifica'.$registro['Planificacion']['id'].'" data-container="body" data-placement="top" data-original-title="Replaniicar"><i class="fa fa-undo"></i> </a>';
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                                
                                                                                                
                                                                                                
                                                                                                
                                                                                                
                                                                                                
												echo '<div class="modal fade" id="historial_'.$registro['Planificacion']['id'].'" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
																	<h4 class="modal-title">Historial Replanificación</h4>
																</div>
																<div class="modal-body"> ';
                                                                                                echo                    '<table class="table table-striped table-bordered table-hover">'
                                                                                                                        . '<thead><tr>'
                                                                                                                        . '<td>Folio</td>'
                                                                                                                        . '<td>Fecha Ant.</td>'
                                                                                                                        . '<td>Duración Ant.</td>'
                                                                                                                        . '<td>Fecha modificación</td>'
                                                                                                                        . '<td>Motivo</td>'
                                                                                                                        . '<td>Comentario</td>'
                                                                                                                        . '<td>Usuario</td>'
                                                                                                                        . '</tr></thead>'
                                                                                                                        . '<tbody id="body'.$registro['Planificacion']['id'].'">'
                                                                                                                        . '<tr><td colspan="7" style="text-align:center;">'
                                                                                                                        . '<img src="/images/loaders/loader12.gif">'
                                                                                                                        . '</td></tr>'
                                                                                                                        . '</tbody>'
                                                                                                                        . '</table>';
												echo			' </div>
																<div class="modal-footer">
																	<button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>';
                                                                                                
                                                                                                
                                                                                                //Hora inicio
                                                                                                $h_i = split(":", $registro['Planificacion']['hora']);
                                                                                                $h_i_h = $h_i[0];
                                                                                                $h_i_m = $h_i[1];
                                                                                                $h_i_s = $h_i[2];
                                                                                                
                                                                                                //tiempo estimado
                                                                                                $t_e = split(":", $registro['Planificacion']['tiempo_trabajo']);
                                                                                                $t_e_h = intval($t_e[0]);
                                                                                                $t_e_m = intval($t_e[1]);
                                                                                                
                                                                                                
                                                                                                
                                                                                                echo '<div class="modal fade" id="replanifica'.$registro['Planificacion']['id'].'" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
                                                                                                        <div class="modal-dialog">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header">
                                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                                                                        <h4 class="modal-title">Replanificar Intervención </h4>
                                                                                                                        <span>('.$registro['Faena']['nombre'].' - ' .$registro['Flota']['nombre'] . ' - ' . $registro['Unidad']['unidad'] . ' - F: '.$registro['Planificacion']['id'].')</span>
                                                                                                                </div>
                                                                                                            
                                                                                                                <div class="modal-body"> ';
                                                                                                echo                '<form action="replanificar/'.$registro['Planificacion']['id'].'" id="f'.$registro['Planificacion']['id'].'" class="horizontal-form" method="post">
                                                                                                                        <div class="form-group">
                                                                                                                                <div class="row">
                                                                                                                                        <label for="fecha" class="col-md-4 control-label">Fecha Reprogramada</label>

                                                                                                                                        <div class="col-md-8">
                                                                                                                                                <input name="fecha_new'.$registro['Planificacion']['id'].'" class="form-control" required="required" type="date" id="fecha_new'.$registro['Planificacion']['id'].'" />
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                        </div>	

                                                                                                                        <div class="form-group">
                                                                                                                                <div class="row">
                                                                                                                                    <input type="hidden" name="id_intervencion'.$registro['Planificacion']['id'].'" id="id_intervencion'.$registro['Planificacion']['id'].'" value="'.$registro['Planificacion']['id'].'">
                                                                                                                                    <input type="hidden" name="fecha_a'.$registro['Planificacion']['id'].'" id="fecha_a'.$registro['Planificacion']['id'].'" value="'.$registro['Planificacion']['fecha'].'">
                                                                                                                                    <input type="hidden" name="hora_a'.$registro['Planificacion']['id'].'" id="hora_a'.$registro['Planificacion']['id'].'" value="'.$registro['Planificacion']['hora'].'">
                                                                                                                                    <input type="hidden" name="tiempo_estimado_a'.$registro['Planificacion']['id'].'" id="tiempo_estimado_a'.$registro['Planificacion']['id'].'" value="'.$registro['Planificacion']['tiempo_trabajo'].'">
                                                                                                                                    
                                                                                                                                        <label for="hora" class="col-md-4 control-label">Hora Reprogramada</label>
                                                                                                                                        <div class="col-md-3">
                                                                                                                                                <select class="form-control" name="hora_new'.$registro['Planificacion']['id'].'" required="required" id="hora_new'.$registro['Planificacion']['id'].'"> 
                                                                                                                                                        <option value="">Seleccione hora</option>
                                                                                                                                                        <option '. ($h_i_h == "01" || $h_i_h == "13" ? 'selected': '') .' value="01">01</option>
                                                                                                                                                        <option '. ($h_i_h == "02" || $h_i_h == "14" ? 'selected': '') .' value="02">02</option>
                                                                                                                                                        <option '. ($h_i_h == "03" || $h_i_h == "15" ? 'selected': '') .' value="03">03</option>
                                                                                                                                                        <option '. ($h_i_h == "04" || $h_i_h == "16" ? 'selected': '') .' value="04">04</option>
                                                                                                                                                        <option '. ($h_i_h == "05" || $h_i_h == "17" ? 'selected': '') .' value="05">05</option>
                                                                                                                                                        <option '. ($h_i_h == "06" || $h_i_h == "18" ? 'selected': '') .' value="06">06</option>
                                                                                                                                                        <option '. ($h_i_h == "07" || $h_i_h == "19" ? 'selected': '') .' value="07">07</option>
                                                                                                                                                        <option '. ($h_i_h == "08" || $h_i_h == "20" ? 'selected': '') .' value="08">08</option>
                                                                                                                                                        <option '. ($h_i_h == "09" || $h_i_h == "21" ? 'selected': '') .' value="09">09</option>
                                                                                                                                                        <option '. ($h_i_h == "10" || $h_i_h == "22" ? 'selected': '') .' value="10">10</option>
                                                                                                                                                        <option '. ($h_i_h == "11" || $h_i_h == "23" ? 'selected': '') .' value="11">11</option>
                                                                                                                                                        <option '. ($h_i_h == "12" || $h_i_h == "00" ? 'selected': '') .' value="12">12</option>
                                                                                                                                                </select>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-md-3">
                                                                                                                                                <select class="form-control" name="minuto_new'.$registro['Planificacion']['id'].'" required="required" id="minuto_new'.$registro['Planificacion']['id'].'"> 
                                                                                                                                                        <option value="">Seleccione minuto</option>
                                                                                                                                                        <option '. ($h_i_m == 00 ? 'selected': '') .' value="00">00</option>
                                                                                                                                                        <option '. ($h_i_m == 15 ? 'selected': '') .' value="15">15</option>
                                                                                                                                                        <option '. ($h_i_m == 30 ? 'selected': '') .' value="30">30</option>
                                                                                                                                                        <option '. ($h_i_m == 45 ? 'selected': '') .' value="45">45</option>
                                                                                                                                                </select>
                                                                                                                                        </div>
                                                                                                                                        <div class="col-md-2">
                                                                                                                                                <select class="form-control" name="periodo_new'.$registro['Planificacion']['id'].'" required="required" id="periodo_new'.$registro['Planificacion']['id'].'"> 
                                                                                                                                                        <option value="">Seleccione período</option>
                                                                                                                                                        <option '. ($h_i_h < 12 ? 'selected': '') .' value="AM">AM</option>
                                                                                                                                                        <option '. ($h_i_h >= 12 ? 'selected': '') .' value="PM">PM</option>
                                                                                                                                                </select>
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                        </div>	

                                                                                                                        <div class="form-group">
                                                                                                                                <div class="row">
                                                                                                                                        <label for="tiempo_estimado_hora" class="col-md-4 control-label">Tiempo estimado de trabajo</label>
                                                                                                                                        <div class="col-md-4">
                                                                                                                                                <input name="tiempo_estimado_hora_new'.$registro['Planificacion']['id'].'" class="form-control" placeholder="Ingrese hora" min="0" required="required" type="number" id="tiempo_estimado_hora_new'.$registro['Planificacion']['id'].'" value="'.$t_e_h.'"  />
                                                                                                                                        </div>
                                                                                                                                        <div class="col-md-4">
                                                                                                                                                <select class="form-control" name="tiempo_estimado_minuto_new'.$registro['Planificacion']['id'].'" required="required" id="tiempo_estimado_minuto_new'.$registro['Planificacion']['id'].'" > 
                                                                                                                                                        <option value="">Seleccione minuto</option>
                                                                                                                                                        <option '. ($t_e_m == 00 ? 'selected': '') .' value="00">00</option>
                                                                                                                                                        <option '. ($t_e_m == 15 ? 'selected': '') .' value="15">15</option>
                                                                                                                                                        <option '. ($t_e_m == 30 ? 'selected': '') .' value="30">30</option>
                                                                                                                                                        <option '. ($t_e_m == 45 ? 'selected': '') .' value="45">45</option>
                                                                                                                                                </select>
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                        </div>	
                                                                                                                        <div class="form-group">
                                                                                                                            <div class="row">
                                                                                                                                    <label for="motivo_new" class="col-md-4 control-label">Motivo</label>
                                                                                                                                    <div class="col-md-8">
                                                                                                                                            <select class="form-control" name="motivo_new'.$registro['Planificacion']['id'].'" required="required" id="motivo_new'.$registro['Planificacion']['id'].'" onchange="activacomentario('.$registro['Planificacion']['id'].');"> 
                                                                                                                                                    <option value="">Seleccione motivo</option>';
                                                                                                                                                    foreach($motivos as $motivo){
                                                                                                                                                        echo '<option value="'.$motivo['Motivo_replanificacion']['id'].'">'.$motivo['Motivo_replanificacion']['nombre'].'</option>';
                                                                                                                                                    }
                                                                                                echo                                        '</select>
                                                                                                                                    </div>
                                                                                                                            </div>
                                                                                                                        </div>	
                                                                                                                        <div class="form-group" id="comen'.$registro['Planificacion']['id'].'" style="display:none;">
                                                                                                                                <div class="row">
                                                                                                                                        <label for="observacion" class="col-md-4 control-label">Comentario</label>
                                                                                                                                        <div class="col-md-8">
                                                                                                                                                <textarea name="observacion_new'.$registro['Planificacion']['id'].'" class="form-control" required="required" id="observacion_new'.$registro['Planificacion']['id'].'"></textarea>
                                                                                                                                        </div>
                                                                                                                                </div>
                                                                                                                        </div>	
                                                                                                                    </form>';
												echo            ' </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
                                                                                                                    <button type="button" class="btn green btn-outline" onclick="enviaForm('.$registro['Planificacion']['id'].')">Replanificar</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                                    
															<!-- /.modal-content -->
                                                                                                            </div>
														<!-- /.modal-dialog -->
													</div>';
                                                                                                
                                                                                                
												
                                                                                                echo '<div class="modal fade" id="modal_'.$registro['Planificacion']['id'].'" tabindex="-1" role="basic" aria-hidden="true" style="display: none;">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
																	<h4 class="modal-title">Comentario Intervención</h4>
																</div>
																<div class="modal-body"> ';
												echo "<div class=\"row\"><div class=\"col-md-3\">Faena</div> <div class=\"col-md-9\">{$registro['Faena']['nombre']}</div></div>";
												echo "<div class=\"row\"><div class=\"col-md-3\">Flota</div> <div class=\"col-md-9\">{$registro['Flota']['nombre']}</div></div>";
												echo "<div class=\"row\"><div class=\"col-md-3\">Unidad</div> <div class=\"col-md-9\">{$registro['Unidad']['unidad']}</div></div>";
												echo "<div class=\"row\"><div class=\"col-md-3\">Correlativo</div> <div class=\"col-md-9\">{$registro['Planificacion']['correlativo_final']}</div></div>";
												echo "<div class=\"row\"><div class=\"col-md-3\">Folio</div> <div class=\"col-md-9\">{$registro['Planificacion']['id']}</div></div>";
												echo "<div class=\"row\"><div class=\"col-md-3\">Fecha inicio</div> <div class=\"col-md-9\">{$registro['Planificacion']['fecha_inicio']}</div></div>";
												echo "<div class=\"row\"><div class=\"col-md-3\">OS SAP</div> <div class=\"col-md-9\">{$registro['Planificacion']['os_sap']}</div></div>";
												
												if ($registro['Planificacion']['estado'] > 2) {
													$comentario = $util->getComentario($registro['Planificacion']['folio']);
													if (strlen($comentario) > 0) {
														echo "<div class=\"row\"><div class=\"col-md-3\">Técnico lider</div> <div class=\"col-md-9\">".$util->getTecnicoLider($json)."</div></div>";
														echo "<div class=\"row\"><div class=\"col-md-3\">Comentarios</div> <div class=\"col-md-9\">$comentario</div></div>";
														$hay_comentarios = true;
													}
												}
												if (strlen($registro['Planificacion']['observacion']) > 0) {
													echo "<div class=\"row\"><div class=\"col-md-3\">Supervisor</div> <div class=\"col-md-9\">".$util->getUsuarioInfo($registro["Planificacion"]["usuario_id"])."</div></div>";
													echo "<div class=\"row\"><div class=\"col-md-3\">Comentarios</div> <div class=\"col-md-9\">{$registro['Planificacion']['observacion']}</div></div>";
													$hay_comentarios = true;
												}
												if (!$hay_comentarios) {
													echo "<div class=\"row\"><div class=\"col-md-12\">No hay comentarios ingresados</div></div>";
												}
												echo			' </div>
																<div class="modal-footer">
																	<button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
																</div>
															</div>
															<!-- /.modal-content -->
														</div>
														<!-- /.modal-dialog -->
													</div>';
												echo "</td>";
												echo "</tr>";
												$i++;
											}
										?>
										</table>
									</div>
									<!-- End table div -->
								</div>
								<!-- End col -->
							</div>
							<!-- End row -->
							
							<div class="row">
								<div class="col-md-5 col-sm-12">
									<div class="dataTables_info" id="table_1_info" role="status" aria-live="polite">Mostrando del <?php echo $inicio;?> a <?php echo $termino;?> de <?php echo $paginator->params()['count'];?> registros</div>
								</div>
								<!-- End col -->
								<div class="col-md-7 col-sm-12">
									<?php
									if (count($registros) > 0) { 
										echo '<div class="dataTables_paginate paging_bootstrap_number" id="table_1_paginate">
										<ul class="pagination pull-right" style="visibility: visible;">';
										echo $paginator->first("<i class=\"fa fa-angle-double-left\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "prev", 'tag' => 'li', 'escape' => false));
										if($paginator->hasPrev()){
											echo $paginator->prev("<i class=\"fa fa-angle-left\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "prev", 'tag' => 'li', 'escape' => false));
										}
										echo $paginator->numbers(array('modulus' => 5, "separator" => '', 'tag' => 'li', 'currentClass' => 'active', 'currentTag' => 'a'));
										if($paginator->hasNext()){
											echo $paginator->next("<i class=\"fa fa-angle-right\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "next", 'tag' => 'li', 'escape' => false));
										}
										echo $paginator->last("<i class=\"fa fa-angle-double-right\" style=\"width: 0.2em;\"></i>&nbsp;", array("class" => "next", 'tag' => 'li', 'escape' => false));
										echo "</ul></div>";
									}
								?>
								</div>
								<!-- End col -->
							</div>
							<!-- End row -->
							
						</div>
						<!-- End div table -->
					</div>
					<!-- End div form -->
				<!--</form>-->
				<!-- End form -->
			</div>
			<!-- End body -->
		</div>
		<!-- End portled -->
	</div>
	<!-- End col -->
</div>
<!-- End row -->
<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function activacomentario(id){
        if($("#motivo_new"+id).val() == 12){
            $("#comen"+id).show();
        }else{
            $("#comen"+id).hide();
        }
    }
    
    function enviaForm(id){
        //nueva fecha
        if($("#fecha_new"+id).val() == ""){
            alert("Debe ingresar una fecha para reporgramar la bitácora.")
            return false;
        }  
        //else{
        //    if($("#fecha_new"+id).val() < $("#fecha_a"+id).val()){
        //        alert("La fecha de replnificacion no puede ser anterior a la de planificacion.")
        //        return false;
        //   }
        //}
        
        //horas, minutos y periodo
        if($("#hora_new"+id).val() == ""){
            alert("Debe seleccionar una hora para reporgramar la bitácora.")
            return false;
        }
        if($("#minuto_new"+id).val() == ""){
            alert("Debe seleccionar los minutos para reporgramar la bitácora.")
            return false;
        }
        if($("#periodo_new"+id).val() == ""){
            alert("Debe seleccionar un periodo para reporgramar la bitácora.")
            return false;
        }
        //tiempo estimado
        if($("#tiempo_estimado_hora_new"+id).val() == ""){
            alert("Debe ingresar un tiempo estimado en horas o dejarlo en 0 para reporgramar la bitácora.")
            return false;
        }
        if($("#tiempo_estimado_minuto_new"+id).val() == ""){
            alert("Debe seleccionar los minutos de tiempo estimado para reporgramar la bitácora.")
            return false;
        }
        
        //motivo y comentario
        if($("#motivo_new"+id).val() == ""){
            alert("Debe seleccionar el motivo por el cual quiere reporgramar la bitácora.")
            return false;
        }
        
        if(confirm("¿Esta seguro de replanificar esta bitácora?")){
            $("#f"+id).submit();
        }
        
    }
    
    function getHistorial(id){
        html =  '';
        $.get( "/Replanificacion/historial/" + id, function(data) {
            var obj = $.parseJSON(data);
            $.each(obj, function(i, item) {
                
                html += '<tr>'
                + '<td>'+ id +'</td>'
                + '<td>'+ item.Replanificacion.fecha_anterior + " " + item.Replanificacion.hora_anterior +'</td>'
                + '<td>'+ item.Replanificacion.tiempo_estimado_anterior +'</td>'
                + '<td>'+ item.Replanificacion.fecha_replanificacion +'</td>'
                + '<td>'+ item.Motivo_replanificacion.nombre +'</td>'
                + '<td>'+ item.Replanificacion.comentario +'</td>'
                + '<td>'+ item.Usuario.nombres + ' ' + item.Usuario.apellidos +'</td>'
                + '<tr>';
                
            });
            $('#body'+id).html();
            $('#body'+id).html(html);
        });
    }
    
</script>