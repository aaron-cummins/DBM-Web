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
				<form action="/Matrices/Motor_new" class="horizontal-form" method="get">
					<div class="form-body">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Motor</label>
									<select class="form-control" name="motor_id">
										<option value="">Todos</option>
										<?php
											foreach ($motores as $motor) { 
												echo "<option value=\"{$motor["Motor"]["id"]}\" ".(($motor_id==$motor["Motor"]["id"]) ? 'selected="selected"' : '').">{$motor["Motor"]["nombre"]} {$motor["TipoEmision"]["nombre"]}</option>"."\n"; 
											}
										?>									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Sistema</label>
									<select class="form-control" name="sistema_id">
										<option value="">Todos</option>
										<?php
											foreach ($sistemas as $sistema) { 
												echo "<option value=\"{$sistema["Sistema"]["id"]}\" ".(($sistema_id==$sistema["Sistema"]["id"]) ? 'selected="selected"' : '').">{$sistema["Sistema"]["nombre"]}</option>"."\n"; 
											}
										?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Subsistema</label>
									<select class="form-control" name="subsistema_id">
										<option value="">Todos</option>
										<?php
											foreach ($subsistemas as $subsistema) { 
												echo "<option value=\"{$subsistema["Subsistema"]["id"]}\" ".(($subsistema_id==$subsistema["Subsistema"]["id"]) ? 'selected="selected"' : '').">{$subsistema["Subsistema"]["nombre"]}</option>"."\n"; 
											}
										?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Posición Subsistema</label>
									<select class="form-control" name="posicion_subsistema_id">
										<option value="">Todos</option>
										<?php
											foreach ($posiciones_subsistemas as $posicion_subsistema) { 
												echo "<option value=\"{$posicion_subsistema["Posiciones_Subsistema"]["id"]}\" ".(($posicion_subsistema_id==$posicion_subsistema["Posiciones_Subsistema"]["id"]) ? 'selected="selected"' : '').">{$posicion_subsistema["Posiciones_Subsistema"]["nombre"]}</option>"."\n"; 
											}
										?>
									</select> 
								</div>
							</div>
							<!--
							<div class="col-md-4">
								<div class="form-group">
									<label>Nº ID</label>
									<input type="text" class="form-control" name="codigo" value="<?php echo $codigo;?>" /> 
								</div>
							</div>
							-->
							<div class="col-md-4">
								<div class="form-group">
									<label>Elemento</label>
									<select class="form-control" name="elemento_id">
										<option value="">Todos</option>
										<?php
											foreach ($elementos as $elemento) { 
												echo "<option value=\"{$elemento["Elemento"]["id"]}\" ".(($elemento_id==$elemento["Elemento"]["id"]) ? 'selected="selected"' : '').">{$elemento["Elemento"]["nombre"]}</option>"."\n"; 
											}
										?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Posición Elemento</label>
									<select class="form-control" name="posicion_elemento_id">
										<option value="">Todos</option>
										<?php
											foreach ($posiciones_elementos as $posicion_elemento) { 
												echo "<option value=\"{$posicion_elemento["Posiciones_Elemento"]["id"]}\" ".(($posicion_elemento_id==$posicion_elemento["Posiciones_Elemento"]["id"]) ? 'selected="selected"' : '').">{$$posicion_elemento["Posiciones_Elemento"]["nombre"]}</option>"."\n"; 
											}
										?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Diagnóstico</label>
									<select class="form-control" name="subsistema">
										<option value="">Todos</option>
										<?php
										foreach ($diagnosticos as $var) { 
											echo "<option value=\"{$var["Diagnostico"]["id"]}\" ".(($diagnostico_id==$var["Diagnostico"]["id"]) ? 'selected="selected"' : '').">{$var["Diagnostico"]["nombre"]}</option>"."\n"; 
										}
									?>
									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Pool</label>
									<select class="form-control" name="pool">
										<option value="">Todos</option>
										<option value="1"<?php echo @$pool == '1' ? "selected=\"selected\"" : ""; ?>>SI</option>
										<option value="0"<?php echo @$pool == '0' ? "selected=\"selected\"" : ""; ?>>NO</option>
									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<label>Estado</label>
									<select class="form-control" name="estado">
										<option value="">Todos</option>
										<option value="1"<?php echo @$estado == '1' ? "selected=\"selected\"" : ""; ?>>Activo</option>
										<option value="0"<?php echo @$estado == '0' ? "selected=\"selected\"" : ""; ?>>Inactivo</option>
									</select> 
								</div>
							</div>
							
							<div class="col-md-4">
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
						<button type="button" class="btn default" onclick="window.location='/Matrices/Motor_new';">Limpiar</button>
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
			<span class="caption-subject bold uppercase">Matriz Motor</span>
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
								<a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="/Matrices/Motor_Xls?<?php echo $query_string;?>"><i class="fa fa-plus"></i> <span>Exportar XLS</span></a>
								<a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="/Matrices/MotorAgregar"><i class="fa fa-plus"></i> <span>Nuevo</span></a>
								<button type="submit" class="btn blue">
									<i class="fa fa-save"></i> Guardar </button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<?php echo "<th style=\"width: 30px;\">#</th>";?>
										<?php echo "<th>" . $paginator->sort('Motor.nombre', 'Motor') . "</th>";?>
										<?php echo "<th>" . $paginator->sort('Sistema.nombre', 'Sistema') . "</th>";?>
										<?php echo "<th>" . $paginator->sort('Subsistema.nombre', 'Subsistema') . "</th>";?>
										<?php echo "<th>" . $paginator->sort('Sistema_Subsistema_Motor_Elemento.codigo', 'ID') . "</th>";?>
										<?php echo "<th>" . $paginator->sort('Elemento.nombre', 'Elemento') . "</th>";?>
										<?php echo "<th>Posición<br />Subsistema</th>";?>
										<?php echo "<th>Posición<br />Elemento</th>";?>
										<?php echo "<th>Diagnóstico</th>";?> 
										
										<?php echo "<th>" . $paginator->sort('Sistema_Subsistema_Motor_Elemento.Pool', 'Pool') . "</th>";?>
										<?php echo "<th>Imagen</th>";?>
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
										<?php echo "<th></th>";?>
									</tr>
									<?php 
									$i = $inicio;
									foreach ($registros as $registro) {
										echo "<input type=\"hidden\" name=\"registro[{$registro['Sistema_Subsistema_Motor_Elemento']['id']}]\" value=\"{$registro['Sistema_Subsistema_Motor_Elemento']['e']}\">";
										echo "<input type=\"hidden\" name=\"registro_pool[{$registro['Sistema_Subsistema_Motor_Elemento']['id']}]\" value=\"{$registro['Sistema_Subsistema_Motor_Elemento']['pool']}\">";
									?>
										<tr>
											<td><?php echo $i++;?></td>
											<td><?php echo $registro["Motor"]["nombre"];?></td>
											<td><?php echo $registro["Sistema"]["nombre"];?></td>
											<td><?php echo $registro["Subsistema"]["nombre"];?></td>
					                        <td><?php echo $registro["Sistema_Subsistema_Motor_Elemento"]["codigo"];?></td>
					                        <td><?php echo $registro["Elemento"]["nombre"];?></td>
											<td align="center">
						                        <a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="Motor_posicion_subsistema/<?php echo $registro['Sistema_Subsistema_Motor_Elemento']['id'];?>"><i class="fa fa-file-text-o"></i><span></span></a>
					                        </td>
											<td align="center">
						                        <a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="Motor_posicion_elemento/<?php echo $registro['Sistema_Subsistema_Motor_Elemento']['id'];?>"><i class="fa fa-file-text-o"></i><span></span></a>
					                        </td>
											<td align="center">
						                        <a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="Motor_diagnostico/<?php echo $registro['Sistema_Subsistema_Motor_Elemento']['id'];?>"><i class="fa fa-file-text-o"></i><span></span></a>
					                        </td>
					                        <?php
												echo "<td align=\"center\" style=\"width: 60px;\">";
												echo '<div class="form-group" style="margin-bottom: 0;">';
												echo '<div class="mt-checkbox-inline">';
												echo '<label class="mt-checkbox mt-checkbox-outline">';
												if($registro['Sistema_Subsistema_Motor_Elemento']['pool'] == '1') {
													echo "<input type=\"checkbox\" name=\"pool[{$registro['Sistema_Subsistema_Motor_Elemento']['id']}]\" value=\"1\" checked=\"checked\" />";
												} else {
													echo "<input type=\"checkbox\" name=\"pool[{$registro['Sistema_Subsistema_Motor_Elemento']['id']}]\" value=\"1\" />";
												}
												echo "<span></span>";
												echo "</label>";
												echo '</div>';
												echo '</div>';
												echo "</td>";	
											?>
											<td align="center">
												<a class="dt-button buttons-print btn dark btn-outline" tabindex="0" aria-controls="table_1" href="MotorImagen/<?php echo $registro['Motor']['id']."/".$registro['Sistema']['id']."/".$registro['Subsistema']['id'];?>"><i class="fa fa-image"></i><span></span></a>
											</td>
											<?php
												echo "<td align=\"center\" style=\"width: 60px;\">";
												echo '<div class="form-group" style="margin-bottom: 0;">';
												echo '<div class="mt-checkbox-inline">';
												echo '<label class="mt-checkbox mt-checkbox-outline">';
												if($registro['Sistema_Subsistema_Motor_Elemento']['e'] == '1') {
													
													echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['Sistema_Subsistema_Motor_Elemento']['id']}]\" value=\"1\" checked=\"checked\" />";
												} else {
													echo "<input type=\"checkbox\" class=\"check-option\" name=\"estado[{$registro['Sistema_Subsistema_Motor_Elemento']['id']}]\" value=\"1\" />";
												}
												echo "<span></span>";
												echo "</label>";
												echo '</div>';
												echo '</div>';
												echo "</td>";	
											?>
											
											<td align="center">
											<?php echo $this->Html->link("Editar", array('action' => 'MotorEditar', $registro['Sistema_Subsistema_Motor_Elemento']['id']), array('class' => 'btn btn-sm blue')); ?>
											</td>
										</tr>
									<?php } ?>
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