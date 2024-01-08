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
							<div class="col-md-6">
								<div class="form-group">
									<label>Correlativo</label>
									<input name="correlativo" class="form-control" type="number" id="correlativo" value="<?php echo @$correlativo;?>" min="1000" />
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label>Folio</label>
									<input name="folio" class="form-control" type="number" id="folio" value="<?php echo @$folio;?>" min="1000" />
								</div>
							</div>
						</div>
					</div>
					<div class="form-actions right">
						<button type="button" class="btn default" onclick="window.location='/Administracion/Folio';">Limpiar</button>
						<button type="submit" class="btn blue">
							<i class="fa fa-filter"></i> Aplicar</button>
					</div>
				</form>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>
<?php if (isset($registros) && count($registros) > 0) { ?>
<div class="portlet light">
	<div class="portlet-title">
		<div class="caption font-dark">
			<i class="icon-settings font-dark"></i>
			<span class="caption-subject bold uppercase">Detalle del evento</span>
		</div>
		<div class="tools"> </div>
	</div>
	<div class="portlet-body form">
		<form action="" class="horizontal-form" method="post">
			<div id="table_1_wrapper" class="dataTables_wrapper">
				<div class="form-body">
					<div class="row">
						<div class="col-md-12"> 
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<th style="width: 50px;">Correlativo</th>
										<th>Folio</th>
										<th>Faena</th>
										<th>Flota</th>
										<th>Unidad</th>
										<th>Tipo</th>
										<th>Fecha inicio</th>
										<th>Tiempo</th>
										<th>Estado</th>
										<th>Acción</th>
										<th>Desaprobar</th>
										<th>Borrar</th>
										<th>Planificar</th>
									</tr>
								<?php
									$i = 1;
									foreach( $registros as $registro ) {
										if (isset($folio) && $folio == $registro['Planificacion']['id']) {
											echo "<tr style=\"background-color: #f2dede;\">";
										} else {
											echo "<tr>";
										}
										
										echo "<td align=\"center\">";
										echo "{$registro['Planificacion']['correlativo_final']}</td>";
										echo "<td>{$registro['Planificacion']['id']}</td>";
										echo "<td>{$registro['Faena']['nombre']}</td>";
										echo "<td>{$registro['Flota']['nombre']}</td>";
										echo "<td>{$registro['Unidad']['unidad']}</td>";
										echo "<td>{$registro['Planificacion']['tipointervencion']}</td>";
										echo "<td>{$registro['Planificacion']['fecha']} {$registro['Planificacion']['hora']}</td>";
										echo "<td>{$registro['Planificacion']['tiempo_trabajo']}</td>";
										echo "<td>{$registro['Estado']['nombre']}</td>";
										
										echo "<td align=\"center\" style=\"width: 80px\">";
										//echo $this->Html->link("Detalle", array('controller' => 'Trabajo', 'action' => 'Previsualizar', $registro['Planificacion']['id']), array('class' => 'btn btn-sm blue'));
										echo $this->Html->link("Detalle", array('controller' => 'Trabajo', 'action' => 'Detalle2', $registro['Planificacion']['id'], 'Folio'), array('class' => 'btn btn-sm blue'));
										echo "</td>";
										
										echo "<td align=\"center\" style=\"width: 60px;\">";
										echo '<div class="form-group" style="margin-bottom: 0;">';
										echo '<div class="mt-checkbox-inline">';
										echo '<label class="mt-checkbox mt-checkbox-outline">';
										if($registro['Planificacion']['estado'] == '4') {
											echo "<input type=\"checkbox\" name=\"desaprobar[]\" value=\"{$registro['Planificacion']['id']}\" class=\"folio_desaprobar desaprobar_$i\" correlativo=\"".($i)."\" />";
										} else {
											echo "<input type=\"checkbox\" name=\"desaprobar[]\" value=\"{$registro['Planificacion']['id']}\" class=\"folio_desaprobar desaprobar_$i\" disabled=\"disabled\" />";
										}
										echo "<span></span>";
										echo "</label>";
										echo '</div>';
										echo '</div>';
										echo "</td>";
										
										echo "<td align=\"center\" style=\"width: 60px;\">";
										echo '<div class="form-group" style="margin-bottom: 0;">';
										echo '<div class="mt-checkbox-inline">';
										echo '<label class="mt-checkbox mt-checkbox-outline">';
										echo "<input type=\"checkbox\" name=\"borrar[]\" value=\"{$registro['Planificacion']['id']}\" class=\"folio_borrar borrar_$i\" correlativo=\"".($i)."\" />";
										echo "<span></span>";
										echo "</label>";
										echo '</div>';
										echo '</div>';
										echo "</td>";
										
										
										echo "<td align=\"center\" style=\"width: 60px;\">";
										if ($registro['Planificacion']['tipointervencion'] != "EX") {
											echo '<div class="form-group" style="margin-bottom: 0;">';
											echo '<div class="mt-checkbox-inline">';
											echo '<label class="mt-checkbox mt-checkbox-outline">';
											echo "<input type=\"checkbox\" name=\"planificar\" value=\"{$registro['Planificacion']['id']}\" class=\"folio_planificar planificar_$i\" correlativo=\"".($i)."\" disabled=\"disabled\" />";
											echo "<span></span>";
											echo "</label>";
											echo '</div>';
											echo '</div>';
										}
										echo "</td>";
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
				<div class="form-actions right">
					<button type="submit" class="btn blue">
						<i class="fa fa-save"></i> Guardar </button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php } ?>