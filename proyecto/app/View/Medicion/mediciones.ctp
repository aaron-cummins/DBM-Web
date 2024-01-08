<?php
        $paginator = $this->Paginator;
	$inicio = $limit * ($paginator->current() - 1) + 1;
	$termino = $inicio + $limit - 1;
	if ($termino > $paginator->params()['count']) {
		$termino = $paginator->params()['count'];
	}
	
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
				<form action="" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Faena</label>
                                                                        <select class="form-control" name="faena_id" id="faena_id" required="required"> 
										<option value="">Todos</option>
										<?php foreach($faenas as $key => $value) { ?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php }?>
									</select>
								</div>
							</div>
							
							<div class="col-md-4">
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
							
							<div class="col-md-4">
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
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Componente</label>
                                                                        <select class="form-control" name="componente_id" id="componente_id" required=""> 
										<option value="">Todos</option>
										<?php foreach($componentes as $key => $value) { ?>
											<option value="<?php echo $value["MedicionComponente"]["id"];?>" <?php echo $componente_id == $value["MedicionComponente"]["id"] ? "selected=\"selected\"" : ""; ?>><?php echo $value["MedicionComponente"]["nombre"];?> <?php echo $value["TipoAdmision"]["nombre"];?> <?php echo $value["TipoEmision"]["nombre"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Fecha desde</label>
									<input type="date" name="fecha_ini" id="fecha_ini" class="form-control" value="<?php echo $fecha_ini;?>" required="required" />
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label>Fecha hasta</label>
									<input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo $fecha_fin;?>" required="required" />
								</div>
							</div>
							
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Medicion/mediciones';">Limpiar</button>
						<button type="submit" class="btn blue"><i class="fa fa-filter"></i> Aplicar</button>
						<button type="submit" class="btn green" name="btn-descargar"><i class="fa fa-file-excel-o"></i> Descargar Excel</button>

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
			<span class="caption-subject bold uppercase">Mediciones</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="" class="horizontal-form" method="post">
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
                                    <?php if($this->Session->read("PermisosCargos")[$this->Session->read('faena_id')][0] == 4) {?>
                                        <div class="row">
						<div class="col-md-12">
							<div class="dt-buttons">
								<a class="dt-button buttons-print btn blue btn-outline" tabindex="0" aria-controls="table_1" href="/Medicion/agregar"><i class="fa fa-plus"></i> <span>Nueva medición</span></a>
							</div>
						</div>
					</div>
                                    <?php } ?>
					<div class="row">
						<div class="col-md-12"> 
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th style="width: 50px;">Cód.</th>
										<th>Faena</th>
										<th>Flota</th>
										<th>Unidad</th>
										<th>ESN</th>
										<th>Medición</th>
										<th>Fecha medición</th>
										<th>PS</th>
										<th>Componente</th>
										<th>Posición</th>
										<th>Usuario</th>
										<th>val. PS</th>
										<th>Intervención (MP)</th>

										<?php if ($cargo == 4) {
											echo '<td></td>';
										}?>
                                                                                
									</tr>
								<?php
								//echo "<pre>";
								//print_r($registros);
								//echo "<pre>";
									foreach( $registros as $registro ) {


										if ($registro[0]['ps'] == '1') {
											echo '<tr style="background-color: black; color: white;">';
										} elseif ($registro[0]['valido'] == 'SI') {
											echo '<tr style="background-color: #C5D9F1; color: black;">';
										} else {
											echo "<tr>";
										}

										echo "<td>{$registro[0]['id']}</td>";
										echo "<td>{$registro[0]['faena']}</td>";
										echo "<td>{$registro[0]['flota']}</td>";
										echo "<td>{$registro[0]['unidad']}</td>";
										echo "<td>{$registro[0]['esn_placa']}</td>";
										echo "<td>{$registro[0]['medicion']}</td>";
										echo "<td>{$registro[0]['fecha']}</td>";

										echo "<td>";
										if ($registro[0]['ps'] == '1') {
											echo 'SI';
										} else {
											echo 'NO';
										}
										echo "</td>";

										echo "<td>{$registro[0]['nombre']}</td>";
										echo "<td>{$registro[0]['posicion']}</td>";
										echo "<td>{$registro[0]['usuario']}</td>";
										echo "<td>" . (isset($registro[0]['val_ps']) ? $registro[0]['val_ps'] : $registro[0]['medicion_inicial']) . "</td>";
										echo "<td>{$registro[0]['correlativo_intervencion']}</td>";

										if ($cargo == 4) {
											echo "<td>";
											echo '<button type="button" class="btn btn-sm red tooltips" onclick="del(' . $registro[0]['id'] . ');" data-placement="top" data-original-title="Eliminar"><i class="fa fa-trash"></i></button>';
											echo '<a class="btn btn-sm blue tooltips" data-toggle="modal" href="#editar_med' . $registro[0]['id'] . '" data-container="body" data-placement="top" data-original-title="Editar"><i class="fa fa-pencil"></i> </a>';
											echo '</td>';
										}
										echo "</tr>";


										echo '<div class="modal fade" id="editar_med' . $registro[0]['id'] . '" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
												
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
															<h4 class="modal-title">Editar Medición </h4>
														</div>
											
												<div class="modal-body"> ';
										$fecha = new DateTime($registro[0]['fecha']);
										echo '<form action="" id="f' . $registro[0]['id'] . '" class="horizontal-form"  method="get">
												<div class="form-group">
													<div class="row">
														<label for="fecha" class="col-md-4 control-label">Fecha</label>
														<div class="col-md-8">
															
															<input name="fecha_new' . $registro[0]['id'] . '" class="form-control" required="required" type="datetime-local" id="fecha_new' . $registro[0]['id'] . '" value="'. $fecha->format('Y-m-d')."T".$fecha->format('H:i:s') .'" />
														</div>
													</div>
												</div>	

												<div class="form-group">
													<div class="row">
														<input type="hidden" name="id_medicion' . $registro[0]['id'] . '" id="id_medicion' . $registro[0]['id'] . '" value="' . $registro[0]['id'] . '">
														<label for="fecha" class="col-md-4 control-label">Nueva medición</label>
														<div class="col-md-8">
															<input name="medicion_new' . $registro[0]['id'] . '" class="form-control" required="required" type="text" id="medicion_new' . $registro[0]['id'] . '" value="' . $registro[0]['medicion'] . '" />
														</div>
													</div>
												</div>															
											</form>';
										echo ' </div>
													<div class="modal-footer">
														<button type="button" class="btn dark btn-outline" data-dismiss="modal">Cerrar</button>
														<button type="button" class="btn green btn-outline" onclick="enviaForm(' . $registro[0]['id'] . ')">Actualizar</button>
													</div>
												</div>
										</div>';
									}?>
								</table>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5 col-sm-12">
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
			</div>
		</form>
	</div>
</div>

<script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function del(id){
        if(confirm("Va elimninar esta medición, ¿esta seguro?")){
            window.location.href  = "/Medicion/eliminarmedicion/"+id+"?faena_id="+$("#faena_id").val()+"&flota_id="+$("#flota_id").val()+"&unidad_id="+$("#unidad_id").val()+"&componente_id="+$("#componente_id").val()+"&fecha_ini="+$("#fecha_ini").val()+"&fecha_fin="+$("#fecha_fin").val()+"";
        }else{
            return false;
        }
    }

	function enviaForm(id){
		const valores = window.location.search;

		//nueva fecha
		if($("#fecha_new"+id).val() == ""){
			alert("Debe ingresar una fecha para actualizar la medición.")
			return false;
		}
		if($("#medicion_new"+id).val() == "" || $("#medicion_new"+id).val() == 0){
			alert("Debe ingresar una medicón la bitácora.")
			return false;
		}

		if(confirm("¿Esta seguro de modificar esta medición?")){
			window.location.href = '/Medicion/mediciones'+valores+'&id='+id+'&'+'fecha_new='+$("#fecha_new"+id).val()+'&'+'medicion_new='+$("#medicion_new"+id).val();
		}

	}
</script>
