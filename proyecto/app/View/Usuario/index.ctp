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
									<label>Nombres</label>
									<input name="data[nombres]" class="form-control" type="text" id="nombres" value="<?php echo $nombres; ?>">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Apellidos</label>
									<input name="data[apellidos]" class="form-control" type="text" id="apellidos" value="<?php echo $apellidos; ?>">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Rut</label>
									<input name="data[usuario]" class="form-control" type="number" max="99999999" maxlength="8" min="1" id="usuario" value="<?php echo $usuario; ?>">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>U</label>
									<input name="data[ucode]" class="form-control" type="text" id="ucode" value="<?php echo $ucode; ?>">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Correo electrónico</label>
									<input name="data[correo_electronico]" class="form-control" type="text" id="correo_electronico" value="<?php echo $correo_electronico; ?>">
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Faena</label>
									<select class="form-control" name="data[faena_id]" id="faena_id"> 
										<option value="">Todos</option>
										<?php foreach($faenas as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo @$faena_id == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
										<?php }?>
									</select>
								</div>
							</div>
							
							<div class="col-md-3">
								<div class="form-group">
									<label>Cargo</label>
									<select class="form-control" name="data[cargo_id]" id="cargo_id"> 
										<option value="">Todos</option>
										<?php foreach($cargos as $key => $value) { ?>
											<option value="<?php echo $key;?>" <?php echo @$cargo_id == $key ? "selected=\"selected\"" : ""; ?>><?php echo $value;?></option>
										<?php }?>
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
						<button type="button" class="btn default" onclick="window.location='/Usuario';">Limpiar</button>
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
			<span class="caption-subject bold uppercase">Usuarios</span>
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
								<a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="/Usuario/agregar"><i class="fa fa-plus"></i> <span>Nuevo</span></a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<?php echo "<th style=\"width:50px;\">" . $paginator->sort('Usuario.id', 'Cód.') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Usuario.nombres', 'Nombres') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Usuario.apellidos', 'Apellidos') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Usuario.usuario', 'Rut') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Usuario.u', 'U') . "</th>"; ?>
										<?php echo "<th>" . $paginator->sort('Usuario.correo_electronico', 'Correo electrónico') . "</th>"; ?>
										<th>Faena</th>
										<th>Cargo</th>
										<th align="center">
											<?php
											echo '<div class="form-group" style="margin-bottom: 0;">';
											echo '<div class="mt-checkbox-inline">';
											echo '<label class="mt-checkbox mt-checkbox-outline">';
											echo "<input type=\"checkbox\" class=\"seleccion-masiva\" />";
											echo "<span></span>";
											echo "</label>";
											echo '</div>';
											echo '</div>';
										?>
										</th>
										<th>&nbsp;</th>
									</tr>
								<?php
									foreach( $registros as $registro ) {
										echo "<tr>";
											echo "<td align=\"center\"><input type=\"hidden\" name=\"registro[{$registro['Usuario']['id']}]\" value=\"{$registro['Usuario']['e']}\">";
											echo "{$registro['Usuario']['id']}</td>";
											echo "<td>{$registro['Usuario']['nombres']}</td>";
											echo "<td>{$registro['Usuario']['apellidos']}</td>";
											echo "<td>{$registro['Usuario']['usuario']}</td>";
											echo "<td>{$registro['Usuario']['u']}</td>";
											echo "<td>{$registro['Usuario']['correo_electronico']}</td>";
											//if($faena_id != '') {
											//	echo "<td>{$registro['Faena']['nombre']}</td>";
											//} else {
												// Faenas
												if(isset($listado[$registro['Usuario']['id']]["Faenas"])){
													echo "<td>".implode(", ", $listado[$registro['Usuario']['id']]["Faenas"])."</td>";
												} else {
													echo "<td></td>";
												}
											//}
											//if($cargo_id != '') {
												//echo "<td>{$registro['Cargo']['nombre']}</td>";
											//} else {
												// Cargos
												if(isset($listado[$registro['Usuario']['id']]["Faenas"])){
													echo "<td>".implode(", ", $listado[$registro['Usuario']['id']]["Cargos"])."</td>";
												} else {
													echo "<td></td>";
												}
											//}
											echo "<td align=\"center\" style=\"width: 60px;\">";
											echo '<div class="form-group" style="margin-bottom: 0;">';
											echo '<div class="mt-checkbox-inline">';
											echo '<label class="mt-checkbox mt-checkbox-outline">';
											if($registro['Usuario']['e'] == '1') {
												echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['Usuario']['id']}]\" value=\"1\" checked=\"checked\" />";
											} else {
												echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['Usuario']['id']}]\" value=\"1\" />";
											}
											echo "<span></span>";
											echo "</label>";
											echo '</div>';
											echo '</div>';
											echo "</td>";
											echo "<td align=\"center\" style=\"width: 250px\">";
												echo $this->Html->link("Faena", array('action' => 'Faena', $registro['Usuario']['id']), array('class' => 'btn btn-sm blue'));
												echo $this->Html->link("Cuadro mando", array('action' => 'CuadroMando', $registro['Usuario']['id']), array('class' => 'btn btn-sm blue'));
												echo $this->Html->link("Editar", array('action' => 'Editar', $registro['Usuario']['id']), array('class' => 'btn btn-sm blue'));
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