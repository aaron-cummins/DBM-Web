<?php
$paginator = $this->Paginator;
$inicio = $limit * ($paginator->current() - 1) + 1;
$termino = $inicio + $limit - 1;
if ($termino > $paginator->params()['count']) {
	$termino = $paginator->params()['count'];
}
$faena_session = $this->Session->read("faena_id");
$usuario_id = $this->Session->read('usuario_id');
$cargos = $this->Session->read("PermisosCargos");

App::import('Controller', 'Utilidades');
$app = new UtilidadesController();
?>
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
				<form action="/Backlog" class="horizontal-form" method="get" id="form_get_backlog" onSubmit="return fechas();">
					<div class="form-body">
						<div class="col-lg-12" id="alerta_fechas" style="display: none">
							<div class="alert alert-danger">
								<strong>Fechas: </strong>  <span class="mensaje_fechas"></span>
							</div>
						</div>
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
									<label>Lugar creación</label>
									<select class="form-control" name="creacion_id" id="creacion_id">
										<option value="" selected="selected">Todos</option>
										<?php
										foreach($lugarcreacion as $key => $value) { ?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php } ?>

										<!--<option value="1"<?php echo @$creacion_id == '1' ? "selected=\"selected\"" : ""; ?>>Web</option>
										<option value="2"<?php echo @$creacion_id == '2' ? "selected=\"selected\"" : ""; ?>>Specto</option>
										<option value="3"<?php echo @$creacion_id == '3' ? "selected=\"selected\"" : ""; ?>>Software</option>-->
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Criticidad</label>
									<select class="form-control" name="criticidad_id" id="criticidad_id">
										<option value="" selected="selected">Todos</option>
										<option value="1"<?php echo @$criticidad_id == '1' ? "selected=\"selected\"" : ""; ?>>Alto</option>
										<option value="2"<?php echo @$criticidad_id == '2' ? "selected=\"selected\"" : ""; ?>>Medio</option>
										<option value="3"<?php echo @$criticidad_id == '3' ? "selected=\"selected\"" : ""; ?>>Bajo</option>
									</select>
								</div>
							</div>
							<!--/span-->
							<div class="col-md-2">
								<div class="form-group">
									<label>Responsable</label>
									<select class="form-control" name="responsable_id" id="responsable_id" >
										<option value="">Todos</option>
										<option value="1"<?php echo @$responsable_id == '1' ? "selected=\"selected\"" : ""; ?>>DCC</option>
										<option value="2"<?php echo @$responsable_id == '2' ? "selected=\"selected\"" : ""; ?>>OEM</option>
										<option value="3"<?php echo @$responsable_id == '3' ? "selected=\"selected\"" : ""; ?>>MINA</option>
									</select>
								</div>
							</div>
							<div class="col-md-2 ">
								<div class="form-group">
									<label>Fecha inicio</label>
									<input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio;?>" max="<?php echo date("Y-m-d");?>" /> </div>
							</div>
							<div class="col-md-2 ">
								<div class="form-group">
									<label>Fecha término;</label>
									<input type="date" name="fecha_termino" id="fecha_termino" class="form-control" value="<?php echo $fecha_termino;?>" max="<?php echo date("Y-m-d");?>" /> </div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Sistema</label>
									<select class="form-control" name="sistema_id" id="sistema_id" >
										<option value="">Todos</option>
										<?php foreach($sistemas as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo @$sistema_id == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
										<?php }?>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Estado</label>
									<select class="form-control" name="estado_id" id="estado_id">
										<option value="" selected="selected">Todos</option>
										<option value="8" <?php echo @$estado_id == 8 ? "selected=\"selected\"" : ""; ?>>Pendiente</option>
										<option value="2" <?php echo @$estado_id == 2 ? "selected=\"selected\"" : ""; ?>>Planificado</option>
										<option value="11" <?php echo @$estado_id == 11 ? "selected=\"selected\"" : ""; ?>>Realizado</option>
										<option value="10" <?php echo @$estado_id == 10 ? "selected=\"selected\"" : ""; ?>>Eliminado</option>

										<option value="8_2" <?php echo @$estado_id == '8_2' ? "selected=\"selected\"" : ""; ?>>Pendiente / Planificado</option>
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label>Tipo</label>
									<select class="form-control" name="tipointervencion" id="tipointervencion">
										<option value="" selected="selected">Todos</option>
										<option value="MP"<?php echo @$tipointervencion == 'MP' ? "selected=\"selected\"" : ""; ?>>MP</option>
										<option value="RP"<?php echo @$tipointervencion == 'RP' ? "selected=\"selected\"" : ""; ?>>RP</option>
										<option value="OP"<?php echo @$tipointervencion == 'OP' ? "selected=\"selected\"" : ""; ?>>OP</option>
										<option value="EX"<?php echo @$tipointervencion == 'EX' ? "selected=\"selected\"" : ""; ?>>EX</option>
										<option value="RI"<?php echo @$tipointervencion == 'RI' ? "selected=\"selected\"" : ""; ?>>RI</option>
									</select>
								</div>
							</div>
							<!--/span-->


							<!--/span-->
							<div class="col-md-2 ">
								<div class="form-group">
									<label>Correlativo</label>
									<input type="text" name="correlativo_" id="correlativo_" class="form-control" value="<?php echo @$correlativo;?>"> </div>
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

							<div class="col-md-2 ">
								<div class="form-group">
									<label>Folio</label>
									<input type="text" name="folio" id="folio" class="form-control" value="<?php echo @$folio;?>"> </div>
							</div>


							<div class="col-md-2">
								<div class="form-group">
									<label>Plan de acción</label>
									<select class="form-control" name="plan_accion" id="plan_accion">
										<option value="" selected="selected">Todos</option>
										<?php foreach ($planaccion as $key => $value) { ?>
											<option value="<?php echo $value["PlanAccion"]["id"]; ?>" <?php echo $plan_accion == $value["PlanAccion"]["id"] ? 'selected="selected"':''; ?> ><?php echo $value["PlanAccion"]["id"] . " - " . $value["PlanAccion"]["nombre"]; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>

						</div>
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Backlog';">Limpiar</button>
						<button type="submit" class="btn blue btn-submit-alert">
							<i class="fa fa-filter"></i> Aplicar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>

<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Backlogs</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="" class="horizontal-form" method="post" id="form-backlogs">
			<input type="hidden" name="correlativo" class="form-control" id="correlativo" />
			<input type="hidden" name="motivo_desactivado" class="form-control" id="motivo_desactivado" />
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="dt-buttons">
								<!--<a class="dt-button buttons-print btn dark btn-outline backlog_agregar_specto" tabindex="0" aria-controls="table_1"><i class="fa fa-plus"></i> <span>Specto</span></a>-->
								<a class="dt-button buttons-print btn dark btn-outline backlog_agregar_web" tabindex="0" aria-controls="table_1"><i class="fa fa-plus"></i> <span>Web</span></a>
								<?php
								if(@$query) {?>
									<a class="dt-button buttons-print btn dark btn-outline btn-submit-alert" tabindex="0" aria-controls="table_1" href="/Backlog/Descargar/Excel?<?php echo @$query;?>"><i class="fa fa-save"></i> <span>Excel</span></a>
								<?php }else { ?>
									<a class="dt-button buttons-print btn dark btn-outline btn-submit-alert" disabled="disabled" tabindex="0" aria-controls="table_1" href="#"><i class="fa fa-save"></i> <span>Excel</span></a>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<?php echo "<th>" . $paginator->sort('Backlog.id', 'Folio') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Faena.nombre', 'Faena') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Flota.nombre', 'Flota') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Unidad.unidad', 'Unidad') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Backlog.fecha_creacion', 'Fecha') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('LugarCreacion.nombre', 'Lugar') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Usuario.u', 'Creador') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Criticidad.nombre', 'Criticidad') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('ResponsableBacklog.nombre', 'Responsable') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Sistema.nombre', 'Sistema') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Estado.nombre', 'Estado') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Backlog.tiempo_estimado', 'Tiempo') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('PlanAccion.nombre', 'P. Acción') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Intervencion.tipointervencion', 'Tipo') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Intervencion.correlativo_final', 'Correlativo') . "</th>"; ?>
										<th>Fec. Intervención</th>
										<th colspan="3" align="center">Acciones</th>
										<?php if($app->check_permissions_role(6, $faena_session, $usuario_id, $cargos) == false) {?>
											<th>Selección</th>
										<?php } ?>
									</tr>
									<?php
									$i = 1;
									foreach( $registros as $registro ) {
										$registro['Backlog']['fecha_creacion'] = strtotime($registro['Backlog']['fecha_creacion']);
										$registro['Backlog']['fecha_creacion'] = date("d-m-Y", $registro['Backlog']['fecha_creacion']);
										$tiempo_estimado = $registro["Backlog"]["tiempo_estimado"];
										$registro["Backlog"]["tiempo_estimado_hora"] = str_pad(floor($tiempo_estimado / 60), 2, "0", STR_PAD_LEFT);
										$registro["Backlog"]["tiempo_estimado_minuto"] = str_pad(($tiempo_estimado % 60), 2, "0", STR_PAD_LEFT);

										if(isset($registro['Intervencion']['fecha'])){
											$registro['Intervencion']['fecha'] = date("d-m-Y", strtotime($registro['Intervencion']['fecha']));
										}

										echo "<tr>";
										echo "<td>{$registro['Backlog']['id']}</td>";
										echo "<td>{$registro['Faena']['nombre']}</td>";
										echo "<td>{$registro['Flota']['nombre']}</td>";
										echo "<td>{$registro['Unidad']['unidad']}</td>";
										echo "<td>{$registro['Backlog']['fecha_creacion']}</td>";
										echo "<td>{$registro['LugarCreacion']['nombre']}</td>";
										echo "<td>{$registro['Usuario']['u']}</td>";
										echo "<td>{$registro['Criticidad']['nombre']}</td>";
										echo "<td>{$registro['ResponsableBacklog']['nombre']}</td>";
										echo "<td>{$registro['Sistema']['nombre']}</td>";
										echo "<td>{$registro['Estado']['nombre']}</td>";
										echo "<td>{$registro['Backlog']['tiempo_estimado_hora']}:{$registro['Backlog']['tiempo_estimado_minuto']}</td>";
										echo "<td>".$registro['PlanAccion']['id']. " - " . $registro['PlanAccion']['nombre'] ."</td>";
										echo "<td>{$registro['Intervencion']['tipointervencion']}</td>";
										echo "<td>{$registro['Intervencion']['correlativo_final']}</td>";
										echo "<td>{$registro['Intervencion']['fecha']}</td>";
										echo "<td>";
										echo '<a class="btn btn-sm red tooltips" data-toggle="modal" href="#modal_'.$registro['Backlog']['id'].'" data-container="body" data-placement="top" data-original-title="Comentarios"><i class="fa fa-comments"></i>  </a>';
										echo '<div class="modal fade" id="modal_'.$registro['Backlog']['id'].'" tabindex="-1" role="basic" aria-hidden="true" style="display: none;background-color:  transparent;;border:  0;box-shadow: none;">
												<div class="modal-dialog" style="width: 500px !important">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
															<h4 class="modal-title">Comentario Backlog</h4>
														</div>
														<div class="modal-body"> ';
										if ($registro['Backlog']['comentario'] != null && strlen($registro['Backlog']['comentario']) > 0) {
											echo "<div class=\"row\"><div class=\"col-md-12\">{$registro['Backlog']['comentario']}</div></div>";
											$hay_comentarios = true;
										}
										if ($registro['Backlog']['estado_id'] == 10 && strlen($registro['Backlog']['motivo_desactivado']) > 0) {
											echo '<hr><h4 class="modal-title">Motivo Desactivación de Backlog</h4><br>';
											echo "<div class=\"row\"><div class=\"col-md-12\">{$registro['Backlog']['motivo_desactivado']}</div></div>";
											$hay_comentarios = true;
										}
										if (!$hay_comentarios) {
											echo "<div class=\"row\"><div class=\"col-md-12\">No hay comentarios ingresados</div></div>";
										}
										echo ' </div>
														<div class="modal-footer">
															<button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
														</div>
													</div>
													<!-- /.modal-content -->
												</div>
												<!-- /.modal-dialog -->
											</div>';
										echo "</td>";


										echo "</td>";

										echo "<td>";
										/*if($registro['Backlog']["creacion_id"] == "2"){
											echo '<a class="btn btn-sm blue tooltips"  data-toggle="modal" href="#modal_edita_'.$registro['Backlog']['id'].'" data-container="body" data-placement="top" data-original-title="Editar Backlog Specto"><i class="fa fa-edit"></i>  </a>';
                                                                                        echo '<div class="modal fade" id="modal_edita_'.$registro['Backlog']['id'].'" tabindex="-1" role="basic" aria-hidden="true" style="display: none;background-color:  transparent;;border:  0;box-shadow: none;">
												<div class="modal-dialog" style="width: 500px !important">
													<div class="modal-content">
														<div class="modal-body">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
															<h4 class="modal-title">Editar Backlog</h4>
                                                                                                                        <br>
                                                                                                                        Seleccione formulario a utilizar
														</div>
														<div class="modal-footer">
                                                                                                                    <button type="button" class="btn red btn-outline" data-dismiss="modal">Cerrar</button>
                                                                                                                    <a class="btn btn green tooltips" href="/Backlog/Specto/'.$registro['Backlog']['id'].'" data-container="body" data-placement="top" data-original-title="Editar backlog specto">Backlog Specto</a>
                                                                                                                    <a class="btn btn blue tooltips" href="/Backlog/Web/'.$registro['Backlog']['id'].'" data-container="body" data-placement="top" data-original-title="Editar backlog web">Backlog Web</a>
														</div>
													</div>
													<!-- /.modal-content -->
												</div>
												<!-- /.modal-dialog -->
											</div>';
										echo "</td>";
											//echo $this->Html->link("Editar", array('controller' => 'Backlog', 'action' => 'Specto', $registro['Backlog']['id']), array('class' => 'btn btn-sm blue'));
										} else {*/
										echo '<a class="btn btn-sm blue tooltips" href="/Backlog/Web/'.$registro['Backlog']['id'].'" data-container="body" data-placement="top" data-original-title="Editar backlog"><i class="fa fa-edit"></i>  </a>';
										//echo $this->Html->link("Editar", array('controller' => 'Backlog', 'action' => 'Web', $registro['Backlog']['id']), array('class' => 'btn btn-sm blue'));
										//}
										echo "</td>";



										echo "<td>";
										if($registro['Backlog']['estado_id'] == 8) {
											//echo $this->Html->link("Detalle", array('controller' => 'Trabajo', 'action' => 'Previsualizar', $registro['Planificacion']['id']), array('class' => 'btn btn-sm blue'));
											echo '<a class="btn btn-sm green tooltips" href="/Trabajo/Planificar/Backlog/'.$registro['Backlog']['id'].'" data-container="body" data-placement="top" data-original-title="Planificar backlog"><i class="fa fa-calendar-check-o"></i>  </a>';
											//echo $this->Html->link("Planificar", array('controller' => 'Trabajo', 'action' => 'Planificar' ,"Backlog", $registro['Backlog']['id']), array('class' => 'btn btn-sm blue'));
										}
										echo "</td>";
									if ($app->check_permissions_role(6, $faena_session, $usuario_id, $cargos) == false) {
										echo "<td align=\"center\">";
										if ($registro['Backlog']['estado_id'] == 8) {
											echo '<div class="form-group" style="margin-bottom: 0;">';
											echo '<div class="mt-checkbox-inline">';
											echo '<label class="mt-checkbox mt-checkbox-outline">';
											echo "<input type=\"checkbox\" name=\"backlog[]\" value=\"{$registro['Backlog']['id']}\" />";
											echo "<span></span>";
											echo "</label>";
											echo '</div>';
											echo '</div>';
										}
										echo "</td>";
									}
										$i++;
										echo "</tr>";
									}
									?>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<input type="hidden" class="trabajos" value="<?php echo ($i - 1);?>" />
							<div class="dataTables_info" id="table_1_info" role="status" aria-live="polite">Mostrando del <?php echo $inicio;?> a <?php echo $termino;?> de <?php echo $paginator->params()['count'];?> registros</div>
						</div>
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
					</div>
				</div>
				<div id="static_desactivar" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
					<div class="modal-body">
						<p> Se desactivará el backlog o los backlogs selecionados, ingrese motivo: </p>
						<div class="alert alert-danger" id="alerta_desactiva" style="display: none;">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
							</button>
							<strong><i class="fa fa-exclamation"></i> ALERTA! </strong>
							Debe ingresar un motivo por el cual desactiva el backlog.
						</div>
						<p> <textarea name="" class="form-control" id="motivo_desactivado_" minlength="5"></textarea> </p>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" onclick="$('#alerta_desactiva').hide();" class="btn btn-outline red">Cancelar</button>
						<button type="button" class="btn green btn-submit3">Aceptar</button>
					</div>
				</div>


				<div id="static_registrado" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
					<div class="modal-body">
						<p> Ingrese correlativo en donde se registró el o los backlog seleccionados: </p>
						<div id="alerta_corr" class="alert alert-danger" style="display: none;">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<strong><i class="fa fa-times"></i> Error!</strong> Debe ingresar un correlativo.
						</div>
						<p> <input type="number" min="1000" name="correlativo_cierra" class="form-control" id="correlativo_cierra" required="required" /> </p>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" onclick="$('#alerta_corr').hide();" class="btn btn-outline red">Cancelar</button>
						<button type="submit" class="btn green btn-submit2">Aceptar</button>
					</div>
				</div>
				<div id="static_pregunta_specto" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
					<div class="modal-body">
						<p> ¿Desea ingresar un backlog Specto a través de un formulario o subir un archivo? </p>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-outline red">Cancelar</button>
						<button type="button" data-dismiss="modal" class="btn green" onclick="window.location='/Backlog/Specto/';">Ingresar un backlog</button>
						<button type="button" data-dismiss="modal" class="btn green" onclick="window.location='/CargaMasiva/BacklogSpecto/';">Subir archivo</button>
					</div>
				</div>
				<div id="static_pregunta_web" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
					<div class="modal-body">
						<p> ¿Desea ingresar un backlog Web a través de un formulario o subir un archivo? </p>
					</div>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-outline red">Cancelar</button>
						<button type="button" data-dismiss="modal" class="btn green" onclick="window.location='/Backlog/Web/';">Ingresar un backlog</button>
						<button type="button" data-dismiss="modal" class="btn green" onclick="window.location='/CargaMasiva/BacklogWeb/';">Subir archivo</button>
					</div>
				</div>
				<div class="form-actions right">
					<?php if ($app->check_permissions_role(6, $faena_session, $usuario_id, $cargos) == false) { ?>
						<button type="button" class="btn grey backlog_desactivar"><i class="fa fa-save"></i> Desactivar </button>
						<button type="button" class="btn blue backlog_registrado"><i class="fa fa-save"></i> Registrado </button>
					<?php } ?>
				</div>
			</div>
		</form>
	</div>
