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
							<div class="col-md-3">
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
							
							<div class="col-md-3">
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
							
							<div class="col-md-3">
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
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Modelo motor</label>
									<select class="form-control" name="motor_id" id="motor_id"> 
										<option value="">Todos</option>
										<?php foreach($motores as $key => $value) { ?>
											<option value="<?php echo $value["Motor"]["id"];?>" <?php echo $motor_id == $value["Motor"]["id"] ? "selected=\"selected\"" : ""; ?>><?php echo $value["Motor"]["nombre"];?> <?php echo $value["TipoAdmision"]["nombre"];?> <?php echo $value["TipoEmision"]["nombre"];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Estado</label>
									<select class="form-control" name="estado">
										<option value="">Todos</option>
										<option value="1"<?php echo @$estado == '1' ? "selected=\"selected\"" : ""; ?>>Activo</option>
										<option value="0"<?php echo @$estado == '0' ? "selected=\"selected\"" : ""; ?>>Inactivo</option>
										<option value="2"<?php echo @$estado == '2' ? "selected=\"selected\"" : ""; ?>>Sin Aprobar</option>
									</select> 
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Resultados por página</label>
									<select class="form-control" name="limit">
										<option value="10" <?php echo $limit == 10 ? "selected=\"selected\"" : ""; ?>>10</option>
										<option value="25" <?php echo $limit == 25 ? "selected=\"selected\"" : ""; ?>>25</option>
										<option value="50" <?php echo $limit == 50 ? "selected=\"selected\"" : ""; ?>>50</option>
										<option value="100" <?php echo $limit == 100 ? "selected=\"selected\"" : ""; ?>>100</option>
									</select> 
								</div>
							</div>
						</div>
						
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Unidad';">Limpiar</button>
						<button type="submit" class="btn blue">
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
			<span class="caption-subject bold uppercase">Unidades</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="" class="horizontal-form" method="post">
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12">
							<div class="dt-buttons">
								<a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="/Unidad/Agregar"><i class="fa fa-plus"></i> <span>Nuevo</span></a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12"> 
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th style="width: 50px;">Cód.</th>
										<th>Faena</th>
										<th>Flota</th>
										<th>Unidad</th>
										<th>Aplicación</th>
										<th>OEM</th>
										<th>Modelo equipo</th>
										<th>Nª serie equipo</th>
										<th>Estado equipo</th>
										<th>Motor</th>
										<th>Tipo admisión</th>
										<th>Tipo emisión</th>
										<th>Modelo motor</th>
										<th>Tipo contrato</th>
										<th>Avance teórico</th>
										<th>&nbsp;</th>
										<th>&nbsp;</th>
									</tr>
								<?php
								//echo "<pre>";
								//print_r($registros);
								//echo "<pre>";
									foreach( $registros as $registro ) {
										echo "<tr>";
											echo "<td align=\"center\"><input type=\"hidden\" name=\"registro[{$registro['Unidad']['id']}]\" value=\"{$registro['Unidad']['e']}\">";
											echo "{$registro['Unidad']['id']}</td>";
											echo "<td>{$registro['Faena']['nombre']}</td>";
											echo "<td>{$registro['Flota']['nombre']}</td>";
											echo "<td>{$registro['Unidad']['unidad']}</td>";
											echo "<td>{$registro['Flota']['aplicacion']}</td>";
											echo "<td>{$registro['Flota']['oem']}</td>";
											echo "<td>{$registro['Unidad']['modelo_equipo']}</td>";
											echo "<td>{$registro['Unidad']['nserie']}</td>";
											echo "<td>{$registro['EstadoEquipo']['nombre']}</td>";
											echo "<td>{$registro['Motor']['nombre']}</td>";
											echo "<td>{$registro['Motor']["TipoAdmision"]['nombre']}</td>";
											echo "<td>{$registro['Motor']["TipoEmision"]["nombre"]}</td>";
											echo "<td>{$registro['Motor']['nombre']} {$registro['Motor']["TipoAdmision"]['nombre']} {$registro['Motor']["TipoEmision"]["nombre"]}</td>";
											echo "<td>{$registro['TipoContrato']['nombre']}</td>";
											echo "<td>{$registro['Unidad']['avance_teorico']}</td>";
											echo "<td align=\"center\" style=\"width: 60px;\">";
											echo '<div class="form-group" style="margin-bottom: 0;">';
											echo '<div class="mt-checkbox-inline">';
											echo '<label class="mt-checkbox mt-checkbox-outline">';
											if($registro['Unidad']['e'] == '1') {
												echo "<input type=\"checkbox\" name=\"estado[{$registro['Unidad']['id']}]\" value=\"1\" checked=\"checked\" />";
											} else {
												echo "<input type=\"checkbox\" name=\"estado[{$registro['Unidad']['id']}]\" value=\"1\" />";
											}
											echo "<span></span>";
											echo "</label>";
											echo '</div>';
											echo '</div>';
											echo "</td>";
											
											
											echo "<td align=\"center\" style=\"width: 80px\">";
												if($registro['Unidad']['e'] == '2') {
													echo $this->Html->link("Aprobar", array('action' => 'Aprobar', $registro['Unidad']['id']), array('class' => 'btn btn-sm blue'));
												} else {
													echo $this->Html->link("Editar", array('action' => 'Editar', $registro['Unidad']['id']), array('class' => 'btn btn-sm blue'));
												}
											echo "</td>";
											
										echo "</tr>";
									}
								?>
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
				<div class="form-actions right">
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
			</div>
		</form>
	</div>
</div>