</div>

<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

	$("#fecha_inicio, #fecha_termino, #faena_id, #correlativo_, #folio").change(() => {
		fechas();
	});

	$("#fecha_inicio, #fecha_termino, #faena_id, #correlativo_, #folio").blur(() => {
		fechas();
	});
	function fechas() {
		var dia_milisegundos = 86400000;
		var correlaivo = $("#correlativo_").val();
		var folio = $("#folio").val();
		var faena = $("#faena_id").val();

		let ini = new Date($("#fecha_inicio").val());
		let fin = new Date($("#fecha_termino").val());

		if(ini > fin) {
			document.getElementById("alerta_fechas").style.display = "block";
			$(".mensaje_fechas").text("La fecha de termino no puede ser menor a la de inicio.");
			$(".btn-submit-alert").attr('disabled', 'disabled');
			return false;
		}else{
			document.getElementById("alerta_fechas").style.display = "none";
			$(".btn-submit-alert").removeAttr('disabled');
		}

		let dif_mili = fin - ini;
		let dif_dia = dif_mili / dia_milisegundos;

		if(faena == "" && (correlativo == "" || correlaivo == 0) && (folio == "" || folio == 0)) {
			if (dif_dia > 366) {
				document.getElementById("alerta_fechas").style.display = "block";
				$(".btn-submit-alert").attr('disabled', 'disabled');
				$(".mensaje_fechas").text("La diferencia de fechas no puede ser mayor a 1 año. Esto solo se permite para descargar la información de una faena, por correlativo o folio.");
				return false;
			} else {
				document.getElementById("alerta_fechas").style.display = "none";
				$(".btn-submit-alert").removeAttr('disabled');
			}
		}else{
			document.getElementById("alerta_fechas").style.display = "none";
			$(".btn-submit-alert").removeAttr('disabled');
		}

		return true;
	}
</script>